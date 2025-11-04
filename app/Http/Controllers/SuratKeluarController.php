<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\NomorSurat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class SuratKeluarController extends Controller
{
    /**
     * Menampilkan daftar surat keluar.
     */
    public function index()
    {
        $letters = SuratKeluar::with('nomorSurat')->latest()->paginate(10);
        return view('outgoing_letters.index', compact('letters'));
    }

    /**
     * Form tambah surat keluar.
     */
    public function create()
    {
        $nomor_surats = NomorSurat::orderBy('nama_pihak')->get();
        return view('outgoing_letters.create', compact('nomor_surats'));
    }

    /**
     * Form edit surat keluar.
     */
    public function edit(SuratKeluar $outgoing_letter)
    {
        return view('outgoing_letters.edit', ['letter' => $outgoing_letter]);
    }

    /**
     * Simpan surat keluar baru dan generate nomor otomatis.
     */

    public function show(SuratKeluar $outgoing_letter)
    {
        return view('outgoing_letters.show', ['letter' => $outgoing_letter]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_surat'  => 'required|date',
            'tujuan'         => 'required|string|max:255',
            'perihal'        => 'required|string|max:255',
            'isi_surat'      => 'required|string',
            'penandatangan'  => 'nullable|string|max:255',
            'nomor_surat_id' => 'required|exists:nomor_surat,id',
        ]);

        // Ambil data pihak
        $pihak = NomorSurat::findOrFail($validated['nomor_surat_id']);

        // Dapatkan id berikutnya secara aman (PostgreSQL) atau fallback ke max+1
        $nextId = null;
        try {
            $row = DB::selectOne("SELECT nextval(pg_get_serial_sequence('surat_keluars','id')) AS id");
            $nextId = $row->id ?? null;
        } catch (\Throwable $e) {
            $nextId = (SuratKeluar::max('id') ?? 0) + 1;
        }

        // Generate nomor surat menggunakan id yang akan dipakai
        $nomorSurat = $this->generateNomorSurat($nextId, $pihak, $validated['tanggal_surat']);

        // Validasi: nomor urut (segmen pertama) tidak boleh sama
        $nomorUrut = $this->extractNomorUrut($nomorSurat);
        if ($nomorUrut !== null) {
            $existsUrut = SuratKeluar::where('nomor_surat', 'LIKE', $nomorUrut . '/%')->exists();
            if ($existsUrut) {
                return back()
                    ->withErrors(['nomor_surat' => 'Nomor urut surat sudah digunakan. Gunakan nomor urut lain.'])
                    ->withInput();
            }
        }

        // Simpan data dengan id dan nomor_surat sudah terisi
        $surat = new SuratKeluar([
            'nomor_surat'     => $nomorSurat,
            'tanggal_surat'   => $validated['tanggal_surat'],
            'tujuan'          => $validated['tujuan'],
            'perihal'         => $validated['perihal'],
            'isi_surat'       => $validated['isi_surat'],
            'penandatangan'   => $validated['penandatangan'] ?? null,
            'user_id'         => auth()->id(),
            'nomor_surat_id'  => $validated['nomor_surat_id'],
            'status_surat'    => 'draft',
        ]);
        // Set id eksplisit agar sesuai dengan nomor_surat yang dihasilkan
        $surat->id = $nextId;
        $surat->save();

        return redirect()
            ->route('outgoing-letters.show', $surat)
            ->with('success', 'Surat keluar berhasil dibuat dengan nomor: ' . $nomorSurat);
    }

    /**
     * Generate nomor surat otomatis (gabung romawi di sini).
     *
     * Format: 001/KETUM/ROIS/FMIPA/UL/V/2025
     */
    private function generateNomorSurat(int $id, NomorSurat $pihak, string $tanggal): string
    {
        $bulan = (int) date('n', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));

        // Konversi bulan ke angka romawi langsung di sini
        $romawi = match ($bulan) {
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
            default => '',
        };

        // Organisasi (ubah sesuai kebutuhan)
        $organisasi = 'ROIS/FMIPA/UL';

        // Gunakan ID surat sebagai nomor urut (3 digit)
        $nomorUrut = str_pad($id, 3, '0', STR_PAD_LEFT);

        // Bentuk format final
        return "{$nomorUrut}/{$pihak->kode_pihak}/{$organisasi}/{$romawi}/{$tahun}";
    }

    /**
     * Ambil segmen pertama (nomor urut) dari nomor_surat.
     */
    private function extractNomorUrut(string $nomorSurat): ?string
    {
        $parts = explode('/', $nomorSurat);
        if (count($parts) >= 1 && $parts[0] !== '') {
            return $parts[0];
        }
        return null;
    }

    /**
     * Update surat keluar (tanpa ubah nomor surat).
     */
    public function update(Request $request, SuratKeluar $outgoing_letter)
    {
        $validated = $request->validate([
            'nomor_surat'     => 'required|string|max:255|unique:surat_keluars,nomor_surat,' . $outgoing_letter->id,
            'tanggal_surat'   => 'required|date',
            'tujuan'          => 'required|string|max:255',
            'perihal'         => 'required|string|max:255',
            'isi_surat'       => 'required|string',
            'penandatangan'   => 'nullable|string|max:255',
            'status_surat'    => 'nullable|in:draft,dicetak,dikirim,selesai',
        ]);

        // Validasi tambahan: nomor urut (segmen pertama) tidak boleh sama dengan surat lain
        $nomorUrut = $this->extractNomorUrut($validated['nomor_surat']);
        if ($nomorUrut !== null) {
            $existsUrut = SuratKeluar::where('id', '<>', $outgoing_letter->id)
                ->where('nomor_surat', 'LIKE', $nomorUrut . '/%')
                ->exists();
            if ($existsUrut) {
                return back()
                    ->withErrors(['nomor_surat' => 'Nomor urut surat sudah digunakan. Gunakan nomor urut lain.'])
                    ->withInput();
            }
        }

        $outgoing_letter->update($validated);

        return redirect()
            ->route('outgoing-letters.show', $outgoing_letter)
            ->with('success', 'Surat keluar berhasil diperbarui.');
    }

    /**
     * Generate surat keluar ke PDF.
     */

    
    public function generateSuratKeluar(SuratKeluar $outgoing_letter)
    {
        $slug = trim(preg_replace('/[^a-zA-Z0-9-_.]+/', '-', $outgoing_letter->perihal), '-');
        $filename = 'Surat-' . ($slug ?: 'tanpa-judul') . '.pdf';

        $pdf = Pdf::loadView('outgoing_letters.pdf', [
                'letter' => $outgoing_letter,
            ])
            ->setPaper('a4', 'portrait');

        // Pastikan direktori di disk public tersedia
        Storage::disk('public')->makeDirectory('surat_keluars');
        $relativePath = 'surat_keluars/' . $outgoing_letter->id . '-' . $filename;
        $absolutePath = Storage::disk('public')->path($relativePath);
        $pdf->save($absolutePath);

        // Update kolom file_pdf dan status
        $outgoing_letter->update([
            'file_pdf' => $relativePath,
            'status_surat' => 'dicetak',
        ]);

        // Unduh file ke user (paksa download)
        return Storage::disk('public')->download($relativePath, $filename);
    }

    public function destroy(SuratKeluar $outgoing_letter)
    {
        if ($outgoing_letter->file_pdf && Storage::disk('public')->exists($outgoing_letter->file_pdf)) {
            Storage::disk('public')->delete($outgoing_letter->file_pdf);
        }
        $outgoing_letter->delete();
        return redirect()->route('outgoing-letters.index')->with('success', 'Surat keluar berhasil dihapus');
    }
}

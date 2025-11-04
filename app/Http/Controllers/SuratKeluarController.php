<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\NomorSurat;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Format;

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
     * Simpan surat keluar baru dan generate nomor otomatis.
     */
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

        // Buat surat kosong untuk mendapatkan ID increment
        $surat = SuratKeluar::create([
            'tanggal_surat'   => $validated['tanggal_surat'],
            'tujuan'          => $validated['tujuan'],
            'perihal'         => $validated['perihal'],
            'isi_surat'       => $validated['isi_surat'],
            'penandatangan'   => $validated['penandatangan'] ?? null,
            'user_id'         => auth()->id(),
            'nomor_surat_id'  => $validated['nomor_surat_id'],
            'status_surat'    => 'draft',
        ]);

        // Ambil data pihak
        $pihak = NomorSurat::findOrFail($validated['nomor_surat_id']);

        // Generate nomor surat (gabung romawi di sini)
        $nomorSurat = $this->generateNomorSurat($surat->id, $pihak, $validated['tanggal_surat']);

        // Update surat dengan nomor surat final
        $surat->update(['nomor_surat' => $nomorSurat]);

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
        $bulan = date('n', strtotime($tanggal));
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
     * Update surat keluar (tanpa ubah nomor surat).
     */
    public function update(Request $request, SuratKeluar $outgoing_letter)
    {
        $validated = $request->validate([
            'tanggal_surat'   => 'required|date',
            'tujuan'          => 'required|string|max:255',
            'perihal'         => 'required|string|max:255',
            'isi_surat'       => 'required|string',
            'penandatangan'   => 'nullable|string|max:255',
            'status_surat'    => 'nullable|in:draft,dicetak,dikirim,selesai',
        ]);

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
        $filename = 'Surat ' . preg_replace('/[^a-zA-Z0-9-_.]/', '-', $outgoing_letter->perihal) . '.pdf';
        $chromePath = env('CHROME_PATH');
        $nodeBinary = env('NODE_BINARY');

        $pdf = Pdf::view('outgoing_letters.pdf', [
                'letter' => $outgoing_letter,
            ])
            ->format(Format::A4)
            ->withBrowsershot(function ($browsershot) use ($chromePath, $nodeBinary) {
                if ($chromePath) $browsershot->setChromePath($chromePath);
                if ($nodeBinary) $browsershot->setNodeBinary($nodeBinary);
            });

        return $pdf->download($filename);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\NomorSurat;
use App\Models\PerihalSurat;
use App\Models\Penandatangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $search = request('search');
        $q = SuratKeluar::with(['nomorSurat', 'perihalSurat', 'penandatangan', 'penandatangans']);
        if ($search) {
            $q->where(function($w) use ($search) {
                $w->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhere('status_surat', 'like', "%{$search}%");
            })->orWhereHas('perihalSurat', function($r) use ($search) {
                $r->where('nama_perihal', 'like', "%{$search}%")
                  ->orWhere('jenis_surat', 'like', "%{$search}%");
            });
        }
        $letters = $q->latest()->paginate(10);
        return view('outgoing_letters.index', compact('letters','search'));
    }

    public function create()
    {
        return view('outgoing_letters.create', [
            'nomor_surats'   => NomorSurat::orderBy('nama_pihak')->get(),
            'perihal_surats' => PerihalSurat::orderBy('nama_perihal')->get(),
            'penandatangans' => Penandatangan::orderBy('nama_penandatangan')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_surat'     => 'required|date',
            'tujuan'            => 'required|string|max:255',
            'nomor_surat_id'    => 'required|exists:nomor_surat,id',
            'perihal_surat_id'  => 'required|exists:perihal_surats,perihal_surat_id',
            'penandatangan_ids' => 'required|array|min:1',
            'penandatangan_ids.*' => 'exists:penandatangans,penandatangan_id',
            'penuju_surat'      => 'required|in:A,B,C',
        ]);

        // Ambil pihak
        $pihak = NomorSurat::find($validated['nomor_surat_id']);

        // Hitung nomor urut terbaru (Postgres) dan susun nomor surat
        $tahun = date('Y', strtotime($validated['tanggal_surat']));
        $bulan = date('n', strtotime($validated['tanggal_surat']));
        $romawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][$bulan-1];
        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            $max = SuratKeluar::whereYear('tanggal_surat', $tahun)
                ->selectRaw("MAX(CAST(SPLIT_PART(nomor_surat, '/', 1) AS INT)) as maxnum")
                ->value('maxnum');
        } else {
            // Fallback untuk sqlite/mysql saat testing: hitung via PHP
            $rows = SuratKeluar::whereYear('tanggal_surat', $tahun)->pluck('nomor_surat');
            $max = 0;
            foreach ($rows as $no) {
                $part = (int) (explode('/', (string)$no)[0] ?? 0);
                if ($part > $max) $max = $part;
            }
        }
        $nextNumber = str_pad(((int)$max) + 1, 3, '0', STR_PAD_LEFT);
        $penuju = $validated['penuju_surat'];
        $nomorSurat = "{$nextNumber}/{$penuju}/{$pihak->kode_pihak}/ROIS/FMIPA/UL/{$romawi}/{$tahun}";

        // Simpan
        $surat = SuratKeluar::create([
            'id'                => (string) \Illuminate\Support\Str::uuid(),
            'nomor_surat'       => $nomorSurat,
            'tanggal_surat'     => $validated['tanggal_surat'],
            'tujuan'            => $validated['tujuan'],
            'user_id'           => auth()->id(),
            'kode_pihak_id'     => $validated['nomor_surat_id'],
            'perihal_surat_id'  => $validated['perihal_surat_id'],
            'penandatangan_id'  => $validated['penandatangan_ids'][0] ?? null, // legacy single
            'nama_kegiatan'     => $request->input('nama_kegiatan'),
            'lokasi_acara'      => $request->input('lokasi_acara'),
            'hari_tanggal'      => $request->input('hari_tanggal'),
            'waktu_acara'       => $request->input('waktu_acara'),
            'isi_tambahan'      => $request->input('isi_tambahan'),
            'status_surat'      => 'draft',
        ]);

        // attach multiple penandatangan with order
        $attach = [];
        foreach ($validated['penandatangan_ids'] as $i => $pid) {
            $attach[$pid] = ['urutan_ttd' => $i + 1];
        }
        $surat->penandatangans()->sync($attach);

        return redirect()->route('outgoing-letters.show', $surat)
            ->with('success', 'Surat berhasil dibuat dengan nomor: ' . $nomorSurat);
    }

    public function show(SuratKeluar $outgoing_letter)
    {
        $outgoing_letter->load(['nomorSurat','perihalSurat','penandatangan']);
        return view('outgoing_letters.show', ['letter' => $outgoing_letter]);
    }

    public function edit(SuratKeluar $outgoing_letter)
    {
        return view('outgoing_letters.edit', [
            'letter' => $outgoing_letter,
            'nomor_surats' => NomorSurat::orderBy('nama_pihak')->get(),
            'perihal_surats' => PerihalSurat::orderBy('nama_perihal')->get(),
            'penandatangans'=> Penandatangan::orderBy('nama_penandatangan')->get(),
        ]);
    }

    public function update(Request $request, SuratKeluar $outgoing_letter)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal_surat_id' => 'required|exists:perihal_surats,perihal_surat_id',
            'penandatangan_ids' => 'sometimes|array|min:1',
            'penandatangan_ids.*'=> 'exists:penandatangans,penandatangan_id',
            'penuju_surat'      => 'nullable|in:A,B,C',
        ]);

        // Susun ulang nomor_surat sesuai format: URUT/PENUJU/KODE/ROIS/FMIPA/UL/ROMAWI/TAHUN
        $partsInput = explode('/', (string)$validated['nomor_surat']);
        $urut = $partsInput[0] ?? '';
        if (!preg_match('/^\d{3}$/', $urut)) {
            return back()->withErrors(['nomor_surat' => 'Nomor urut harus 3 digit di awal nomor surat.'])->withInput();
        }
        $tahun = date('Y', strtotime($validated['tanggal_surat']));
        $bulan = date('n', strtotime($validated['tanggal_surat']));
        $romawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][$bulan-1];
        $penuju = $validated['penuju_surat'] ?? ($partsInput[1] ?? 'A');
        $kodePihak = optional($outgoing_letter->nomorSurat)->kode_pihak;
        if (!$kodePihak) {
            $kodePihak = NomorSurat::where('id', $outgoing_letter->kode_pihak_id)->value('kode_pihak');
        }
        $nomorComposed = "{$urut}/{$penuju}/{$kodePihak}/ROIS/FMIPA/UL/{$romawi}/{$tahun}";

        // Cek duplikasi penuh selain diri sendiri
        $existsFull = SuratKeluar::where('id', '!=', $outgoing_letter->id)
            ->where('nomor_surat', $nomorComposed)
            ->exists();
        if ($existsFull) {
            return back()->withErrors(['nomor_surat' => 'Nomor surat sudah dipakai.'])->withInput();
        }

        $outgoing_letter->update([
            'nomor_surat'       => $nomorComposed,
            'tanggal_surat'     => $validated['tanggal_surat'],
            'tujuan'            => $validated['tujuan'],
            'perihal_surat_id'  => $validated['perihal_surat_id'],
            'penandatangan_id'  => $validated['penandatangan_ids'][0] ?? $outgoing_letter->penandatangan_id,
            'nama_kegiatan'     => $request->input('nama_kegiatan'),
            'lokasi_acara'      => $request->input('lokasi_acara'),
            'hari_tanggal'      => $request->input('hari_tanggal'),
            'waktu_acara'       => $request->input('waktu_acara'),
            'isi_tambahan'      => $request->input('isi_tambahan'),
            'status_surat'      => $request->input('status_surat', $outgoing_letter->status_surat),
        ]);

        if (isset($validated['penandatangan_ids'])) {
            $attach = [];
            foreach ($validated['penandatangan_ids'] as $i => $pid) {
                $attach[$pid] = ['urutan_ttd' => $i + 1];
            }
            $outgoing_letter->penandatangans()->sync($attach);
        }

        return redirect()->route('outgoing-letters.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function generateSuratKeluar(SuratKeluar $outgoing_letter)
    {
        $outgoing_letter->load(['perihalSurat','penandatangan','nomorSurat']);
        // Ambil nama view template dari tabel perihal
        $view = $outgoing_letter->perihalSurat->template_view
            ?? 'templates.undangan';

        // Gunakan filename aman (hindari karakter "/" membuat subfolder)
        $safeFilename = preg_replace('/[^\w\-]+/','-', $outgoing_letter->nomor_surat) . '.pdf';

        // Siapkan logo kop sebagai data URI agar DomPDF bisa merender
        $leftPath = public_path(config('kop.left_logo'));
        $rightPath = public_path(config('kop.right_logo'));
        $kopLeft = (is_file($leftPath)) ? ('data:image/'.pathinfo($leftPath, PATHINFO_EXTENSION).';base64,'.base64_encode(@file_get_contents($leftPath))) : null;
        $kopRight = (is_file($rightPath)) ? ('data:image/'.pathinfo($rightPath, PATHINFO_EXTENSION).';base64,'.base64_encode(@file_get_contents($rightPath))) : null;

        $pdf = Pdf::loadView($view, [
                'letter' => $outgoing_letter,
                'kopLeft' => $kopLeft,
                'kopRight'=> $kopRight,
            ])
            ->setPaper('a4', 'portrait');

        Storage::disk('public')->makeDirectory('surat_keluars');
        $path = "surat_keluars/{$safeFilename}";
        $pdf->save(Storage::disk('public')->path($path));

        $outgoing_letter->update([
            'file_pdf'     => $path,
            'status_surat' => 'dicetak'
        ]);

        return Storage::disk('public')->download($path, $safeFilename);
    }

    public function previewTemplate(Request $request)
    {
        $request->validate([
            'perihal_surat_id' => 'required|exists:perihal_surats,perihal_surat_id'
        ]);
        $perihal = PerihalSurat::findOrFail($request->input('perihal_surat_id'));
        $view = $perihal->template_view ?? 'templates.undangan';
        if (!view()->exists($view)) {
            return response('<div class="text-danger">Template view tidak ditemukan: ' . e($view) . '</div>', 404);
        }
        return view($view, ['letter' => null, 'preview' => true]);
    }


    public function destroy(SuratKeluar $outgoing_letter)
    {
        if ($outgoing_letter->file_pdf) {
            Storage::disk('public')->delete($outgoing_letter->file_pdf);
        }

        $outgoing_letter->delete();
        return back()->with('success', 'Surat berhasil dihapus.');
    }
}

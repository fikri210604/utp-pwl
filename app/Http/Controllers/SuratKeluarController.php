<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\NomorSurat;
use App\Models\PerihalSurat;
use App\Models\Penandatangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $letters = SuratKeluar::with(['nomorSurat', 'perihal', 'penandatangan'])
            ->latest()
            ->paginate(10);

        return view('outgoing_letters.index', compact('letters'));
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
        $request->validate([
            'tanggal_surat'     => 'required|date',
            'tujuan'            => 'required|string|max:255',
            'nomor_surat_id'    => 'required|exists:nomor_surat,id',
            'perihal_surat_id'  => 'required|exists:perihal_surats,id',
            'penandatangan_id'  => 'required|exists:penandatangans,id',
        ]);

        // Ambil pihak
        $pihak = NomorSurat::find(request('nomor_surat_id'));

        // Cari nomor urut terakhir dalam tahun yang sama
        $tahun = date('Y', strtotime(request('tanggal_surat')));
        $last = SuratKeluar::whereYear('tanggal_surat', $tahun)->orderBy('nomor_surat', 'desc')->first();
        $lastNumber = $last ? intval(explode('/', $last->nomor_surat)[0]) : 0;
        $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        // Konversi bulan ke romawi
        $bulan = date('n', strtotime(request('tanggal_surat')));
        $romawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][$bulan-1];

        // Susun nomor surat
        $nomorSurat = "{$nextNumber}/{$pihak->kode_pihak}/ROIS/FMIPA/UL/{$romawi}/{$tahun}";

        // Simpan
        $surat = SuratKeluar::create([
            'id'                => (string) \Illuminate\Support\Str::uuid(),
            'nomor_surat'       => $nomorSurat,
            'tanggal_surat'     => request('tanggal_surat'),
            'tujuan'            => request('tujuan'),
            'user_id'           => auth()->id(),
            'kode_pihak_id'     => request('nomor_surat_id'),
            'perihal_surat_id'  => request('perihal_surat_id'),
            'penandatangan_id'  => request('penandatangan_id'),
            'nama_kegiatan'     => request('nama_kegiatan'),
            'lokasi_acara'      => request('lokasi_acara'),
            'hari_tanggal'      => request('hari_tanggal'),
            'waktu_acara'       => request('waktu_acara'),
            'isi_tambahan'      => request('isi_tambahan'),
            'status_surat'      => 'draft',
        ]);

        return redirect()->route('outgoing-letters.show', $surat)
            ->with('success', 'Surat berhasil dibuat dengan nomor: ' . $nomorSurat);
    }

    public function show(SuratKeluar $outgoing_letter)
    {
        return view('outgoing_letters.show', compact('outgoing_letter'));
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
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal_surat_id' => 'required|exists:perihal_surats,id',
            'penandatangan_id'=> 'required|exists:penandatangans,id',
        ]);

        // Validasi nomor urut (3 digit depan)
        $nomorUrutBaru = explode('/', request('nomor_surat'))[0];
        $tahun = date('Y', strtotime(request('tanggal_surat')));

        $exists = SuratKeluar::where('id', '!=', $outgoing_letter->id)
            ->whereYear('tanggal_surat', $tahun)
            ->where('nomor_surat', 'LIKE', $nomorUrutBaru.'/%')
            ->exists();

        if ($exists) {
            return back()->withErrors(['nomor_surat' => '⚠️ Nomor urut sudah dipakai pada surat lain di tahun yang sama.'])->withInput();
        }

        $outgoing_letter->update([
            'nomor_surat'       => request('nomor_surat'),
            'tanggal_surat'     => request('tanggal_surat'),
            'tujuan'            => request('tujuan'),
            'perihal_surat_id'  => request('perihal_surat_id'),
            'penandatangan_id'  => request('penandatangan_id'),
            'nama_kegiatan'     => request('nama_kegiatan'),
            'lokasi_acara'      => request('lokasi_acara'),
            'hari_tanggal'      => request('hari_tanggal'),
            'waktu_acara'       => request('waktu_acara'),
            'isi_tambahan'      => request('isi_tambahan'),
            'status_surat'      => request('status_surat'),
        ]);

        return back()->with('success', 'Surat berhasil diperbarui.');
    }

    public function generateSuratKeluar(SuratKeluar $outgoing_letter)
{
    // pilih template sesuai jenis surat
    $template = $outgoing_letter->perihal->template ?? 'peminjaman';

    $filename = $outgoing_letter->nomor_surat . '.pdf';

    $pdf = Pdf::loadView("outgoing_letters.templates.$template", [
            'letter' => $outgoing_letter
        ])
        ->setPaper('a4', 'portrait');

    Storage::disk('public')->makeDirectory('surat_keluars');
    $path = "surat_keluars/{$filename}";
    $pdf->save(Storage::disk('public')->path($path));

    $outgoing_letter->update([
        'file_pdf'     => $path,
        'status_surat' => 'dicetak'
    ]);

    return Storage::disk('public')->download($path, $filename);
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

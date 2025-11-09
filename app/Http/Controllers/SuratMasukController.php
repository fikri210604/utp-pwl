<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Rules\ValidPdf;

class SuratMasukController extends Controller
{
    public function index()
    {
        $search = request()->input('search');
        $q = SuratMasuk::query();
        if ($search) {
            $q->where(function($w) use ($search) {
                $w->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }
        $letters = $q->latest()->paginate(10);
        return view('incoming_letters.index', compact('letters','search'));
    }

    public function create()
    {
        return view('incoming_letters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255|unique:surat_masuks,nomor_surat',
            'tanggal_surat' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'tanggal_diterima' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);
        try {
            // Simpan file ke storage (folder surat-masuk)
            $path = $request->file('file')->store('surat-masuk', 'public');

            // Simpan ke database
            $letter = SuratMasuk::create([
                'nomor_surat'      => request('nomor_surat'),
                'tanggal_surat'    => request('tanggal_surat'),
                'pengirim'         => request('pengirim'),
                'perihal'          => request('perihal'),
                'tanggal_diterima' => request('tanggal_diterima'),
                'keterangan'       => request('keterangan'),
                'penerima_id'      => auth()->id(),
                'file_path'        => $path,
            ]);

            return redirect()
                ->route('incoming-letters.show', $letter)
                ->with('success', 'Surat masuk berhasil disimpan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors(['surat' => 'Gagal menyimpan surat masuk.'])->withInput();
        }
    }

    public function show(SuratMasuk $incoming_letter)
    {
        return view('incoming_letters.show', ['letter' => $incoming_letter]);
    }

    public function destroy(SuratMasuk $incoming_letter)
    {
        try {
            if ($incoming_letter->file_path && Storage::disk('public')->exists($incoming_letter->file_path)) {
                Storage::disk('public')->delete($incoming_letter->file_path);
            }
        } catch (\Throwable $e) {
            report($e);
            // lanjut hapus record meski penghapusan file gagal
        }
        $incoming_letter->delete();
        return redirect()->route('incoming-letters.index')->with('success', 'Surat masuk dihapus.');
    }
}

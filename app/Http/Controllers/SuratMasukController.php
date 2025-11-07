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

        // Simpan file ke storage (folder surat-masuk)
        $path = $request->file('file')->store('surat-masuk', 'public');

        // Simpan ke database
        $letter = SuratMasuk::create([
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'pengirim' => $validated['pengirim'],
            'penerima_id' => auth()->id(),
            'perihal' => $validated['perihal'],
            'tanggal_diterima' => $validated['tanggal_diterima'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'file_path' => $path,
        ]);

        return redirect()
            ->route('incoming-letters.show', $letter)
            ->with('success', 'Surat masuk berhasil disimpan.');
    }

    public function show(SuratMasuk $incoming_letter)
    {
        return view('incoming_letters.show', ['letter' => $incoming_letter]);
    }

    public function destroy(SuratMasuk $incoming_letter)
    {
        if ($incoming_letter->file_path && Storage::disk('public')->exists($incoming_letter->file_path)) {
            Storage::disk('public')->delete($incoming_letter->file_path);
        }
        $incoming_letter->delete();
        return redirect()->route('incoming-letters.index')->with('success', 'Surat masuk dihapus.');
    }
}

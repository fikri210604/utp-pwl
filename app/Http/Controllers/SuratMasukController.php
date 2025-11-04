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
        $letters = SuratMasuk::latest()->paginate(10);
        return view('incoming_letters.index', compact('letters'));
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
            'penerima' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'tanggal_diterima' => 'nullable|date',
            'keterangan' => 'nullable|string',
            // wajib (non-nullable) sesuai migrasi
            'nomor_surat_id' => 'required|integer|exists:nomor_surat,id',
            'file' => ['required','file','mimes:pdf','mimetypes:application/pdf','max:10240', new ValidPdf],
        ]);

        $path = $request->file('file')->store('public/surat-masuk');

        // Hindari mass-assignment issue dan set kolom sesuai migrasi
        $letter = new SuratMasuk();
        $letter->nomor_surat = $validated['nomor_surat'];
        $letter->tanggal_surat = $validated['tanggal_surat'];
        $letter->pengirim = $validated['pengirim'];
        $letter->penerima = $validated['penerima'];
        $letter->perihal = $validated['perihal'];
        $letter->tanggal_diterima = $validated['tanggal_diterima'] ?? null;
        $letter->keterangan = $validated['keterangan'] ?? null;
        $letter->file_path = $path;

        // Wajib (non-nullable) sesuai migrasi
        $letter->user_id = auth()->id();
        $letter->nomor_surat_id = $validated['nomor_surat_id'];

        $letter->save();

        return redirect()->route('incoming-letters.show', $letter)->with('success', 'Surat masuk berhasil disimpan.');
    }

    public function show(SuratMasuk $incoming_letter)
    {
        return view('incoming_letters.show', ['letter' => $incoming_letter]);
    }

    public function destroy(SuratMasuk $incoming_letter)
    {
        if ($incoming_letter->file_path && Storage::exists($incoming_letter->file_path)) {
            Storage::delete($incoming_letter->file_path);
        }
        $incoming_letter->delete();
        return redirect()->route('incoming-letters.index')->with('success', 'Surat masuk dihapus.');
    }
}

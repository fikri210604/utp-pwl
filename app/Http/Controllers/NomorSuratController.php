<?php

namespace App\Http\Controllers;

use App\Models\NomorSurat; // Pastikan model Anda ada di 'App\Models\NomorSurat'
use Illuminate\Http\Request;

class NomorSuratController extends Controller
{
    /**
     * Tampilkan daftar semua kode pihak (dengan search).
     */
    public function index()
    {
        // Definisikan $search di luar 'if'
        $search = request()->input('search');

        $query = NomorSurat::query();

        // Handle search
        if ($search) {
            $query->where('kode_pihak', 'like', '%' . $search . '%')
                  ->orWhere('nama_pihak', 'like', '%' . $search . '%');
        }

        $letter_code = $query->latest()->paginate(10);

        // Kirim $letter_code dan $search ke view
        return view('letter_code.index', compact('letter_code', 'search'));
    }

    /**
     * Form tambah pihak baru.
     */
    public function create()
    {
        return view('letter_code.create');
    }

    /**
     * Simpan pihak baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi dinamis: kode_pihak hanya wajib jika bukan acara
        $rules = [
            'nama_pihak' => 'required|string|max:100',
            'is_acara'   => 'nullable|boolean',
        ];
        if (!$request->boolean('is_acara')) {
            $rules['kode_pihak'] = 'required|string|max:50|unique:nomor_surat,kode_pihak';
        } else {
            $rules['kode_pihak'] = 'nullable|string|max:50|unique:nomor_surat,kode_pihak';
        }
        $request->validate($rules);

        // Susun kode
        if ($request->boolean('is_acara')) {
            $base = strtoupper($request->input('nama_pihak'));
            $kode = str_starts_with($base, 'PAN-') ? $base : ('PAN-' . $base);
        } else {
            $kode = strtoupper($request->input('kode_pihak'));
        }

        NomorSurat::create([
            'kode_pihak' => $kode,
            'nama_pihak' => $request->input('nama_pihak'),
        ]);

        return redirect()->route('letter_code.index')
            ->with('success', 'Kode pihak berhasil ditambahkan!');
    }


    /**
     * Form edit kode pihak.
     */
    public function edit(NomorSurat $letterCode)
    {
        // $letterCode otomatis diambil dari ID di URL (Route Model Binding)
        return view('letter_code.edit', compact('letterCode'));
    }

    /**
     * Update data kode pihak.
     */
    public function update(Request $request, NomorSurat $letterCode)
    {
        // Validasi dinamis untuk update
        $rules = [
            'nama_pihak' => 'required|string|max:100',
            'is_acara'   => 'nullable|boolean',
        ];
        if (!$request->boolean('is_acara')) {
            $rules['kode_pihak'] = 'required|string|max:50|unique:nomor_surat,kode_pihak,' . $letterCode->id;
        } else {
            $rules['kode_pihak'] = 'nullable|string|max:50|unique:nomor_surat,kode_pihak,' . $letterCode->id;
        }
        $request->validate($rules);

        if ($request->boolean('is_acara')) {
            $base = strtoupper($request->input('nama_pihak'));
            $kode = str_starts_with($base, 'PAN-') ? $base : ('PAN-' . $base);
        } else {
            $kode = strtoupper($request->input('kode_pihak'));
        }

        $letterCode->update([
            'kode_pihak' => $kode,
            'nama_pihak' => $request->input('nama_pihak'),
        ]);

        return redirect()->route('letter_code.index')
            ->with('success', 'Data pihak berhasil diperbarui!');
    }

    /**
     * Hapus data pihak.
     */
    public function destroy(NomorSurat $letterCode)
    {
        $letterCode->delete();

        return redirect()->route('letter_code.index')
            ->with('success', 'Data pihak berhasil dihapus!');
    }
}

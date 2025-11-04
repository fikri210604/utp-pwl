<?php

namespace App\Http\Controllers;

use App\Models\NomorSurat;
use Illuminate\Http\Request;

class NomorSuratController extends Controller
{
    /**
     * Tampilkan daftar semua kode pihak.
     */
    public function index()
    {
        $nomor_surat = NomorSurat::latest()->paginate(10);
        return view('nomor_surat.index', compact('nomor_surat'));
    }

    /**
     * Form tambah pihak baru.
     */
    public function create()
    {
        return view('nomor_surat.create');
    }

    /**
     * Simpan pihak baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_pihak' => 'required|string|max:50|unique:nomor_surat,kode_pihak',
            'nama_pihak' => 'required|string|max:100',
            'is_acara'   => 'nullable|boolean',
        ]);

        // Ambil kode input & ubah ke huruf besar
        $kode = strtoupper($request->kode_pihak);

        // Jika kegiatan (acara), tambahkan prefix PAN-
        if ($request->boolean('is_acara')) {
            if (!str_starts_with($kode, 'PAN-')) {
                $kode = 'PAN-' . $kode;
            }
        }

        // Simpan ke database
        NomorSurat::create([
            'kode_pihak' => $kode,
            'nama_pihak' => $request->nama_pihak,
        ]);

        return redirect()->route('nomor-surat.index')
            ->with('success', 'Kode pihak berhasil ditambahkan!');
    }

    /**
     * Form edit kode pihak.
     */
    public function edit(NomorSurat $nomorSurat)
    {
        return view('nomor_surat.edit', compact('nomorSurat'));
    }

    /**
     * Update data kode pihak.
     */
    public function update(Request $request, NomorSurat $nomorSurat)
    {
        $request->validate([
            'kode_pihak' => 'required|string|max:50|unique:nomor_surat,kode_pihak,' . $nomorSurat->id,
            'nama_pihak' => 'required|string|max:100',
            'is_acara'   => 'nullable|boolean',
        ]);

        $kode = strtoupper($request->kode_pihak);

        // Tambahkan prefix PAN- kalau kegiatan
        if ($request->boolean('is_acara')) {
            if (!str_starts_with($kode, 'PAN-')) {
                $kode = 'PAN-' . $kode;
            }
        }

        $nomorSurat->update([
            'kode_pihak' => $kode,
            'nama_pihak' => $request->nama_pihak,
        ]);

        return redirect()->route('nomor-surat.index')
            ->with('success', 'Data pihak berhasil diperbarui!');
    }

    /**
     * Hapus data pihak.
     */
    public function destroy(NomorSurat $nomorSurat)
    {
        $nomorSurat->delete();
        return redirect()->route('nomor-surat.index')
            ->with('success', 'Data pihak berhasil dihapus!');
    }
}

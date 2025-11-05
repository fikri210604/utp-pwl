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
        // Diubah dari $nomor_surat menjadi $letter_code
        $letter_code = NomorSurat::latest()->paginate(10);
        
        // Sekarang variabel $letter_code sesuai dengan string 'letter_code'
        return view('letter_code.index', compact('letter_code'));
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

        // Route ini sudah benar
        return redirect()->route('letter_code.index')
            ->with('success', 'Kode pihak berhasil ditambahkan!');
    }

    /**
     * Form edit kode pihak.
     */
    // Diubah dari $nomorSurat menjadi $letterCode agar konsisten
    public function edit(NomorSurat $letterCode)
    {
        // Diubah dari 'nomorSurat' menjadi 'letterCode'
        return view('letter_code.edit', compact('letterCode'));
    }

    /**
     * Update data kode pihak.
     */
    // Diubah dari $nomorSurat menjadi $letterCode agar konsisten
    public function update(Request $request, NomorSurat $letterCode)
    {
        $request->validate([
            // Menggunakan $letterCode->id
            'kode_pihak' => 'required|string|max:50|unique:nomor_surat,kode_pihak,' . $letterCode->id,
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

        // Update menggunakan $letterCode
        $letterCode->update([
            'kode_pihak' => $kode,
            'nama_pihak' => $request->nama_pihak,
        ]);

        // Diubah dari 'nomor-surat.index' menjadi 'letter_code.index'
        return redirect()->route('letter_code.index')
            ->with('success', 'Data pihak berhasil diperbarui!');
    }

    /**
     * Hapus data pihak.
     */
    // Diubah dari $nomorSurat menjadi $letterCode agar konsisten
    public function destroy(NomorSurat $letterCode)
    {
        // Hapus menggunakan $letterCode
        $letterCode->delete();

        // Diubah dari 'nomor-sura.index' menjadi 'letter_code.index'
        return redirect()->route('letter_code.index')
            ->with('success', 'Data pihak berhasil dihapus!');
    }
}
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
        $request->validate([
            'nama_pihak' => 'required|string|max:100',
            'kode_pihak' => 'required|string|max:50|unique:nomor_surat,kode_pihak',
            'is_acara' => 'nullable|boolean',
        ]);

        $kode = strtoupper($request->kode_pihak);

        // Tambahkan prefix PAN- jika 'is_acara' dicentang
        if ($request->boolean('is_acara')) {
            if (!str_starts_with($kode, 'PAN-')) {
                $kode = 'PAN-' . $kode;
            }
        }

        NomorSurat::create([
            'kode_pihak' => $kode,
            'nama_pihak' => $request->nama_pihak,
            // 'is_acara' => $request->boolean('is_acara'), // (Opsional: jika Anda punya kolom ini)
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
        $request->validate([
            'nama_pihak' => 'required|string|max:100',
            // Abaikan rule unique untuk ID $letterCode saat ini
            'kode_pihak' => 'required|string|max:50|unique:nomor_surat,kode_pihak,' . $letterCode->id,
            'is_acara' => 'nullable|boolean',
        ]);

        $kode = strtoupper($request->kode_pihak);

        // Tambahkan prefix PAN- jika 'is_acara' dicentang
        if ($request->boolean('is_acara')) {
            if (!str_starts_with($kode, 'PAN-')) {
                $kode = 'PAN-' . $kode;
            }
        }

        $letterCode->update([
            'kode_pihak' => $kode,
            'nama_pihak' => $request->nama_pihak,
            // 'is_acara' => $request->boolean('is_acara'), // (Opsional)
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
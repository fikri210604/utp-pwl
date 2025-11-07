<?php

namespace App\Http\Controllers;

use App\Models\PerihalSurat;
use Illuminate\Http\Request;

class PerihalSuratController extends Controller
{
    public function index()
    {
        $perihal_surat = PerihalSurat::latest()->paginate(10);
        return view('perihal_surat.index', compact('perihal_surat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perihal' => 'required|string|max:255|unique:perihal_surats,nama_perihal',
            'jenis_surat'  => 'required|in:undangan,peminjaman,lainnya',
            'template'     => 'nullable|string|max:255'
        ]);

        PerihalSurat::create($validated);

        return back()->with('success', 'Perihal surat berhasil ditambahkan.');
    }

    public function update(Request $request, PerihalSurat $perihalSurat)
    {
        $validated = $request->validate([
            'nama_perihal' => 'required|string|max:255|unique:perihal_surats,nama_perihal,' . $perihalSurat->id,
            'jenis_surat'  => 'required|in:undangan,peminjaman,lainnya',
            'template'     => 'nullable|string|max:255'
        ]);

        $perihalSurat->update($validated);

        return back()->with('success', 'Perihal surat berhasil diperbarui.');
    }

    public function destroy(PerihalSurat $perihalSurat)
    {
        $perihalSurat->delete();
        return back()->with('success', 'Perihal surat berhasil dihapus.');
    }
}

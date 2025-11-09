<?php

namespace App\Http\Controllers;

use App\Models\PerihalSurat;
use Illuminate\Http\Request;

class PerihalSuratController extends Controller
{
    public function index()
    {
        $search = request()->input('search');
        $query = PerihalSurat::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_perihal', 'like', '%' . $search . '%')
                  ->orWhere('jenis_surat', 'like', '%' . $search . '%')
                  ->orWhere('template_view', 'like', '%' . $search . '%');
            });
        }
        $perihal_surat = $query->latest()->paginate(10);
        return view('perihal_surat.index', compact('perihal_surat', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perihal'  => 'required|string|max:255|unique:perihal_surats,nama_perihal',
            'jenis_surat'   => 'required|in:undangan,peminjaman,lainnya',
            'template_view' => 'nullable|string|max:255',
            'template'      => 'nullable|string|max:255', // legacy form field name
        ]);

        PerihalSurat::create([
            'nama_perihal'  => request('nama_perihal'),
            'jenis_surat'   => request('jenis_surat'),
            'template_view' => request('template_view') ?? request('template'),
        ]);

        return back()->with('success', 'Perihal surat berhasil ditambahkan.');
    }

    public function update(Request $request, PerihalSurat $perihalSurat)
    {
        $validated = $request->validate([
            'nama_perihal'  => 'required|string|max:255|unique:perihal_surats,nama_perihal,' . $perihalSurat->perihal_surat_id . ',perihal_surat_id',
            'jenis_surat'   => 'required|in:undangan,peminjaman,lainnya',
            'template_view' => 'nullable|string|max:255',
            'template'      => 'nullable|string|max:255',
        ]);

        $perihalSurat->update([
            'nama_perihal'  => $request->input('nama_perihal'),
            'jenis_surat'   => $request->input('jenis_surat'),
            'template_view' => $request->input('template_view') ?? $request->input('template') ?? $perihalSurat->template_view,
        ]);

        return back()->with('success', 'Perihal surat berhasil diperbarui.');
    }

    public function destroy(PerihalSurat $perihalSurat)
    {
        $perihalSurat->delete();
        return back()->with('success', 'Perihal surat berhasil dihapus.');
    }
}

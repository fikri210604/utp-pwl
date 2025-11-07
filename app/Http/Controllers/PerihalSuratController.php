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

        $data = [
            'nama_perihal'  => $validated['nama_perihal'],
            'jenis_surat'   => $validated['jenis_surat'],
            'template_view' => $validated['template_view'] ?? $validated['template'] ?? null,
        ];

        PerihalSurat::create($data);

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

        $data = [
            'nama_perihal'  => $validated['nama_perihal'],
            'jenis_surat'   => $validated['jenis_surat'],
            'template_view' => $validated['template_view'] ?? $validated['template'] ?? $perihalSurat->template_view,
        ];

        $perihalSurat->update($data);

        return back()->with('success', 'Perihal surat berhasil diperbarui.');
    }

    public function destroy(PerihalSurat $perihalSurat)
    {
        $perihalSurat->delete();
        return back()->with('success', 'Perihal surat berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Penandatangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenandatanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');
        $q = Penandatangan::query();
        if ($search) {
            $q->where(function($w) use ($search) {
                $w->where('nama_penandatangan', 'like', "%{$search}%")
                  ->orWhere('nip_npm_penandatangan', 'like', "%{$search}%")
                  ->orWhere('jabatan_penandatangan', 'like', "%{$search}%");
            });
        }
        $penandatangan = $q->latest()->paginate(10);
        return view('penandatangan.index', compact('penandatangan','search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penandatangan'      => 'required|string|max:255',
            'nip_npm_penandatangan'   => 'nullable|numeric|digits_between:8,20',
            'jabatan_penandatangan'   => 'required|string|max:255',
            'gambar_tandatangan'      => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('gambar_tandatangan')) {
            $path = $request->file('gambar_tandatangan')->store('tanda_tangan', 'public');
        }

        Penandatangan::create([
            'nama_penandatangan'    => $validated['nama_penandatangan'],
            'nip_npm_penandatangan' => $validated['nip_npm_penandatangan'] ?? null,
            'jabatan_penandatangan' => $validated['jabatan_penandatangan'],
            'gambar_tandatangan'    => $path
        ]);

        return back()->with('success', 'Penandatangan berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penandatangan $penandatangan)
    {
        $validated = $request->validate([
            'nama_penandatangan'      => 'required|string|max:255',
            'nip_npm_penandatangan'   => 'nullable|numeric|digits_between:8,20',
            'jabatan_penandatangan'   => 'required|string|max:255',
            'gambar_tandatangan'      => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        // Jika upload gambar baru → hapus lama
        $path = $penandatangan->gambar_tandatangan;
        if ($request->hasFile('gambar_tandatangan')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('gambar_tandatangan')->store('tanda_tangan', 'public');
        }

        $penandatangan->update([
            'nama_penandatangan'    => $validated['nama_penandatangan'],
            'nip_npm_penandatangan' => $validated['nip_npm_penandatangan'] ?? null,
            'jabatan_penandatangan' => $validated['jabatan_penandatangan'],
            'gambar_tandatangan'    => $path
        ]);

        return back()->with('success', 'Penandatangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penandatangan $penandatangan)
    {
        if ($penandatangan->gambar_tandatangan && Storage::disk('public')->exists($penandatangan->gambar_tandatangan)) {
            Storage::disk('public')->delete($penandatangan->gambar_tandatangan);
        }

        $penandatangan->delete();

        return back()->with('success', 'Penandatangan berhasil dihapus.');
    }
}

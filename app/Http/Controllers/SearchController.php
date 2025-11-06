<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;

class SearchController extends Controller
{
    /**
     * Handle the search request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return view('search.results', ['results' => collect(), 'query' => '']);
        }

        // Cari di Surat Masuk
        $incoming = SuratMasuk::where('perihal', 'like', "%{$query}%")
            ->orWhere('nomor_surat', 'like', "%{$query}%")
            ->orWhere('pengirim', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                $item->type = 'in';
                return $item;
            });

        // Cari di Surat Keluar
        $outgoing = SuratKeluar::where('perihal', 'like', "%{$query}%")
            ->orWhere('nomor_surat', 'like', "%{$query}%")
            ->orWhere('tujuan', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                $item->type = 'out';
                return $item;
            });

        $results = $incoming->merge($outgoing)->sortByDesc('tanggal_surat');

        return view('search.results', compact('results', 'query'));
    }
}
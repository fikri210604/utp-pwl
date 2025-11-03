<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Format;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $letters = SuratKeluar::latest()->paginate(10);
        return view('outgoing_letters.index', compact('letters'));
    }

    public function create()
    {
        return view('outgoing_letters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255|unique:surat_keluars,nomor_surat',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'isi_surat' => 'required|string',
            'penandatangan' => 'nullable|string|max:255',
        ]);

        $letter = SuratKeluar::create($validated);

        return redirect()->route('outgoing-letters.show', $letter)->with('success', 'Surat keluar berhasil dibuat.');
    }

    public function show(SuratKeluar $outgoing_letter)
    {
        return view('outgoing_letters.show', ['letter' => $outgoing_letter]);
    }

    public function generateSuratKeluar(SuratKeluar $outgoing_letter)
    {
        $filename = 'surat-keluar-' . preg_replace('/[^a-zA-Z0-9-_.]/', '-', $outgoing_letter->nomor_surat) . '.pdf';
        $chromePath = env('CHROME_PATH');
        $nodeBinary = env('NODE_BINARY');

        $pdf = Pdf::view('outgoing_letters.pdf', [
                'letter' => $outgoing_letter,
            ])
            ->format(Format::A4)
            ->withBrowsershot(function ($browsershot) use ($chromePath, $nodeBinary) {
                if ($chromePath) {
                    $browsershot->setChromePath($chromePath);
                }
                if ($nodeBinary) {
                    $browsershot->setNodeBinary($nodeBinary);
                }
            });

        return $pdf->download($filename);
    }
}

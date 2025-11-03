@extends('layouts.app')

@section('title','Surat Keluar')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:.5rem;">
            <h1 style="margin-top:0;">Surat Keluar</h1>
            <div style="display:flex; gap:.5rem;">
                <a class="btn" href="{{ route('outgoing-letters.pdf', $letter) }}">Download PDF</a>
                <button class="btn btn-secondary" onclick="window.print()">Cetak via Browser</button>
            </div>
        </div>

        <div style="background:#fff; color:#111; padding:1rem; margin-top:1rem; border:1px solid #e5e7eb;">
            <div style="text-align:center; border-bottom:2px solid #000; padding-bottom:.5rem; margin-bottom:1rem;">
                <div style="font-size:1.25rem; font-weight:700;">KOP SURAT INSTANSI</div>
                <div>Alamat, Telepon, Email</div>
            </div>

            <div style="display:flex; justify-content:space-between; margin-bottom:1rem;">
                <div>Nomor: {{ $letter->nomor_surat }}</div>
                <div>Tanggal: {{ \Illuminate\Support\Carbon::parse($letter->tanggal_surat)->translatedFormat('d F Y') }}</div>
            </div>

            <div style="margin-bottom:1rem;">
                <div>Kepada Yth.</div>
                <div style="font-weight:600;">{{ $letter->tujuan }}</div>
            </div>

            <div style="margin-bottom:1rem;">
                <div style="font-weight:600;">Perihal: {{ $letter->perihal }}</div>
            </div>

            <div style="white-space:pre-wrap; min-height:200px;">{!! nl2br(e($letter->isi_surat)) !!}</div>

            <div style="display:flex; justify-content:flex-end; margin-top:2rem;">
                <div style="text-align:left;">
                    <div>Hormat kami,</div>
                    <div style="height:80px;"></div>
                    <div style="font-weight:600;">{{ $letter->penandatangan ?: '................................' }}</div>
                </div>
            </div>
        </div>

        <div style="margin-top:1rem;">
            <a class="btn btn-secondary" href="{{ route('outgoing-letters.index') }}">Kembali</a>
        </div>
    </div>

    <style>
        @media print {
            body { background:#fff; }
            .container > .nav, .container > .card > div:first-child .btn, .btn, .btn-secondary { display:none !important; }
            .card { border:none; padding:0; }
        }
    </style>
@endsection

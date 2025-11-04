@extends('layouts.app')

@section('title','Detail Surat Masuk')

@section('content')
    <div class="card">
        <h1 style="margin-top:0;">Detail Surat Masuk</h1>

        <div style="display:grid; grid-template-columns: 180px 1fr; gap:.5rem;">
            <div>Nomor Surat</div><div>: {{ $letter->nomor_surat }}</div>
            <div>Tanggal Surat</div><div>: {{ \Illuminate\Support\Carbon::parse($letter->tanggal_surat)->format('d/m/Y') }}</div>
            <div>Pengirim</div><div>: {{ $letter->pengirim }}</div>
            <div>Perihal</div><div>: {{ $letter->perihal }}</div>
            <div>Tanggal Diterima</div><div>: {{ $letter->tanggal_diterima ? \Illuminate\Support\Carbon::parse($letter->tanggal_diterima)->format('d/m/Y') : '-' }}</div>
            <div>Keterangan</div><div>: {{ $letter->keterangan ?? '-' }}</div>
            <div>Berkas</div>
            <div>:
                @if ($letter->file_path)
                    <a class="btn btn-secondary" target="_blank" href="{{ asset('storage/'.$letter->file_path) }}">Buka PDF</a>
                @else
                    -
                @endif
            </div>
        </div>

        <div style="margin-top:1rem;">
            <a class="btn btn-secondary" href="{{ route('incoming-letters.index') }}">Kembali</a>
        </div>
    </div>
@endsection

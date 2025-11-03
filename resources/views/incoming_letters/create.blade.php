@extends('layouts.app')

@section('title','Tambah Surat Masuk')

@section('content')
    <div class="card">
        <h1 style="margin-top:0;">Tambah Surat Masuk</h1>
        <form action="{{ route('incoming-letters.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Nomor Surat</label>
            <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}">
            @error('nomor_surat')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Tanggal Surat</label>
            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}">
            @error('tanggal_surat')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Pengirim</label>
            <input type="text" name="pengirim" value="{{ old('pengirim') }}">
            @error('pengirim')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Perihal</label>
            <input type="text" name="perihal" value="{{ old('perihal') }}">
            @error('perihal')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Tanggal Diterima (opsional)</label>
            <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima') }}">
            @error('tanggal_diterima')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Keterangan (opsional)</label>
            <textarea name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
            @error('keterangan')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Berkas PDF</label>
            <input type="file" name="file" accept="application/pdf">
            @error('file')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <div style="margin-top:1rem; display:flex; gap:.5rem;">
                <button class="btn" type="submit">Simpan</button>
                <a class="btn btn-secondary" href="{{ route('incoming-letters.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection


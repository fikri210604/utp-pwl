@extends('layouts.app')

@section('title','Buat Surat Keluar')

@section('content')
    <div class="card">
        <h1 style="margin-top:0;">Buat Surat Keluar</h1>
        <form action="{{ route('outgoing-letters.store') }}" method="POST">
            @csrf

            <label>Nomor Surat</label>
            <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}">
            @error('nomor_surat')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Tanggal Surat</label>
            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}">
            @error('tanggal_surat')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Tujuan</label>
            <input type="text" name="tujuan" value="{{ old('tujuan') }}">
            @error('tujuan')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Perihal</label>
            <input type="text" name="perihal" value="{{ old('perihal') }}">
            @error('perihal')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Isi Surat</label>
            <textarea name="isi_surat" rows="10">{{ old('isi_surat') }}</textarea>
            @error('isi_surat')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <label>Penandatangan (opsional)</label>
            <input type="text" name="penandatangan" value="{{ old('penandatangan') }}">
            @error('penandatangan')<div style="color:#dc2626;">{{ $message }}</div>@enderror

            <div style="margin-top:1rem; display:flex; gap:.5rem;">
                <button class="btn" type="submit">Simpan & Lihat</button>
                <a class="btn btn-secondary" href="{{ route('outgoing-letters.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Kode Surat')

@section('content')
    <div class="card">
        <h1>Edit Kode Surat</h1>

        {{-- Perbaikan: route('nomor-surat.update', $nomorSurat) diubah menjadi 'letter_code.update', $letterCode --}}
        <form action="{{ route('letter_code.update', $letterCode) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="kode_pihak" class="form-label">Kode Pihak</label>
                {{-- Perbaikan: $nomorSurat diubah menjadi $letterCode --}}
                <input type="text" class="form-control" id="kode_pihak" name="kode_pihak" value="{{ $letterCode->kode_pihak }}" required>
            </div>

            <div class="mb-3">
                <label for="nama_pihak" class="form-label">Nama Pihak</label>
                {{-- Perbaikan: $nomorSurat diubah menjadi $letterCode --}}
                <input type="text" class="form-control" id="nama_pihak" name="nama_pihak" value="{{ $letterCode->nama_pihak }}" required>
            </div>

            <div class="mb-3 form-check">
                <input 
                    type="checkbox" 
                    class="form-check-input" 
                    id="is_acara" 
                    name="is_acara" 
                    value="1"
                    {{-- Perbaikan: Logika disederhanakan & $nomorSurat diubah menjadi $letterCode --}}
                    {{ str_starts_with($letterCode->kode_pihak, 'PAN-') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_acara">Apakah Acara?</label>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            {{-- Perbaikan: route('nomor-surat.index') diubah menjadi 'letter_code.index' --}}
            <a href="{{ route('letter_code.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
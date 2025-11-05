@extends('layouts.app')

@section('title', 'Tambah Kode Surat')

@section('content')
    <div class="card">
        <h1>Tambah Kode Surat</h1>

        {{-- Perbaikan: route('nomor-surat.store') diubah menjadi 'letter_code.store' --}}
        <form action="{{ route('letter_code.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="kode_pihak" class="form-label">Kode Pihak/Acara</label>
                <input type="text" class="form-control" id="kode_pihak" name="kode_pihak">
                <div class="form-text">Jika kode dibuat untuk acara, maka tidak perlu diisi Kode Pihak ini</div>
            </div>
            <div class="mb-3">
                <label for="nama_pihak" class="form-label">Nama Pihak/Acara</label>
                <input type="text" class="form-control" id="nama_pihak" name="nama_pihak" required>
                @error('nama_pihak')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_acara" name="is_acara" value="1">
                <label class="form-check-label" for="is_acara">Apakah Acara?</label>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            {{-- Perbaikan: route('nomor-surat.index') diubah menjadi 'letter_code.index' --}}
            <a href="{{ route('letter_code.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
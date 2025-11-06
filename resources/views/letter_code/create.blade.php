@extends('layouts.app')

@section('title', 'Tambah Kode Surat')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Tambah Kode Surat</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('letter_code.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="kode_pihak" class="form-label">Kode Pihak/Acara</label>
                            <input type="text" class="form-control @error('kode_pihak') is-invalid @enderror" id="kode_pihak" name="kode_pihak" value="{{ old('kode_pihak') }}">
                            <div class="form-text">Jika kode dibuat untuk acara, maka tidak perlu diisi Kode Pihak ini</div>
                            @error('kode_pihak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nama_pihak" class="form-label">Nama Pihak/Acara</label>
                            <input type="text" class="form-control @error('nama_pihak') is-invalid @enderror" id="nama_pihak" name="nama_pihak" value="{{ old('nama_pihak') }}" required>
                            @error('nama_pihak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_acara" name="is_acara" value="1" {{ old('is_acara') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_acara">Apakah Acara?</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('letter_code.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

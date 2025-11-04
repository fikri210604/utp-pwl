@extends('layouts.app')

@section('title','Tambah Surat Masuk')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📥 Tambah Surat Masuk</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('incoming-letters.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nomor Surat --}}
                <div class="mb-3">
                    <label for="nomor_surat" class="form-label">Nomor Surat</label>
                    <input type="text" id="nomor_surat" name="nomor_surat" class="form-control"
                           value="{{ old('nomor_surat') }}" required>
                    @error('nomor_surat')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal Surat --}}
                <div class="mb-3">
                    <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                    <input type="date" id="tanggal_surat" name="tanggal_surat" class="form-control"
                           value="{{ old('tanggal_surat') }}" required>
                    @error('tanggal_surat')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Pengirim --}}
                <div class="mb-3">
                    <label for="pengirim" class="form-label">Pengirim</label>
                    <input type="text" id="pengirim" name="pengirim" class="form-control"
                           value="{{ old('pengirim') }}" required>
                    @error('pengirim')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Perihal --}}
                <div class="mb-3">
                    <label for="perihal" class="form-label">Perihal</label>
                    <input type="text" id="perihal" name="perihal" class="form-control"
                           value="{{ old('perihal') }}" required>
                    @error('perihal')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal Diterima (Opsional) --}}
                <div class="mb-3">
                    <label for="tanggal_diterima" class="form-label">Tanggal Diterima (opsional)</label>
                    <input type="date" id="tanggal_diterima" name="tanggal_diterima" class="form-control"
                           value="{{ old('tanggal_diterima') }}">
                    @error('tanggal_diterima')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan (opsional)</label>
                    <textarea id="keterangan" name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Upload File --}}
                <div class="mb-3">
                    <label for="file" class="form-label">Berkas PDF</label>
                    <input type="file" id="file" name="file" class="form-control" accept="application/pdf" required>
                    <div class="form-text">Unggah berkas surat dalam format PDF.</div>
                    @error('file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('incoming-letters.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

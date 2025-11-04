@extends('layouts.app')

@section('title', 'Edit Surat Keluar')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">✏️ Edit Surat Keluar</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('outgoing-letters.update', $letter) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" value="{{ old('nomor_surat', $letter->nomor_surat) }}" required>
            <div class="form-text">Pastikan unik dan sesuai format kebijakan instansi.</div>
            @error('nomor_surat')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Surat</label>
            <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', $letter->tanggal_surat) }}">
            @error('tanggal_surat')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Tujuan</label>
            <input type="text" name="tujuan" class="form-control" value="{{ old('tujuan', $letter->tujuan) }}">
            @error('tujuan')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Perihal</label>
            <input type="text" name="perihal" class="form-control" value="{{ old('perihal', $letter->perihal) }}">
            @error('perihal')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Isi Surat</label>
            <textarea id="isi_surat" name="isi_surat" class="form-control" rows="10">{{ old('isi_surat', $letter->isi_surat) }}</textarea>
            @error('isi_surat')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Penandatangan</label>
            <input type="text" name="penandatangan" class="form-control" value="{{ old('penandatangan', $letter->penandatangan) }}" placeholder="Nama penandatangan">
            @error('penandatangan')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('outgoing-letters.show', $letter) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
@endsection

@section('scripts')
    <link rel="stylesheet" href="{{ asset('richtexteditorforphp/richtexteditor/rte_theme_default.css') }}" />
    <script type="text/javascript" src="{{ asset('richtexteditorforphp/richtexteditor/rte.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var editor = new RichTextEditor("#isi_surat");
            editor.setHeight(350);
        });
    </script>
@endsection

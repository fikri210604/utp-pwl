@extends('layouts.app')

@section('title', 'Buat Surat Keluar')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">📄 Buat Surat Keluar</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('outgoing-letters.store') }}" method="POST">
          @csrf
  
          <div class="mb-3">
            <label class="form-label">Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" value="{{ old('nomor_surat') }}">
            @error('nomor_surat')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>
  
          <div class="mb-3">
            <label class="form-label">Tanggal Surat</label>
            <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat') }}">
            @error('tanggal_surat')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>
  
          <div class="mb-3">
            <label class="form-label">Tujuan</label>
            <input type="text" name="tujuan" class="form-control" value="{{ old('tujuan') }}">
            @error('tujuan')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>
  
          <div class="mb-3">
            <label class="form-label">Perihal</label>
            <input type="text" name="perihal" class="form-control" value="{{ old('perihal') }}">
            @error('perihal')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>
  
          <div class="mb-3">
            <label class="form-label">Isi Surat</label>
            <textarea id="isi_surat" name="isi_surat" class="form-control" rows="10">{{ old('isi_surat') }}</textarea>
            @error('isi_surat')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>
  
          <div class="mb-3">
            <label class="form-label">Penandatangan (opsional)</label>
            <input type="text" name="penandatangan" class="form-control" value="{{ old('penandatangan') }}">
            @error('penandatangan')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>
  
          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('outgoing-letters.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan & Lihat</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
@endsection

@section('scripts')
    {{-- Rich Text Editor --}}
    <link rel="stylesheet" href="{{ asset('richtexteditorforphp/richtexteditor/rte_theme_default.css') }}" />
    <script type="text/javascript" src="{{ asset('richtexteditorforphp/richtexteditor/rte.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var editor = new RichTextEditor("#isi_surat");
            editor.setHeight(350);
        });
    </script>
@endsection

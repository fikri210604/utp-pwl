
@extends('layouts.app')

@section('title', 'Detail Surat Keluar')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h4 m-0">Detail Surat Keluar</h1>
            <a href="{{ route('outgoing-letters.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type-="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card shadow-sm">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title mb-0">Surat</h5>
              <div class="btn-group" role="group" aria-label="Aksi Surat">
                <a href="{{ route('outgoing-letters.edit', $letter) }}" class="btn btn-sm btn-warning" title="Edit">
                  <i class="bi bi-pencil-fill"></i>
                </a>
                <a href="{{ route('outgoing-letters.pdf', $letter) }}" target="_blank" class="btn btn-sm btn-primary" title="Lihat/Cetak PDF">
                  <i class="bi bi-printer-fill"></i>
                </a>
                <button onclick="window.print()" class="btn btn-sm btn-info" title="Cetak via Browser">
                  <i class="bi bi-display"></i>
                </button>
                <form action="{{ route('outgoing-letters.destroy', $letter) }}" method="POST" onsubmit="confirmDelete(event)">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                    <i class="bi bi-trash-fill"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
          <div class="card-body">
            @php($view = optional($letter->perihalSurat)->template_view)
            @if($view && view()->exists($view))
              @include($view, ['letter' => $letter, 'preview' => false])
            @else
              <div class="text-muted">Template tidak ditemukan. Menampilkan konten lama.</div>
              {!! $letter->isi_surat !!}
            @endif
          </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: #fff; }
        .btn-group, .btn-secondary, .card-header .d-flex.justify-content-between a.btn-secondary {
            display: none !important;
        }
        .card { border: none !important; box-shadow: none !important; }
        .card-header { display: none !important; }
        .d-flex.align-items-center.justify-content-between.mb-3 { display: none !important; }
    }
</style>
@endsection

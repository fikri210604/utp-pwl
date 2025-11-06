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

        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Nomor: {{ $letter->nomor_surat }}</h5>
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
                {{-- KOP SURAT --}}
                <div class="text-center border-bottom border-2 border-dark pb-2 mb-4">
                    <h4 class="fw-bold mb-1">KOP SURAT INSTANSI</h4>
                    <p class="mb-0">Alamat, Telepon, Email</p>
                </div>

                {{-- Detail Surat --}}
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <strong>Nomor:</strong> {{ $letter->nomor_surat }}
                    </div>
                    <div>
                        <strong>Tanggal:</strong> {{ \Illuminate\Support\Carbon::parse($letter->tanggal_surat)->translatedFormat('d F Y') }}
                    </div>
                </div>

                <div class="mb-4">
                    <div>Kepada Yth.</div>
                    <div class="fw-bold">{{ $letter->tujuan }}</div>
                </div>

                <div class="mb-4">
                    <div class="fw-bold">Perihal: {{ $letter->perihal }}</div>
                </div>

                {{-- Isi Surat --}}
                <div style="min-height: 200px;" class="mb-5">
                    {!! $letter->isi_surat !!}
                </div>

                {{-- Tanda Tangan --}}
                <div class="d-flex justify-content-end mt-5">
                    <div class="text-center">
                        <div>Hormat kami,</div>
                        <div style="height: 80px;"></div>
                        <div class="fw-bold">{{ $letter->penandatangan ?: '................................' }}</div>
                    </div>
                </div>
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

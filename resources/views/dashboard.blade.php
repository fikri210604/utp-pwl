@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h4 m-0">Dashboard</h1>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-secondary small">Surat Masuk</div>
                        <div class="fs-4 fw-semibold">@isset($incomingCount){{ $incomingCount }}@else-&@endisset</div>
                    </div>
                    <a class="btn btn-sm btn-primary" href="{{ route('incoming-letters.index') }}">Lihat</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-secondary small">Surat Keluar</div>
                        <div class="fs-4 fw-semibold">@isset($outgoingCount){{ $outgoingCount }}@else-&@endisset</div>
                    </div>
                    <a class="btn btn-sm btn-primary" href="{{ route('outgoing-letters.index') }}">Lihat</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="mb-2 text-secondary small">Aksi Cepat</div>
                <div class="d-grid gap-2">
                    <a class="btn btn-outline-primary" href="{{ route('incoming-letters.create') }}">Tambah Surat Masuk</a>
                    <a class="btn btn-outline-primary" href="{{ route('outgoing-letters.create') }}">Tambah Surat Keluar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


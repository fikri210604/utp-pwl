@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
    <div class="row">
        {{-- Kolom Detail Surat --}}
        <div class="col-12 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Detail Surat</h5>
                        <div class="btn-toolbar">
                            <a href="{{ route('incoming-letters.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row detail-list">
                        <dt class="col-sm-4">Perihal</dt>
                        <dd class="col-sm-8">{{ $letter->perihal }}</dd>

                        <dt class="col-sm-4">Nomor Surat</dt>
                        <dd class="col-sm-8">{{ $letter->nomor_surat }}</dd>

                        <dt class="col-sm-4">Tanggal Surat</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($letter->tanggal_surat)->isoFormat('D MMMM Y') }}</dd>

                        <dt class="col-sm-4">Pengirim</dt>
                        <dd class="col-sm-8">{{ $letter->pengirim }}</dd>

                        <dt class="col-sm-4">Tanggal Diterima</dt>
                        <dd class="col-sm-8">
                            {{ $letter->tanggal_diterima ? \Carbon\Carbon::parse($letter->tanggal_diterima)->isoFormat('D MMMM Y') : '-' }}
                        </dd>

                        <dt class="col-sm-4">Keterangan</dt>
                        <dd class="col-sm-8">{{ $letter->keterangan ?? '-' }}</dd>
                    </dl>
                    <style>
                        .detail-list dt::after {
                            content: " :";
                            float: right;
                        }
                    </style>

                    <div class="d-flex justify-content-end">
                        <form action="{{ route('incoming-letters.destroy', $letter) }}" method="POST"
                            onsubmit="confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">
                                <i class="bi bi-trash-fill me-2"></i> Hapus Surat
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Pratinjau PDF --}}
        <div class="col-12 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pratinjau Berkas</h5>
                </div>
                <div class="card-body p-0">
                    @if ($letter->file_path)
                        <iframe src="{{ asset('storage/' . $letter->file_path) }}"
                            style="width: 100%; height: 75vh; border: none;"></iframe>
                    @else
                        <div class="p-4 text-center text-muted">
                            <p><i class="bi bi-x-circle fs-3"></i></p>
                            <p>Berkas tidak ditemukan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


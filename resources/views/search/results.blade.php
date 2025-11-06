@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
    <h1 class="h3 mb-4">Hasil Pencarian untuk "{{ $query }}"</h1>

    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Ditemukan {{ $results->count() }} hasil</h6>
        </div>
        <div class="card-body">
            @if($results->isEmpty())
                <div class="text-center p-4">
                    <p class="text-muted">Tidak ada hasil yang ditemukan.</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach ($results as $letter)
                        <a href="{{ $letter->type == 'in' ? route('incoming-letters.show', $letter->id) : route('outgoing-letters.show', $letter->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-{{ $letter->type == 'in' ? 'primary' : 'success' }} me-2">
                                    {{ $letter->type == 'in' ? 'Masuk' : 'Keluar' }}
                                </span>
                                <strong>{{ $letter->perihal }}</strong>
                                <small class="d-block text-muted">
                                    No: {{ $letter->nomor_surat }} | Tanggal: {{ \Carbon\Carbon::parse($letter->tanggal_surat)->format('d M Y') }}
                                </small>
                            </div>
                            <small>{{ \Carbon\Carbon::parse($letter->tanggal_surat)->diffForHumans() }}</small>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
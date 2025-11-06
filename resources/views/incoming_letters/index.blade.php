@extends('layouts.app')

@section('title','Surat Masuk')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h4 m-0">Daftar Surat Masuk</h1>
            <a href="{{ route('incoming-letters.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Tambah</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nomor Surat</th>
                                <th>Tanggal</th>
                                <th>Pengirim</th>
                                <th>Perihal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($letters as $letter)
                            <tr>
                                <td>{{ $letter->nomor_surat }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($letter->tanggal_surat)->format('d/m/Y') }}</td>
                                <td>{{ $letter->pengirim }}</td>
                                <td>{{ $letter->perihal }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton-{{ $letter->id }}" data-bs-toggle="dropdown" aria-expanded="false" title="Pilih Aksi">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $letter->id }}">
                                            <li><a class="dropdown-item" href="{{ route('incoming-letters.show', $letter) }}"><i class="bi bi-eye-fill me-2"></i>Detail</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('incoming-letters.destroy', $letter) }}" method="POST" onsubmit="confirmDelete(event)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger" type="submit"><i class="bi bi-trash-fill me-2"></i>Hapus</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Belum ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($letters->hasPages())
                <div class="mt-3">
                    {{ $letters->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
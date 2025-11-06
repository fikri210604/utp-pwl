@extends('layouts.app')

@section('title', 'Kode Surat')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h4 m-0">Daftar Kode Surat</h1>
            <a href="{{ route('letter_code.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Tambah</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type-="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                
                <form action="{{ route('letter_code.index') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari berdasarkan kode atau nama pihak..." 
                               value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                        <a href="{{ route('letter_code.index') }}" class="btn btn-outline-danger" title="Reset Pencarian"><i class="bi bi-x-lg"></i></a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Kode Pihak</th>
                                <th scope="col">Nama Pihak</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($letter_code as $kode)
                                <tr>
                                    <td>{{ $kode->kode_pihak }}</td>
                                    <td>{{ $kode->nama_pihak }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton-{{ $kode->id }}" data-bs-toggle="dropdown" aria-expanded="false" title="Pilih Aksi">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $kode->id }}">
                                                <li><a class="dropdown-item" href="{{ route('letter_code.edit', $kode) }}"><i class="bi bi-pencil-fill me-2"></i>Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('letter_code.destroy', $kode) }}" method="POST" onsubmit="return confirm('Yakin hapus data dengan kode: {{ $kode->kode_pihak }}?')">
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
                                <tr>
                                    <td colspan="3" class="text-center">
                                        @if(!empty($search))
                                            Data dengan kata kunci '{{ $search }}' tidak ditemukan.
                                        @else
                                            Belum ada data.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($letter_code->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $letter_code->appends(request()->except('page'))->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
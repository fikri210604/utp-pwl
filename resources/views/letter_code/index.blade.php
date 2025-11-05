@extends('layouts.app')

@section('title', 'Kode Surat')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h1 style="margin:0;">Daftar Kode Surat</h1>
            {{-- Perbaikan: route('letter_create.create') diubah menjadi 'letter_code.create' --}}
            <a href="{{ route('letter_code.create') }}" class="btn btn-primary">Tambah Kode Surat</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Kode Pihak</th>
                    <th>Nama Pihak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Variabel $letter_code ini sudah benar --}}
                @forelse($letter_code as $kode)
                    <tr>
                        <td>{{ $kode->kode_pihak }}</td>
                        <td>{{ $kode->nama_pihak }}</td>
                        <td>
                            {{-- Rute ini sudah benar --}}
                            <a class="btn btn-secondary" href="{{ route('letter_code.edit', $kode) }}">Edit</a>
                            
                            {{-- Rute ini sudah benar --}}
                            <form action="{{ route('letter_code.destroy', $kode) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus kode surat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination ini sudah benar --}}
        <div style="margin-top:1rem;">{{ $letter_code->links() }}</div>
    </div>
@endsection
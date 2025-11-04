@extends('layouts.app')

@section('title','Surat Masuk')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h1 style="margin:0;">Daftar Surat Masuk</h1>
            <a href="{{ route('incoming-letters.create') }}" class="btn">Tambah</a>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Pengirim</th>
                <th>Perihal</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($letters as $letter)
                <tr>
                    <td>{{ $letter->nomor_surat }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($letter->tanggal_surat)->format('d/m/Y') }}</td>
                    <td>{{ $letter->pengirim }}</td>
                    <td>{{ $letter->perihal }}</td>
                    <td>
                        <a class="btn btn-secondary" href="{{ route('incoming-letters.show', $letter) }}">Detail</a>
                        <form action="{{ route('incoming-letters.destroy', $letter) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button onclick="confirmDelete(event)" class="btn btn-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>

    </div>
@endsection


@extends('layouts.app')

@section('title','Surat Keluar')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h1 style="margin:0;">Daftar Surat Keluar</h1>
            <a href="{{ route('outgoing-letters.create') }}" class="btn">Buat Surat</a>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Tujuan</th>
                <th>Perihal</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($letters as $letter)
                <tr>
                    <td>{{ $letter->nomor_surat }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($letter->tanggal_surat)->format('d/m/Y') }}</td>
                    <td>{{ $letter->tujuan }}</td>
                    <td>{{ $letter->perihal }}</td>
                    <td>
                        <a class="btn btn-secondary" href="{{ route('outgoing-letters.show', $letter) }}">Lihat</a>
                        <a class="btn" href="{{ route('outgoing-letters.edit', $letter) }}">Edit</a>
                        <a class="btn" target="_blank" href="{{ route('outgoing-letters.pdf', $letter) }}">Lihat/Print PDF</a>
                        <form action="{{ route('outgoing-letters.destroy', $letter) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus surat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top:1rem;">{{ $letters->links() }}</div>
    </div>
@endsection

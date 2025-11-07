@extends('layouts.app')

@section('title', 'Penandatangan')

@section('content')
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="m-0">Daftar Penandatangan</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPenandatanganModal">
      <i class="bi bi-plus-circle me-1"></i> Tambah Penandatangan
    </button>
  </div>
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card mt-3">
    <div class="card-body">
      <form action="{{ route('penandatangan.index') }}" method="GET" class="mb-3">
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Cari nama / NIP-NPM / jabatan..." value="{{ $search ?? '' }}">
          <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
          <a href="{{ route('penandatangan.index') }}" class="btn btn-outline-danger" title="Reset Pencarian"><i class="bi bi-x-lg"></i></a>
        </div>
      </form>
    </div>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Nama</th><th>NIP/NPM</th><th>Jabatan</th><th>Tanda Tangan</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        @foreach($penandatangan as $p)
          <tr>
            <td>{{ $p->nama_penandatangan }}</td>
            <td>{{ $p->nip_npm_penandatangan }}</td>
            <td>{{ $p->jabatan_penandatangan }}</td>
            <td>
              @if($p->gambar_tandatangan)
                <img src="{{ asset('storage/' . $p->gambar_tandatangan) }}" alt="ttd" style="height:40px">
              @endif
            </td>
            <td class="text-end">
              <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdown-ttd-{{ $p->penandatangan_id }}" data-bs-toggle="dropdown" aria-expanded="false" title="Pilih Aksi">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-ttd-{{ $p->penandatangan_id }}">
                  <li>
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editPenandatanganModal"
                      data-id="{{ $p->penandatangan_id }}"
                      data-nama="{{ $p->nama_penandatangan }}"
                      data-nip="{{ $p->nip_npm_penandatangan }}"
                      data-jabatan="{{ $p->jabatan_penandatangan }}">
                      <i class="bi bi-pencil-fill me-2"></i>Edit
                    </a>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form action="{{ route('penandatangan.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus penandatangan ini?')">
                      @csrf @method('DELETE')
                      <button class="dropdown-item text-danger" type="submit"><i class="bi bi-trash-fill me-2"></i>Hapus</button>
                    </form>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $penandatangan->links() }}</div>
  </div>
  <!-- Modal: Create Penandatangan -->
  <div class="modal fade" id="createPenandatanganModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Penandatangan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('penandatangan.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Nama</label>
                <input type="text" name="nama_penandatangan" class="form-control" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">NIP/NPM</label>
                <input type="text" name="nip_npm_penandatangan" class="form-control">
              </div>
              <div class="col-md-3">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan_penandatangan" class="form-control" required>
              </div>
              <div class="col-md-2">
                <label class="form-label">Tanda Tangan (img)</label>
                <input type="file" name="gambar_tandatangan" class="form-control">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal: Edit Penandatangan -->
  <div class="modal fade" id="editPenandatanganModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Penandatangan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editPenandatanganForm" action="#" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Nama</label>
                <input type="text" name="nama_penandatangan" id="editNama" class="form-control" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">NIP/NPM</label>
                <input type="text" name="nip_npm_penandatangan" id="editNip" class="form-control">
              </div>
              <div class="col-md-3">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan_penandatangan" id="editJabatan" class="form-control" required>
              </div>
              <div class="col-md-2">
                <label class="form-label">Tanda Tangan (img)</label>
                <input type="file" name="gambar_tandatangan" class="form-control">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('show.bs.modal', function (event) {
  const target = event.target;
  if (target && target.id === 'editPenandatanganModal') {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const nama = button.getAttribute('data-nama');
    const nip = button.getAttribute('data-nip');
    const jabatan = button.getAttribute('data-jabatan');

    document.getElementById('editNama').value = nama || '';
    document.getElementById('editNip').value = nip || '';
    document.getElementById('editJabatan').value = jabatan || '';
    document.getElementById('editPenandatanganForm').setAttribute('action', `{{ url('penandatangan') }}/${id}`);
  }
});
</script>
@endsection

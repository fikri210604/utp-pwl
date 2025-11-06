@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Manajemen User</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">+ Tambah User</button>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="text-center">#</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Role</th>
              <th>Jabatan</th>
              <th>Tanda Tangan</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse($user as $u)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $u->name }}</td>
              <td>{{ $u->email }}</td>
              <td><span class="badge bg-secondary text-uppercase">{{ $u->role }}</span></td>
              <td>{{ $u->jabatan }}</td>
              <td>
                @if ($u->tanda_tangan)
                  <img src="{{ asset('storage/'.$u->tanda_tangan) }}" alt="Tanda tangan {{ $u->name }}" class="img-thumbnail" style="max-height:48px;">
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td class="text-center">
                <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton-{{ $u->id }}" data-bs-toggle="dropdown" aria-expanded="false" title="Pilih Aksi">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $u->id }}">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $u->id }}"><i class="bi bi-pencil-fill me-2"></i>Edit</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteUserModal-{{ $u->id }}"><i class="bi bi-trash-fill me-2"></i>Hapus</a></li>
                    </ul>
                </div>
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editUserModal-{{ $u->id }}" tabindex="-1" aria-labelledby="editUserModalLabel-{{ $u->id }}" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel-{{ $u->id }}">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('user_manajemen.update', $u) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label">Nama</label>
                          <input type="text" name="name" class="form-control" value="{{ old('name', $u->name) }}" required>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Email</label>
                          <input type="email" name="email" class="form-control" value="{{ old('email', $u->email) }}" required>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Password</label>
                          <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Role</label>
                          <input type="text" name="role" class="form-control" value="{{ old('role', $u->role) }}" required>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Jabatan</label>
                          <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $u->jabatan) }}" required>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Tanda Tangan (opsional)</label>
                          <input type="file" name="tanda_tangan" class="form-control" accept="image/*">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteUserModal-{{ $u->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel-{{ $u->id }}" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel-{{ $u->id }}">Hapus User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('user_manajemen.destroy', $u) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                      <p>Yakin ingin menghapus user <strong>{{ $u->name }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">Belum ada data user</td>
            </tr>
          @endforelse
          </tbody>
      </div>

      @if ($user->hasPages())
      <div class="p-3 border-top">
        {{ $user->links() }}
      </div>
      @endif
    </div>
  </div>

  <!-- Create Modal -->
  <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createUserModalLabel">Tambah User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('user_manajemen.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Role</label>
                <input type="text" name="role" class="form-control" value="{{ old('role') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Tanda Tangan (opsional)</label>
                <input type="file" name="tanda_tangan" class="form-control" accept="image/*">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger mt-3">
      <strong>Terjadi kesalahan:</strong>
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
@endsection

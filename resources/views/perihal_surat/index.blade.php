@extends('layouts.app')

@section('title', 'Perihal Surat')

@section('content')
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="m-0">Perihal Surat</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPerihalModal">
      <i class="bi bi-plus-circle me-1"></i> Tambah Perihal
    </button>
  </div>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row g-3">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('perihal_surat.index') }}" method="GET" class="mb-3">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Cari nama perihal / jenis / view..." value="{{ $search ?? '' }}">
              <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
              <a href="{{ route('perihal_surat.index') }}" class="btn btn-outline-danger" title="Reset Pencarian"><i class="bi bi-x-lg"></i></a>
            </div>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Nama</th><th>Jenis</th><th>Template View</th><th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
            @foreach($perihal_surat as $p)
              <tr>
                <td>{{ $p->nama_perihal }}</td>
                <td>{{ ucfirst($p->jenis_surat) }}</td>
                <td><code>{{ $p->template_view }}</code></td>
                <td class="text-end">
                  <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdown-perihal-{{ $p->perihal_surat_id }}" data-bs-toggle="dropdown" aria-expanded="false" title="Pilih Aksi">
                      <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-perihal-{{ $p->perihal_surat_id }}">
                      <li>
                        <a href="#" class="dropdown-item"
                           data-bs-toggle="modal" data-bs-target="#editPerihalModal"
                           data-id="{{ $p->perihal_surat_id }}"
                           data-nama="{{ $p->nama_perihal }}"
                           data-jenis="{{ $p->jenis_surat }}"
                           data-template="{{ $p->template_view }}">
                           <i class="bi bi-pencil-fill me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a href="#" class="dropdown-item" onclick="previewPerihal({{ $p->perihal_surat_id }}); return false;">
                          <i class="bi bi-eye-fill me-2"></i>Preview
                        </a>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('perihal_surat.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus perihal ini?')">
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
        <div class="card-footer d-flex justify-content-between align-items-center">
          <div class="text-muted small">
            Menampilkan {{ $perihal_surat->firstItem() ?? 0 }}-{{ $perihal_surat->lastItem() ?? 0 }} dari {{ $perihal_surat->total() }} data
          </div>
          <div>
            @if ($perihal_surat->hasPages())
              {{ $perihal_surat->appends(request()->except('page'))->links() }}
            @endif
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card h-100">
        <div class="card-header">
          <strong>Preview Template</strong>
        </div>
        <div class="card-body">
          <div id="perihalTemplatePreview" class="border rounded p-3">
            <div class="text-muted">Klik Preview pada salah satu perihal.</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Create Perihal -->
  <div class="modal fade" id="createPerihalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Perihal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('perihal_surat.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama Perihal</label>
                <input type="text" name="nama_perihal" class="form-control" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Jenis</label>
                <select name="jenis_surat" class="form-select" required>
                  <option value="undangan">Undangan</option>
                  <option value="peminjaman">Peminjaman</option>
                  <option value="lainnya">Lainnya</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Blade View Template</label>
                <input type="text" name="template_view" class="form-control" placeholder="templates.undangan" required>
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

  <!-- Modal: Edit Perihal (generic) -->
  <div class="modal fade" id="editPerihalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Perihal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editPerihalForm" action="#" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama Perihal</label>
                <input type="text" name="nama_perihal" id="editNamaPerihal" class="form-control" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Jenis</label>
                <select name="jenis_surat" id="editJenisSurat" class="form-select" required>
                  <option value="undangan">Undangan</option>
                  <option value="peminjaman">Peminjaman</option>
                  <option value="lainnya">Lainnya</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Blade View Template</label>
                <input type="text" name="template_view" id="editTemplateView" class="form-control" placeholder="templates.undangan" required>
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
async function previewPerihal(id) {
  const box = document.getElementById('perihalTemplatePreview');
  if (!box) return;
  box.innerHTML = '<div class="text-muted">Memuat preview...</div>';
  try {
    const res = await fetch(`{{ route('outgoing-letters.template-preview') }}?perihal_surat_id=${id}`);
    const html = await res.text();
    box.innerHTML = html;
  } catch (e) {
    box.innerHTML = '<div class="text-danger">Gagal memuat preview.</div>';
  }
}

// Populate edit modal on show
document.addEventListener('show.bs.modal', function (event) {
  const target = event.target;
  if (target && target.id === 'editPerihalModal') {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const nama = button.getAttribute('data-nama');
    const jenis = button.getAttribute('data-jenis');
    const template = button.getAttribute('data-template');

    document.getElementById('editNamaPerihal').value = nama || '';
    document.getElementById('editJenisSurat').value = jenis || 'undangan';
    document.getElementById('editTemplateView').value = template || '';
    document.getElementById('editPerihalForm').setAttribute('action', `{{ url('perihal_surat') }}/${id}`);
  }
});
</script>
@endsection

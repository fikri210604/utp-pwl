@extends('layouts.app')

@section('title', 'Edit Surat Keluar')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">✏️ Edit Surat Keluar</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('outgoing-letters.update', $letter) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Nomor Surat</label>
            <input type="text" id="nomorSuratInput" name="nomor_surat" class="form-control" value="{{ old('nomor_surat', $letter->nomor_surat) }}" required>
            <div class="form-text">Pastikan unik dan sesuai format kebijakan instansi.</div>
            @error('nomor_surat')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Surat</label>
            <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', $letter->tanggal_surat) }}">
            @error('tanggal_surat')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Penuju Surat</label>
            @php($penuju = old('penuju_surat', $letter->penuju))
            <select id="penujuSelect" name="penuju_surat" class="form-select">
              <option value="">-- Pilih Penuju Surat --</option>
              <option value="A" @selected($penuju==='A')>A (Dalam Lingkup Universitas Lampung)</option>
              <option value="B" @selected($penuju==='B')>B (Luar Lingkup Universitas Lampung)</option>
              <option value="C" @selected($penuju==='C')>C (Surat Khusus)</option>
            </select>
            <div class="form-text">Mengubah penuju akan memperbarui segmen ke-2 nomor surat.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Tujuan</label>
            <input type="text" name="tujuan" class="form-control" value="{{ old('tujuan', $letter->tujuan) }}">
            @error('tujuan')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Jenis Surat / Perihal</label>
            <select name="perihal_surat_id" id="perihalSelect" class="form-select" required>
              <option value="">-- Pilih Perihal --</option>
              @foreach($perihal_surats as $p)
                <option value="{{ $p->id }}" data-jenis="{{ $p->jenis_surat }}" data-template="{{ $p->template }}" {{ (string)old('perihal_surat_id', $letter->perihal_surat_id) === (string)$p->id ? 'selected' : '' }}>
                  {{ $p->nama_perihal }} ({{ ucfirst($p->jenis_surat) }})
                </option>
              @endforeach
            </select>
            @error('perihal_surat_id')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Preview Template</label>
            <div id="templatePreviewBox" class="border rounded p-3">
              <div class="text-muted">Pilih perihal untuk melihat template.</div>
            </div>
          </div>

          <div id="acaraFields" class="border rounded p-3 mb-3 d-none">
            <h6 class="fw-bold">Detail Acara / Kegiatan</h6>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan', $letter->nama_kegiatan) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Lokasi Acara</label>
                <input type="text" name="lokasi_acara" class="form-control" value="{{ old('lokasi_acara', $letter->lokasi_acara) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Hari & Tanggal</label>
                <input type="text" name="hari_tanggal" class="form-control" value="{{ old('hari_tanggal', $letter->hari_tanggal) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Waktu Acara</label>
                <input type="text" name="waktu_acara" class="form-control" value="{{ old('waktu_acara', $letter->waktu_acara) }}">
              </div>
            </div>
            <div class="mt-3">
              <label class="form-label">Keterangan Tambahan</label>
              <textarea name="isi_tambahan" class="form-control" rows="3">{{ old('isi_tambahan', $letter->isi_tambahan) }}</textarea>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('outgoing-letters.show', $letter) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
@endsection

@section('scripts')
    <script>
function updatePerihalUI() {
  const select = document.getElementById('perihalSelect');
  if (!select) return;
  const opt = select.options[select.selectedIndex];
  const jenis = opt ? (opt.dataset.jenis || '') : '';
  const showAcara = (jenis === 'undangan' || jenis === 'peminjaman');
  document.getElementById('acaraFields').classList.toggle('d-none', !showAcara);
}

async function loadTemplatePreview() {
  const box = document.getElementById('templatePreviewBox');
  const select = document.getElementById('perihalSelect');
  const id = select?.value;
  if (!id) {
    box.innerHTML = '<div class="text-muted">Pilih perihal untuk melihat template.</div>';
    return;
  }
  box.innerHTML = '<div class="text-muted">Memuat preview template...</div>';
  try {
    const res = await fetch(`{{ route('outgoing-letters.template-preview') }}?perihal_surat_id=${encodeURIComponent(id)}`);
    const html = await res.text();
    box.innerHTML = html;
  } catch (e) {
    box.innerHTML = '<div class="text-danger">Gagal memuat preview template.</div>';
  }
}

document.getElementById('perihalSelect').addEventListener('change', function() {
  updatePerihalUI();
  loadTemplatePreview();
});

// Tambah/Hapus Penandatangan
document.getElementById('addPenandatangan').addEventListener('click', function() {
  let row = document.querySelector('.penandatangan-row').cloneNode(true);
  row.querySelector('.removeRow').classList.remove('d-none');
  row.querySelector('select').value = '';
  document.getElementById('penandatanganWrapper').appendChild(row);
});

document.addEventListener('click', function(e){
  if(e.target.classList.contains('removeRow')){
    if(document.querySelectorAll('.penandatangan-row').length > 1){
      e.target.parentElement.remove();
    }
  }
});

// Sinkron penuju -> nomor surat (replace segmen ke-2)
document.getElementById('penujuSelect').addEventListener('change', function(){
  const val = this.value;
  const input = document.getElementById('nomorSuratInput');
  if (!input || !val) return;
  const parts = input.value.split('/');
  if (parts.length >= 2) {
    parts[1] = val;
    input.value = parts.join('/');
  }
});

document.addEventListener('DOMContentLoaded', function(){
  updatePerihalUI();
  loadTemplatePreview();
});
    </script>
@endsection


@extends('layouts.app')

@section('title', 'Buat Surat Keluar')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>📄 Buat Surat Keluar</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('outgoing-letters.store') }}" method="POST">
                @csrf

                {{-- Prefix / Kode Pihak --}}
                <div class="mb-3">
                    <label class="form-label">Pihak Pengirim(Prefix Surat)</label>
                    <select name="nomor_surat_id" class="form-select" required>
                        <option value="">-- Pilih Pihak --</option>
                        @foreach($nomor_surats as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_pihak }} ({{ $p->kode_pihak }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" class="form-control" required>
                </div>

                {{-- Penuju Surat --}}
                <div class="mb-3">
                    <label class="form-label">Penuju Surat</label>
                    <select name="penuju_surat" class="form-select" required>
                        <option value="">-- Pilih Penuju Surat --</option>
                        <option value="A">A (Dalam Lingkup Universitas Lampung)</option>
                        <option value="B">B (Luar Lingkup Universitas Lampung)</option>
                        <option value="C">C (Surat Khusus)</option>
                    </select>
                </div>

                {{-- Tujuan --}}
                <div class="mb-3">
                    <label class="form-label">Tujuan</label>
                    <input type="text" name="tujuan" class="form-control" required>
                </div>

                {{-- Jenis / Perihal Surat --}}
                <div class="mb-3">
                    <label class="form-label">Perihal Surat</label>
                    <select name="perihal_surat_id" id="perihalSelect" class="form-select" required>
                        <option value="">-- Pilih Perihal --</option>
                        @foreach($perihal_surats as $p)
                        <option value="{{ $p->id }}" data-template="{{ $p->template }}" data-jenis="{{ $p->jenis_surat }}">
                            {{ $p->nama_perihal }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Preview Template Berdasarkan Perihal --}}
                <div class="mb-3">
                    <label class="form-label">Preview Template</label>
                    <div id="templatePreviewBox" class="border rounded p-3">
                        <div class="text-muted">Pilih perihal untuk melihat template.</div>
                    </div>
                </div>

                {{-- Field Dinamis Kegiatan --}}
                <div id="acaraFields" class="d-none border rounded p-3 mb-3">
                    <h6 class="fw-bold">Detail Acara / Kegiatan</h6>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Lokasi Acara</label>
                            <input type="text" name="lokasi_acara" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Hari & Tanggal</label>
                            <input type="text" name="hari_tanggal" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Waktu Acara</label>
                            <input type="text" name="waktu_acara" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- Catatan Tambahan --}}
                <div class="mb-3">
                    <label class="form-label">Catatan Tambahan (Opsional)</label>
                    <textarea name="isi_tambahan" class="form-control" rows="3"></textarea>
                </div>

                {{-- Multiple Penandatangan --}}
                <label class="form-label fw-bold">Penandatangan</label>
                <div id="penandatanganWrapper">
                  <div class="input-group mb-2 penandatangan-row">
                    <select name="penandatangan_ids[]" class="form-select" required>
                        <option value="">-- Pilih Penandatangan --</option>
                        @foreach($penandatangans as $pt)
                        <option value="{{ $pt->id }}">{{ $pt->nama_penandatangan }} - {{ $pt->jabatan_penandatangan }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger removeRow d-none">✕</button>
                </div>
                
                </div>

                <button type="button" class="btn btn-sm btn-outline-primary" id="addPenandatangan">+ Tambah Penandatangan</button>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('outgoing-letters.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary ms-2">Simpan</button>
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

document.addEventListener('DOMContentLoaded', function() {
    updatePerihalUI();
    loadTemplatePreview();
});

// Tambah/Hapus Penandatangan
document.getElementById('addPenandatangan').addEventListener('click', function() {
    let row = document.querySelector('.penandatangan-row').cloneNode(true);
    row.querySelector('.removeRow').classList.remove('d-none'); // tombol hapus aktif
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
</script>
@endsection

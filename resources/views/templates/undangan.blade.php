
@php
    $isPreview = isset($preview) && $preview;
    $L = $letter ?? null;
@endphp

<style>
  .letter-container { font-size: 12pt; line-height: 1.45; }
  .letter-section { margin-bottom: 14px; }
  .signers { margin-top: 60px; }
  .signers .slot { text-align: right; margin-bottom: 40px; }
  .meta { margin-top: 6px; margin-bottom: 12px; }
  .meta .row { display: flex; justify-content: space-between; }
</style>

<div class="letter-container">
  @include('templates.partials.kop')
  <div class="text-center letter-section">
    <strong>SURAT UNDANGAN</strong>
  </div>
  <div class="mb-2">Nomor: {{ $L->nomor_surat ?? '____/____/____' }}</div>
  <div class="mb-2">Tanggal: {{ isset($L->tanggal_surat) ? \Illuminate\Support\Carbon::parse($L->tanggal_surat)->format('d/m/Y') : '___/___/____' }}</div>
  <div class="mb-3">Kepada Yth: {{ $L->tujuan ?? '________________________' }}</div>

  <p>
    Dengan hormat, kami mengundang Saudara untuk menghadiri kegiatan berikut:
  </p>
  <ul>
    <li>Nama Kegiatan: {{ $L->nama_kegiatan ?? '________' }}</li>
    <li>Tempat: {{ $L->lokasi_acara ?? '________' }}</li>
    <li>Hari/Tanggal: {{ $L->hari_tanggal ?? '________' }}</li>
    <li>Waktu: {{ $L->waktu_acara ?? '________' }}</li>
  </ul>

  @if(!$isPreview && !empty($L?->isi_tambahan))
    <p>{!! nl2br(e($L->isi_tambahan)) !!}</p>
  @else
    <p class="text-muted">(Tambahan informasi undangan ditampilkan di sini)</p>
  @endif

  <div class="signers">
    <div class="text-end">Hormat kami,</div>
    <div class="row mt-4" style="display:flex; gap: 16px; justify-content: flex-end;">
      @php
        $signers = $L?->penandatangans ?? collect([]);
        if ($signers->isEmpty() && $L?->penandatangan) { $signers = collect([$L->penandatangan]); }
      @endphp
      @forelse($signers as $s)
        <div class="slot">
          <div style="height:90px"></div>
          <div><strong>{{ $s->nama_penandatangan }}</strong></div>
          <div>{{ $s->jabatan_penandatangan }}</div>
        </div>
      @empty
        <div class="slot"><strong>________________</strong></div>
      @endforelse
    </div>
  </div>
</div>

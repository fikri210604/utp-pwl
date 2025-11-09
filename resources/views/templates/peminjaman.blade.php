@php
  $isPreview = isset($preview) && $preview;
  $L = $letter ?? null;
@endphp

<style>
  .letter-container {
    font-size: 12pt;
    line-height: 1.45;
  }

  .letter-section {
    margin-bottom: 14px;
  }

  .signers {
    margin-top: 60px;
  }

  .signers .slot {
    text-align: right;
    margin-bottom: 40px;
  }

  .meta {
    margin-top: 6px;
    margin-bottom: 12px;
  }

  .meta .row {
    display: flex;
    justify-content: space-between;
  }
</style>

<div class="letter-container">
  @include('templates.partials.kop')
  <div class="text-center letter-section">
    <strong>SURAT PEMINJAMAN</strong>
  </div>
  <div class="mb-2">Perihal: Peminjaman - {{ $L->perihal ?? '________________' }}</div>
  <div class="mb-2">Nomor: {{ $L->nomor_surat ?? '____/____/____' }}</div>
  <div class="mb-2">Lampiran: -</div>
  <div class="mb-3">Kepada Yth: {{ $L->tujuan ?? '________________________' }}</div>

  <p>Assalamualaikum Warahmatullahi Wabarakatuh</p>
  <p>
    Segala puji bagi Allah Dzat yang telah menciptakan langit dan bumi beserta isinya. Shalawat serta salam semoga tetap tercurah kepada pemimpin terbaik umat, suri tauladan kita yaitu Rasulullah Muhammad SAW.
  </p>
  <p>
    Sehubungan dengan diadakannya {{ $L->nama_kegiatan ?? 'kegiatan' }} maka kami bermaksud meminta izin untuk meminjam ruangan untuk acara tersebut yang Insya Allah akan dilaksanakan pada:
  </p>
  <div class="mb-2">
    Hari, Tanggal           : {{ $L->hari_tanggal ?? (isset($L->tanggal_surat) ? \Illuminate\Support\Carbon::parse($L->tanggal_surat)->translatedFormat('l, d F Y') : '________') }}<br>
    Tempat                  : {{ $L->lokasi_acara ?? '________' }}<br>
    Waktu                   : {{ $L->waktu_acara ?? '________' }}
  </div>
  <p>
    Demikian surat ini kami buat. Atas bantuan dan partisipasi dalam mensukseskan acara kami ucapkan terima kasih. Semoga Allah SWT melindungi dan membalas segala amal perbuatan kita.
  </p>
  <p>
    <em>Intansyurullaha yansyurkum wayutsabbit aqdamakum</em> — Jika kalian menolong agama Allah, maka Allah akan menolong kalian dan menguatkan pijakan kalian.
  </p>
  <p>(QS. Muhammad: 7)</p>
  <p>Wassalamualaikum Warahmatullahi Wabarakatuh</p>

  <div class="signers">
    <div class="text-end">Hormat kami,</div>
    @php
      $signers = $L?->penandatangans ?? collect([]);
      if ($signers->isEmpty() && $L?->penandatangan) {
        $signers = collect([$L->penandatangan]);
      }
    @endphp
    @forelse($signers->chunk(2) as $row)
      <div class="row d-flex justify-content-between mt-4 mb-4">
        @if($row->count() === 1)
          <div class="slot" style="width:48%;"></div>
          @php $s = $row[0]; @endphp
          <div class="slot text-center" style="width:48%;">
            <div style="height:90px"></div>
            <div>{{ $s->jabatan_penandatangan }}</div>
            <div>
              @php
                $__p = public_path('storage/' . ($s->gambar_tandatangan ?? ''));
                $__img = (is_file($__p)) ? ('data:image/'.pathinfo($__p, PATHINFO_EXTENSION).';base64,'.base64_encode(@file_get_contents($__p))) : null;
              @endphp
              @if($__img)
                <img src="{{ $__img }}" alt="Ttd" style="height:60px;">
              @endif
            </div>
            <div><strong>{{ $s->nama_penandatangan }}</strong></div>
            <div><strong>NPM/NIP:</strong> {{ $s->nip_npm_penandatangan }}</div>
          </div>
        @else
          @foreach($row as $s)
            <div class="slot text-center" style="width:48%;">
              <div style="height:90px"></div>
              <div>{{ $s->jabatan_penandatangan }}</div>
              <div>
                @php
                  $__p = public_path('storage/' . ($s->gambar_tandatangan ?? ''));
                  $__img = (is_file($__p)) ? ('data:image/'.pathinfo($__p, PATHINFO_EXTENSION).';base64,'.base64_encode(@file_get_contents($__p))) : null;
                @endphp
                @if($__img)
                  <img src="{{ $__img }}" alt="Ttd" style="height:60px;">
                @endif
              </div>
              <div><strong>{{ $s->nama_penandatangan }}</strong></div>
              <div><strong>NPM/NIP:</strong> {{ $s->nip_npm_penandatangan }}</div>
            </div>
          @endforeach
        @endif
      </div>
    @empty
      <div class="row mt-4">
        <div class="slot"><strong>________________</strong></div>
      </div>
    @endforelse
  </div>

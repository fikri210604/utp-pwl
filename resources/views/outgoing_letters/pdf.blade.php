<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keluar - {{ $letter->nomor_surat }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; }
        .kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 16px; }
        .kop .title { font-size: 18px; font-weight: 700; }
        .kop .sub { font-size: 12px; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 16px; }
        .section { margin-bottom: 12px; }
        .bold { font-weight: 700; }
        .ttd { margin-top: 40px; display: flex; justify-content: flex-end; }
        .ttd .box { text-align: left; }
        .isi { white-space: pre-wrap; min-height: 200px; }
    </style>
    @php
        \Carbon\Carbon::setLocale('id');
    @endphp
</head>
<body>
    <div class="kop">
        <div class="title">KOP SURAT INSTANSI</div>
        <div class="sub">Alamat, Telepon, Email</div>
    </div>

    <div class="meta">
        <div>Nomor: {{ $letter->nomor_surat }}</div>
        <div>Tanggal: {{ \Illuminate\Support\Carbon::parse($letter->tanggal_surat)->translatedFormat('d F Y') }}</div>
    </div>

    <div class="section">
        <div>Kepada Yth.</div>
        <div class="bold">{{ $letter->tujuan }}</div>
    </div>

    <div class="section bold">Perihal: {{ $letter->perihal }}</div>

    <div class="section isi">{!! $letter->isi_surat !!}</div>

    <div class="ttd">
        <div class="box">
            <div>Hormat kami,</div>
            <div style="height:80px;"></div>
            <div class="bold">{{ $letter->penandatangan ?: '................................' }}</div>
        </div>
    </div>
</body>
</html>


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
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .meta-table td { vertical-align: top; }
        .section { margin-bottom: 12px; }
        .bold { font-weight: 700; }
        .ttd { margin-top: 40px; }
        .ttd-table { width: 100%; border-collapse: collapse; }
        .ttd-table td { width: 50%; text-align: center; padding: 0 6px; }
        .ttd-slot .jabatan { margin-bottom: 6px; }
        .ttd-slot .ttd-image { height: 60px; margin: 6px 0; }
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

    <table class="meta-table">
        <tr>
            <td>Nomor: {{ $letter->nomor_surat }}</td>
            <td>Lampiran: - </td>
        </tr>
    </table>

    <div class="section">
        <div>Kepada Yth.</div>
        <div class="bold">{{ $letter->tujuan }}</div>
    </div>

    @php
        $jenis = optional($letter->perihalSurat)->jenis_surat;
    @endphp
    <div class="section bold">Perihal: {{ $jenis ? ucfirst($jenis) . ' - ' : '' }}{{ $letter->perihal }}</div>

    <div class="section isi">
        @if($jenis === 'peminjaman')
            <p>Assalamualaikum Warahmatullahi Wabarakatuh</p>
            <p>
                Segala puji bagi Allah Dzat yang telah menciptakan langit dan bumi beserta isinya. Shalawat serta salam semoga tetap tercurah kepada pemimpin terbaik umat, suri tauladan kita yaitu Rasulullah Muhammad SAW.
            </p>
            <p>
                Sehubungan dengan diadakannya {{ $letter->nama_kegiatan ?? 'kegiatan' }} maka kami bermaksud meminta izin untuk meminjam ruangan untuk acara tersebut yang Insya Allah akan dilaksanakan pada:
            </p>
            <p>
                Hari, Tanggal           : {{ $letter->hari_tanggal ?? (isset($letter->tanggal_surat) ? \Illuminate\Support\Carbon::parse($letter->tanggal_surat)->translatedFormat('l, d F Y') : '________') }}<br>
                Tempat                  : {{ $letter->lokasi_acara ?? '________' }}<br>
                Waktu                   : {{ $letter->waktu_acara ?? '________' }}
            </p>
            <p>
                Demikian surat ini kami buat. Atas bantuan dan partisipasi dalam mensukseskan acara kami ucapkan terima kasih. Semoga Allah SWT melindungi dan membalas segala amal perbuatan kita.
            </p>
            <p>
                <em>Intansyurullaha yansyurkum wayutsabbit aqdamakum</em> — Jika kalian menolong agama Allah, maka Allah akan menolong kalian dan menguatkan pijakan kalian.
            </p>
            <p>(QS. Muhammad: 7)</p>
            <p>Wassalamualaikum Warahmatullahi Wabarakatuh</p>
        @elseif($jenis === 'undangan')
            <p>Assalamualaikum Warahmatullahi Wabarakatuh</p>
            <p>
                Segala puji bagi Allah Dzat  yang telah menciptakan langit dan bumi beserta isinya. Shalawat serta salam semoga tetap tercurah kepada pemimpin terbaik umat, suri tauladan kita yaitu Rasulullah Muhammad SAW.
            </p>
            <p>
                Sehubungan dengan diadakannya {{ $letter->nama_kegiatan ?? 'acara' }} maka kami bermaksud   mengundang   Saudara   untuk   hadir   dalam   acara   yang   Insya  Allah   akan dilaksanakan pada:
            </p>
            <p>
                Hari, Tanggal           : {{ $letter->hari_tanggal ?? (isset($letter->tanggal_surat) ? \Illuminate\Support\Carbon::parse($letter->tanggal_surat)->translatedFormat('l, d F Y') : '________') }}<br>
                Tempat                  : {{ $letter->lokasi_acara ?? '________' }}<br>
                Waktu                   : {{ $letter->waktu_acara ?? '________' }}
            </p>
            <p>
                Demikian surat ini kami buat. Atas bantuan dan partisipasi dalam mengsukseskan acara kami ucapkan terimakasih. Semoga Allah SWT melindungi dan membalas segala amal perbuatan kita.
            </p>
            <p>
                <em>Intansyurullaha yansyurkum wayutsabbit aqdamakum</em> Jika kalian menolong agama Allah, maka Allah akan menolong kalian dan menguatkan pijakan kalian.
            </p>
            <p>(QS. Muhammad: 7)</p>
            <p>Wassalamualaikum Warahmatullahi Wabarakatuh</p>
        @else
            {!! $letter->isi_surat !!}
        @endif
    </div>

    <div class="ttd">
        <div style="text-align: right;">Hormat kami,</div>
        @php
            $signers = $letter?->penandatangans ?? collect([]);
            if ($signers->isEmpty() && $letter?->penandatangan) { $signers = collect([$letter->penandatangan]); }
        @endphp
        @if($signers->isEmpty())
            <table class="ttd-table"><tr><td>................................</td><td></td></tr></table>
        @else
            @foreach($signers->chunk(2) as $row)
                <table class="ttd-table" style="margin-bottom: 24px;">
                    <tr>
                        @if($row->count() === 1)
                            <td></td>
                            <td>
                                <div class="ttd-slot">
                                    <div class="jabatan">{{ $row[0]->jabatan_penandatangan }}</div>
                                    @if(!empty($row[0]->gambar_tandatangan))
                                        <img class="ttd-image" src="{{ public_path('storage/' . $row[0]->gambar_tandatangan) }}" alt="Ttd">
                                    @else
                                        <div style="height:60px"></div>
                                    @endif
                                    <div class="bold">{{ $row[0]->nama_penandatangan }}</div>
                                    <div><strong>NPM/NIP:</strong> {{ $row[0]->nip_npm_penandatangan }}</div>
                                </div>
                            </td>
                        @else
                            @foreach($row as $s)
                                <td>
                                    <div class="ttd-slot">
                                        <div class="jabatan">{{ $s->jabatan_penandatangan }}</div>
                                        @if(!empty($s->gambar_tandatangan))
                                            <img class="ttd-image" src="{{ public_path('storage/' . $s->gambar_tandatangan) }}" alt="Ttd">
                                        @else
                                            <div style="height:60px"></div>
                                        @endif
                                        <div class="bold">{{ $s->nama_penandatangan }}</div>
                                        <div><strong>NPM/NIP:</strong> {{ $s->nip_npm_penandatangan }}</div>
                                    </div>
                                </td>
                            @endforeach
                        @endif
                    </tr>
                </table>
            @endforeach
        @endif
    </div>
</body>
</html>

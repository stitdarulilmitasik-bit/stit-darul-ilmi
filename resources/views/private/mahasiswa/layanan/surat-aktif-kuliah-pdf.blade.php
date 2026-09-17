@php
    $logo = null;
    $candidates = [storage_path('app/public/images/logo/logo-vert.png'), public_path('storage/images/logo/logo-vert.png')];
    foreach ($candidates as $candidate) {
        if (is_file($candidate)) {
            $mime = mime_content_type($candidate) ?: 'image/png';
            $logo = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($candidate));
            break;
        }
    }
    $tgl = \Carbon\Carbon::parse($tanggal_surat)->locale('id')->translatedFormat('d F Y');
@endphp
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><style>
@page{margin:18mm 18mm 16mm 22mm}body{font-family:DejaVu Sans,Arial,sans-serif;font-size:11pt;line-height:1.45;color:#111}.kop{border-bottom:3px solid #111;padding-bottom:8px;margin-bottom:18px}.kop-table{width:100%;border-collapse:collapse}.kop-logo{width:80px;text-align:center}.kop-logo img{width:62px;height:62px;object-fit:contain}.kop-text{text-align:center}.kop-text .a{font-size:14pt;font-weight:bold}.kop-text .b{font-size:17pt;font-weight:bold}.kop-text .c{font-size:9.5pt}.kop-text .d{font-size:9pt}.title{text-align:center;font-weight:bold;font-size:13pt;text-decoration:underline;margin-top:5px}.nomor{text-align:center;margin-bottom:20px}.row{display:table;width:100%;margin:3px 0}.label{display:table-cell;width:190px}.value{display:table-cell}.indent{margin-left:22px}.ttd{width:100%;margin-top:30px;border-collapse:collapse}.ttd td{vertical-align:top}.right{text-align:center;width:45%}.tembusan{margin-top:28px;font-size:9.5pt}.signature-space{height:70px}.small{font-size:9.5pt}
</style></head>
<body>
<div class="kop"><table class="kop-table"><tr><td class="kop-logo">@if($logo)<img src="{{ $logo }}">@endif</td><td class="kop-text"><div class="a">SEKOLAH TINGGI ILMU TARBIYAH</div><div class="b">STIT DARUL ILMI TASIKMALAYA</div><div class="c">SK Menteri Agama RI No. 536 Tahun 2026</div><div class="d">Alamat : Jl. Cirahayu Sindangraja Jamanis Kabupaten Tasikmalaya Jawa Barat 46175</div></td></tr></table></div>
<div class="title">SURAT KETERANGAN AKTIF KULIAH</div>
<div class="nomor">Nomor : {{ $nomor_surat }}</div>
<p>Yang bertandatangan di bawah ini:</p>
<div class="row"><div class="label">Nama</div><div class="value">: {{ $pejabat_nama }}</div></div>
<div class="row"><div class="label">NIP/NIK</div><div class="value">: {{ $pejabat_nip }}</div></div>
<div class="row"><div class="label">Jabatan</div><div class="value">: {{ $pejabat_jabatan }}</div></div>
<div class="row"><div class="label">Perguruan Tinggi</div><div class="value">: STIT Darul Ilmi Tasikmalaya</div></div>
<p>Menerangkan bahwa:</p>
<div class="row"><div class="label">Nama</div><div class="value">: {{ $nama }}</div></div>
<div class="row"><div class="label">NIK</div><div class="value">: {{ $nik }}</div></div>
<div class="row"><div class="label">NIM</div><div class="value">: {{ $nim }}</div></div>
<div class="row"><div class="label">Tempat, Tanggal Lahir</div><div class="value">: {{ $ttl }}</div></div>
<div class="row"><div class="label">Alamat</div><div class="value">: {{ $alamat }}</div></div>
<p>Merupakan mahasiswa aktif pada:</p>
<div class="row"><div class="label">Jenjang</div><div class="value">: {{ $jenjang }}</div></div>
<div class="row"><div class="label">Program Studi</div><div class="value">: {{ $program_studi }}</div></div>
<div class="row"><div class="label">Memulai studi pada</div><div class="value">: {{ $periode_mulai }} tahun akademik {{ $tahun_akademik }}</div></div>
<p>Surat keterangan ini dibuat atas permohonan yang bersangkutan untuk dipergunakan sebagaimana mestinya, khususnya untuk keperluan <strong>{{ $keperluan }}</strong>.</p>
<p>Tasikmalaya, {{ $tgl }}</p>
<table class="ttd"><tr><td></td><td class="right">Ketua STIT Darul Ilmi Tasikmalaya<div class="signature-space"></div><strong><u>{{ $pejabat_nama }}</u></strong><br>NIP/NIK. {{ $pejabat_nip }}</td></tr></table>
<div class="tembusan"><strong>Tembusan:</strong><br>1. Ketua Prodi MPI STIT Darul Ilmi Tasikmalaya<br>2. Arsip</div>
</body></html>

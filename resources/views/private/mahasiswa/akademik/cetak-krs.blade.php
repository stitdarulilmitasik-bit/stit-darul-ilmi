<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cetak KRS - {{ $mahasiswa->name ?? $mahasiswa->numb_nim }}</title>
<style>
@page { size: A4 portrait; margin: 15mm; }
body { font-family: Arial, sans-serif; color:#111; font-size:12px; }
.header { text-align:center; border-bottom:2px solid #111; padding-bottom:10px; margin-bottom:14px; }
.header h2,.header h3,.header p { margin:2px 0; }
table { width:100%; border-collapse:collapse; }
th,td { border:1px solid #222; padding:6px; }
th { background:#eee; }
.meta td { border:0; padding:3px 0; }
.text-center{text-align:center}.text-right{text-align:right}
.signature { margin-top:35px; width:100%; display:flex; justify-content:flex-end; }
.signature-box { width:220px; text-align:center; }
.no-print { margin-bottom:15px; }
@media print { .no-print { display:none; } }
</style>
</head>
<body>
<div class="no-print"><button onclick="window.print()">Cetak / Print</button></div>
<div class="header">
    <h2>{{ $webs->school_name ?? 'PERGURUAN TINGGI' }}</h2>
    <p>{{ $webs->school_address ?? '' }}</p>
    <h3>KARTU RENCANA STUDI (KRS)</h3>
    <p>{{ $currentSemester->name ?? '' }} - {{ $currentSemester->type ?? '' }}</p>
</div>
<table class="meta">
<tr><td width="18%">Nama</td><td>: {{ $mahasiswa->name ?? '-' }}</td><td width="18%">NIM</td><td>: {{ $mahasiswa->numb_nim ?? '-' }}</td></tr>
<tr><td>Program Studi</td><td>: {{ $mahasiswa->programStudi->name ?? '-' }}</td><td>Semester</td><td>: {{ $mahasiswa->semester ?? '-' }}</td></tr>
<tr><td>Status KRS</td><td>: {{ $krsHeader->status ?? 'Belum dibuat' }}</td><td>Kode KRS</td><td>: {{ $krsHeader->code ?? '-' }}</td></tr>
</table>
<br>
<table>
<thead><tr><th width="5%">No</th><th width="15%">Kode</th><th>Mata Kuliah</th><th width="8%">SKS</th><th>Kelas</th><th>Dosen</th></tr></thead>
<tbody>
@forelse($krs as $i => $item)
<tr>
<td class="text-center">{{ $i+1 }}</td>
<td>{{ $item->mataKuliah->kode_mk ?? $item->mataKuliah->code ?? '-' }}</td>
<td>{{ $item->mataKuliah->nama ?? $item->mataKuliah->name ?? '-' }}</td>
<td class="text-center">{{ $item->sks ?? $item->mataKuliah->sks ?? 0 }}</td>
<td>{{ $item->kelas->nama_kelas ?? $item->kelas->name ?? '-' }}</td>
<td>{{ $item->dosen->nama_lengkap ?? $item->dosen->name ?? '-' }}</td>
</tr>
@empty
<tr><td colspan="6" class="text-center">Belum ada mata kuliah dalam KRS.</td></tr>
@endforelse
</tbody>
<tfoot><tr><th colspan="3" class="text-right">Total SKS</th><th class="text-center">{{ $krs->sum('sks') }}</th><th colspan="2"></th></tr></tfoot>
</table>
<div class="signature">
<div class="signature-box">
<p>Mengetahui,<br>Dosen Pembimbing Akademik</p>
<br><br><br>
<strong>{{ $krsHeader->dosenPA->name ?? '________________________' }}</strong>
</div>
</div>
<script>window.addEventListener('load', function(){ setTimeout(function(){ window.print(); }, 350); });</script>
</body>
</html>

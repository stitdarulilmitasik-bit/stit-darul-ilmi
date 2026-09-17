@extends('core-themes.core-backpage')

@section('custom-css')
<style>
    .gpa-card{padding:1.5rem;border-radius:12px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;box-shadow:0 4px 12px rgba(79,70,229,.18)}
    .gpa-value{font-size:3.25rem;font-weight:700;line-height:1;margin:.5rem 0}
    .semester-card{margin-bottom:1.5rem;border-radius:10px;overflow:hidden}
    .semester-header{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1rem 1.25rem;background:#f8fafc;border-bottom:1px solid #e5e7eb;cursor:pointer}
    .semester-title{font-weight:600;margin:0;color:#1f2937}
    .semester-ips{font-weight:700;color:#4f46e5;white-space:nowrap}
    .grade-A,.grade-B{background:#dcfce7;color:#166534}.grade-C{background:#fef3c7;color:#92400e}.grade-D,.grade-E{background:#fee2e2;color:#991b1b}.grade-pending{background:#f3f4f6;color:#4b5563}
    @media print{.d-print-none{display:none!important}.card{box-shadow:none!important;border:1px solid #ddd}.semester-card{break-inside:avoid}}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header d-print-none mb-3">
        <div class="row align-items-center">
            <div class="col"><h2 class="page-title">Nilai & IPK</h2><div class="text-muted">Transkrip Nilai Akademik</div></div>
            <div class="col-auto"><button type="button" class="btn btn-outline-primary" onclick="window.print()">Cetak Transkrip</button></div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-5 col-lg-4">
            <div class="gpa-card h-100">
                <div class="opacity-75">Indeks Prestasi Kumulatif</div>
                <div class="gpa-value">{{ $ipk ?? '0.00' }}</div>
                <div>Total {{ number_format((float)($totalSks ?? 0),0) }} SKS</div>
            </div>
        </div>
        <div class="col-md-7 col-lg-8">
            <div class="card h-100"><div class="card-body">
                <h3 class="card-title">Keterangan Nilai</h3>
                <div class="row g-3">
                    <div class="col-6 col-md-4"><span class="badge bg-success-lt me-2">A</span> 85–100</div>
                    <div class="col-6 col-md-4"><span class="badge bg-success-lt me-2">B</span> 65–84</div>
                    <div class="col-6 col-md-4"><span class="badge bg-warning-lt me-2">C</span> 55–64</div>
                    <div class="col-6 col-md-4"><span class="badge bg-danger-lt me-2">D</span> 45–54</div>
                    <div class="col-6 col-md-4"><span class="badge bg-danger-lt me-2">E</span> &lt;45</div>
                </div>
            </div></div>
        </div>
    </div>

    <h2 class="page-title mb-3">Riwayat Nilai per Semester</h2>
    @if(empty($semesters) || count($semesters) === 0)
        <div class="card"><div class="card-body"><div class="empty py-5"><p class="empty-title">Belum ada data nilai</p><p class="empty-subtitle text-muted">Data nilai mahasiswa akan tampil setelah nilai dipublikasikan.</p></div></div></div>
    @else
        @foreach($semesters as $semesterId => $semester)
            @php
                $semesterData = $semester->first()->tahunAkademik;
                $totalSksSemester = 0; $totalMutuSemester = 0;
            @endphp
            @foreach($semester as $nilai)
                @php
                    $mk = $nilai->mataKuliah;
                    $sks = (float)($mk->sks ?? $mk->bsks ?? 0);
                    $bobot = $nilai->nilai_mutu !== null ? (float)$nilai->nilai_mutu : (float)($nilai->nilai_angka >= 85 ? 4 : ($nilai->nilai_angka >= 80 ? 3.7 : ($nilai->nilai_angka >= 75 ? 3.3 : ($nilai->nilai_angka >= 70 ? 3 : ($nilai->nilai_angka >= 65 ? 2.7 : ($nilai->nilai_angka >= 60 ? 2.3 : ($nilai->nilai_angka >= 55 ? 2 : ($nilai->nilai_angka >= 50 ? 1.7 : ($nilai->nilai_angka >= 45 ? 1 : 0)))))))));
                    $totalSksSemester += $sks; $totalMutuSemester += $bobot * $sks;
                @endphp
            @endforeach
            @php $ips = $totalSksSemester > 0 ? $totalMutuSemester / $totalSksSemester : 0; @endphp

            <div class="card semester-card">
                <div class="semester-header" role="button" tabindex="0" onclick="toggleSemester(this)">
                    <div><h3 class="semester-title">{{ $semesterData->name ?? $semesterData->nama ?? 'Semester '.$semesterId }}</h3><div class="text-muted small">{{ $semesterData->tahun_ajaran ?? $semesterData->year ?? '' }}</div></div>
                    <div class="semester-ips">IPS {{ number_format($ips,2) }} <span class="ms-2">⌄</span></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table mb-0">
                        <thead><tr><th>Kode MK</th><th>Mata Kuliah</th><th class="text-center">SKS</th><th class="text-center">Nilai</th><th class="text-center">Grade</th><th class="text-center">Bobot</th></tr></thead>
                        <tbody>
                        @foreach($semester as $nilai)
                            @php
                                $mk=$nilai->mataKuliah; $angka=$nilai->nilai_angka; $bobot=$nilai->nilai_mutu !== null ? (float)$nilai->nilai_mutu : ($angka>=85?4:($angka>=80?3.7:($angka>=75?3.3:($angka>=70?3:($angka>=65?2.7:($angka>=60?2.3:($angka>=55?2:($angka>=50?1.7:($angka>=45?1:0)))))))));
                                $grade=$nilai->nilai_huruf ?: ($angka===null?'?':($angka>=85?'A':($angka>=75?'B+':($angka>=70?'B':($angka>=65?'B-':($angka>=55?'C':($angka>=45?'D':'E')))))));
                                $gradeClass='grade-'.substr($grade,0,1);
                            @endphp
                            <tr>
                                <td>{{ $mk->kode_mk ?? $mk->code ?? '-' }}</td>
                                <td>{{ $mk->nama ?? $mk->name ?? '-' }}</td>
                                <td class="text-center">{{ $mk->sks ?? $mk->bsks ?? 0 }}</td>
                                <td class="text-center">{{ $angka !== null ? number_format((float)$angka,2) : '-' }}</td>
                                <td class="text-center"><span class="badge {{ $gradeClass }}">{{ $grade }}</span></td>
                                <td class="text-center">{{ number_format($bobot,2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-light"><td colspan="2" class="text-end fw-bold">Total SKS</td><td class="text-center fw-bold">{{ number_format($totalSksSemester,0) }}</td><td colspan="3"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection

@section('custom-js')
<script>
function toggleSemester(header){const table=header.closest('.semester-card').querySelector('table');table.parentElement.style.display=table.parentElement.style.display==='none'?'':'none';}
</script>
@endsection
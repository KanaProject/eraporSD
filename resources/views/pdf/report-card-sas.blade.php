<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor Akhir Semester - {{ $student->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; line-height: 1.3; color: #333; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 0.25rem; }
        .mb-4 { margin-bottom: 0.5rem; }
        .mt-4 { margin-top: 0.5rem; }
        
        /* Header table */
        table.header { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.header td { vertical-align: top; padding: 1px 5px; }
        .title { font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase; margin-bottom: 10px; }
        
        /* Data table */
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.data th, table.data td { border: 1px solid #000; padding: 4px; }
        table.data th { background-color: #e5e7eb; text-align: center; font-weight: bold; }
        
        /* Signatures */
        table.signatures { width: 100%; border-collapse: collapse; margin-top: 20px; text-align: center; }
        table.signatures td { width: 33%; padding: 5px; }
        .signature-line { margin-top: 50px; border-bottom: 1px solid #000; display: inline-block; width: 80%; }
        
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

<div class="title">LAPORAN HASIL PENILAIAN {{ strtoupper($period->name) }}</div>

<table class="header">
    <tr>
        <td width="20%">Nama Peserta Didik</td>
        <td width="2%">:</td>
        <td width="48%">{{ strtoupper($student->name) }}</td>
        
        <td width="12%">Kelas</td>
        <td width="2%">:</td>
        <td width="16%">{{ $student->schoolClass->name }}</td>
    </tr>
    <tr>
        <td>NIS / NISN</td>
        <td>:</td>
        <td>{{ $student->nis ?? '-' }}/{{ $student->nisn ?? '-' }}</td>
        
        <td>Semester</td>
        <td>:</td>
        <td>{{ strtoupper($semester->type) == 'GANJIL' ? '1 ( Satu )' : '2 ( Dua )' }}</td>
    </tr>
    <tr>
        <td>Nama Sekolah</td>
        <td>:</td>
        <td>{{ $school->name ?? '-' }}</td>
        
        <td>Tahun Pelajaran</td>
        <td>:</td>
        <td>{{ $semester->academicYear->name }}</td>
    </tr>
    <tr>
        <td>Alamat Sekolah</td>
        <td>:</td>
        <td colspan="4">{{ $school->address ?? '-' }}</td>
    </tr>
</table>

<div class="font-bold mb-2 mt-4">A. NILAI PENGETAHUAN DAN KETERAMPILAN</div>
<table class="data">
    <thead>
        <tr>
            <th rowspan="2" width="5%">No</th>
            <th rowspan="2" width="37%">Mata Pelajaran</th>
            <th rowspan="2" width="10%">KKTP</th>
            <th colspan="3">Nilai</th>
        </tr>
        <tr>
            <th width="16%">{{ $period->labelPengetahuan() }}</th>
            <th width="16%">{{ $period->labelKeterampilan() }}</th>
            <th width="16%">Rata- Rata</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $no = 1;
            $sumP = 0; $countP = 0;
            $sumK = 0; $countK = 0;
            $sumAvg = 0; $countAvg = 0;
        @endphp
        @foreach($mainSubjects as $main)
            @if($main->children->isEmpty())
                @php 
                    $grade = $grades->get($main->id); 
                    $kkm   = $configs->get($main->id)?->kkm ?? 70;
                    $np    = $grade ? $grade->nilai_pengetahuan : null;
                    $nk    = $grade ? $grade->nilai_keterampilan : null;
                    if($np !== null) { $sumP += $np; $countP++; }
                    if($nk !== null) { $sumK += $nk; $countK++; }
                    
                    $avgSubj = null;
                    if ($np !== null && $nk !== null) { $avgSubj = round(($np + $nk) / 2); }
                    elseif ($np !== null) { $avgSubj = round($np); }
                    elseif ($nk !== null) { $avgSubj = round($nk); }
                    if ($avgSubj !== null) { $sumAvg += $avgSubj; $countAvg++; }
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $main->name }}</td>
                    <td class="text-center">{{ round($kkm) }}</td>
                    <td class="text-center">{{ $np !== null ? round($np) : '-' }}</td>
                    <td class="text-center">{{ $nk !== null ? round($nk) : '-' }}</td>
                    <td class="text-center">{{ $avgSubj !== null ? $avgSubj : '-' }}</td>
                </tr>
            @else
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $main->name }}</td>
                    <td colspan="4" style="background-color: #e5e7eb;"></td>
                </tr>
                @php $childChar = 'a'; @endphp
                @foreach($main->children as $child)
                    @php 
                        $grade = $grades->get($child->id); 
                        $kkm   = $configs->get($child->id)?->kkm ?? 70;
                        $np    = $grade ? $grade->nilai_pengetahuan : null;
                        $nk    = $grade ? $grade->nilai_keterampilan : null;
                        if($np !== null) { $sumP += $np; $countP++; }
                        if($nk !== null) { $sumK += $nk; $countK++; }
                        
                        $avgSubj = null;
                        if ($np !== null && $nk !== null) { $avgSubj = round(($np + $nk) / 2); }
                        elseif ($np !== null) { $avgSubj = round($np); }
                        elseif ($nk !== null) { $avgSubj = round($nk); }
                        if ($avgSubj !== null) { $sumAvg += $avgSubj; $countAvg++; }
                    @endphp
                    <tr>
                        <td class="text-center"></td>
                        <td>{{ $childChar++ }}. {{ $child->name }}</td>
                        <td class="text-center">{{ round($kkm) }}</td>
                        <td class="text-center">{{ $np !== null ? round($np) : '-' }}</td>
                        <td class="text-center">{{ $nk !== null ? round($nk) : '-' }}</td>
                        <td class="text-center">{{ $avgSubj !== null ? $avgSubj : '-' }}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
        <tr>
            <td colspan="3" class="text-right" style="padding-right:15px"></td>
            <td class="text-center">{{ $countP > 0 ? number_format(round($sumP / $countP, 2), 2, ',', '.') : '-' }}</td>
            <td class="text-center">{{ $countK > 0 ? number_format(round($sumK / $countK, 2), 2, ',', '.') : '-' }}</td>
            <td class="text-center">{{ $countAvg > 0 ? number_format(round($sumAvg / $countAvg, 2), 2, ',', '.') : '-' }}</td>
        </tr>
    </tbody>
</table>

<div class="font-bold mb-2 mt-4">B. CATATAN WALI KELAS</div>
<table class="data">
    <tr>
        <td style="padding: 15px;">
            {{ $note->note ?? 'Belum ada catatan' }}
        </td>
    </tr>
</table>

@if($period->code == 'SAT')
<div class="font-bold mb-2 mt-4">C. KEPUTUSAN KELAS</div>
<table class="data">
    <tr>
        <td style="padding: 15px;">
            Berdasarkan pencapaian seluruh kompetensi, peserta didik dinyatakan:<br><br>
            <span class="font-bold" style="font-size: 14px;">( NAIK / TINGGAL ) KELAS .....</span>
        </td>
    </tr>
</table>
@endif

<table class="signatures">
    <tr>
        <td>
            Mengetahui,<br>
            Orang Tua/Wali<br>
            <br><br><br><br>
            <div class="signature-line"></div>
        </td>
        <td></td>
        <td>
            {{ $period->report_place ?? ($school->city ?? '....................') }}, {{ $period->report_date ? \Carbon\Carbon::parse($period->report_date)->translatedFormat('d F Y') : '..............................' }}<br>
            Wali Kelas<br>
            <br><br><br><br>
            <span class="font-bold">{{ $walas->name }}</span><br>
            NIP. {{ $walas->nip ?? '-' }}
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            Mengetahui,<br>
            Kepala Sekolah<br>
            <br><br><br><br>
            <span class="font-bold">{{ $school->principal_name ?? '-' }}</span><br>
            NIP. {{ $school->principal_nip ?? '-' }}
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>

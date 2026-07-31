<x-layouts.walas title="Legger Nilai">
<div class="page-header">
    <h2 class="page-title">Legger Nilai — Kelas {{ $myClass->name }}</h2>
    <p class="page-subtitle">Rekap seluruh nilai per mapel per siswa untuk periode terpilih</p>
</div>

<div class="flex gap-2 mb-6 overflow-x-auto">
    @foreach($periods as $p)
    <a href="{{ route('walas.ledger.index', ['period_id' => $p->id]) }}"
        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $period->id == $p->id ? 'bg-primary-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
        {{ $p->name }}
    </a>
    @endforeach
</div>

<div class="card overflow-x-auto">
    <div class="mb-4">
        <span class="badge-neutral">Periode: {{ $period->name }}</span>
        <span class="badge-info ml-2">Total Siswa: {{ $students->count() }}</span>
        <span class="badge-success ml-2">Total Mapel: {{ $subjects->count() }}</span>
    </div>
    
    <table class="w-full text-sm border-collapse border border-slate-200">
        <thead>
            <tr>
                <th rowspan="2" class="border border-slate-200 bg-slate-50 p-2 font-semibold text-slate-700 w-10 text-center">No</th>
                <th rowspan="2" class="border border-slate-200 bg-slate-50 p-2 font-semibold text-slate-700 text-left min-w-[200px]">Nama Siswa</th>
                @foreach($subjects as $subject)
                <th colspan="2" class="border border-slate-200 bg-slate-100 p-2 font-semibold text-slate-800 text-center whitespace-nowrap" title="{{ $subject->name }}">
                    {{ $subject->code }}
                </th>
                @endforeach
            </tr>
            <tr>
                @foreach($subjects as $subject)
                <th class="border border-slate-200 bg-slate-50 p-1 text-xs font-semibold text-slate-600 text-center w-12">{{ $period->isAstsType() ? 'S1' : 'P' }}</th>
                <th class="border border-slate-200 bg-slate-50 p-1 text-xs font-semibold text-slate-600 text-center w-12">{{ $period->isAstsType() ? 'S2' : 'K' }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $student)
            <tr class="hover:bg-primary-50/50">
                <td class="border border-slate-200 p-2 text-center text-slate-500">{{ $i+1 }}</td>
                <td class="border border-slate-200 p-2 font-medium text-slate-800">{{ $student->name }}</td>
                @foreach($subjects as $subject)
                @php 
                    $grade = $ledger[$student->id][$subject->id] ?? null; 
                    $kkm   = $configs[$subject->id]?->kkm ?? 70;
                    $p     = $grade?->nilai_pengetahuan;
                    $k     = $grade?->nilai_keterampilan;
                    $isPUnder = $p !== null && $p < $kkm;
                    $isKUnder = $k !== null && $k < $kkm;
                @endphp
                <td class="border border-slate-200 p-2 text-center {{ $isPUnder ? 'text-red-600 font-bold bg-red-50' : 'text-slate-700' }}">
                    {{ $p !== null ? round($p) : '-' }}
                </td>
                <td class="border border-slate-200 p-2 text-center {{ $isKUnder ? 'text-red-600 font-bold bg-red-50' : 'text-slate-700' }}">
                    {{ $k !== null ? round($k) : '-' }}
                </td>
                @endforeach
            </tr>
            @empty
            <tr><td colspan="{{ 2 + ($subjects->count() * 2) }}" class="text-center py-10 text-slate-400">Belum ada data siswa.</td></tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="mt-4 text-xs text-slate-500 flex flex-wrap gap-4">
        <div><span class="font-bold text-red-600">Merah</span> = Nilai di bawah KKM</div>
        <div><strong>S1 / P</strong> = {{ $period->labelPengetahuan() }}</div>
        <div><strong>S2 / K</strong> = {{ $period->labelKeterampilan() }}</div>
    </div>
</div>
</x-layouts.walas>

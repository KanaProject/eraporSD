<x-layouts.walas title="Legger Nilai">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-900 to-blue-700 rounded-xl shadow-lg p-6 mb-6 text-white flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.375 15h17.25M5.625 15v1.5a2.25 2.25 0 01-2.25 2.25h-.75m18-3v1.5a2.25 2.25 0 01-2.25 2.25h-.75m-15-6h17.25m-17.25 0v-1.5a2.25 2.25 0 012.25-2.25h12.75a2.25 2.25 0 012.25 2.25v1.5M3 15v6m18-6v6" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold mb-1">Legger Nilai</h2>
                <div class="flex flex-wrap items-center gap-2 text-sm text-indigo-100 mt-1">
                    <span class="bg-indigo-900/50 px-2.5 py-1 rounded-md font-medium border border-indigo-500/50">Kelas {{ $myClass->name }}</span>
                    <span class="bg-indigo-900/50 px-2.5 py-1 rounded-md font-medium border border-indigo-500/50">Rekap seluruh nilai mapel</span>
                </div>
            </div>
        </div>
    </div>

<div class="flex gap-2 mb-6 overflow-x-auto">
    @foreach($periods as $p)
    <a href="{{ route('walas.ledger.index', ['period_id' => $p->id]) }}"
        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $period->id == $p->id ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-indigo-50' }}">
        {{ $p->name }}
    </a>
    @endforeach
</div>

<div class="card overflow-x-auto">
    <div class="mb-4 flex flex-wrap gap-2">
        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-md text-xs font-semibold border border-indigo-200">Periode: {{ $period->name }}</span>
        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-md text-xs font-semibold border border-blue-200">Total Siswa: {{ $students->count() }}</span>
        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-md text-xs font-semibold border border-purple-200">Total Mapel: {{ $subjects->count() }}</span>
    </div>
    
    <table class="w-full text-sm border-collapse border border-slate-200">
        <thead>
            <tr>
                <th rowspan="2" class="border border-slate-200 bg-indigo-50/50 p-2 font-semibold text-indigo-900 w-10 text-center">No</th>
                <th rowspan="2" class="border border-slate-200 bg-indigo-50/50 p-2 font-semibold text-indigo-900 text-left min-w-[200px]">Nama Siswa</th>
                @foreach($subjects as $subject)
                <th colspan="2" class="border border-slate-200 bg-indigo-100/50 p-2 font-semibold text-indigo-900 text-center whitespace-nowrap" title="{{ $subject->name }}">
                    {{ $subject->code }}
                </th>
                @endforeach
            </tr>
            <tr>
                @foreach($subjects as $subject)
                <th class="border border-slate-200 bg-indigo-50/50 p-1 text-xs font-semibold text-indigo-800 text-center w-12">{{ $period->isAstsType() ? 'S1' : 'P' }}</th>
                <th class="border border-slate-200 bg-indigo-50/50 p-1 text-xs font-semibold text-indigo-800 text-center w-12">{{ $period->isAstsType() ? 'S2' : 'K' }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $student)
            <tr class="hover:bg-indigo-50/40">
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

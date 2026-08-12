<x-layouts.walas title="Legger Nilai">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-900 to-blue-700 rounded-xl shadow-lg p-4 sm:p-6 mb-6 text-white flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3 sm:gap-4 w-full">
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center overflow-hidden shrink-0 shadow-inner mt-1 sm:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-8 sm:h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.375 15h17.25M5.625 15v1.5a2.25 2.25 0 01-2.25 2.25h-.75m18-3v1.5a2.25 2.25 0 01-2.25 2.25h-.75m-15-6h17.25m-17.25 0v-1.5a2.25 2.25 0 012.25-2.25h12.75a2.25 2.25 0 012.25 2.25v1.5M3 15v6m18-6v6" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-xl sm:text-2xl font-bold mb-1 truncate">Legger Nilai</h2>
                <div class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-2 text-sm text-indigo-100 mt-1">
                    <span class="bg-indigo-900/50 px-2.5 py-1 rounded-md font-medium border border-indigo-500/50">Kelas {{ $myClass->name }}</span>
                    <select onchange="window.location.href=this.value" class="max-w-full bg-indigo-900/50 text-indigo-50 border border-indigo-500/50 rounded-md px-2.5 py-1 pr-8 outline-none focus:ring-2 focus:ring-indigo-400 text-sm font-medium cursor-pointer appearance-none hover:bg-indigo-800/50 transition-colors" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23e0e7ff%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.7rem top 50%; background-size: 0.65rem auto;">
                        @foreach($periods as $p)
                            <option value="{{ route('walas.ledger.index', ['period_id' => $p->id]) }}" {{ $period->id == $p->id ? 'selected' : '' }} class="bg-indigo-900 text-white">
                                Periode: {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-slate-100 flex flex-wrap gap-4 bg-white">
        <div class="text-sm text-slate-500">
            Total Siswa: <span class="font-semibold text-slate-700">{{ $students->count() }}</span>
        </div>
        <div class="text-sm text-slate-500 border-l border-slate-200 pl-4">
            Total Mapel: <span class="font-semibold text-slate-700">{{ $subjects->count() }}</span>
        </div>
    </div>
    
    <div class="overflow-y-auto overflow-x-auto max-h-[60vh] relative">
        <table class="w-full text-sm border-collapse border border-slate-200">
            <thead class="sticky top-0 z-10 shadow-sm outline outline-1 outline-slate-200">
                <tr>
                <th rowspan="2" class="border border-slate-200 bg-indigo-50 p-2 font-semibold text-indigo-900 w-10 text-center">No</th>
                <th rowspan="2" class="border border-slate-200 bg-indigo-50 p-2 font-semibold text-indigo-900 text-left min-w-[200px]">Nama Siswa</th>
                @foreach($subjects as $subject)
                <th colspan="2" class="border border-slate-200 bg-indigo-100 p-2 font-semibold text-indigo-900 text-center whitespace-nowrap" title="{{ $subject->name }}">
                    {{ $subject->code }}
                </th>
                @endforeach
            </tr>
            <tr>
                @foreach($subjects as $subject)
                <th class="border border-slate-200 bg-indigo-50 p-1 text-xs font-semibold text-indigo-800 text-center w-12">{{ $period->isAstsType() ? 'S1' : 'P' }}</th>
                <th class="border border-slate-200 bg-indigo-50 p-1 text-xs font-semibold text-indigo-800 text-center w-12">{{ $period->isAstsType() ? 'S2' : 'K' }}</th>
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
    </div>
    
    <div class="p-4 bg-slate-50 border-t border-slate-200 text-xs text-slate-500 flex flex-wrap gap-4">
        <div><span class="font-bold text-red-600">Merah</span> = Nilai di bawah KKM</div>
        <div><strong>S1 / P</strong> = {{ $period->labelPengetahuan() }}</div>
        <div><strong>S2 / K</strong> = {{ $period->labelKeterampilan() }}</div>
    </div>
</div>
</x-layouts.walas>

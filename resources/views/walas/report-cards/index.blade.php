<x-layouts.walas title="Cetak Rapor">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-900 to-blue-700 rounded-xl shadow-lg p-4 sm:p-6 mb-6 text-white flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3 sm:gap-4 w-full">
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-8 sm:h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-xl sm:text-2xl font-bold mb-1 truncate">Cetak Rapor</h2>
                <div class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-2 text-sm text-indigo-100 mt-1">
                    <span class="bg-indigo-900/50 px-2.5 py-1 rounded-md font-medium border border-indigo-500/50">Kelas {{ $myClass->name }}</span>
                    <select onchange="window.location.href=this.value" class="max-w-full bg-white text-indigo-900 border border-white/80 rounded-md px-2.5 py-1 pr-8 outline-none focus:ring-2 focus:ring-white text-sm font-semibold cursor-pointer appearance-none hover:bg-indigo-50 transition-colors shadow-sm" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%234338ca%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.7rem top 50%; background-size: 0.65rem auto;">
                        @foreach($periods as $p)
                            <option value="{{ route('walas.report-cards.index', ['period_id' => $p->id]) }}" {{ $period->id == $p->id ? 'selected' : '' }} class="bg-white text-indigo-900">
                                Periode: {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

<div class="card p-0 overflow-hidden">
    <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-white">
        <div class="text-sm text-slate-500">
            Total Siswa: <span class="font-semibold text-slate-700">{{ $students->count() }}</span>
        </div>
        @if($students->isNotEmpty())
        <form method="POST" action="{{ route('walas.report-cards.generate-all') }}"
            data-confirm="Generate rapor untuk seluruh siswa di kelas? Proses ini mungkin memakan waktu beberapa saat."
            data-confirm-title="Generate Semua Rapor"
            data-confirm-type="info"
            data-confirm-ok="Ya, Generate Sekarang">
            @csrf
            <input type="hidden" name="period_id" value="{{ $period->id }}">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-all shadow hover:shadow-md hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Generate Semua
            </button>
        </form>
        @endif
    </div>



    <div class="overflow-y-auto max-h-[60vh] relative">
        <table class="data-table w-full text-sm flex flex-col lg:table">
            <thead class="sticky top-0 z-10 shadow-sm hidden lg:table-header-group">
                <tr class="border-b-2 border-indigo-200 bg-indigo-50">
                    <th class="w-10 text-indigo-900 font-semibold py-3 px-4">No</th>
                    <th class="text-indigo-900 font-semibold py-3 px-4 text-left">Nama Siswa</th>
                    <th class="text-indigo-900 font-semibold py-3 px-4 text-left">NIS/NISN</th>
                    <th class="text-center text-indigo-900 font-semibold py-3 px-4">Status Cetak</th>
                    <th class="text-right text-indigo-900 font-semibold py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="flex flex-col lg:table-row-group gap-4 lg:gap-0 p-4 lg:p-0">
                @forelse($students as $i => $student)
                @php $status = $statuses->get($student->id); @endphp
                <tr class="flex flex-col lg:table-row border border-slate-200 lg:border-b lg:border-x-0 lg:border-t-0 lg:border-slate-100 rounded-xl lg:rounded-none bg-white overflow-hidden shadow-sm lg:shadow-none hover:bg-indigo-50/40">
                    <td class="hidden lg:table-cell text-slate-400 py-3 px-4">{{ $i+1 }}</td>
                    
                    <td class="font-semibold lg:font-medium text-slate-800 py-3 px-4 border-b border-slate-100 lg:border-none flex justify-between items-center bg-slate-50/50 lg:bg-transparent">
                        <div class="flex items-center gap-3">
                            <span class="lg:hidden bg-indigo-100 text-indigo-700 w-6 h-6 flex items-center justify-center rounded-full text-xs">{{ $i+1 }}</span>
                            {{ $student->name }}
                        </div>
                        <div class="lg:hidden">
                            @if($status && $status->isGenerated())
                                <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-[10px] font-bold uppercase">Sudah</span>
                            @else
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-[10px] font-bold uppercase">Belum</span>
                            @endif
                        </div>
                    </td>
                    
                    <td class="text-slate-500 py-2 px-4 lg:py-3 border-b border-slate-50 lg:border-none flex lg:table-cell justify-between items-center text-xs lg:text-sm">
                        <span class="lg:hidden font-medium text-slate-400 uppercase">NIS/NISN</span>
                        {{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}
                    </td>
                    
                    <td class="hidden lg:table-cell text-center py-3 px-4">
                        @if($status && $status->isGenerated())
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-md text-xs font-semibold border border-emerald-200">Sudah Dicetak</span>
                            <div class="text-xs text-slate-400 mt-1">{{ $status->generated_at->format('d/m/Y H:i') }}</div>
                        @else
                            <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-md text-xs font-semibold border border-indigo-200">Belum Dicetak</span>
                        @endif
                    </td>
                    
                    <td class="py-3 px-4 lg:text-right">
                        <div class="flex flex-col lg:flex-row gap-2 justify-end">
                            <a href="{{ route('walas.report-cards.preview', ['student_id' => $student->id, 'period_id' => $period->id]) }}" target="_blank" class="w-full lg:w-auto justify-center bg-teal-600 hover:bg-teal-700 text-white px-3 py-2 lg:py-1.5 rounded-md text-sm font-semibold inline-flex items-center gap-1.5 transition-all shadow-sm hover:shadow hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Lihat
                            </a>
                            <form method="POST" action="{{ route('walas.report-cards.generate') }}" class="w-full lg:w-auto">
                                @csrf
                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                <input type="hidden" name="period_id" value="{{ $period->id }}">
                                <button type="submit" class="w-full lg:w-auto justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 lg:py-1.5 rounded-md text-sm font-semibold inline-flex items-center gap-1.5 transition-all shadow-sm hover:shadow hover:-translate-y-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                    {{ $status && $status->isGenerated() ? 'Cetak Ulang' : 'Cetak' }}
                                </button>
                            </form>
                        </div>
                        @if($status && $status->isGenerated())
                            <div class="lg:hidden text-center text-[10px] text-slate-400 mt-2">Dicetak pada: {{ $status->generated_at->format('d/m/Y H:i') }}</div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr class="flex lg:table-row"><td colspan="5" class="text-center py-10 text-slate-400 w-full">Belum ada siswa di kelas ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-layouts.walas>

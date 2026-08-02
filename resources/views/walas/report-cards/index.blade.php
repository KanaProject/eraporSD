<x-layouts.walas title="Cetak Rapor">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-900 to-blue-700 rounded-xl shadow-lg p-6 mb-6 text-white flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold mb-1">Cetak Rapor</h2>
                <div class="flex flex-wrap items-center gap-2 text-sm text-indigo-100 mt-1">
                    <span class="bg-indigo-900/50 px-2.5 py-1 rounded-md font-medium border border-indigo-500/50">Kelas {{ $myClass->name }}</span>
                    <span class="bg-indigo-900/50 px-2.5 py-1 rounded-md font-medium border border-indigo-500/50">Unduh PDF rapor</span>
                </div>
            </div>
        </div>
    </div>

<div class="flex gap-2 mb-6 overflow-x-auto">
    @foreach($periods as $p)
    <a href="{{ route('walas.report-cards.index', ['period_id' => $p->id]) }}"
        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $period->id == $p->id ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-indigo-50' }}">
        {{ $p->name }}
    </a>
    @endforeach
</div>

<div class="card overflow-x-auto">
    <div class="flex items-center justify-between mb-4">
        <div class="flex flex-wrap gap-2">
            <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-md text-xs font-semibold border border-indigo-200">Periode: {{ $period->name }}</span>
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-md text-xs font-semibold border border-blue-200">Total Siswa: {{ $students->count() }}</span>
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

    <table class="data-table">
        <thead>
            <tr class="border-b-2 border-indigo-200 bg-indigo-50/30">
                <th class="w-10 text-indigo-900 font-semibold py-3 px-4">No</th>
                <th class="text-indigo-900 font-semibold py-3 px-4 text-left">Nama Siswa</th>
                <th class="text-indigo-900 font-semibold py-3 px-4 text-left">NIS/NISN</th>
                <th class="text-center text-indigo-900 font-semibold py-3 px-4">Status Cetak</th>
                <th class="text-right text-indigo-900 font-semibold py-3 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $student)
            @php $status = $statuses->get($student->id); @endphp
            <tr class="border-b border-slate-100 hover:bg-indigo-50/40">
                <td class="text-slate-400 py-3 px-4">{{ $i+1 }}</td>
                <td class="font-medium text-slate-800 py-3 px-4">{{ $student->name }}</td>
                <td class="text-slate-500 py-3 px-4">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</td>
                <td class="text-center py-3 px-4">
                    @if($status && $status->isGenerated())
                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-md text-xs font-semibold border border-emerald-200">Sudah Dicetak</span>
                        <div class="text-xs text-slate-400 mt-1">{{ $status->generated_at->format('d/m/Y H:i') }}</div>
                    @else
                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-md text-xs font-semibold border border-indigo-200">Belum Dicetak</span>
                    @endif
                </td>
                <td class="text-right py-3 px-4">
                    <form method="POST" action="{{ route('walas.report-cards.generate') }}">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        <input type="hidden" name="period_id" value="{{ $period->id }}">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-md text-sm font-semibold inline-flex items-center gap-1.5 transition-all shadow-sm hover:shadow hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            {{ $status && $status->isGenerated() ? 'Cetak Ulang' : 'Cetak Rapor' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-10 text-slate-400">Belum ada siswa di kelas ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</x-layouts.walas>

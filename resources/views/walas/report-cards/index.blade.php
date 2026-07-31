<x-layouts.walas title="Cetak Rapor">
<div class="page-header">
    <h2 class="page-title">Cetak Rapor — Kelas {{ $myClass->name }}</h2>
    <p class="page-subtitle">Unduh dokumen rapor PDF siswa per periode</p>
</div>

<div class="flex gap-2 mb-6 overflow-x-auto">
    @foreach($periods as $p)
    <a href="{{ route('walas.report-cards.index', ['period_id' => $p->id]) }}"
        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $period->id == $p->id ? 'bg-primary-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
        {{ $p->name }}
    </a>
    @endforeach
</div>

<div class="card overflow-x-auto">
    <div class="flex items-center justify-between mb-4">
        <div>
            <span class="badge-neutral">Periode: {{ $period->name }}</span>
            <span class="badge-info ml-2">Total Siswa: {{ $students->count() }}</span>
        </div>
        @if($students->isNotEmpty())
        <form method="POST" action="{{ route('walas.report-cards.generate-all') }}"
            data-confirm="Generate rapor untuk seluruh siswa di kelas? Proses ini mungkin memakan waktu beberapa saat."
            data-confirm-title="Generate Semua Rapor"
            data-confirm-type="info"
            data-confirm-ok="Ya, Generate Sekarang">
            @csrf
            <input type="hidden" name="period_id" value="{{ $period->id }}">
            <button type="submit" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Generate Semua
            </button>
        </form>
        @endif
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="w-10">No</th>
                <th>Nama Siswa</th>
                <th>NIS/NISN</th>
                <th class="text-center">Status Cetak</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $student)
            @php $status = $statuses->get($student->id); @endphp
            <tr>
                <td class="text-slate-400">{{ $i+1 }}</td>
                <td class="font-medium text-slate-800">{{ $student->name }}</td>
                <td class="text-slate-500">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</td>
                <td class="text-center">
                    @if($status && $status->isGenerated())
                        <span class="badge-success">Sudah Dicetak</span>
                        <div class="text-xs text-slate-400 mt-1">{{ $status->generated_at->format('d/m/Y H:i') }}</div>
                    @else
                        <span class="badge-neutral">Belum Dicetak</span>
                    @endif
                </td>
                <td class="text-right">
                    <form method="POST" action="{{ route('walas.report-cards.generate') }}">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        <input type="hidden" name="period_id" value="{{ $period->id }}">
                        <button type="submit" class="btn-primary btn-sm">
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

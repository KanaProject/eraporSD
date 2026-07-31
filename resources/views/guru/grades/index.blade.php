<x-layouts.guru title="Input Nilai">
<div class="page-header"><h2 class="page-title">Pilih Kelas untuk Input Nilai</h2><p class="page-subtitle">Periode: {{ $activePeriod?->name ?? 'Belum ada periode aktif' }}</p></div>
@php
    $groupedProgress = collect($progress)->groupBy(function($p) {
        return $p['assignment']->schoolClass->name;
    })->sortKeys();
@endphp

<div class="space-y-6">
    @forelse($groupedProgress as $className => $items)
    <div class="card">
        <div class="border-b border-slate-100 pb-3 mb-4">
            <h3 class="text-lg font-bold text-slate-800">Kelas {{ $className }}</h3>
        </div>
        <div class="space-y-4">
            @foreach($items as $p)
            @php $pct = $p['total'] > 0 ? round(($p['graded']/$p['total'])*100) : 0; @endphp
            <div class="flex flex-col md:flex-row items-center gap-4 p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="flex-1 w-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-slate-800">{{ $p['assignment']->subject->name }}</span>
                        <span class="text-sm font-bold {{ $pct >= 100 ? 'text-green-600' : ($pct > 0 ? 'text-amber-600' : 'text-slate-500') }}">{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 mb-2">
                        <div class="bg-primary-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $p['graded'] }} / {{ $p['total'] }} siswa diisi
                    </div>
                </div>
                <div class="w-full md:w-auto">
                    <a href="{{ route('guru.grades.input', ['subject_id' => $p['assignment']->subject_id, 'class_id' => $p['assignment']->school_class_id]) }}" class="btn-primary w-full md:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Input Nilai
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="card text-center py-16 text-slate-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
        Belum ada penugasan mapel untuk tahun ajaran aktif.
    </div>
    @endforelse
</div>
</x-layouts.guru>

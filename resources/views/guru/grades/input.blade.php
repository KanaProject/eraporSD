<x-layouts.guru title="Input Nilai — {{ $subject->name }} Kelas {{ $class->name }}">

    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-800 to-teal-600 rounded-xl shadow-lg p-6 mb-6 text-white flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold mb-1">Input Nilai — {{ $subject->name }}</h2>
                <div class="flex flex-wrap items-center gap-2 text-sm text-emerald-100 mt-1">
                    <span class="bg-emerald-900/50 px-2.5 py-1 rounded-md font-medium border border-emerald-500/50">Kelas {{ $class->name }}</span>
                    <span class="bg-emerald-900/50 px-2.5 py-1 rounded-md font-medium border border-emerald-500/50">Periode: {{ $period->name }}</span>
                </div>
            </div>
        </div>
    </div>

<!-- Period switcher -->
<div class="flex gap-2 mb-6 overflow-x-auto">
    @foreach($periods as $p)
    <a href="{{ route('guru.grades.input', ['subject_id' => $subject->id, 'class_id' => $class->id, 'period_id' => $p->id]) }}"
        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $period->id == $p->id ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-emerald-50' }}">
        {{ $p->name }}
    </a>
    @endforeach
</div>

<form method="POST" action="{{ route('guru.grades.save') }}">
    @csrf
    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
    <input type="hidden" name="class_id" value="{{ $class->id }}">
    <input type="hidden" name="period_id" value="{{ $period->id }}">

    <div class="card overflow-x-auto">
        <div class="flex items-center justify-between mb-4">
            <div class="text-sm text-slate-500">
                Nilai: <span class="font-medium">1.00 – 100.00</span> (input 2 desimal) ·
                Rapor: <span class="font-medium">dibulatkan</span> ·
                Predikat berdasarkan <span class="font-medium">KKM per mapel</span>
            </div>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b-2 border-slate-200">
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 w-8">#</th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600">Nama Siswa</th>
                    <th class="text-center py-3 px-2 font-semibold text-slate-600 min-w-[90px]">
                        <div>UH 1</div>
                        <div class="text-xs font-normal text-slate-400">Ul. Harian 1</div>
                    </th>
                    <th class="text-center py-3 px-2 font-semibold text-slate-600 min-w-[90px]">
                        <div>UH 2</div>
                        <div class="text-xs font-normal text-slate-400">Ul. Harian 2</div>
                    </th>
                    <th class="text-center py-3 px-2 font-semibold text-slate-600 min-w-[90px]">
                        <div>Teori</div>
                        <div class="text-xs font-normal text-slate-400">Ujian Teori</div>
                    </th>
                    <th class="text-center py-3 px-2 font-semibold text-slate-600 min-w-[90px]">
                        <div>Praktek</div>
                        <div class="text-xs font-normal text-slate-400">Ujian Praktek</div>
                    </th>
                    <th class="text-center py-3 px-2 font-semibold text-emerald-700 min-w-[90px] bg-emerald-50/50">
                        <div>{{ $period->labelPengetahuan() }}</div>
                        <div class="text-xs font-normal text-slate-400">Auto-hitung</div>
                    </th>
                    <th class="text-center py-3 px-2 font-semibold text-emerald-700 min-w-[90px] bg-emerald-50/50">
                        <div>{{ $period->labelKeterampilan() }}</div>
                        <div class="text-xs font-normal text-slate-400">= Praktek</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                @php $grade = $grades->get($student->id); @endphp
                <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                    <td class="py-2 px-3 text-slate-400 text-xs">{{ $students->firstItem() + $i }}</td>
                    <td class="py-2 px-3">
                        <div class="font-medium text-slate-800">{{ $student->name }}</div>
                        <div class="text-xs text-slate-400">{{ $student->nis ?? '-' }}</div>
                    </td>
                    @foreach(['uh1','uh2','ujian_teori','ujian_praktek'] as $field)
                    <td class="py-2 px-1 text-center">
                        <input type="number" name="grades[{{ $student->id }}][{{ $field }}]"
                            value="{{ $grade ? number_format((float)($grade->$field ?? 0), 2, '.', '') : '' }}"
                            class="w-20 text-center text-sm border border-slate-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent disabled:bg-slate-100 disabled:text-slate-400"
                            min="0" max="100" step="0.01" placeholder="—" {{ !$period->is_active ? 'disabled' : '' }}>
                    </td>
                    @endforeach
                    <td class="py-2 px-2 text-center bg-emerald-50/30">
                        <span class="font-semibold text-emerald-700 text-sm">
                            {{ $grade && $grade->nilai_pengetahuan ? number_format((float)$grade->nilai_pengetahuan, 2) : '—' }}
                        </span>
                    </td>
                    <td class="py-2 px-2 text-center bg-emerald-50/30">
                        <span class="font-semibold text-emerald-700 text-sm">
                            {{ $grade && $grade->nilai_keterampilan ? number_format((float)$grade->nilai_keterampilan, 2) : '—' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-10 text-slate-400">Belum ada siswa di kelas ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($students->isNotEmpty())
        <div class="mt-4 pb-4">
            {{ $students->links() }}
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center mt-4 pt-4 border-t border-slate-100 gap-4">
            <span class="text-xs text-slate-400">{{ $students->total() }} total siswa · Nilai disimpan otomatis ke database</span>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('guru.grades.index') }}" data-confirm="Apakah Anda yakin ingin kembali? Harap simpan dulu perubahan nilai jika belum disimpan agar tidak hilang." data-confirm-title="Peringatan Belum Disimpan" data-confirm-type="warning" data-confirm-ok="Ya, Kembali" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 font-medium text-sm transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali
                </a>

                @if($period->is_active)
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 transition-all shadow hover:shadow-md hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Simpan Semua Nilai
                    </button>
                @else
                    <span class="text-sm text-red-600 font-medium flex items-center gap-1.5 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Periode Terkunci
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>
</form>

</x-layouts.guru>

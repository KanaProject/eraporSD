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
                    <span class="bg-emerald-900/50 px-2.5 py-1 rounded-md font-medium border border-emerald-500/50">Kelas {{ $class->name }}</span>
                    <select onchange="window.location.href=this.value" class="bg-emerald-900/50 text-emerald-50 border border-emerald-500/50 rounded-md px-2.5 py-1 pr-8 outline-none focus:ring-2 focus:ring-emerald-400 text-sm font-medium cursor-pointer appearance-none hover:bg-emerald-800/50 transition-colors" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23d1fae5%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.7rem top 50%; background-size: 0.65rem auto;">
                        @foreach($periods as $p)
                            <option value="{{ route('guru.grades.input', ['subject_id' => $subject->id, 'class_id' => $class->id, 'period_id' => $p->id]) }}" {{ $period->id == $p->id ? 'selected' : '' }} class="bg-emerald-900 text-white">
                                Periode: {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

<form method="POST" action="{{ route('guru.grades.save') }}">
    @csrf
    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
    <input type="hidden" name="class_id" value="{{ $class->id }}">
    <input type="hidden" name="period_id" value="{{ $period->id }}">

    <div class="card p-0 overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-white">
            <div class="text-sm text-slate-500 hidden md:block">
                Nilai: <span class="font-medium">1.00 – 100.00</span> ·
                Rapor: <span class="font-medium">dibulatkan</span>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('guru.grades.index') }}" data-confirm="Apakah Anda yakin ingin kembali? Harap simpan dulu perubahan nilai." data-confirm-title="Kembali" data-confirm-type="warning" data-confirm-ok="Ya, Kembali" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 font-medium text-sm transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali
                </a>

                @if($period->is_active && $students->isNotEmpty())
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-all shadow-sm hover:shadow hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Simpan Semua Nilai
                    </button>
                @elseif(!$period->is_active)
                    <span class="text-sm text-red-600 font-medium flex items-center gap-1.5 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Periode Terkunci
                    </span>
                @endif
            </div>
        </div>

        <div class="overflow-y-auto overflow-x-auto max-h-[60vh] relative">
            <table class="w-full text-sm">
                <thead class="sticky top-0 z-10 shadow-sm bg-white outline outline-1 outline-slate-200">
                <tr>
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
                    <th class="text-center py-3 px-2 font-semibold text-emerald-700 min-w-[90px] bg-emerald-50">
                        <div>{{ $period->labelPengetahuan() }}</div>
                        <div class="text-xs font-normal text-slate-400">Auto-hitung</div>
                    </th>
                    <th class="text-center py-3 px-2 font-semibold text-emerald-700 min-w-[90px] bg-emerald-50">
                        <div>{{ $period->labelKeterampilan() }}</div>
                        <div class="text-xs font-normal text-slate-400">= Praktek</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                @php $grade = $grades->get($student->id); @endphp
                <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                    <td class="py-2 px-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
                    <td class="py-2 px-3">
                        <div class="font-medium text-slate-800">{{ $student->name }}</div>
                        <div class="text-xs text-slate-400">{{ $student->nis ?? '-' }}</div>
                    </td>
                    @foreach(['uh1','uh2','ujian_teori','ujian_praktek'] as $colIndex => $field)
                    <td class="py-2 px-1 text-center">
                        <input type="number" name="grades[{{ $student->id }}][{{ $field }}]"
                            value="{{ $grade ? number_format((float)($grade->$field ?? 0), 2, '.', '') : '' }}"
                            class="grade-input w-20 text-center text-sm border border-slate-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent disabled:bg-slate-100 disabled:text-slate-400 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            data-row="{{ $i }}" data-col="{{ $colIndex }}"
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
        </div>

        @if($students->isNotEmpty())
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="text-xs text-slate-400">{{ $students->count() }} total siswa · Pastikan menekan tombol Simpan Semua Nilai di bagian atas.</span>
        </div>
        @endif
    </div>
</form>

<script>
document.addEventListener('keydown', function(e) {
    if (!e.target.classList.contains('grade-input')) return;
    
    let row = parseInt(e.target.dataset.row);
    let col = parseInt(e.target.dataset.col);
    let newTarget = null;
    
    switch(e.key) {
        case 'ArrowUp':
            newTarget = document.querySelector(`.grade-input[data-row="${row - 1}"][data-col="${col}"]`);
            break;
        case 'ArrowDown':
        case 'Enter':
            newTarget = document.querySelector(`.grade-input[data-row="${row + 1}"][data-col="${col}"]`);
            break;
        case 'ArrowLeft':
            newTarget = document.querySelector(`.grade-input[data-row="${row}"][data-col="${col - 1}"]`);
            break;
        case 'ArrowRight':
            newTarget = document.querySelector(`.grade-input[data-row="${row}"][data-col="${col + 1}"]`);
            break;
    }
    
    if (newTarget) {
        e.preventDefault();
        newTarget.focus();
        newTarget.select();
    }
});
</script>

</x-layouts.guru>

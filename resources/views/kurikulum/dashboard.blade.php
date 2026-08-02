<x-layouts.kurikulum title="Dashboard Kurikulum">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-800 to-fuchsia-600 rounded-xl shadow-lg p-6 mb-6 text-white flex flex-col md:flex-row items-center justify-between gap-4 relative overflow-hidden">
        <div class="relative z-10 flex items-center gap-4 w-full md:w-auto">
            <div class="w-16 h-16 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center shrink-0 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold mb-1">Dashboard Kurikulum</h2>
                <div class="flex flex-wrap items-center gap-2 text-sm text-purple-100">
                    <span class="bg-purple-900/50 px-2.5 py-1 rounded-md font-medium border border-purple-500/50">TA: {{ $activeYear?->name ?? '-' }}</span>
                    <span class="bg-purple-900/50 px-2.5 py-1 rounded-md font-medium border border-purple-500/50">Periode Aktif: {{ \App\Models\AssessmentPeriod::getActive()?->name ?? '-' }}</span>
                </div>
            </div>
        </div>
        <div class="relative z-10 w-full md:w-auto shrink-0 flex items-center gap-3">
            <label for="periodFilter" class="text-sm font-semibold text-purple-100 whitespace-nowrap hidden md:block">Filter Periode:</label>
            <select id="periodFilter" onchange="window.location.href='?period_id='+this.value" class="form-input text-sm py-2 pl-3 pr-8 w-full md:min-w-[220px] cursor-pointer text-slate-800 font-semibold border-0 shadow-md rounded-lg">
                @foreach($periods as $p)
                    <option value="{{ $p->id }}" {{ $selectedPeriod?->id == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} {{ $p->is_active ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Decorations -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-20 -mb-10 w-32 h-32 bg-purple-400 opacity-20 rounded-full blur-xl"></div>
    </div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="stat-card p-4 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 transition-transform">
        <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div>
            <div class="text-2xl font-black text-slate-800 leading-none mb-1">{{ $stats['total_classes'] }}</div>
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Rombongan Belajar (Kelas)</div>
        </div>
    </div>
    
    <div class="stat-card p-4 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 transition-transform">
        <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div>
            <div class="text-2xl font-black text-slate-800 leading-none mb-1">{{ $stats['total_teachers'] }}</div>
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Tenaga Pendidik</div>
        </div>
    </div>
    
    <div class="stat-card p-4 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 transition-transform">
        <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
        </div>
        <div>
            <div class="text-2xl font-black text-slate-800 leading-none mb-1">{{ $stats['total_subjects'] }}</div>
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran Aktif</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Chart: Progress Nilai Guru -->
    <div class="card flex flex-col items-center justify-center p-8 relative overflow-hidden">
        <!-- background accent -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-emerald-50 opacity-50"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 rounded-full bg-emerald-50 opacity-50"></div>

        <h3 class="text-lg font-bold text-slate-800 mb-6 relative z-10">Progres Pengisian Nilai (Guru)</h3>
        <div class="relative w-40 h-40 flex items-center justify-center rounded-full bg-slate-100 shadow-inner z-10" style="background: conic-gradient(#10b981 {{ $gradingProgress }}%, #e2e8f0 {{ $gradingProgress }}%);">
            <div class="absolute w-[8.5rem] h-[8.5rem] bg-white rounded-full flex flex-col items-center justify-center shadow-sm">
                <span class="text-3xl font-black text-emerald-600">{{ $gradingProgress }}%</span>
            </div>
        </div>
        <p class="text-sm text-slate-500 mt-6 font-medium text-center relative z-10">
            <span class="text-emerald-600 font-bold text-lg">{{ $completedAssignments }}</span> dari <span class="text-slate-800 font-bold">{{ $totalAssignments }}</span>
            <br> Penugasan Kelas/Mapel Selesai
        </p>
    </div>

    <!-- Chart: Progress Rapor Walas -->
    <div class="card flex flex-col items-center justify-center p-8 relative overflow-hidden">
        <!-- background accent -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-blue-50 opacity-50"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 rounded-full bg-blue-50 opacity-50"></div>

        <h3 class="text-lg font-bold text-slate-800 mb-6 relative z-10">Progres Cetak Rapor (Walas)</h3>
        <div class="relative w-40 h-40 flex items-center justify-center rounded-full bg-slate-100 shadow-inner z-10" style="background: conic-gradient(#3b82f6 {{ $reportProgress }}%, #e2e8f0 {{ $reportProgress }}%);">
            <div class="absolute w-[8.5rem] h-[8.5rem] bg-white rounded-full flex flex-col items-center justify-center shadow-sm">
                <span class="text-3xl font-black text-blue-600">{{ $reportProgress }}%</span>
            </div>
        </div>
        <p class="text-sm text-slate-500 mt-6 font-medium text-center relative z-10">
            <span class="text-blue-600 font-bold text-lg">{{ $totalReportCardsGenerated }}</span> dari <span class="text-slate-800 font-bold">{{ $totalStudentsAll }}</span>
            <br> Siswa Telah Dicetak Rapornya
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Table: Rapor Merah Guru -->
    <div class="card p-0 overflow-hidden flex flex-col border border-red-100">
        <div class="p-4 border-b border-red-100 bg-red-50/80">
            <h3 class="text-base font-bold text-red-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                Belum Selesai Input Nilai
            </h3>
            <p class="text-xs text-red-600/80 mt-1 font-medium">Daftar Guru yang belum 100% melengkapi nilai</p>
        </div>
        <div class="overflow-x-auto overflow-y-auto flex-1 max-h-[400px] scrollbar-thin">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white border-b border-slate-100 text-slate-500 sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold text-xs uppercase tracking-wider">Guru / Mapel</th>
                        <th class="px-5 py-3.5 font-semibold text-xs uppercase tracking-wider">Kelas</th>
                        <th class="px-5 py-3.5 font-semibold text-xs uppercase tracking-wider text-right">Progres</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($pendingGrading as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-5 py-3">
                            <div class="font-bold text-slate-800 mb-0.5">{{ $item['teacher'] }}</div>
                            <div class="text-xs text-slate-500">{{ $item['subject'] }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-xs font-bold border border-slate-200">{{ $item['class'] }}</span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="font-black text-red-600 text-base">{{ $item['graded'] }} <span class="text-sm text-slate-400 font-medium">/ {{ $item['total'] }}</span></div>
                            <div class="text-[10px] text-slate-400 uppercase tracking-wide font-semibold mt-0.5">siswa</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-5 py-12 text-center text-slate-400">
                            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="font-semibold text-slate-700">Luar biasa!</p>
                            <p class="text-sm mt-1">Semua guru sudah 100% selesai input nilai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table: Progress Walas -->
    <div class="card p-0 overflow-hidden flex flex-col border border-blue-100">
        <div class="p-4 border-b border-blue-100 bg-blue-50/80">
            <h3 class="text-base font-bold text-blue-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                Status Cetak Rapor
            </h3>
            <p class="text-xs text-blue-600/80 mt-1 font-medium">Pantau progres wali kelas mencetak rapor</p>
        </div>
        <div class="overflow-x-auto overflow-y-auto flex-1 max-h-[400px] scrollbar-thin">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white border-b border-slate-100 text-slate-500 sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold text-xs uppercase tracking-wider">Kelas / Walas</th>
                        <th class="px-5 py-3.5 font-semibold text-xs uppercase tracking-wider text-right">Progres Cetak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($walasProgress as $item)
                    @php $isDone = $item['printed'] >= $item['total']; @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-5 py-3">
                            <div class="font-bold text-slate-800 mb-0.5">Kelas {{ $item['class'] }}</div>
                            <div class="text-xs text-slate-500">{{ $item['walas'] }}</div>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="font-black text-base {{ $isDone ? 'text-emerald-600' : 'text-blue-600' }}">{{ $item['printed'] }} <span class="text-sm font-medium {{ $isDone ? 'text-emerald-400' : 'text-slate-400' }}">/ {{ $item['total'] }}</span></div>
                            <div class="text-[10px] uppercase tracking-wide font-semibold mt-0.5 {{ $isDone ? 'text-emerald-500' : 'text-slate-400' }}">{{ $isDone ? 'Tuntas' : 'siswa' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-5 py-12 text-center text-slate-400">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            </div>
                            Belum ada penugasan wali kelas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layouts.kurikulum>

<x-layouts.guru title="Dashboard Guru">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-xl shadow-lg p-6 mb-6 text-white flex flex-col md:flex-row items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" alt="Profile" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
            </div>
        </div>
        <div class="text-center md:text-right">
            <h1 class="text-2xl md:text-3xl font-black uppercase tracking-wider drop-shadow-md text-white/90">Dashboard Penilaian</h1>
        </div>
    </div>

    <!-- Widgets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Widget 1: Kelas -->
        <div class="bg-gradient-to-br from-emerald-600 to-green-700 rounded-xl shadow-md p-4 text-white flex items-center gap-4 transition-transform hover:scale-[1.02]">
            <div class="p-3 bg-white/20 rounded-lg shrink-0 border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <div>
                <p class="text-sm text-green-100 font-medium">Kelas Saya</p>
                <h3 class="text-xl font-bold">{{ $classesTaught->count() }} Kelas</h3>
            </div>
        </div>

        <!-- Widget 2: Mapel -->
        <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl shadow-md p-4 text-white flex items-center gap-4 transition-transform hover:scale-[1.02]">
            <div class="p-3 bg-white/20 rounded-lg shrink-0 border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <p class="text-sm text-orange-100 font-medium">Mata Pelajaran</p>
                <h3 class="text-xl font-bold truncate max-w-[140px]" title="{{ $subjectsTaught->count() > 1 ? $subjectsTaught->count() . ' Mapel' : ($subjectsTaught->first()?->name ?? '-') }}">
                    {{ $subjectsTaught->count() > 1 ? $subjectsTaught->count() . ' Mapel' : ($subjectsTaught->first()?->name ?? '-') }}
                </h3>
            </div>
        </div>

        <!-- Widget 3: Semester -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-700 rounded-xl shadow-md p-4 text-white flex items-center gap-4 transition-transform hover:scale-[1.02]">
            <div class="p-3 bg-white/20 rounded-lg shrink-0 border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <p class="text-sm text-indigo-100 font-medium">Semester</p>
                <h3 class="text-xl font-bold">{{ $activePeriod ? $activePeriod->name : '-' }}</h3>
            </div>
        </div>

        <!-- Widget 4: Tahun Ajaran -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-md p-4 text-white flex items-center gap-4 transition-transform hover:scale-[1.02]">
            <div class="p-3 bg-white/20 rounded-lg shrink-0 border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
            </div>
            <div>
                <p class="text-sm text-blue-100 font-medium">Tahun Ajaran</p>
                <h3 class="text-xl font-bold">{{ $activeYear ? $activeYear->name : '-' }}</h3>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
        <form method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full md:w-auto">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <label class="font-bold text-slate-700 whitespace-nowrap">Pilih Kelas:</label>
                <select name="class_id" class="form-select border-slate-300 rounded-lg shadow-sm focus:border-blue-600 focus:ring-blue-600 w-full sm:w-40 bg-white font-medium text-slate-700" onchange="this.form.submit()">
                    @if($classesTaught->isEmpty())
                        <option value="">Tidak ada kelas</option>
                    @else
                        @foreach($classesTaught as $class)
                            <option value="{{ $class->id }}" {{ ($selectedClass && $selectedClass->id == $class->id) ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            @if($subjectsForClass->isNotEmpty())
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <label class="font-bold text-slate-700 whitespace-nowrap">Mapel:</label>
                <select name="subject_id" class="form-select border-slate-300 rounded-lg shadow-sm focus:border-blue-600 focus:ring-blue-600 w-full sm:w-56 bg-white font-medium text-slate-700" onchange="this.form.submit()">
                    @foreach($subjectsForClass as $subject)
                        <option value="{{ $subject->id }}" {{ ($subjectForClass && $subjectForClass->id == $subject->id) ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
        </form>
        <div class="w-full md:w-auto flex justify-end">
            <a href="{{ route('guru.grades.index') }}" class="inline-flex w-full md:w-auto items-center justify-center gap-2 px-6 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                <span>Cek Nilai Siswa</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="flex flex-col gap-6">
        <!-- Chart -->
        <div class="w-full bg-slate-50 rounded-xl shadow-md border border-slate-200 p-5 flex flex-col min-h-[350px]">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-200 pb-2">Grafik Nilai 1 tahun</h3>
            <div class="relative w-full flex-1">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Table -->
        <div class="w-full bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <h3 class="text-lg font-bold text-blue-900">Daftar Nilai Siswa {{ $selectedClass ? '- '.$selectedClass->name : '' }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-white uppercase bg-[#1a365d] text-center border-b border-blue-900">
                        <tr>
                            <th scope="col" class="px-4 py-3.5 w-12 border-r border-blue-800/50">No</th>
                            <th scope="col" class="px-4 py-3.5 text-left border-r border-blue-800/50">Nama Siswa</th>
                            <th scope="col" class="px-4 py-3.5 border-r border-blue-800/50 w-24">Nilai UH</th>
                            <th scope="col" class="px-4 py-3.5 border-r border-blue-800/50 w-28">Pengetahuan</th>
                            <th scope="col" class="px-4 py-3.5 border-r border-blue-800/50 w-28">Keterampilan</th>
                            <th scope="col" class="px-4 py-3.5 border-r border-blue-800/50 w-24">Nilai Akhir</th>
                            <th scope="col" class="px-4 py-3.5 w-28">Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                            @php
                                $grade = $tableGrades->get($student->id);
                                $uh1 = $grade ? (float) $grade->uh1 : 0;
                                $uh2 = $grade ? (float) $grade->uh2 : 0;
                                $avgUh = $grade && ($grade->uh1 !== null || $grade->uh2 !== null) ? round(($uh1 + $uh2) / ($grade->uh1 !== null && $grade->uh2 !== null ? 2 : 1)) : '-';
                                $pen = $grade && $grade->nilai_pengetahuan !== null ? round((float) $grade->nilai_pengetahuan) : '-';
                                $ket = $grade && $grade->nilai_keterampilan !== null ? round((float) $grade->nilai_keterampilan) : '-';
                                $akhir = ($pen !== '-' && $ket !== '-') ? round(($pen + $ket) / 2) : '-';
                                $predikat = $grade ? $grade->predikat_pengetahuan : '-';
                            @endphp
                            <tr class="bg-white border-b hover:bg-blue-50/50 text-center transition-colors">
                                <td class="px-4 py-3 border-r border-slate-100 font-medium text-slate-500">{{ $students->firstItem() + $index }}.</td>
                                <td class="px-4 py-3 border-r border-slate-100 text-left font-semibold text-slate-800 whitespace-nowrap">{{ $student->name }}</td>
                                <td class="px-4 py-3 border-r border-slate-100 font-medium text-slate-700">{{ $avgUh }}</td>
                                <td class="px-4 py-3 border-r border-slate-100 font-medium text-slate-700">{{ $pen }}</td>
                                <td class="px-4 py-3 border-r border-slate-100 font-medium text-slate-700">{{ $ket }}</td>
                                <td class="px-4 py-3 border-r border-slate-100 font-bold text-slate-900 bg-slate-50/50">{{ $akhir }}</td>
                                <td class="px-4 py-3">
                                    @if($predikat === 'Tuntas')
                                        <span class="inline-flex items-center justify-center bg-blue-600 text-white text-[11px] px-2.5 py-1 rounded shadow-sm font-bold uppercase tracking-wider w-full">Tuntas</span>
                                    @elseif($predikat === 'Belum Tuntas')
                                        <span class="inline-flex items-center justify-center bg-red-500 text-white text-[11px] px-2.5 py-1 rounded shadow-sm font-bold uppercase tracking-wider w-full">Blm Tuntas</span>
                                    @else
                                        <span class="text-slate-400 font-medium">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-slate-500 bg-slate-50/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                    <p class="font-medium text-slate-600">Belum ada data siswa di kelas ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($students->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('growthChart');
        if (!ctx) return;
        
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);
        
        new Chart(ctx, {
            type: 'line',
            data: { 
                labels: chartLabels, 
                datasets: [{
                    label: 'Rata-rata Kelas',
                    data: chartData,
                    borderColor: '#1e3a8a', // blue-900
                    backgroundColor: 'rgba(30, 58, 138, 0.05)',
                    tension: 0.4, 
                    borderWidth: 3, 
                    pointRadius: 4, 
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#1e3a8a',
                    pointBorderWidth: 2,
                    fill: true
                }] 
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)', 
                        titleFont: { family: "'Inter', sans-serif", size: 13, weight: 'bold' },
                        bodyFont: { family: "'Inter', sans-serif", size: 14 }, 
                        padding: 12, 
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) { return 'Rata-rata: ' + context.parsed.y.toFixed(2); }
                        }
                    }
                },
                scales: {
                    y: { 
                        min: 0, 
                        max: 100, 
                        grid: { color: '#e2e8f0', drawBorder: false }, 
                        border: { dash: [4, 4] }, 
                        ticks: { font: { family: "'Inter', sans-serif", size: 11 }, color: '#64748b', stepSize: 20 } 
                    },
                    x: { 
                        grid: { display: false, drawBorder: false }, 
                        ticks: { font: { family: "'Inter', sans-serif", size: 11 }, color: '#64748b' } 
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                animation: { duration: 800, easing: 'easeOutQuart' }
            }
        });
    });
    </script>
    @endpush
</x-layouts.guru>

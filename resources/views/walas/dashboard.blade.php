<x-layouts.walas title="Dashboard Analitik Kelas">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-xl shadow-lg p-6 mb-6 text-white flex flex-col md:flex-row items-center md:justify-between gap-4">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-4 w-full md:w-auto">
            <div class="w-16 h-16 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center overflow-hidden shrink-0 shadow-inner text-xl font-bold">
                @php
                    $initials = collect(explode(' ', Auth::user()->name))->map(fn($n) => substr($n, 0, 1))->take(2)->implode('');
                @endphp
                {{ strtoupper($initials) }}
            </div>
            <div class="flex-1 w-full md:w-auto">
                <h2 class="text-2xl font-bold mb-2">{{ Auth::user()->name }}</h2>
                <div class="flex flex-wrap items-center gap-2 text-sm text-blue-100">
                    <span class="bg-blue-800/50 px-2.5 py-1 rounded-md font-medium border border-blue-600/50">Wali Kelas {{ $myClass->name ?? '-' }}</span>
                    <span class="bg-blue-800/50 px-2.5 py-1 rounded-md font-medium border border-blue-600/50">{{ $students->count() }} Siswa</span>
                    @if(isset($myClasses) && $myClasses->count() > 1)
                    <form method="GET" class="inline-block mt-2 sm:mt-0 w-full sm:w-auto">
                        <select name="class_id" class="form-select py-1 pl-3 pr-8 bg-blue-800/80 border-blue-600/50 text-white rounded-md focus:ring-blue-400 focus:border-blue-400 text-xs font-medium cursor-pointer w-full sm:w-auto" onchange="this.form.submit()">
                            @foreach($myClasses as $c)
                            <option value="{{ $c->id }}" {{ $myClass->id == $c->id ? 'selected' : '' }} class="text-slate-800 bg-white">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="text-left md:text-right w-full md:w-auto">
            <h1 class="text-xl md:text-3xl font-black uppercase tracking-wider drop-shadow-md text-white/90">DASHBOARD WALI KELAS</h1>
        </div>
    </div>

    @if(!$myClass)
    <div class="card text-center py-16 text-slate-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        Anda belum ditugaskan sebagai wali kelas untuk tahun ajaran aktif.
    </div>
    @else



    <!-- Widgets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Widget 1: L/P -->
        <div class="bg-gradient-to-br from-emerald-600 to-green-700 rounded-xl shadow-md p-4 text-white flex items-center gap-4 transition-transform hover:scale-[1.02]">
            <div class="p-3 bg-white/20 rounded-lg shrink-0 border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <div>
                <div class="text-sm font-bold flex flex-col gap-0.5 mt-1">
                    <span>Laki-laki : {{ $students->where('gender', 'L')->count() }}</span>
                    <span>Perempuan : {{ $students->where('gender', 'P')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Widget 2: Mapel -->
        <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl shadow-md p-4 text-white flex items-center gap-4 transition-transform hover:scale-[1.02]">
            <div class="p-3 bg-white/20 rounded-lg shrink-0 border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <p class="text-sm text-orange-100 font-medium">Mata Pelajaran</p>
                <h3 class="text-xl font-bold">{{ $subjects->count() }} Mapel</h3>
            </div>
        </div>

        <!-- Widget 3: Semester -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-700 rounded-xl shadow-md p-4 text-white flex items-center gap-4 transition-transform hover:scale-[1.02]">
            <div class="p-3 bg-white/20 rounded-lg shrink-0 border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <p class="text-sm text-indigo-100 font-medium">Periode Aktif</p>
                <h3 class="text-[17px] font-bold leading-tight" title="{{ $activePeriod ? $activePeriod->name : '-' }}">{{ $activePeriod ? $activePeriod->name : '-' }}</h3>
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

<div class="card mb-6">
    <h3 class="card-title mb-4">Grafik Pertumbuhan Rata-Rata Kelas</h3>
    <div class="min-h-[350px] w-full"><canvas id="classAvgChart"></canvas></div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
    <div class="card h-full flex flex-col">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
            <h3 class="card-title">Grafik Pertumbuhan Rata-Rata Siswa</h3>
            <select id="studentSelect1" class="form-select text-sm w-full md:w-56">
                <option value="">-- Pilih Siswa --</option>
                @foreach($students as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-h-[350px] flex-1 w-full"><canvas id="studentAvgChart"></canvas></div>
    </div>

    <div class="card h-full flex flex-col">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
            <h3 class="card-title">Grafik Mata Pelajaran Siswa</h3>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <select id="studentSelect2" class="form-select text-sm w-full sm:w-40">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
                <select id="subjectSelect" class="form-select text-sm w-full sm:w-40">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="min-h-[350px] flex-1 w-full"><canvas id="subjectAvgChart"></canvas></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Card Top 10 -->
    <div class="card bg-white h-full border border-slate-200 shadow-sm flex flex-col">
        <h3 class="card-title mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
            Ranking 10 Besar ({{ $activePeriod?->name ?? 'Periode Aktif' }})
        </h3>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 border-b border-slate-200 w-12 text-center">No</th>
                        <th class="px-4 py-3 border-b border-slate-200">Nama Siswa</th>
                        <th class="px-4 py-3 border-b border-slate-200 w-24 text-center">Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($top10 as $index => $row)
                        <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-2 text-center font-bold {{ $index < 3 ? 'text-amber-500 text-base' : 'text-slate-500' }}">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 font-medium text-slate-800">{{ $row['student']->name }}</td>
                            <td class="px-4 py-2 text-center font-bold text-primary-600">{{ $row['avg'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500 bg-slate-50/50">Belum ada data nilai pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Card Bintang Kelas per Mapel -->
    <div class="card bg-white h-full border border-slate-200 shadow-sm flex flex-col">
        <h3 class="card-title mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            Bintang Kelas Per Mapel ({{ $activePeriod?->name ?? 'Periode Aktif' }})
        </h3>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 border-b border-slate-200">Mata Pelajaran</th>
                        <th class="px-4 py-3 border-b border-slate-200">Nama Siswa</th>
                        <th class="px-4 py-3 border-b border-slate-200 w-24 text-center">Tertinggi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjectStars as $row)
                        <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-2 font-medium text-slate-700">{{ $row['subject']->name }}</td>
                            <td class="px-4 py-2 font-semibold text-slate-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                                <span class="truncate max-w-[140px] xl:max-w-xs" title="{{ $row['student']->name }}">{{ $row['student']->name }}</span>
                            </td>
                            <td class="px-4 py-2 text-center font-bold text-indigo-600">{{ $row['score'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500 bg-slate-50/50">Belum ada data nilai pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodNames = @json($periods->pluck('name'));
    const pIds = @json($periods->pluck('id'));

    const classData = @json($classData);
    const studentData = @json($studentData);
    const studentSubjectData = @json($studentSubjectData);

    const commonOptions = {
        responsive: true, maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, max: 100 } },
        elements: { line: { tension: 0.3 } }
    };

    // Chart 1: Class Average
    const classAvgCtx = document.getElementById('classAvgChart').getContext('2d');
    const classChart = new Chart(classAvgCtx, {
        type: 'line',
        data: {
            labels: periodNames,
            datasets: [{
                label: 'Rata-Rata Kelas',
                data: pIds.map(id => classData[id]?.avg ?? null),
                borderColor: '#0284c7', backgroundColor: 'rgba(2, 132, 199, 0.1)',
                borderWidth: 2, fill: true, pointBackgroundColor: '#0284c7'
            }]
        },
        options: commonOptions
    });

    // Chart 2: Student Average
    const studentAvgCtx = document.getElementById('studentAvgChart').getContext('2d');
    const studentChart = new Chart(studentAvgCtx, {
        type: 'line',
        data: {
            labels: periodNames,
            datasets: [{
                label: 'Rata-Rata Siswa',
                data: [null, null, null, null],
                borderColor: '#16a34a', backgroundColor: 'rgba(22, 163, 74, 0.1)',
                borderWidth: 2, fill: true, pointBackgroundColor: '#16a34a'
            }]
        },
        options: commonOptions
    });

    document.getElementById('studentSelect1').addEventListener('change', function(e) {
        const studentId = e.target.value;
        if(studentId && studentData[studentId]) {
            studentChart.data.datasets[0].data = pIds.map(id => studentData[studentId][id]?.avg ?? null);
            studentChart.data.datasets[0].label = 'Rata-Rata Siswa (' + e.target.options[e.target.selectedIndex].text + ')';
        } else {
            studentChart.data.datasets[0].data = [null, null, null, null];
            studentChart.data.datasets[0].label = 'Rata-Rata Siswa';
        }
        studentChart.update();
    });

    // Chart 3: Subject Average
    const subjectAvgCtx = document.getElementById('subjectAvgChart').getContext('2d');
    const subjectChart = new Chart(subjectAvgCtx, {
        type: 'line',
        data: {
            labels: periodNames,
            datasets: [{
                label: 'Nilai Mata Pelajaran',
                data: [null, null, null, null],
                borderColor: '#8b5cf6', backgroundColor: 'rgba(139, 92, 246, 0.1)',
                borderWidth: 2, fill: true, pointBackgroundColor: '#8b5cf6'
            }]
        },
        options: commonOptions
    });

    function updateSubjectChart() {
        const studentId = document.getElementById('studentSelect2').value;
        const subjectId = document.getElementById('subjectSelect').value;
        const selText = document.getElementById('subjectSelect').options[document.getElementById('subjectSelect').selectedIndex]?.text;
        
        if (studentId && subjectId && studentSubjectData[studentId] && studentSubjectData[studentId][subjectId]) {
            subjectChart.data.datasets[0].data = pIds.map(id => studentSubjectData[studentId][subjectId][id] ?? null);
            subjectChart.data.datasets[0].label = 'Nilai ' + selText;
        } else {
            subjectChart.data.datasets[0].data = [null, null, null, null];
            subjectChart.data.datasets[0].label = 'Nilai Mata Pelajaran';
        }
        subjectChart.update();
    }

    document.getElementById('studentSelect2').addEventListener('change', updateSubjectChart);
    document.getElementById('subjectSelect').addEventListener('change', updateSubjectChart);
    
    // Auto trigger first select items if available
    if(document.getElementById('studentSelect1').options.length > 1) {
        document.getElementById('studentSelect1').selectedIndex = 1;
        document.getElementById('studentSelect1').dispatchEvent(new Event('change'));
        
        document.getElementById('studentSelect2').selectedIndex = 1;
    }
    if(document.getElementById('subjectSelect').options.length > 1) {
        document.getElementById('subjectSelect').selectedIndex = 1;
        document.getElementById('subjectSelect').dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endif
</x-layouts.walas>

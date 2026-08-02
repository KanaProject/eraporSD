<x-layouts.walas title="Dashboard Analitik Kelas">
<div class="page-header"><h2 class="page-title">Dashboard Analitik Kelas</h2><p class="page-subtitle">Tren Pertumbuhan Nilai Selama 4 Periode</p></div>

@if(!$myClass)
<div class="card text-center py-16 text-slate-400">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    Anda belum ditugaskan sebagai wali kelas untuk tahun ajaran aktif.
</div>
@else

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="card bg-gradient-to-br from-primary-600 to-primary-700 text-white border-0 shadow-lg shadow-primary-500/30">
        <div class="flex justify-between items-start">
            <div>
                <div class="font-medium text-primary-100 mb-1">Kelas Anda</div>
                <div class="text-4xl font-extrabold mb-4">{{ $myClass->name ?? '-' }}</div>
            </div>
            @if(isset($myClasses) && $myClasses->count() > 1)
            <form method="GET">
                <select name="class_id" class="form-select bg-white/20 border border-white/30 text-white rounded-lg focus:ring-white/50 focus:border-white/50 text-sm" onchange="this.form.submit()">
                    @foreach($myClasses as $c)
                    <option value="{{ $c->id }}" {{ $myClass->id == $c->id ? 'selected' : '' }} class="text-slate-800">{{ $c->name }}</option>
                    @endforeach
                </select>
            </form>
            @endif
        </div>
        <div class="flex items-center gap-2 text-primary-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            <span class="font-medium text-lg">{{ $students->count() }} Siswa Aktif</span>
        </div>
    </div>
    
    <div class="card flex flex-col justify-center">
        <h3 class="card-title mb-2">Tahun Ajaran Aktif</h3>
        <div class="text-2xl font-bold text-slate-800">{{ $activeYear->name }}</div>
        <p class="text-sm text-slate-500 mt-2">Analitik di bawah ini merangkum perkembangan nilai siswa dalam 4 periode penilaian pada tahun ajaran ini.</p>
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

<div class="card mb-6">
    <h3 class="card-title mb-4">Grafik Pertumbuhan Rata-Rata Kelas</h3>
    <div class="h-72"><canvas id="classAvgChart"></canvas></div>
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
        <div class="h-72 flex-1"><canvas id="studentAvgChart"></canvas></div>
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
        <div class="h-72 flex-1"><canvas id="subjectAvgChart"></canvas></div>
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

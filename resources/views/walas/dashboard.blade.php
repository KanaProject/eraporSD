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

<div class="card mb-6">
    <h3 class="card-title mb-4">Grafik Pertumbuhan Rata-Rata Kelas</h3>
    <div class="h-72"><canvas id="classAvgChart"></canvas></div>
</div>

<div class="card mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <h3 class="card-title">Grafik Pertumbuhan Rata-Rata Siswa</h3>
        <select id="studentSelect1" class="form-select text-sm w-full md:w-64">
            <option value="">-- Pilih Siswa --</option>
            @foreach($students as $s)
            <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="h-72"><canvas id="studentAvgChart"></canvas></div>
</div>

<div class="card mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <h3 class="card-title">Grafik Mata Pelajaran Siswa</h3>
        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
            <select id="studentSelect2" class="form-select text-sm w-full sm:w-48">
                <option value="">-- Pilih Siswa --</option>
                @foreach($students as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
            <select id="subjectSelect" class="form-select text-sm w-full sm:w-48">
                <option value="">-- Pilih Mata Pelajaran --</option>
                @foreach($subjects as $sub)
                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="h-72"><canvas id="subjectAvgChart"></canvas></div>
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

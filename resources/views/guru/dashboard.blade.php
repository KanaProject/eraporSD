<x-layouts.guru title="Dashboard Guru">
<div class="page-header">
    <h2 class="page-title">Dashboard Guru</h2>
    <p class="page-subtitle">Tahun Ajaran {{ $activeYear?->name ?? '-' }}</p>
</div>

<div class="card p-6">
    <div class="border-b border-slate-100 pb-4 mb-6">
        <h3 class="text-xl font-bold text-slate-800">Tren Pertumbuhan Rata-Rata Nilai (4 Periode Terakhir)</h3>
        <p class="text-sm text-slate-500 mt-1">Grafik ini menampilkan rata-rata nilai pengetahuan siswa untuk setiap mata pelajaran yang Anda ampu.</p>
    </div>
    
    @if(empty($chartLabels) || $subjectsTaught->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
            Belum ada data periode atau penugasan mapel.
        </div>
    @else
        <style>
        .custom-scroll::-webkit-scrollbar { height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>

        <div class="mb-8 bg-slate-50/50 rounded-2xl p-5 border border-slate-100">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex-1 min-w-0">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Mata Pelajaran</label>
                    <div class="flex gap-2 overflow-x-auto pb-2 custom-scroll">
                        <button class="subject-card shrink-0 px-4 py-2 bg-primary-600 text-white border-transparent rounded-xl font-medium text-sm transition-all shadow-sm" data-id="">Semua Mapel</button>
                        @foreach($subjectsTaught as $subject)
                            <button class="subject-card shrink-0 px-4 py-2 bg-white text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-slate-900 rounded-xl font-medium text-sm transition-all" data-id="{{ $subject->id }}">{{ $subject->name }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="w-full lg:w-72 shrink-0">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Filter Siswa</label>
                    <select id="studentFilter" class="form-select text-sm w-full bg-white border-slate-200 rounded-xl py-2.5 shadow-sm focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        <option value="">-- Rata-Rata Seluruh Siswa --</option>
                        @foreach($studentsTaught as $student)
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="relative w-full h-[400px]">
            <canvas id="growthChart"></canvas>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('growthChart');
    if (!ctx) return;
    
    const colors = ['#0284c7', '#16a34a', '#ca8a04', '#dc2626', '#9333ea', '#4f46e5', '#ea580c', '#db2777'];
    const subjectData = @json($subjectData);
    const chartLabels = @json($chartLabels);
    
    let selectedSubject = '';
    let selectedStudent = '';
    let idleTimer = null;

    let chartInstance = new Chart(ctx, {
        type: 'line',
        data: { labels: chartLabels, datasets: [] },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, font: { family: "'Inter', sans-serif" } } },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)', titleFont: { family: "'Inter', sans-serif", size: 13 },
                    bodyFont: { family: "'Inter', sans-serif", size: 13 }, padding: 12, cornerRadius: 8,
                    callbacks: { label: function(context) { return context.dataset.label + ': ' + context.parsed.y.toFixed(2); } }
                }
            },
            scales: {
                y: { min: 0, max: 100, grid: { color: '#f1f5f9' }, border: { dash: [4, 4] }, ticks: { font: { family: "'Inter', sans-serif" }, color: '#64748b' } },
                x: { grid: { display: false }, ticks: { font: { family: "'Inter', sans-serif" }, color: '#64748b' } }
            },
            animation: { duration: 400 }
        }
    });

    function updateChart() {
        let newDatasets = [];
        let index = 0;

        if (selectedSubject === '') {
            for (const [subjId, data] of Object.entries(subjectData)) {
                let yData = selectedStudent === '' ? data.average : data.students[selectedStudent];
                if(yData) {
                    const color = colors[index % colors.length];
                    const subjectName = document.querySelector(`.subject-card[data-id="${subjId}"]`).innerText;
                    newDatasets.push({
                        label: subjectName,
                        data: yData, borderColor: color, backgroundColor: color, tension: 0.3, borderWidth: 2, pointRadius: 4, pointHoverRadius: 6,
                    });
                    index++;
                }
            }
        } else {
            const data = subjectData[selectedSubject];
            if(data) {
                let yData = selectedStudent === '' ? data.average : data.students[selectedStudent];
                const color = colors[0];
                const subjectName = document.querySelector(`.subject-card[data-id="${selectedSubject}"]`).innerText;
                newDatasets.push({
                    label: subjectName,
                    data: yData, borderColor: color, backgroundColor: color, tension: 0.3, borderWidth: 3, pointRadius: 5, pointHoverRadius: 7,
                });
            }
        }

        chartInstance.data.datasets = newDatasets;
        chartInstance.update();
        startIdleTimer();
    }

    function startIdleTimer() {
        clearTimeout(idleTimer);
        // Reset to default after 10 seconds of inactivity
        idleTimer = setTimeout(() => {
            if(selectedSubject !== '' || selectedStudent !== '') {
                selectedSubject = '';
                selectedStudent = '';
                document.getElementById('studentFilter').value = '';
                document.querySelectorAll('.subject-card').forEach(b => {
                    if(b.dataset.id === '') {
                        b.className = 'subject-card shrink-0 px-4 py-2 bg-primary-600 text-white border-transparent rounded-xl font-medium text-sm transition-all shadow-sm';
                    } else {
                        b.className = 'subject-card shrink-0 px-4 py-2 bg-white text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-slate-900 rounded-xl font-medium text-sm transition-all';
                    }
                });
                updateChart();
            }
        }, 10000);
    }

    // Interactions
    document.querySelectorAll('.subject-card').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.subject-card').forEach(b => {
                b.className = 'subject-card shrink-0 px-4 py-2 bg-white text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-slate-900 rounded-xl font-medium text-sm transition-all';
            });
            this.className = 'subject-card shrink-0 px-4 py-2 bg-primary-600 text-white border-transparent rounded-xl font-medium text-sm transition-all shadow-sm';
            selectedSubject = this.dataset.id;
            updateChart();
        });
    });

    document.getElementById('studentFilter').addEventListener('change', function() {
        selectedStudent = this.value;
        updateChart();
    });

    ['mousemove', 'click', 'keypress', 'touchstart'].forEach(evt => {
        document.body.addEventListener(evt, () => {
            if(selectedSubject !== '' || selectedStudent !== '') {
                startIdleTimer();
            }
        }, { passive: true });
    });

    updateChart();
});
</script>
@endpush
</x-layouts.guru>

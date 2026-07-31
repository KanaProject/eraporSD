<x-layouts.kurikulum title="Dashboard Kurikulum">
<div class="page-header"><h2 class="page-title">Dashboard Kurikulum</h2><p class="page-subtitle">Manajemen mata pelajaran dan konfigurasi penilaian</p></div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon bg-primary-50"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg></div>
        <div><div class="stat-value">{{ $stats['total_subjects'] }}</div><div class="stat-label">Mata Pelajaran</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-blue-50"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg></div>
        <div><div class="stat-value">{{ $stats['total_configs'] }}</div><div class="stat-label">Konfigurasi KKM</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-green-50"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg></div>
        <div><div class="stat-value">{{ $stats['total_assign'] }}</div><div class="stat-label">Penugasan Guru</div></div>
    </div>
</div>
<div class="card">
    <h3 class="card-title mb-4">Mata Pelajaran per Tingkat Kelas</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        @for($g=1;$g<=6;$g++)
        <div class="border border-slate-200 rounded-lg p-3">
            <div class="text-xs font-bold text-slate-500 mb-2">KELAS {{ $g }}</div>
            @foreach($subjectsByGrade->get($g, collect()) as $config)
            <div class="text-xs text-slate-600 py-0.5 border-b border-slate-100 last:border-0">{{ $config->subject->name }}</div>
            @endforeach
        </div>
        @endfor
    </div>
</div>
</x-layouts.kurikulum>

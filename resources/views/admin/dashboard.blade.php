<x-layouts.admin title="Dashboard Admin">

<div class="page-header">
    <h2 class="page-title">Dashboard</h2>
    <p class="page-subtitle">Selamat datang di E-Rapor — Panel Administrasi</p>
</div>

<!-- Stats Row -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon bg-blue-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_students'] }}</div>
            <div class="stat-label">Total Siswa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-green-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_teachers'] }}</div>
            <div class="stat-label">Guru</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-purple-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_classes'] }}</div>
            <div class="stat-label">Rombel</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-amber-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_subjects'] }}</div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
    </div>
</div>

<!-- Active Period Info + Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Periode Aktif</h3>
            <span class="badge-success">Aktif</span>
        </div>
        @if($activePeriod)
        <div class="space-y-2">
            <div class="flex justify-between text-sm"><span class="text-slate-500">Tahun Ajaran</span><span class="font-medium">{{ $activeYear?->name ?? '-' }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-slate-500">Semester</span><span class="font-medium">{{ $activeSem?->name ?? '-' }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-slate-500">Periode</span><span class="font-semibold text-primary-700">{{ $activePeriod->name }}</span></div>
        </div>
        @else
        <p class="text-sm text-slate-500">Belum ada periode aktif. <a href="{{ route('admin.academic-years.index') }}" class="text-primary-600 underline">Atur sekarang</a></p>
        @endif
    </div>

    <div class="card col-span-2">
        <div class="card-header">
            <h3 class="card-title">Aksi Cepat</h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <a href="{{ route('admin.students.index') }}" class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors text-sm font-medium text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                Data Siswa
            </a>
            <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors text-sm font-medium text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                Tambah Guru
            </a>
            <a href="{{ route('admin.academic-years.index') }}" class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors text-sm font-medium text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                Tahun Ajaran
            </a>
            <a href="{{ route('admin.homeroom.index') }}" class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors text-sm font-medium text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                Atur Wali Kelas
            </a>
            <a href="{{ route('admin.school.edit') }}" class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors text-sm font-medium text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
                Profil Sekolah
            </a>
        </div>
    </div>
</div>

<!-- Class Overview -->
@if(count($classProgress) > 0)
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Rekap Kelas</h3>
        <span class="text-xs text-slate-400">Tahun Ajaran {{ $activeYear?->name }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th class="text-center">Total Siswa</th>
                    <th class="text-center">Penugasan Guru</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classProgress as $cp)
                <tr>
                    <td class="font-medium">Kelas {{ $cp['class']->name }}</td>
                    <td class="text-center">
                        <span class="badge-info">{{ $cp['total_students'] }} siswa</span>
                    </td>
                    <td class="text-center">
                        @if($cp['assignments'] > 0)
                            <span class="badge-success">{{ $cp['assignments'] }} mapel</span>
                        @else
                            <span class="badge-neutral">Belum ada</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

</x-layouts.admin>

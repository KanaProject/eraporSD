<x-layouts.app :title="$title ?? 'Dashboard Guru'" portalLabel="Portal Guru">
    <x-slot name="sidebarNav">
        @php $route = request()->route()->getName(); @endphp

        <a href="{{ route('guru.dashboard') }}" class="sidebar-item {{ $route === 'guru.dashboard' ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            Dashboard
        </a>

        @if(\App\Models\TeacherSubjectAssignment::where('user_id', auth()->id())->where('academic_year_id', \App\Models\AcademicYear::getActive()?->id)->exists())
        <div class="sidebar-section-label">Penilaian</div>
        <a href="{{ route('guru.grades.index') }}" class="sidebar-item {{ str_starts_with($route, 'guru.grades') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
            Input Nilai
        </a>
        @endif

        @if(session('active_role') === 'guru')
        <div class="sidebar-section-label">Akun</div>
        <a href="{{ route('role.select') }}" class="sidebar-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
            Ganti Peran
        </a>
        @endif
    </x-slot>

    {{ $slot }}
</x-layouts.app>

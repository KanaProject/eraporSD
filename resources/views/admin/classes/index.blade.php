<x-layouts.admin title="Manajemen Kelas">
<div class="page-header flex items-center justify-between">
    <div><h2 class="page-title">Manajemen Kelas</h2><p class="page-subtitle">Kelas aktif di sekolah</p></div>
    <a href="{{ route('admin.classes.create') }}" class="btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Tambah Kelas
    </a>
</div>
<div class="space-y-8">
    @forelse($classes as $level => $levelClasses)
    <div>
        <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-200 pb-2">Tingkat {{ $level }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($levelClasses as $class)
            <div class="card p-3 hover:shadow-card-hover transition-shadow flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center text-primary-700 font-bold text-base shrink-0">{{ $class->name }}</div>
                    <div>
                        <div class="font-bold text-slate-800 leading-tight">Kelas {{ $class->name }}</div>
                        <div class="text-[11px] font-medium text-slate-500 mt-0.5">{{ $class->students_count }} siswa</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.classes.destroy', $class) }}"
                    data-confirm="Hapus kelas {{ $class->name }}? Data ini tidak dapat dikembalikan."
                    data-confirm-title="Hapus Kelas"
                    data-confirm-type="danger">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-icon text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100" title="Hapus Kelas">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="card text-center py-12 text-slate-400">Belum ada kelas.</div>
    @endforelse
</div>
</x-layouts.admin>

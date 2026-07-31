<x-layouts.admin title="Penetapan Wali Kelas">
<div class="page-header">
    <h2 class="page-title">Penetapan Wali Kelas</h2>
    <p class="page-subtitle">Tahun Ajaran {{ $activeYear?->name ?? '-' }}</p>
</div>
@if(!$activeYear)
    <div class="card text-center py-8 text-slate-500">Tidak ada tahun ajaran aktif. <a href="{{ route('admin.academic-years.index') }}" class="text-primary-600 underline">Atur Tahun Ajaran</a></div>
@else
<div class="space-y-8">
    @foreach($classes as $level => $levelClasses)
    <div>
        <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-200 pb-2">Tingkat {{ $level }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($levelClasses as $class)
            @php $currentWalas = $class->homeroomAssignments->first(); @endphp
            <div class="card p-4 hover:shadow-card-hover transition-shadow flex flex-col justify-between">
                <div class="mb-4">
                    <div class="font-bold text-slate-800 text-lg">Kelas {{ $class->name }}</div>
                </div>
                <form method="POST" action="{{ route('admin.homeroom.assign') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="school_class_id" value="{{ $class->id }}">
                    <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
                    <div class="flex flex-col gap-1 w-full">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Wali Kelas</label>
                        <select name="user_id" class="form-select w-full text-sm py-1.5 px-2">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($walasUsers as $guru)
                            <option value="{{ $guru->id }}" {{ $currentWalas?->user_id == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1 w-full">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Guru Pendamping</label>
                        <select name="companion_id" class="form-select w-full text-sm py-1.5 px-2">
                            <option value="">-- Tanpa Pendamping --</option>
                            @foreach($guruUsers as $guru)
                            <option value="{{ $guru->id }}" {{ $currentWalas?->companion_id == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-primary py-2 px-3 text-sm w-full mt-2 justify-center">Simpan Penempatan</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endif
</x-layouts.admin>

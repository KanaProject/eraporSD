<x-layouts.kurikulum title="Penugasan Guru">
<div class="page-header"><h2 class="page-title">Penugasan Guru ke Mata Pelajaran</h2><p class="page-subtitle">Tahun Ajaran {{ $activeYear?->name ?? '-' }}</p></div>
<div class="flex flex-wrap gap-2 mb-6">
    @for($g=1;$g<=6;$g++)
    <a href="{{ route('kurikulum.assignments.index', ['grade' => $g]) }}"
        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $gradeLevel == $g ? 'bg-primary-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
        Kelas {{ $g }}
    </a>
    @endfor
</div>
@if(!$activeYear)
    <div class="card text-center py-8 text-slate-500">Tidak ada tahun ajaran aktif.</div>
@else
<div class="card overflow-x-auto">
    <table class="w-full text-sm flex flex-col md:table">
        <thead class="hidden md:table-header-group">
            <tr class="border-b border-slate-200">
                <th class="text-left py-3 px-4 font-semibold text-slate-600">Mata Pelajaran</th>
                @foreach($classes as $class)
                <th class="text-center py-3 px-2 font-semibold text-slate-600 min-w-[160px]">Kelas {{ $class->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="flex flex-col md:table-row-group gap-4 md:gap-0 p-4 md:p-0">
            @foreach($subjects as $subject)
            <tr class="flex flex-col md:table-row border border-slate-200 md:border-b md:border-x-0 md:border-t-0 md:border-slate-100 rounded-xl md:rounded-none overflow-hidden">
                <td class="py-3 px-4 font-semibold text-slate-700 bg-slate-50 md:bg-transparent border-b border-slate-200 md:border-none">
                    {{ $subject->name }}
                </td>
                @foreach($classes as $class)
                @php $key = $subject->id.'_'.$class->id; $assigned = $assignments->get($key)?->first(); @endphp
                <td class="py-3 px-4 md:py-2 md:px-2 flex md:table-cell items-center gap-4 border-b border-slate-50 md:border-none last:border-none">
                    <span class="md:hidden text-xs font-semibold text-slate-500 uppercase tracking-wide w-20 shrink-0">Kelas {{ $class->name }}</span>
                    <form method="POST" action="{{ route('kurikulum.assignments.assign') }}" class="w-full md:w-auto">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        <input type="hidden" name="school_class_id" value="{{ $class->id }}">
                        <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
                        <select name="user_id" class="form-select text-sm md:text-xs py-2 md:py-1 w-full" onchange="this.form.submit()">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $assigned?->user_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
</x-layouts.kurikulum>

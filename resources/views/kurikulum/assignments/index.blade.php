<x-layouts.kurikulum title="Penugasan Guru">
<div class="page-header"><h2 class="page-title">Penugasan Guru ke Mata Pelajaran</h2><p class="page-subtitle">Tahun Ajaran {{ $activeYear?->name ?? '-' }}</p></div>
<div class="flex gap-2 mb-6">
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
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-200">
                <th class="text-left py-3 px-4 font-semibold text-slate-600">Mata Pelajaran</th>
                @foreach($classes as $class)
                <th class="text-center py-3 px-2 font-semibold text-slate-600 min-w-[160px]">Kelas {{ $class->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $subject)
            <tr class="border-b border-slate-100">
                <td class="py-3 px-4 font-medium">{{ $subject->name }}</td>
                @foreach($classes as $class)
                @php $key = $subject->id.'_'.$class->id; $assigned = $assignments->get($key)?->first(); @endphp
                <td class="py-2 px-2">
                    <form method="POST" action="{{ route('kurikulum.assignments.assign') }}">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        <input type="hidden" name="school_class_id" value="{{ $class->id }}">
                        <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
                        <select name="user_id" class="form-select text-xs py-1" onchange="this.form.submit()">
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

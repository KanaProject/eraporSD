<x-layouts.kurikulum title="KKM & Bobot Nilai">
<div class="page-header">
    <h2 class="page-title">Konfigurasi KKM & Bobot Nilai</h2>
    <p class="page-subtitle">Kurikulum Merdeka — Bobot UH + Bobot Teori harus = 100%</p>
</div>

<!-- Grade Level Filter -->
<div class="flex flex-wrap gap-2 mb-6">
    @for($g=1;$g<=6;$g++)
    <a href="{{ route('kurikulum.configs.index', ['grade' => $g]) }}"
        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $gradeLevel == $g ? 'bg-primary-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
        Kelas {{ $g }}
    </a>
    @endfor
</div>

@if($subjects->isEmpty())
    <div class="card text-center py-12 text-slate-400">Belum ada mata pelajaran untuk kelas {{ $gradeLevel }}. <a href="{{ route('kurikulum.subjects.index') }}" class="text-primary-600 underline">Tambah Mapel</a></div>
@else
<form method="POST" action="{{ route('kurikulum.configs.update') }}">
    @csrf
    <input type="hidden" name="grade_level" value="{{ $gradeLevel }}">
    <div class="card overflow-x-auto">
        <table class="w-full text-sm flex flex-col lg:table">
            <thead class="hidden lg:table-header-group">
                <tr class="border-b border-slate-200">
                    <th class="text-left py-3 px-4 font-semibold text-slate-600">Mata Pelajaran</th>
                    <th class="text-center py-3 px-2 font-semibold text-slate-600">Pengaturan KKM & Bobot (Berlaku untuk seluruh Kelas {{ $gradeLevel }})</th>
                </tr>
            </thead>
            <tbody class="flex flex-col lg:table-row-group">
                @php $currentGroup = ''; @endphp
                @foreach($subjects as $subject)
                @if($currentGroup != $subject->group)
                    <tr class="bg-slate-50 flex flex-col lg:table-row"><td colspan="2" class="font-semibold text-slate-700 py-2 px-4">{{ $subject->group }}</td></tr>
                    @php $currentGroup = $subject->group; @endphp
                @endif
                <tr class="border-b border-slate-100 flex flex-col lg:table-row">
                    <td class="py-3 px-4 font-medium text-slate-800 border-b border-slate-50 lg:border-none">
                        <label class="flex items-center gap-3 cursor-pointer">
                            @php $config = $configs[$subject->id] ?? null; @endphp
                            <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" class="form-checkbox text-primary-600 rounded toggle-subject" {{ $config ? 'checked' : '' }} data-target="config-row-{{ $subject->id }}">
                            <span>
                                @if($subject->parent_id)
                                    <span class="text-slate-400 mr-1">└</span> {{ $subject->name }}
                                @else
                                    {{ $subject->name }}
                                @endif
                            </span>
                        </label>
                    </td>
                    <td class="py-3 px-4 lg:px-2 bg-slate-50/50 lg:bg-transparent">
                        <div id="config-row-{{ $subject->id }}" class="flex flex-col lg:flex-row items-start lg:items-center justify-start lg:justify-center gap-4 lg:gap-8 py-2 lg:py-1 transition-opacity {{ $config ? '' : 'opacity-30 pointer-events-none' }}">
                            <!-- KKM -->
                            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-1 lg:gap-2 w-full lg:w-auto">
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide lg:shrink-0">KKM</span>
                                <input type="number" name="configs[{{ $subject->id }}][kkm]" value="{{ $config->kkm ?? 70 }}"
                                    class="form-input text-sm font-medium py-1.5 px-3 w-full lg:w-24 text-center config-input" min="0" max="100" step="1" {{ $config ? '' : 'disabled' }}>
                            </div>
                            
                            <!-- Bobots -->
                            <div class="flex flex-row gap-4 w-full lg:w-auto">
                                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-1 lg:gap-2 flex-1 lg:flex-none">
                                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide lg:shrink-0">Bobot UH</span>
                                    <div class="flex items-center gap-2 w-full lg:w-auto">
                                        <input type="number" name="configs[{{ $subject->id }}][bobot_uh]" value="{{ $config->bobot_uh ?? 50 }}"
                                            class="form-input text-sm font-medium py-1.5 px-3 flex-1 w-0 lg:w-20 lg:flex-none text-center input-bobot-uh config-input" min="0" max="100" step="1" {{ $config ? '' : 'disabled' }}>
                                        <span class="text-xs text-slate-400 font-medium shrink-0 lg:w-4">%</span>
                                    </div>
                                </div>
                                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-1 lg:gap-2 flex-1 lg:flex-none">
                                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide lg:shrink-0">Bobot Teori</span>
                                    <div class="flex items-center gap-2 w-full lg:w-auto">
                                        <input type="number" name="configs[{{ $subject->id }}][bobot_teori]" value="{{ $config->bobot_teori ?? 50 }}"
                                            class="form-input text-sm font-medium py-1.5 px-3 flex-1 w-0 lg:w-20 lg:flex-none text-center input-bobot-teori config-input" min="0" max="100" step="1" {{ $config ? '' : 'disabled' }}>
                                        <span class="text-xs text-slate-400 font-medium shrink-0 lg:w-4">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex justify-end mt-4">
        <button type="submit" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            Simpan Konfigurasi Kelas {{ $gradeLevel }}
        </button>
    </div>
</form>
@endif

<script>
    document.querySelectorAll('.toggle-subject').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const row = document.getElementById(this.dataset.target);
            const inputs = row.querySelectorAll('.config-input');
            if (this.checked) {
                row.classList.remove('opacity-30', 'pointer-events-none');
                inputs.forEach(input => input.disabled = false);
            } else {
                row.classList.add('opacity-30', 'pointer-events-none');
                inputs.forEach(input => input.disabled = true);
            }
        });
    });

    document.querySelectorAll('.input-bobot-uh').forEach(input => {
        input.addEventListener('input', function() {
            let val = parseFloat(this.value) || 0;
            if (val > 100) { val = 100; this.value = 100; }
            if (val < 0) { val = 0; this.value = 0; }
            const row = this.closest('tr');
            const teoriInput = row.querySelector('.input-bobot-teori');
            if (teoriInput) {
                teoriInput.value = 100 - val;
            }
        });
    });

    document.querySelectorAll('.input-bobot-teori').forEach(input => {
        input.addEventListener('input', function() {
            let val = parseFloat(this.value) || 0;
            if (val > 100) { val = 100; this.value = 100; }
            if (val < 0) { val = 0; this.value = 0; }
            const row = this.closest('tr');
            const uhInput = row.querySelector('.input-bobot-uh');
            if (uhInput) {
                uhInput.value = 100 - val;
            }
        });
    });
</script>

</x-layouts.kurikulum>

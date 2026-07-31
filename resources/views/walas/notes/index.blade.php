<x-layouts.walas title="Catatan Wali Kelas">
<div class="page-header">
    <h2 class="page-title">Catatan & Perkembangan Karakter — Kelas {{ $myClass->name }}</h2>
    <p class="page-subtitle">Input catatan khusus wali kelas</p>
</div>

<div class="flex gap-2 mb-6 overflow-x-auto">
    @foreach($periods as $p)
    <a href="{{ route('walas.notes.index', ['period_id' => $p->id]) }}"
        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $period->id == $p->id ? 'bg-primary-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
        {{ $p->name }} ({{ $activeYear->name }})
    </a>
    @endforeach
</div>

<form method="POST" action="{{ route('walas.notes.save') }}">
    @csrf
    <input type="hidden" name="period_id" value="{{ $period->id }}">
    
    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b-2 border-slate-200">
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 w-8">#</th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 min-w-[200px]">Nama Siswa</th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 min-w-[300px]">Catatan Wali Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                @php $note = $notes->get($student->id); @endphp
                <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                    <td class="py-3 px-3 text-slate-400 text-xs align-top">{{ $i+1 }}</td>
                    <td class="py-3 px-3 align-top">
                        <div class="font-medium text-slate-800">{{ $student->name }}</div>
                        <div class="text-xs text-slate-400">{{ $student->nis ?? '-' }}</div>
                    </td>
                    <td class="py-3 px-3">
                        <textarea name="notes[{{ $student->id }}][note]" rows="3" 
                            class="form-input text-sm w-full disabled:bg-slate-100 disabled:text-slate-400" placeholder="Cth: Pertahankan prestasimu dan tingkatkan kedisiplinan..." {{ !$period->is_active ? 'disabled' : '' }}>{{ $note->note ?? '' }}</textarea>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center py-10 text-slate-400">Belum ada siswa di kelas ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($students->isNotEmpty())
        <div class="flex justify-end mt-4 pt-4 border-t border-slate-100">
            @if($period->is_active)
                <button type="submit" class="btn-primary">Simpan Catatan</button>
            @else
                <span class="text-sm text-red-600 font-medium flex items-center gap-1.5 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Periode Terkunci
                </span>
            @endif
        </div>
        @endif
    </div>
</form>
</x-layouts.walas>

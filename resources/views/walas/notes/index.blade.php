<x-layouts.walas title="Catatan Wali Kelas">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-900 to-blue-700 rounded-xl shadow-lg p-6 mb-6 text-white flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold mb-1">Catatan Wali Kelas</h2>
                <div class="flex flex-wrap items-center gap-2 text-sm text-indigo-100 mt-1">
                    <span class="bg-indigo-900/50 px-2.5 py-1 rounded-md font-medium border border-indigo-500/50">Kelas {{ $myClass->name }}</span>
                    <span class="bg-indigo-900/50 px-2.5 py-1 rounded-md font-medium border border-indigo-500/50">Input perkembangan siswa</span>
                </div>
            </div>
        </div>
    </div>

<div class="flex gap-2 mb-6 overflow-x-auto">
    @foreach($periods as $p)
    <a href="{{ route('walas.notes.index', ['period_id' => $p->id]) }}"
        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $period->id == $p->id ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-indigo-50' }}">
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
                <tr class="border-b-2 border-indigo-200 bg-indigo-50/30">
                    <th class="text-left py-3 px-3 font-semibold text-indigo-900 w-8">#</th>
                    <th class="text-left py-3 px-3 font-semibold text-indigo-900 min-w-[200px]">Nama Siswa</th>
                    <th class="text-left py-3 px-3 font-semibold text-indigo-900 min-w-[300px]">Catatan Wali Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                @php $note = $notes->get($student->id); @endphp
                <tr class="border-b border-slate-100 hover:bg-indigo-50/50">
                    <td class="py-3 px-3 text-slate-400 text-xs align-top">{{ $i+1 }}</td>
                    <td class="py-3 px-3 align-top">
                        <div class="font-medium text-slate-800">{{ $student->name }}</div>
                        <div class="text-xs text-slate-400">{{ $student->nis ?? '-' }}</div>
                    </td>
                    <td class="py-3 px-3">
                        <textarea name="notes[{{ $student->id }}][note]" rows="3" 
                            class="form-input text-sm w-full border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 disabled:bg-slate-100 disabled:text-slate-400 transition-shadow" placeholder="Cth: Pertahankan prestasimu dan tingkatkan kedisiplinan..." {{ !$period->is_active ? 'disabled' : '' }}>{{ $note->note ?? '' }}</textarea>
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
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 transition-all shadow hover:shadow-md hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Simpan Catatan
                </button>
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

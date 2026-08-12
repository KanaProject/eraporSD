<x-layouts.walas title="Catatan Wali Kelas">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-900 to-blue-700 rounded-xl shadow-lg p-4 sm:p-6 mb-6 text-white flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3 sm:gap-4 w-full">
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-8 sm:h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-xl sm:text-2xl font-bold mb-1 truncate">Catatan Wali Kelas</h2>
                <div class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-2 text-sm text-indigo-100 mt-1">
                    <span class="bg-indigo-900/50 px-2.5 py-1 rounded-md font-medium border border-indigo-500/50">Kelas {{ $myClass->name }}</span>
                    <select onchange="window.location.href=this.value" class="max-w-full bg-indigo-900/50 text-indigo-50 border border-indigo-500/50 rounded-md px-2.5 py-1 pr-8 outline-none focus:ring-2 focus:ring-indigo-400 text-sm font-medium cursor-pointer appearance-none hover:bg-indigo-800/50 transition-colors" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23e0e7ff%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.7rem top 50%; background-size: 0.65rem auto;">
                        @foreach($periods as $p)
                            <option value="{{ route('walas.notes.index', ['period_id' => $p->id]) }}" {{ $period->id == $p->id ? 'selected' : '' }} class="bg-indigo-900 text-white">
                                Periode: {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

<form method="POST" action="{{ route('walas.notes.save') }}">
    @csrf
    <input type="hidden" name="period_id" value="{{ $period->id }}">
    
    <div class="card p-0 overflow-hidden">
        @if($students->isNotEmpty())
        <div class="flex justify-between items-center p-4 border-b border-slate-100 bg-white">
            <div class="text-sm text-slate-500">
                Total Siswa: <span class="font-semibold text-slate-700">{{ $students->count() }}</span>
            </div>
            @if($period->is_active)
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-all shadow-sm hover:shadow hover:-translate-y-0.5">
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

        <div class="overflow-y-auto max-h-[60vh] relative">
            <table class="w-full text-sm flex flex-col lg:table">
                <thead class="sticky top-0 z-10 shadow-sm hidden lg:table-header-group">
                <tr class="border-b-2 border-indigo-200 bg-indigo-50">
                    <th class="text-left py-3 px-3 font-semibold text-indigo-900 w-8">#</th>
                    <th class="text-left py-3 px-3 font-semibold text-indigo-900 min-w-[200px]">Nama Siswa</th>
                    <th class="text-left py-3 px-3 font-semibold text-indigo-900 min-w-[300px]">Catatan Wali Kelas</th>
                </tr>
            </thead>
            <tbody class="flex flex-col lg:table-row-group gap-4 lg:gap-0 p-4 lg:p-0">
                @forelse($students as $i => $student)
                @php $note = $notes->get($student->id); @endphp
                <tr class="flex flex-col lg:table-row border border-slate-200 lg:border-b lg:border-x-0 lg:border-t-0 lg:border-slate-100 rounded-xl lg:rounded-none bg-white overflow-hidden shadow-sm lg:shadow-none hover:bg-indigo-50/50">
                    <td class="hidden lg:table-cell py-3 px-3 text-slate-400 text-xs align-top">{{ $i+1 }}</td>
                    <td class="py-3 px-4 lg:px-3 align-top border-b border-slate-100 lg:border-none flex items-center gap-3 bg-slate-50/50 lg:bg-transparent">
                        <span class="lg:hidden bg-indigo-100 text-indigo-700 w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold shrink-0">{{ $i+1 }}</span>
                        <div>
                            <div class="font-semibold lg:font-medium text-slate-800">{{ $student->name }}</div>
                            <div class="text-xs text-slate-400">{{ $student->nis ?? '-' }}</div>
                        </div>
                    </td>
                    <td class="py-3 px-4 lg:px-3">
                        <textarea name="notes[{{ $student->id }}][note]" rows="3" 
                            class="form-input text-sm w-full border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 disabled:bg-slate-100 disabled:text-slate-400 transition-shadow" placeholder="Cth: Pertahankan prestasimu dan tingkatkan kedisiplinan..." {{ !$period->is_active ? 'disabled' : '' }}>{{ $note->note ?? '' }}</textarea>
                    </td>
                </tr>
                @empty
                <tr class="flex lg:table-row"><td colspan="3" class="text-center py-10 text-slate-400 w-full">Belum ada siswa di kelas ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($students->isNotEmpty())
        <div class="p-4 border-t border-slate-100 bg-slate-50 text-xs text-slate-400 text-center">
            Pastikan menekan tombol Simpan Catatan di bagian atas setelah selesai menginput.
        </div>
        @endif
        </div>
    </div>
</form>
</x-layouts.walas>

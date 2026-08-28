<x-layouts.walas title="Absensi Siswa">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold mb-1 truncate text-gray-800">Absensi Siswa Harian</h2>
                <p class="text-sm text-gray-500">
                    Kelas: <span class="font-semibold text-gray-700">{{ $assignment->schoolClass->name }}</span> | 
                    Tahun Ajaran: <span class="font-semibold text-gray-700">{{ $academicYear->name }}</span>
                </p>
                <div class="mt-3 flex gap-3 text-xs">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500"></span> Hadir</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-400"></span> Sakit</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Izin</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500"></span> Alpa</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-gray-400"></span> Libur</span>
                </div>
            </div>
            
            <form action="{{ route('walas.attendances.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto items-center">
                <label for="month" class="text-sm font-medium text-gray-700 whitespace-nowrap">Bulan:</label>
                <select name="month" id="month" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:w-auto p-2.5">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                            {{ $name }} {{ ($num >= 7 && $num <= 12) ? explode('/', $academicYear->name)[0] : (count(explode('/', $academicYear->name)) > 1 ? explode('/', $academicYear->name)[1] : explode('/', $academicYear->name)[0] + 1) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('walas.attendances.store') }}" method="POST">
                @csrf
                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200">
                            <!-- Row 1: Group headers -->
                            <tr class="border-b border-slate-200">
                                <th rowspan="2" scope="col" class="px-4 py-3 font-semibold w-12 text-center sticky left-0 bg-slate-100 z-10 border-r border-slate-200">No</th>
                                <th rowspan="2" scope="col" class="px-4 py-3 font-semibold min-w-[200px] sticky left-[48px] bg-slate-100 z-10 border-r border-slate-300 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.08)]">Nama Siswa</th>
                                <th colspan="{{ $daysInMonth }}" scope="col" class="px-2 py-2 font-bold text-center bg-indigo-100 text-indigo-700 border-l border-slate-200 tracking-widest">Tanggal</th>
                                <th rowspan="2" scope="col" class="px-4 py-3 font-semibold w-16 text-center border-l border-slate-200 bg-yellow-100 text-yellow-700">S</th>
                                <th rowspan="2" scope="col" class="px-4 py-3 font-semibold w-16 text-center bg-blue-100 text-blue-700">I</th>
                                <th rowspan="2" scope="col" class="px-4 py-3 font-semibold w-16 text-center bg-red-100 text-red-700">A</th>
                            </tr>
                            <!-- Row 2: Date numbers -->
                            <tr>
                                @for($d = 1; $d <= $daysInMonth; $d++)
                                    <th scope="col" class="px-2 py-2 font-semibold w-10 text-center border-l border-slate-200 text-indigo-700">{{ $d }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $index => $student)
                                @php
                                    $attendance = $attendances[$student->id] ?? null;
                                    $dailyData = $attendance->daily_data ?? [];
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors student-row" data-student="{{ $student->id }}">
                                    <td class="px-4 py-2 text-center text-gray-500 sticky left-0 bg-white z-10 border-r border-gray-100">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 sticky left-[48px] bg-white z-10 border-r border-gray-200 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] truncate max-w-[200px]">{{ $student->name }}</td>
                                    
                                    @for($d = 1; $d <= $daysInMonth; $d++)
                                        @php
                                            $status = $dailyData[$d] ?? 'H';
                                            $bgClass = 'bg-green-500 text-white'; // H default
                                            if ($status === 'S') $bgClass = 'bg-yellow-400 text-white';
                                            if ($status === 'I') $bgClass = 'bg-blue-500 text-white';
                                            if ($status === 'A') $bgClass = 'bg-red-500 text-white';
                                            if ($status === 'L') $bgClass = 'bg-gray-400 text-white';
                                        @endphp
                                        <td class="p-1 text-center">
                                            <div class="day-cell cursor-pointer w-8 h-8 mx-auto rounded flex items-center justify-center font-bold text-xs select-none transition-colors {{ $bgClass }}" 
                                                 data-status="{{ $status }}"
                                                 data-day="{{ $d }}">
                                                {{ $status }}
                                            </div>
                                            <input type="hidden" name="attendances[{{ $student->id }}][{{ $d }}]" value="{{ $status }}" class="day-input" data-status="{{ $status }}">
                                        </td>
                                    @endfor
                                    
                                    <td class="px-4 py-2 text-center font-bold border-l border-gray-200 bg-yellow-50/50 text-yellow-700 total-s">{{ $attendance->sakit ?? 0 }}</td>
                                    <td class="px-4 py-2 text-center font-bold bg-blue-50/50 text-blue-700 total-i">{{ $attendance->izin ?? 0 }}</td>
                                    <td class="px-4 py-2 text-center font-bold bg-red-50/50 text-red-700 total-a">{{ $attendance->alpa ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $daysInMonth + 5 }}" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada siswa di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($students->isNotEmpty())
                <div class="p-6 bg-gray-50/50 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Absensi
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cycle: H -> S -> I -> A -> L -> H
            const cycle = {
                'H': { next: 'S', class: 'bg-yellow-400 text-white' },
                'S': { next: 'I', class: 'bg-blue-500 text-white' },
                'I': { next: 'A', class: 'bg-red-500 text-white' },
                'A': { next: 'L', class: 'bg-gray-400 text-white' },
                'L': { next: 'H', class: 'bg-green-500 text-white' }
            };

            const dayCells = document.querySelectorAll('.day-cell');
            
            dayCells.forEach(cell => {
                cell.addEventListener('click', function() {
                    let currentStatus = this.getAttribute('data-status');
                    let nextStatusInfo = cycle[currentStatus];
                    
                    if(!nextStatusInfo) return; // safety fallback

                    // Update UI cell
                    this.setAttribute('data-status', nextStatusInfo.next);
                    this.textContent = nextStatusInfo.next;
                    this.className = `day-cell cursor-pointer w-8 h-8 mx-auto rounded flex items-center justify-center font-bold text-xs select-none transition-colors ${nextStatusInfo.class}`;
                    
                    // Update hidden input
                    const row = this.closest('tr');
                    const day = this.getAttribute('data-day');
                    const hiddenInput = row.querySelector(`input[name$="[${day}]"]`);
                    if(hiddenInput) {
                        hiddenInput.value = nextStatusInfo.next;
                        hiddenInput.setAttribute('data-status', nextStatusInfo.next);
                    }
                    
                    // Recalculate totals for this row
                    recalculateTotals(row);
                });
            });

            function recalculateTotals(row) {
                const inputs = row.querySelectorAll('.day-input');
                let sakit = 0, izin = 0, alpa = 0;
                
                inputs.forEach(input => {
                    const status = input.value;
                    if(status === 'S') sakit++;
                    if(status === 'I') izin++;
                    if(status === 'A') alpa++;
                });
                
                row.querySelector('.total-s').textContent = sakit;
                row.querySelector('.total-i').textContent = izin;
                row.querySelector('.total-a').textContent = alpa;
            }
        });
    </script>
    @endpush
</x-layouts.walas>

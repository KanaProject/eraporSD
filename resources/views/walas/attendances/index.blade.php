<x-layouts.walas title="Absensi Siswa">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold mb-1 truncate text-gray-800">Absensi Siswa</h2>
                <p class="text-sm text-gray-500">
                    Kelas: <span class="font-semibold text-gray-700">{{ $assignment->schoolClass->name }}</span> | 
                    Tahun Ajaran: <span class="font-semibold text-gray-700">{{ $academicYear->name }}</span>
                </p>
            </div>
            
            <form action="{{ route('walas.attendances.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto items-center">
                <label for="month" class="text-sm font-medium text-gray-700 whitespace-nowrap">Bulan:</label>
                <select name="month" id="month" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:w-auto p-2.5">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                            {{ $name }}
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
                        <thead class="text-xs text-gray-600 uppercase bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold w-16 text-center">No</th>
                                <th scope="col" class="px-6 py-4 font-semibold">Nama Siswa</th>
                                <th scope="col" class="px-6 py-4 font-semibold w-24 text-center">Sakit</th>
                                <th scope="col" class="px-6 py-4 font-semibold w-24 text-center">Izin</th>
                                <th scope="col" class="px-6 py-4 font-semibold w-24 text-center">Alpa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $index => $student)
                                @php
                                    $attendance = $attendances[$student->id] ?? null;
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-center text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $student->name }}</td>
                                    <td class="px-4 py-3">
                                        <input type="number" 
                                               name="attendances[{{ $student->id }}][sakit]" 
                                               value="{{ $attendance->sakit ?? 0 }}" 
                                               min="0"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center sm:text-sm">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" 
                                               name="attendances[{{ $student->id }}][izin]" 
                                               value="{{ $attendance->izin ?? 0 }}" 
                                               min="0"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center sm:text-sm">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" 
                                               name="attendances[{{ $student->id }}][alpa]" 
                                               value="{{ $attendance->alpa ?? 0 }}" 
                                               min="0"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center sm:text-sm">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
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
                        Simpan Absensi Bulan {{ $months[$selectedMonth] }}
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</x-layouts.walas>

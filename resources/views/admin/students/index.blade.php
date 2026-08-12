<x-layouts.admin title="Data Siswa">
<div class="page-header flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="page-title">Data Siswa</h2>
        <p class="page-subtitle">Total {{ $students->total() }} siswa aktif</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <button type="button" onclick="openModal('modal-import-student')" class="btn-secondary flex items-center justify-center gap-2 w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            Import Excel
        </button>
        <button type="button" onclick="openModal('modal-create-student')" class="btn-primary flex items-center justify-center gap-2 w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Siswa
        </button>
    </div>
</div>
<div class="card">
    <form method="GET" class="flex gap-3 mb-4 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" class="form-input max-w-xs" placeholder="Cari nama / NIS...">
        <select name="status" class="form-select w-32">
            <option value="aktif" {{ request('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
            <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua</option>
        </select>
        <select name="class_id" class="form-select w-36">
            <option value="">Semua Kelas</option>
            @foreach($classes as $class)
            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>Kelas {{ $class->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary">Cari</button>
        <a href="{{ route('admin.students.index') }}" class="btn-secondary">Reset</a>
    </form>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Nama Siswa</th><th>NIS</th><th>L/P</th><th>Kelas</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td class="font-medium">
                        {{ $student->name }}
                        @if(!$student->is_active)
                        <span class="badge badge-error ml-2 text-xs">Non-Aktif</span>
                        @endif
                    </td>
                    <td class="text-slate-500">{{ $student->nis ?? '-' }}</td>
                    <td><span class="badge {{ $student->gender === 'L' ? 'badge-info' : 'badge-warning' }}">{{ $student->gender }}</span></td>
                    <td>{{ $student->schoolClass?->name ?? '-' }}</td>
                    <td class="text-right">
                        <div class="flex justify-end gap-1">
                            @if($student->is_active)
                            <button type="button" class="btn-icon" title="Edit" 
                                data-student="{{ json_encode([
                                    'id' => $student->id,
                                    'name' => $student->name,
                                    'nis' => $student->nis,
                                    'nisn' => $student->nisn,
                                    'gender' => $student->gender,
                                    'class_id' => $student->school_class_id,
                                    'birth_place' => $student->birth_place,
                                    'birth_date' => $student->birth_date ? $student->birth_date->format('Y-m-d') : '',
                                    'religion' => $student->religion,
                                    'parent_name' => $student->parent_name,
                                    'parent_phone' => $student->parent_phone,
                                    'address' => $student->address
                                ]) }}"
                                onclick="editStudent(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" class="inline"
                                data-confirm="Nonaktifkan {{ $student->name }} dari sistem?"
                                data-confirm-title="Nonaktifkan Siswa"
                                data-confirm-type="danger"
                                data-confirm-ok="Ya, Nonaktifkan">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon text-red-500 hover:text-red-700 hover:bg-red-50" title="Non-Aktifkan">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.students.toggle', $student) }}" class="inline"
                                data-confirm="Aktifkan kembali {{ $student->name }} ke dalam sistem?"
                                data-confirm-title="Aktifkan Siswa"
                                data-confirm-type="success"
                                data-confirm-ok="Ya, Aktifkan">
                                @csrf
                                <button type="submit" class="btn-icon text-green-500 hover:text-green-700 hover:bg-green-50" title="Aktifkan Kembali">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-slate-400 py-8">Tidak ada siswa ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $students->links() }}</div>
</div>

    <!-- Modal Create Student -->
    <div id="modal-create-student" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-create-student')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-teal-50 flex items-center justify-between shrink-0">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tambah Siswa
                </h3>
                <button type="button" onclick="closeModal('modal-create-student')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('admin.students.store') }}" method="POST" class="overflow-y-auto p-6">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group col-span-2">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin *</label>
                        <select name="gender" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kelas</label>
                        <select name="school_class_id" class="form-select">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">Kelas {{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="birth_place" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="birth_date" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Agama</label>
                        <select name="religion" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Orang Tua/Wali</label>
                        <input type="text" name="parent_name" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. HP Orang Tua</label>
                        <input type="text" name="parent_phone" class="form-input">
                    </div>
                    <div class="form-group col-span-2">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" rows="2" class="form-input"></textarea>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-create-student')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Siswa</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Student -->
    <div id="modal-edit-student" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-edit-student')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50 flex items-center justify-between shrink-0">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/></svg>
                    Edit Siswa
                </h3>
                <button type="button" onclick="closeModal('modal-edit-student')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="form-edit-student" method="POST" class="overflow-y-auto p-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group col-span-2">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin *</label>
                        <select name="gender" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kelas</label>
                        <select name="school_class_id" class="form-select">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">Kelas {{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="birth_place" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="birth_date" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Agama</label>
                        <select name="religion" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Orang Tua/Wali</label>
                        <input type="text" name="parent_name" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. HP Orang Tua</label>
                        <input type="text" name="parent_phone" class="form-input">
                    </div>
                    <div class="form-group col-span-2">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" rows="2" class="form-input"></textarea>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-student')">Batal</button>
                    <button type="submit" class="btn-primary">Update Siswa</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Import Student -->
    <div id="modal-import-student" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-import-student')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-orange-50 flex items-center justify-between shrink-0">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    Import Siswa
                </h3>
                <button type="button" onclick="closeModal('modal-import-student')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto p-6">
                @csrf
                
                @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-lg text-sm border border-red-100">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <script>
                    // Auto-open modal if there are errors after submit
                    document.addEventListener('DOMContentLoaded', function() {
                        openModal('modal-import-student');
                    });
                </script>
                @endif

                <div class="mb-6 bg-slate-50 p-4 rounded-lg border border-slate-100 text-sm">
                    <p class="mb-2 text-slate-600">Pastikan format kolom sesuai dengan template yang disediakan sistem.</p>
                    <a href="{{ route('admin.students.template') }}" class="text-primary-600 font-semibold hover:underline flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Download Template Excel
                    </a>
                </div>
                <div class="form-group">
                    <label class="form-label">Pilih File Excel (.xlsx / .csv) *</label>
                    <input type="file" name="file" accept=".xlsx,.csv" class="form-input" required>
                    <p class="text-xs text-slate-400 mt-1">Siswa dengan NIS yang sudah ada akan dilewati (di-skip).</p>
                </div>
                
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-import-student')">Batal</button>
                    <button type="submit" class="btn-primary" onclick="this.innerHTML='Mengunggah...';">Proses Import</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.classList.add('opacity-100');
                modal.lastElementChild.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.firstElementChild.classList.remove('opacity-100');
            modal.lastElementChild.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        function editStudent(btn) {
            const data = JSON.parse(btn.dataset.student);
            const form = document.getElementById('form-edit-student');
            form.action = `/admin/students/${data.id}`;
            form.elements['name'].value = data.name || '';
            form.elements['nis'].value = data.nis || '';
            form.elements['nisn'].value = data.nisn || '';
            form.elements['gender'].value = data.gender || '';
            form.elements['school_class_id'].value = data.class_id || '';
            form.elements['birth_place'].value = data.birth_place || '';
            form.elements['birth_date'].value = data.birth_date || '';
            form.elements['religion'].value = data.religion || '';
            form.elements['parent_name'].value = data.parent_name || '';
            form.elements['parent_phone'].value = data.parent_phone || '';
            form.elements['address'].value = data.address || '';
            openModal('modal-edit-student');
        }
    </script>
    @endpush
</x-layouts.admin>

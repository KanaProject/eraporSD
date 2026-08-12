<x-layouts.admin title="Manajemen Pengguna">

<div class="page-header flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="page-title">Manajemen Pengguna</h2>
        <p class="page-subtitle">Kelola akun guru, wali kelas, dan staff kurikulum</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <button type="button" onclick="openModal('modal-import-user')" class="btn-secondary flex items-center justify-center gap-2 w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            Import Excel
        </button>
        <button type="button" onclick="openCreateModal()" class="btn-primary flex items-center justify-center gap-2 w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Pengguna
        </button>
    </div>
</div>

<div class="card">
    <!-- Filters -->
    <form method="GET" class="flex flex-col md:flex-row gap-3 mb-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-input w-full md:max-w-xs" placeholder="Cari nama / username...">
        <div class="flex flex-wrap sm:flex-nowrap gap-3 w-full md:w-auto">
            <select name="status" class="form-select w-full sm:w-32">
                <option value="aktif"   {{ request('status', 'aktif') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif'         ? 'selected' : '' }}>Non-Aktif</option>
                <option value="semua"   {{ request('status') == 'semua'             ? 'selected' : '' }}>Semua</option>
            </select>
            <select name="role" class="form-select w-full sm:w-40">
                <option value="">Semua Peran</option>
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit" class="btn-primary flex-1 md:flex-none justify-center">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary flex-1 md:flex-none justify-center">Reset</a>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th class="hidden md:table-cell">Username</th>
                    <th class="hidden md:table-cell">NIP</th>
                    <th class="hidden md:table-cell">Peran</th>
                    <th class="hidden md:table-cell text-center">Status</th>
                    <th class="hidden md:table-cell text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="font-medium">
                        <div class="flex items-center gap-2">
                            <button type="button" class="md:hidden w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center shrink-0 transition-colors" onclick="toggleRow('row-{{ $user->id }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" id="icon-{{ $user->id }}"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </button>
                            <span>{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="hidden md:table-cell text-slate-500">{{ $user->username }}</td>
                    <td class="hidden md:table-cell text-slate-500 text-xs">{{ $user->nip ?? '-' }}</td>
                    <td class="hidden md:table-cell">
                        <div class="flex flex-wrap gap-1">
                        @foreach($user->roles as $role)
                            <span class="badge {{ $role->name === 'admin' ? 'badge-danger' : ($role->name === 'kurikulum' ? 'badge-info' : 'badge-success') }}">{{ ucfirst($role->name) }}</span>
                        @endforeach
                        </div>
                    </td>
                    <td class="hidden md:table-cell text-center">
                        @if($user->is_active)
                            <span class="badge-success">Aktif</span>
                        @else
                            <span class="badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="hidden md:table-cell text-right">
                        @include('admin.users.partials.actions', ['user' => $user])
                    </td>
                </tr>
                {{-- Hidden Row for Mobile --}}
                <tr id="row-{{ $user->id }}" class="hidden md:hidden bg-slate-50 border-b border-slate-100">
                    <td colspan="1" class="px-4 py-3">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Username</span>
                                <span class="font-medium text-slate-700">{{ $user->username }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">NIP</span>
                                <span class="text-slate-700">{{ $user->nip ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Peran</span>
                                <div class="flex flex-wrap gap-1 justify-end">
                                    @foreach($user->roles as $role)
                                        <span class="badge {{ $role->name === 'admin' ? 'badge-danger' : ($role->name === 'kurikulum' ? 'badge-info' : 'badge-success') }}">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Status</span>
                                <div>
                                    @if($user->is_active)
                                        <span class="badge-success">Aktif</span>
                                    @else
                                        <span class="badge-danger">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-1">
                                <span class="text-slate-500">Aksi</span>
                                <div>
                                    @include('admin.users.partials.actions', ['user' => $user])
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-slate-400 py-8">Tidak ada pengguna ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</div>

{{-- ── Edit User Modal ── --}}
<div id="editUserBackdrop"
    class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-6">
    <div id="editUserBox"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex overflow-hidden">

        {{-- Left panel --}}
        <div class="w-48 flex-shrink-0 bg-gradient-to-b from-primary-700 to-primary-900 text-white flex flex-col items-center justify-center px-5 py-8 text-center">
            <div id="euAvatar"
                class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center font-bold text-3xl ring-4 ring-white/25 mb-4"></div>
            <div id="euName" class="text-sm font-bold leading-snug mb-1"></div>
            <div id="euUsername" class="text-primary-200 text-xs"></div>
            <div class="mt-6 pt-5 border-t border-white/20 w-full text-left">
                <div class="text-white/50 text-xs mb-1 uppercase tracking-wider">Role saat ini</div>
                <div id="euRoles" class="flex flex-wrap gap-1 mt-1"></div>
            </div>
        </div>

        {{-- Right panel --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Edit Pengguna</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Perbarui informasi akun</p>
                </div>
                <button type="button" onclick="closeEditModal()"
                    class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form id="editUserForm" method="POST" class="px-6 py-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="col-span-2">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" id="euInputName" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" id="euInputUsername" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" id="euInputNip" class="form-input">
                    </div>
                    <div class="col-span-2">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" id="euInputPhone" class="form-input">
                    </div>
                    <div class="col-span-2">
                        <label class="form-label">Peran *</label>
                        <div class="flex flex-wrap gap-4 mt-1" id="euRolesCheckboxes">
                            @foreach($roles as $role)
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                    class="eu-role-check rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm font-medium capitalize">{{ $role->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeEditModal()" class="btn-secondary flex-1 justify-center">Batal</button>
                    <button type="submit" class="btn-primary flex-1 justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Create User Modal ── --}}
<div id="createUserBackdrop"
    class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-6">
    <div id="createUserBox"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-primary-50 to-slate-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Tambah Pengguna</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Buat akun baru untuk guru atau staff</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateModal()"
                class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="createUserForm" method="POST" action="{{ route('admin.users.store') }}" class="px-6 py-5 overflow-y-auto max-h-[80vh]">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div class="col-span-2">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-input" placeholder="budi.santoso" required>
                </div>
                <div>
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-input">
                </div>
                <div class="col-span-2">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" class="form-input">
                </div>
                <div class="col-span-2">
                    <label class="form-label">Peran *</label>
                    <div class="flex flex-wrap gap-4 mt-1">
                        @foreach($roles as $role)
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm font-medium capitalize">{{ $role->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="mt-3 p-3 bg-slate-50 border border-slate-100 rounded-lg flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                        <div>
                            <p class="text-xs text-slate-500">Password default untuk pengguna baru adalah <strong class="text-slate-700">12345678</strong>.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeCreateModal()" class="btn-secondary flex-1 justify-center">Batal</button>
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes editModalIn {
    from { opacity: 0; transform: scale(0.95) translateY(-10px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
#editUserBox, #createUserBox { animation: editModalIn 0.2s ease-out; }
</style>

<script>
/* ── Edit Modal ── */
function openEditModal(btn) {
    const d = btn.dataset;
    // Populate left panel
    document.getElementById('euAvatar').textContent   = d.name.charAt(0);
    document.getElementById('euName').textContent     = d.name;
    document.getElementById('euUsername').textContent = '@' + d.username;

    // Role badges in left panel
    const roleWrap = document.getElementById('euRoles');
    roleWrap.innerHTML = '';
    (d.roles || '').split(',').filter(Boolean).forEach(function(r) {
        const span = document.createElement('span');
        span.textContent = r.trim().charAt(0).toUpperCase() + r.trim().slice(1);
        span.className = 'text-xs bg-white/20 text-white px-2 py-0.5 rounded-full';
        roleWrap.appendChild(span);
    });

    // Populate form fields
    document.getElementById('euInputName').value     = d.name;
    document.getElementById('euInputUsername').value = d.username;
    document.getElementById('euInputNip').value      = d.nip   || '';
    document.getElementById('euInputPhone').value    = d.phone || '';

    // Set form action
    document.getElementById('editUserForm').action   = d.action;

    // Check correct roles
    const userRoles = (d.roles || '').split(',').map(function(r){ return r.trim(); });
    document.querySelectorAll('.eu-role-check').forEach(function(cb) {
        cb.checked = userRoles.includes(cb.value);
    });

    // Show modal
    const backdrop = document.getElementById('editUserBackdrop');
    const box      = document.getElementById('editUserBox');
    backdrop.classList.remove('hidden');
    box.style.animation = 'editModalIn 0.2s ease-out';
}

function closeEditModal() {
    document.getElementById('editUserBackdrop').classList.add('hidden');
}

document.getElementById('editUserBackdrop').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

/* ── Create Modal ── */
function openCreateModal() {
    const backdrop = document.getElementById('createUserBackdrop');
    const box      = document.getElementById('createUserBox');
    // Reset form
    document.getElementById('createUserForm').reset();
    backdrop.classList.remove('hidden');
    box.style.animation = 'editModalIn 0.2s ease-out';
}

function closeCreateModal() {
    document.getElementById('createUserBackdrop').classList.add('hidden');
}

document.getElementById('createUserBackdrop').addEventListener('click', function(e) {
    if (e.target === this) closeCreateModal();
});

/* ── Generic Modal (by ID) ── */
function openModal(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.remove('hidden');
        const box = el.querySelector('.modal-box');
        if (box) box.style.animation = 'editModalIn 0.2s ease-out';
    }
}
function closeModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('hidden');
}
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('modal-backdrop')) {
        e.target.classList.add('hidden');
    }
});

function toggleRow(id) {
    const row = document.getElementById(id);
    const icon = document.getElementById(id.replace('row-', 'icon-'));
    if (row.classList.contains('hidden')) {
        row.classList.remove('hidden');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />';
        icon.classList.add('text-red-500');
    } else {
        row.classList.add('hidden');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />';
        icon.classList.remove('text-red-500');
    }
}
</script>

{{-- ─── Modal Import Excel ─── --}}
<div id="modal-import-user" class="modal-backdrop hidden">
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="modal-title">Import Pengguna dari Excel</h3>
            <button type="button" onclick="closeModal('modal-import-user')" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm text-blue-800">
                <p class="font-semibold mb-1">📋 Petunjuk Import:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Gunakan template Excel yang disediakan</li>
                    <li>Kolom <strong>Nama Lengkap</strong> dan <strong>Username</strong> wajib diisi</li>
                    <li>Kolom <strong>Peran</strong> diisi: <code class="bg-blue-100 px-1 rounded">guru</code>, <code class="bg-blue-100 px-1 rounded">walas</code>, atau <code class="bg-blue-100 px-1 rounded">kurikulum</code></li>
                    <li>Password default semua akun baru: <strong>12345678</strong></li>
                    <li>Username yang sudah ada akan dilewati (tidak duplikat)</li>
                </ul>
            </div>
            <a href="{{ route('admin.users.template') }}" class="btn-secondary w-full flex items-center justify-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download Template Excel
            </a>
            <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Upload File Excel (.xlsx / .csv)</label>
                    <input type="file" name="file" accept=".xlsx,.csv,.xls" class="form-input" required>
                </div>
                <div class="flex gap-2 justify-end mt-4">
                    <button type="button" onclick="closeModal('modal-import-user')" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

</x-layouts.admin>

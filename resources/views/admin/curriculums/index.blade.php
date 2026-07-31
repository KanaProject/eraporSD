<x-layouts.admin title="Manajemen Kurikulum">
    <div class="page-header flex items-center justify-between">
        <div>
            <h2 class="page-title">Manajemen Kurikulum</h2>
            <p class="page-subtitle">Kelola data master kurikulum yang digunakan di sekolah</p>
        </div>
        <button type="button" class="btn-primary" onclick="openModal('modal-create-curriculum')">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Kurikulum
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($curriculums as $curriculum)
            <div class="card flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2">{{ $curriculum->name }}</h3>
                        @if($curriculum->is_active)
                            <span class="badge-success">Aktif</span>
                        @else
                            <span class="badge-neutral">Tidak Aktif</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button" class="btn-icon bg-slate-50 hover:bg-slate-100" title="Edit"
                            onclick="editCurriculum({{ $curriculum->id }}, '{{ addslashes($curriculum->name) }}', {{ $curriculum->is_active ? 'true' : 'false' }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.curriculums.destroy', $curriculum) }}" class="inline"
                            data-confirm="Hapus kurikulum {{ $curriculum->name }}?"
                            data-confirm-title="Hapus Kurikulum"
                            data-confirm-type="danger">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full card text-center py-16">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                <p class="text-slate-500 mb-2">Belum ada data kurikulum</p>
                <button type="button" onclick="openModal('modal-create-curriculum')" class="text-primary-600 font-medium hover:underline">Tambah sekarang</button>
            </div>
        @endforelse
    </div>

    @if($curriculums->hasPages())
        <div class="mt-4">
            {{ $curriculums->links() }}
        </div>
    @endif

    <!-- Modal Create -->
    <div id="modal-create-curriculum" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-create-curriculum')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-teal-50 flex items-center justify-between shrink-0">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tambah Kurikulum
                </h3>
                <button type="button" onclick="closeModal('modal-create-curriculum')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('admin.curriculums.store') }}" method="POST" class="overflow-y-auto p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Nama Kurikulum <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="form-input" required placeholder="Contoh: Kurikulum Merdeka">
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" class="form-checkbox" checked value="1">
                        <span class="text-sm font-medium text-slate-700">Aktif digunakan</span>
                    </label>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-create-curriculum')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modal-edit-curriculum" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-edit-curriculum')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50 flex items-center justify-between shrink-0">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                    Edit Kurikulum
                </h3>
                <button type="button" onclick="closeModal('modal-edit-curriculum')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="form-edit-curriculum" method="POST" class="overflow-y-auto p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Nama Kurikulum <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-input" required>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="edit_is_active" class="form-checkbox" value="1">
                        <span class="text-sm font-medium text-slate-700">Aktif digunakan</span>
                    </label>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-curriculum')">Batal</button>
                    <button type="submit" class="btn-primary">Update</button>
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

        function editCurriculum(id, name, isActive) {
            const form = document.getElementById('form-edit-curriculum');
            form.action = `/admin/curriculums/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_is_active').checked = isActive;
            openModal('modal-edit-curriculum');
        }
    </script>
    @endpush
</x-layouts.admin>

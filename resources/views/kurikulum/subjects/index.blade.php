<x-layouts.kurikulum title="Mata Pelajaran">
<div class="page-header flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="page-title">Master Mata Pelajaran</h2>
        <p class="page-subtitle">Kelola daftar master mata pelajaran sekolah (tanpa memandang kelas)</p>
    </div>
    <div class="flex w-full sm:w-auto">
        <button type="button" onclick="openSubjectModal()" class="btn-primary w-full sm:w-auto justify-center flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Mapel
        </button>
    </div>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Mapel</th>
                    <th class="hidden md:table-cell">Kode</th>
                    <th class="hidden md:table-cell">Kelompok</th>
                    <th class="hidden md:table-cell text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $currentGroup = ''; @endphp
                @forelse($subjects as $subject)
                @if($currentGroup != $subject->group)
                    <tr class="bg-slate-50"><td colspan="4" class="font-semibold text-slate-700 py-2">{{ $subject->group }}</td></tr>
                    @php $currentGroup = $subject->group; @endphp
                @endif
                <tr>
                    <td class="font-medium">
                        <div class="flex items-center gap-2">
                            <button type="button" class="md:hidden w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center shrink-0 transition-colors" onclick="toggleRow('row-{{ $subject->id }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" id="icon-{{ $subject->id }}"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </button>
                            <div>
                                @if($subject->parent_id)
                                    <span class="text-slate-400 mr-1">└</span> {{ $subject->name }}
                                @else
                                    {{ $subject->name }}
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="hidden md:table-cell"><span class="badge-neutral">{{ $subject->code }}</span></td>
                    <td class="hidden md:table-cell">{{ $subject->parent_id ? 'Anak dari: ' . $subject->parent->name : '-' }}</td>
                    <td class="text-right hidden md:table-cell">
                        <div class="flex justify-end gap-1">
                            <button type="button" class="btn-icon text-amber-500 hover:text-amber-600"
                                onclick="editSubject('{{ $subject->id }}', '{{ addslashes($subject->name) }}', '{{ addslashes($subject->code) }}', '{{ $subject->group }}', '{{ $subject->parent_id }}', '{{ $subject->sort_order }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.12l-2.316.711a.75.75 0 01-.94-.94l.71-2.315a4.5 4.5 0 011.12-1.89l12.33-12.33zM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            </button>
                            <form method="POST" action="{{ route('kurikulum.subjects.destroy', $subject) }}" class="inline"
                                data-confirm="Hapus mata pelajaran {{ $subject->name }}?"
                                data-confirm-title="Hapus Mata Pelajaran"
                                data-confirm-type="danger"
                                data-confirm-ok="Ya, Hapus">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon text-red-400 hover:text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                {{-- Hidden Row for Mobile --}}
                <tr id="row-{{ $subject->id }}" class="hidden md:hidden bg-slate-50 border-b border-slate-100">
                    <td colspan="4" class="px-4 py-3">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Kode</span>
                                <span class="badge-neutral">{{ $subject->code }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Induk</span>
                                <span class="text-slate-700">{{ $subject->parent_id ? $subject->parent->name : '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-1">
                                <span class="text-slate-500">Aksi</span>
                                <div class="flex justify-end gap-1">
                                    <button type="button" class="btn-icon text-amber-500 hover:text-amber-600"
                                        onclick="editSubject('{{ $subject->id }}', '{{ addslashes($subject->name) }}', '{{ addslashes($subject->code) }}', '{{ $subject->group }}', '{{ $subject->parent_id }}', '{{ $subject->sort_order }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.12l-2.316.711a.75.75 0 01-.94-.94l.71-2.315a4.5 4.5 0 011.12-1.89l12.33-12.33zM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    </button>
                                    <form method="POST" action="{{ route('kurikulum.subjects.destroy', $subject) }}" class="inline"
                                        data-confirm="Hapus mata pelajaran {{ $subject->name }}?"
                                        data-confirm-title="Hapus Mata Pelajaran"
                                        data-confirm-type="danger"
                                        data-confirm-ok="Ya, Hapus">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon text-red-400 hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-slate-400 py-6">Belum ada master mata pelajaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Subject --}}
<div id="modal-subject" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h2 class="text-lg font-bold text-slate-800" id="formTitle">Tambah Mata Pelajaran</h2>
            <button type="button" onclick="closeSubjectModal()" class="w-8 h-8 rounded-lg hover:bg-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('kurikulum.subjects.store') }}" id="subjectForm" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Nama Mapel *</label>
                <input type="text" name="name" id="inputName" class="form-input" value="{{ old('name') }}" required>
                @error('name')<p class="form-error text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Kode *</label>
                    <input type="text" name="code" id="inputCode" class="form-input" value="{{ old('code') }}" placeholder="MTK" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort_order" id="inputOrder" class="form-input" value="{{ old('sort_order', 0) }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Kelompok *</label>
                <select name="group" id="inputGroup" class="form-select" required>
                    <option value="A. Mata Pelajaran" {{ old('group') == 'A. Mata Pelajaran' ? 'selected' : '' }}>A. Mata Pelajaran (Umum)</option>
                    <option value="B. Muatan Lokal" {{ old('group') == 'B. Muatan Lokal' ? 'selected' : '' }}>B. Muatan Lokal</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Induk Mapel (Opsional)</label>
                <select name="parent_id" id="inputParent" class="form-select">
                    <option value="">-- Tidak ada induk --</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-1">Pilih jika ini adalah anak mapel (misal: Fiqih bagian dari PAI).</p>
            </div>
            <div class="flex gap-3 pt-2 border-t border-slate-100 mt-4">
                <button type="button" class="btn-secondary w-full justify-center" onclick="closeSubjectModal()">Batal</button>
                <button type="submit" id="btnSubmit" class="btn-primary w-full justify-center">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modal-subject');
    const form = document.getElementById('subjectForm');
    const formTitle = document.getElementById('formTitle');
    const formMethod = document.getElementById('formMethod');
    const inputName = document.getElementById('inputName');
    const inputCode = document.getElementById('inputCode');
    const inputGroup = document.getElementById('inputGroup');
    const inputParent = document.getElementById('inputParent');
    const inputOrder = document.getElementById('inputOrder');
    const btnSubmit = document.getElementById('btnSubmit');
    const storeUrl = "{{ route('kurikulum.subjects.store') }}";

    function openSubjectModal() {
        form.action = storeUrl;
        formMethod.value = 'POST';
        form.reset();
        inputGroup.value = 'A. Mata Pelajaran';
        inputOrder.value = '0';
        
        formTitle.textContent = 'Tambah Mata Pelajaran';
        btnSubmit.textContent = 'Tambah Mapel';
        
        modal.classList.remove('hidden');
    }

    function closeSubjectModal() {
        modal.classList.add('hidden');
    }

    function editSubject(id, name, code, group, parent_id, sort_order) {
        form.action = `/kurikulum/subjects/${id}`;
        formMethod.value = 'PUT';
        
        inputName.value = name;
        inputCode.value = code;
        inputGroup.value = group;
        inputParent.value = parent_id || '';
        inputOrder.value = sort_order;
        
        formTitle.textContent = 'Edit Mata Pelajaran';
        btnSubmit.textContent = 'Simpan Perubahan';
        
        modal.classList.remove('hidden');
    }

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
</x-layouts.kurikulum>

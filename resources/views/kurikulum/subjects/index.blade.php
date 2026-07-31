<x-layouts.kurikulum title="Mata Pelajaran">
<div class="page-header flex items-center justify-between">
    <div><h2 class="page-title">Master Mata Pelajaran</h2><p class="page-subtitle">Kelola daftar master mata pelajaran sekolah (tanpa memandang kelas)</p></div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add Subject Form -->
    <div class="card" id="formCard">
        <h3 class="card-title mb-4" id="formTitle">Tambah Mata Pelajaran</h3>
        <form method="POST" action="{{ route('kurikulum.subjects.store') }}" id="subjectForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Nama Mapel *</label>
                <input type="text" name="name" id="inputName" class="form-input" value="{{ old('name') }}" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Kode *</label>
                <input type="text" name="code" id="inputCode" class="form-input" value="{{ old('code') }}" placeholder="MTK" required>
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
            <div class="form-group">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" id="inputOrder" class="form-input" value="{{ old('sort_order', 0) }}">
            </div>
            <div class="flex gap-2">
                <button type="button" id="btnCancelEdit" class="btn-secondary w-full justify-center hidden" onclick="cancelEdit()">Batal</button>
                <button type="submit" id="btnSubmit" class="btn-primary w-full justify-center">Tambah Mapel</button>
            </div>
        </form>
    </div>
    <!-- Subject List -->
    <div class="col-span-2 card">
        <h3 class="card-title mb-4">Daftar Master Mata Pelajaran</h3>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>Nama Mapel</th><th>Kode</th><th>Kelompok</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @php $currentGroup = ''; @endphp
                    @forelse($subjects as $subject)
                    @if($currentGroup != $subject->group)
                        <tr class="bg-slate-50"><td colspan="4" class="font-semibold text-slate-700 py-2">{{ $subject->group }}</td></tr>
                        @php $currentGroup = $subject->group; @endphp
                    @endif
                    <tr>
                        <td class="font-medium">
                            @if($subject->parent_id)
                                <span class="text-slate-400 mr-1">└</span> {{ $subject->name }}
                            @else
                                {{ $subject->name }}
                            @endif
                        </td>
                        <td><span class="badge-neutral">{{ $subject->code }}</span></td>
                        <td>{{ $subject->parent_id ? 'Anak dari: ' . $subject->parent->name : '-' }}</td>
                        <td class="text-right">
                            <button type="button" class="btn-icon text-amber-500 hover:text-amber-600 mr-2"
                                onclick="editSubject('{{ $subject->id }}', '{{ addslashes($subject->name) }}', '{{ addslashes($subject->code) }}', '{{ $subject->group }}', '{{ $subject->parent_id }}', '{{ $subject->sort_order }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.12l-2.316.711a.75.75 0 01-.94-.94l.71-2.315a4.5 4.5 0 011.12-1.89l12.33-12.33zM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            </button>
                            <form method="POST" action="{{ route('kurikulum.subjects.destroy', $subject) }}" class="inline"
                                data-confirm="Nonaktifkan mata pelajaran {{ $subject->name }}?"
                                data-confirm-title="Nonaktifkan Mata Pelajaran"
                                data-confirm-type="warning"
                                data-confirm-ok="Ya, Nonaktifkan">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon text-red-400 hover:text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-slate-400 py-6">Belum ada master mata pelajaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layouts.kurikulum>

<script>
    const form = document.getElementById('subjectForm');
    const formTitle = document.getElementById('formTitle');
    const formMethod = document.getElementById('formMethod');
    const inputName = document.getElementById('inputName');
    const inputCode = document.getElementById('inputCode');
    const inputGroup = document.getElementById('inputGroup');
    const inputParent = document.getElementById('inputParent');
    const inputOrder = document.getElementById('inputOrder');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnCancelEdit = document.getElementById('btnCancelEdit');
    const storeUrl = "{{ route('kurikulum.subjects.store') }}";

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
        btnCancelEdit.classList.remove('hidden');

        // Scroll to form
        document.getElementById('formCard').scrollIntoView({ behavior: 'smooth' });
    }

    function cancelEdit() {
        form.action = storeUrl;
        formMethod.value = 'POST';
        
        inputName.value = '';
        inputCode.value = '';
        inputGroup.value = 'A. Mata Pelajaran';
        inputParent.value = '';
        inputOrder.value = '0';
        
        formTitle.textContent = 'Tambah Mata Pelajaran';
        btnSubmit.textContent = 'Tambah Mapel';
        btnCancelEdit.classList.add('hidden');
    }
</script>

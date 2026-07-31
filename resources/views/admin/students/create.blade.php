@php $isEdit = isset($student) && $student->exists; @endphp
<x-layouts.admin :title="$isEdit ? 'Edit Siswa' : 'Tambah Siswa'">
<div class="page-header"><h2 class="page-title">{{ $isEdit ? 'Edit Siswa: '.$student->name : 'Tambah Siswa' }}</h2></div>
<div class="max-w-2xl">
    <div class="card">
        <form method="POST" action="{{ $isEdit ? route('admin.students.update', $student) : route('admin.students.store') }}">
            @csrf @if($isEdit) @method('PUT') @endif
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group col-span-2">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $student->name ?? '') }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-input" value="{{ old('nis', $student->nis ?? '') }}">
                    @error('nis')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-input" value="{{ old('nisn', $student->nisn ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin *</label>
                    <select name="gender" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('gender', $student->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $student->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <select name="school_class_id" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('school_class_id', $student->school_class_id ?? '') == $class->id ? 'selected' : '' }}>Kelas {{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="birth_place" class="form-input" value="{{ old('birth_place', $student->birth_place ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="birth_date" class="form-input" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d') ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Agama</label>
                    <select name="religion" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $r)
                        <option value="{{ $r }}" {{ old('religion', $student->religion ?? '') == $r ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Orang Tua/Wali</label>
                    <input type="text" name="parent_name" class="form-input" value="{{ old('parent_name', $student->parent_name ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP Orang Tua</label>
                    <input type="text" name="parent_phone" class="form-input" value="{{ old('parent_phone', $student->parent_phone ?? '') }}">
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" rows="2" class="form-input">{{ old('address', $student->address ?? '') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-2">
                <a href="{{ route('admin.students.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">{{ $isEdit ? 'Perbarui' : 'Simpan' }} Siswa</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.admin>

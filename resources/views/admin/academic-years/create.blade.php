<x-layouts.admin title="Tambah Tahun Ajaran">

<div class="page-header">
    <h2 class="page-title">Tambah Tahun Ajaran</h2>
    <p class="page-subtitle">Sistem akan otomatis membuat 2 semester dan 4 periode penilaian</p>
</div>

<div class="max-w-md">
    <div class="card">
        <form method="POST" action="{{ route('admin.academic-years.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Kurikulum <span class="text-red-500">*</span></label>
                <select name="curriculum_id" class="form-input" required>
                    <option value="">-- Pilih Kurikulum --</option>
                    @foreach($curriculums as $curriculum)
                        <option value="{{ $curriculum->id }}" {{ old('curriculum_id') == $curriculum->id ? 'selected' : '' }}>
                            {{ $curriculum->name }}
                        </option>
                    @endforeach
                </select>
                @error('curriculum_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group mt-4">
                <label class="form-label">Nama Tahun Ajaran <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="Contoh: 2025/2026" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 mb-4 text-sm text-primary-700">
                <div class="font-semibold mb-2">Yang akan dibuat otomatis:</div>
                <ul class="space-y-1 text-xs">
                    <li>✓ Semester Ganjil</li>
                    <li>&nbsp;&nbsp;→ Periode ASTS Ganjil</li>
                    <li>&nbsp;&nbsp;→ Periode SAS (Sumatif Akhir Semester)</li>
                    <li>✓ Semester Genap</li>
                    <li>&nbsp;&nbsp;→ Periode ASTS Genap</li>
                    <li>&nbsp;&nbsp;→ Periode SAT (Sumatif Akhir Tahun)</li>
                </ul>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.academic-years.index') }}" class="btn-secondary flex-1 justify-center">Batal</a>
                <button type="submit" class="btn-primary flex-1 justify-center">Buat Tahun Ajaran</button>
            </div>
        </form>
    </div>
</div>

</x-layouts.admin>

<x-layouts.admin title="Tambah Kelas">
<div class="page-header"><h2 class="page-title">Tambah Kelas Baru</h2></div>
<div class="max-w-sm">
    <div class="card">
        <form method="POST" action="{{ route('admin.classes.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Tingkat Kelas *</label>
                <select name="grade_level" class="form-select" required>
                    <option value="">-- Pilih Tingkat --</option>
                    @for($i=1;$i<=6;$i++)
                    <option value="{{ $i }}" {{ old('grade_level') == $i ? 'selected' : '' }}>Kelas {{ $i }}</option>
                    @endfor
                </select>
                @error('grade_level')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Rombel (Seksi) *</label>
                <input type="text" name="section" class="form-input" value="{{ old('section') }}" placeholder="A, B, C, ..." maxlength="5" required>
                @error('section')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.classes.index') }}" class="btn-secondary flex-1 justify-center">Batal</a>
                <button type="submit" class="btn-primary flex-1 justify-center">Buat Kelas</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.admin>

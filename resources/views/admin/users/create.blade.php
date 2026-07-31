<x-layouts.admin title="Tambah Pengguna">
<div class="page-header"><h2 class="page-title">Tambah Pengguna</h2></div>
<div class="max-w-2xl">
    <div class="card">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group col-span-2">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-input" value="{{ old('username') }}" placeholder="budi.santoso" required>
                    @error('username')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-input" value="{{ old('nip') }}">
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">Peran *</label>
                    <div class="flex flex-wrap gap-4 mt-1">
                        @foreach($roles as $role)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm font-medium capitalize">{{ $role->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('roles')<p class="form-error">{{ $message }}</p>@enderror
                    <p class="text-xs text-slate-400 mt-1">Password default: <strong>12345678</strong></p>
                </div>
            </div>
            <div class="flex gap-3 mt-2">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.admin>

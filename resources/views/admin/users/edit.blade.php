<x-layouts.admin title="Edit Pengguna">
<div class="page-header"><h2 class="page-title">Edit Pengguna: {{ $user->name }}</h2></div>
<div class="max-w-2xl">
    <div class="card">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group col-span-2">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-input" value="{{ old('username', $user->username) }}" required>
                    @error('username')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-input" value="{{ old('nip', $user->nip) }}">
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">Peran *</label>
                    <div class="flex flex-wrap gap-4 mt-1">
                        @foreach($roles as $role)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm font-medium capitalize">{{ $role->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('roles')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex gap-3 mt-2">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui Pengguna</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.admin>

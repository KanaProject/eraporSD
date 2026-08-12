<div class="flex justify-end gap-1">
    {{-- Edit: open modal --}}
    <button type="button" class="btn-icon" title="Edit Pengguna"
        onclick="openEditModal(this)"
        data-id="{{ $user->id }}"
        data-name="{{ $user->name }}"
        data-username="{{ $user->username }}"
        data-nip="{{ $user->nip }}"
        data-phone="{{ $user->phone }}"
        data-roles="{{ $user->getRoleNames()->implode(',') }}"
        data-action="{{ route('admin.users.update', $user) }}"
        data-reset-url="{{ route('admin.users.reset-password', $user) }}"
        data-toggle-url="{{ route('admin.users.toggle', $user) }}"
        data-is-active="{{ $user->is_active ? '1' : '0' }}"
        data-is-admin="{{ $user->hasRole('admin') ? '1' : '0' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/></svg>
    </button>

    </button>
</div>

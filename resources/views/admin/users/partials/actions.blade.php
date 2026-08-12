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

    @if(!$user->hasRole('admin'))
    <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="inline"
        data-confirm="Reset password {{ $user->name }} ke 12345678?"
        data-confirm-title="Reset Password"
        data-confirm-type="warning"
        data-confirm-ok="Ya, Reset">
        @csrf
        <button type="submit" class="btn-icon" title="Reset Password">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
        </button>
    </form>
    <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline">
        @csrf
        <button type="submit" class="btn-icon {{ $user->is_active ? 'text-amber-500 hover:text-amber-700' : 'text-green-500 hover:text-green-700' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
            @if($user->is_active)
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @endif
        </button>
    </form>
    @endif
</div>

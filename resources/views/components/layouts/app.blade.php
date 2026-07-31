<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'E-Rapor' }} — E-Rapor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">

<!-- Sidebar -->
<aside class="sidebar scrollbar-thin">
    @php $school = \App\Models\School::getInstance(); @endphp
    <div class="sidebar-brand">
        @if($school->logo_path)
            <img src="{{ Storage::url($school->logo_path) }}" alt="Logo" class="w-10 h-10 object-contain rounded-lg shrink-0">
        @else
            <div class="sidebar-brand-icon text-lg shrink-0">ER</div>
        @endif
        <div class="min-w-0">
            <div class="sidebar-brand-text text-primary-700">E-Rapor</div>
            <div class="text-[11px] text-slate-500 font-medium truncate uppercase">{{ $school->name ?: 'Portal Admin' }}</div>
        </div>
    </div>

    <nav class="sidebar-nav scrollbar-thin">
        {{ $sidebarNav }}
    </nav>

</aside>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <header class="topbar">
        <div id="topbar-left" class="flex-1 mr-4 min-w-0"></div>
        <div class="flex items-center gap-4 shrink-0">

            @php
                $activeRole = session('active_role', auth()->user()->getRoleNames()->first());
                $activeYear = \App\Models\AcademicYear::getActive();
                $activeSemester = \App\Models\Semester::getActive();
            @endphp
            
            @if(in_array($activeRole, ['kurikulum', 'guru', 'walas']))
                @if($activeYear && $activeSemester)
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-green-50 border border-green-100 rounded-full" title="Tahun Ajaran Aktif">
                        <div class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </div>
                        <span class="text-xs font-semibold text-green-700">TA. {{ $activeYear->name }} — {{ $activeSemester->name }}</span>
                    </div>
                @endif
            @endif

            {{-- Avatar + simple dropdown with 2 menu items --}}
            <div class="relative" id="profileMenu">
                <button id="profileToggle" type="button"
                    class="flex items-center gap-2 rounded-xl px-2 py-1.5 hover:bg-slate-100 transition-colors focus:outline-none">
                    <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-sm ring-2 ring-primary-200">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="hidden md:block leading-tight text-left">
                        <div class="text-sm font-medium text-slate-700">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-400">{{ ucfirst(session('active_role', auth()->user()->getRoleNames()->first())) }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition-transform duration-200" id="profileChevron" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                {{-- 2-item dropdown --}}
                <div id="profileDropdown"
                    class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1 overflow-hidden">

                    {{-- User info mini header --}}
                    <div class="px-4 py-3 border-b border-slate-100">
                        <div class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</div>
                        @if(auth()->user()->bio)
                        <div class="text-xs text-slate-400 truncate mt-0.5">{{ auth()->user()->bio }}</div>
                        @else
                        <div class="text-xs text-slate-400 mt-0.5">{{ ucfirst(session('active_role', auth()->user()->getRoleNames()->first())) }}</div>
                        @endif
                    </div>

                    {{-- Menu: Profil --}}
                    <button type="button" id="openProfileModal"
                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors text-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        Edit Profil
                    </button>

                    {{-- Divider --}}
                    <div class="border-t border-slate-100 mx-2"></div>

                    {{-- Menu: Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Flash session data injected as JS for toast system --}}
    @if(session('success') || session('error') || session('warning') || session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success')) Toast.success(@json(session('success'))); @endif
            @if(session('error'))   Toast.error(@json(session('error')));   @endif
            @if(session('warning')) Toast.warning(@json(session('warning'))); @endif
            @if(session('info'))    Toast.info(@json(session('info')));    @endif
        });
    </script>
    @endif

    <!-- Page Content -->
    <main class="page-container">
        {{ $slot }}
    </main>
</div>

{{-- ────────────── Profile Modal Dialog ────────────── --}}
<div id="profileModalBackdrop"
    class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-6">
    <div id="profileModalBox"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex overflow-hidden">

        {{-- ── Left Panel: User Card ── --}}
        <div class="w-56 flex-shrink-0 bg-gradient-to-b from-primary-700 to-primary-900 text-white flex flex-col items-center justify-center px-6 py-8 text-center">
            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-3xl ring-4 ring-white/25 mb-4">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="text-base font-bold leading-snug mb-1">{{ auth()->user()->name }}</div>
            <div class="text-primary-200 text-xs font-medium mb-2">{{ ucfirst(session('active_role', auth()->user()->getRoleNames()->first())) }}</div>
            @if(auth()->user()->bio)
            <div class="text-white/60 text-xs italic leading-relaxed">{{ auth()->user()->bio }}</div>
            @else
            <div class="text-white/40 text-xs italic">Belum ada bio</div>
            @endif

            <div class="mt-6 pt-5 border-t border-white/20 w-full text-left">
                <div class="text-white/50 text-xs mb-1 uppercase tracking-wider">Username</div>
                <div class="text-white text-sm font-medium">{{ auth()->user()->username }}</div>
                @if(auth()->user()->nip)
                <div class="text-white/50 text-xs mt-3 mb-1 uppercase tracking-wider">NIP</div>
                <div class="text-white text-sm font-medium">{{ auth()->user()->nip }}</div>
                @endif
            </div>
        </div>

        {{-- ── Right Panel: Forms ── --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Edit Profil</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Perbarui informasi akun Anda</p>
                </div>
                <button type="button" id="closeProfileModal"
                    class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form Body --}}
            <form method="POST" action="{{ route('profile.update') }}" class="px-6 py-5 flex-1 overflow-y-auto">
                @csrf
                @method('PUT')

                @if($errors->profileUpdate->any() || session('profile_error'))
                <div class="alert-error mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <span>{{ $errors->profileUpdate->first() ?: session('profile_error_message', 'Terjadi kesalahan pada profil.') }}</span>
                </div>
                @endif

                {{-- Info section --}}
                <div class="mb-4">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                        class="form-input" placeholder="Nama lengkap" required>
                </div>
                <div class="mb-5">
                    <label class="form-label">Bio Singkat <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input type="text" name="bio" value="{{ old('bio', auth()->user()->bio) }}"
                        class="form-input" placeholder="Contoh: Wali Kelas 4A · SDN 01" maxlength="255">
                    <p class="text-xs text-slate-400 mt-1">Tampil di dropdown profil</p>
                </div>

                {{-- Password section --}}
                <div class="border-t border-slate-100 pt-4 mb-5">
                    <p class="text-sm font-semibold text-slate-700 mb-0.5">Ganti Password</p>
                    <p class="text-xs text-slate-400 mb-3">Kosongkan jika tidak ingin mengganti</p>
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password"
                            class="form-input" placeholder="••••••••">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="new_password"
                                class="form-input" placeholder="Min. 6 karakter">
                        </div>
                        <div>
                            <label class="form-label">Ulangi Password</label>
                            <input type="password" name="new_password_confirmation"
                                class="form-input" placeholder="Ulangi">
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button type="button" id="cancelProfileModal" class="btn-secondary flex-1 justify-center">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary flex-1 justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes dropdownIn {
    from { opacity: 0; transform: translateY(-6px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(-10px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
#profileModalBox { animation: modalIn 0.2s ease-out; }
</style>

<script>
(function () {
    /* ── Dropdown ── */
    const toggle   = document.getElementById('profileToggle');
    const dropdown = document.getElementById('profileDropdown');
    const chevron  = document.getElementById('profileChevron');
    let open = false;

    function openDropdown() {
        open = true;
        dropdown.classList.remove('hidden');
        dropdown.style.animation = 'dropdownIn 0.18s ease-out';
        chevron.style.transform  = 'rotate(180deg)';
    }
    function closeDropdown() {
        open = false;
        dropdown.classList.add('hidden');
        chevron.style.transform = '';
    }

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        open ? closeDropdown() : openDropdown();
    });
    document.addEventListener('click', function (e) {
        if (open && !document.getElementById('profileMenu').contains(e.target)) closeDropdown();
    });

    /* ── Profile Modal ── */
    const backdrop  = document.getElementById('profileModalBackdrop');
    const modalBox  = document.getElementById('profileModalBox');
    const openBtn   = document.getElementById('openProfileModal');
    const closeBtn  = document.getElementById('closeProfileModal');
    const cancelBtn = document.getElementById('cancelProfileModal');

    function openModal() {
        closeDropdown();
        backdrop.classList.remove('hidden');
        modalBox.style.animation = 'modalIn 0.2s ease-out';
    }
    function closeModal() {
        backdrop.classList.add('hidden');
    }

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', function (e) {
        if (e.target === backdrop) closeModal();
    });

    @if($errors->profileUpdate->isNotEmpty() || session('profile_error'))
    openModal();
    @endif
})();
</script>

{{-- ═══════════════════════════════════════════════════
     TOAST NOTIFICATION CONTAINER
════════════════════════════════════════════════════ --}}
<div id="toastContainer"
    class="fixed bottom-5 right-5 z-[100] flex flex-col gap-2 pointer-events-none"
    style="min-width:320px; max-width:400px;"
></div>

{{-- ═══════════════════════════════════════════════════
     CONFIRM DIALOG
════════════════════════════════════════════════════ --}}
<div id="confirmBackdrop"
    class="hidden fixed inset-0 z-[110] bg-black/50 backdrop-blur-sm flex items-center justify-center p-6">
    <div id="confirmBox"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        {{-- Icon + Header --}}
        <div class="px-6 pt-6 pb-4 text-center">
            <div id="confirmIconWrap"
                class="w-14 h-14 rounded-full mx-auto flex items-center justify-center mb-4">
                <svg id="confirmIcon" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7"
                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path id="confirmIconPath" stroke-linecap="round" stroke-linejoin="round" d=""/>
                </svg>
            </div>
            <h3 id="confirmTitle" class="text-lg font-bold text-slate-800 mb-1"></h3>
            <p  id="confirmMessage" class="text-sm text-slate-500 leading-relaxed"></p>
        </div>
        {{-- Actions --}}
        <div class="flex gap-3 px-6 pb-6">
            <button id="confirmCancel"
                class="btn-secondary flex-1 justify-center py-2.5">
                Batal
            </button>
            <button id="confirmOk"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-1">
            </button>
        </div>
    </div>
</div>

<style>
/* ── Toast animations ── */
@keyframes toastIn {
    from { opacity: 0; transform: translateX(100%) scale(0.95); }
    to   { opacity: 1; transform: translateX(0) scale(1); }
}
@keyframes toastOut {
    from { opacity: 1; transform: translateX(0) scale(1); }
    to   { opacity: 0; transform: translateX(100%) scale(0.95); }
}
/* ── Confirm animation ── */
@keyframes confirmIn {
    from { opacity: 0; transform: scale(0.92) translateY(-12px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
#confirmBox { animation: confirmIn 0.22s ease-out; }
</style>

<script>
/* ═══════════════════════════════════════════════
   TOAST SYSTEM
═══════════════════════════════════════════════ */
const Toast = (function () {
    const container = document.getElementById('toastContainer');

    const configs = {
        success: {
            bg:   'bg-white',
            bar:  'bg-primary-500',
            icon: 'text-primary-600',
            title: 'Berhasil',
            path: 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        },
        error: {
            bg:   'bg-white',
            bar:  'bg-red-500',
            icon: 'text-red-500',
            title: 'Gagal',
            path: 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        },
        warning: {
            bg:   'bg-white',
            bar:  'bg-amber-400',
            icon: 'text-amber-500',
            title: 'Perhatian',
            path: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
        },
        info: {
            bg:   'bg-white',
            bar:  'bg-blue-500',
            icon: 'text-blue-500',
            title: 'Informasi',
            path: 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
        },
    };

    function show(type, message, duration = 4500) {
        const cfg  = configs[type] || configs.info;
        const wrap = document.createElement('div');
        wrap.className = 'pointer-events-auto relative flex items-start gap-3 rounded-xl shadow-xl border border-slate-100 px-4 py-3 ' + cfg.bg;
        wrap.style.cssText = 'animation: toastIn 0.3s ease-out; min-width:300px;';
        wrap.innerHTML = `
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-xl ${cfg.bar}"></div>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 mt-0.5 ${cfg.icon}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="${cfg.path}"/>
            </svg>
            <div class="flex-1 min-w-0">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">${cfg.title}</div>
                <div class="text-sm text-slate-800 mt-0.5 leading-snug">${message}</div>
            </div>
            <button class="ml-1 text-slate-300 hover:text-slate-500 transition-colors flex-shrink-0 toast-close">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="absolute bottom-0 left-1 right-0 h-0.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full ${cfg.bar} opacity-30 toast-progress" style="animation: toastProgress ${duration}ms linear forwards;"></div>
            </div>`;

        wrap.querySelector('.toast-close').addEventListener('click', () => dismiss(wrap));
        container.appendChild(wrap);

        const timer = setTimeout(() => dismiss(wrap), duration);
        wrap._timer = timer;
    }

    function dismiss(el) {
        clearTimeout(el._timer);
        el.style.animation = 'toastOut 0.3s ease-in forwards';
        setTimeout(() => el.remove(), 300);
    }

    return {
        success: (msg, d) => show('success', msg, d),
        error:   (msg, d) => show('error', msg, d),
        warning: (msg, d) => show('warning', msg, d),
        info:    (msg, d) => show('info', msg, d),
    };
})();

/* ═══════════════════════════════════════════════
   CONFIRM DIALOG SYSTEM
═══════════════════════════════════════════════ */
const Confirm = (function () {
    const backdrop = document.getElementById('confirmBackdrop');
    const box      = document.getElementById('confirmBox');
    const title    = document.getElementById('confirmTitle');
    const message  = document.getElementById('confirmMessage');
    const okBtn    = document.getElementById('confirmOk');
    const cancelBtn= document.getElementById('confirmCancel');
    const iconWrap = document.getElementById('confirmIconWrap');
    const iconPath = document.getElementById('confirmIconPath');
    let _onConfirm = null;

    const presets = {
        danger: {
            iconBg:   'bg-red-100',
            iconClr:  'text-red-500',
            iconPath: 'M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0',
            btnClass: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
            btnLabel: 'Ya, Hapus',
        },
        warning: {
            iconBg:   'bg-amber-100',
            iconClr:  'text-amber-500',
            iconPath: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
            btnClass: 'bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-400',
            btnLabel: 'Ya, Lanjutkan',
        },
        info: {
            iconBg:   'bg-primary-100',
            iconClr:  'text-primary-600',
            iconPath: 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
            btnClass: 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500',
            btnLabel: 'Konfirmasi',
        },
    };

    function show({ title: t, message: m, type = 'danger', okLabel, onConfirm }) {
        const preset = presets[type] || presets.danger;
        title.textContent   = t || 'Konfirmasi';
        message.textContent = m || 'Apakah Anda yakin?';
        iconWrap.className  = `w-14 h-14 rounded-full mx-auto flex items-center justify-center mb-4 ${preset.iconBg}`;
        document.getElementById('confirmIcon').className = `w-7 h-7 ${preset.iconClr}`;
        iconPath.setAttribute('d', preset.iconPath);
        okBtn.className     = `flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-1 ${preset.btnClass}`;
        okBtn.textContent   = okLabel || preset.btnLabel;
        _onConfirm = onConfirm;
        backdrop.classList.remove('hidden');
        box.style.animation = 'confirmIn 0.22s ease-out';
    }

    function hide() { backdrop.classList.add('hidden'); }

    okBtn.addEventListener('click', function () {
        hide();
        if (typeof _onConfirm === 'function') _onConfirm();
    });
    cancelBtn.addEventListener('click', hide);
    backdrop.addEventListener('click', (e) => { if (e.target === backdrop) hide(); });

    return { show };
})();

/* ═══════════════════════════════════════════════
   AUTO-INTERCEPT: data-confirm on forms & links
   Usage:
     <form data-confirm="Yakin hapus data ini?" data-confirm-type="danger">
     <a href="..." data-confirm="Yakin?">Hapus</a>
═══════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {
    // Forms with data-confirm
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Confirm.show({
                title:     form.dataset.confirmTitle   || 'Konfirmasi Aksi',
                message:   form.dataset.confirm,
                type:      form.dataset.confirmType    || 'danger',
                okLabel:   form.dataset.confirmOk      || null,
                onConfirm: function () { form.submit(); },
            });
        });
    });

    // Links / buttons with data-confirm
    document.querySelectorAll('a[data-confirm], button[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const href = el.getAttribute('href');
            Confirm.show({
                title:     el.dataset.confirmTitle  || 'Konfirmasi Aksi',
                message:   el.dataset.confirm,
                type:      el.dataset.confirmType   || 'danger',
                okLabel:   el.dataset.confirmOk     || null,
                onConfirm: function () {
                    if (href && href !== '#') window.location.href = href;
                },
            });
        });
    });
    // Move title and subtitle to topbar, but leave buttons in the view content
    const pageHeader = document.querySelector('.page-header');
    const topbarLeft = document.getElementById('topbar-left');
    if (pageHeader && topbarLeft) {
        const title = pageHeader.querySelector('.page-title');
        const subtitle = pageHeader.querySelector('.page-subtitle');
        
        if (title || subtitle) {
            const wrapper = document.createElement('div');
            if (title) {
                title.className = 'text-lg font-bold text-slate-800 leading-tight';
                wrapper.appendChild(title);
            }
            if (subtitle) {
                subtitle.className = 'text-xs text-slate-400 mt-0.5 italic';
                wrapper.appendChild(subtitle);
            }
            topbarLeft.appendChild(wrapper);
        }
        
        // Clean up empty wrapper divs in page-header left behind by title/subtitle
        Array.from(pageHeader.children).forEach(child => {
            if (child.tagName === 'DIV' && child.innerHTML.trim() === '') {
                child.remove();
            }
        });
        
        // If page-header is now empty (no buttons), hide it so content moves up
        if (pageHeader.innerHTML.trim() === '') {
            pageHeader.style.display = 'none';
        } else {
            // If buttons remain, ensure they are pushed to the right side elegantly
            pageHeader.classList.add('flex', 'justify-end');
        }
    }
});
</script>

@stack('scripts')
</body>
</html>

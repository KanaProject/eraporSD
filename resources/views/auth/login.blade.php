<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Rapor — {{ $school->name ?? 'Sistem Manajemen Rapor' }}</title>
    <meta name="description" content="E-Rapor Digital SDIT AT-TAQWA — Sistem Pengelolaan Data Nilai Siswa berbasis web yang modern dan terintegrasi.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .landing-bg {
            background: linear-gradient(140deg, #1e3a8a 0%, #2563eb 45%, #3b82f6 75%, #60a5fa 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.20);
        }
        .stat-glass {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.20);
            backdrop-filter: blur(8px);
        }
        /* Modal */
        #loginModal {
            display: none;
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(10,20,50,0.65);
            backdrop-filter: blur(6px);
            align-items: center; justify-content: center;
        }
        #loginModal.open { display: flex; }
        #loginModal > .modal-box {
            animation: popIn .25s cubic-bezier(.34,1.56,.64,1);
        }
        @keyframes popIn {
            from { opacity:0; transform:scale(.88) translateY(20px); }
            to   { opacity:1; transform:scale(1)  translateY(0); }
        }
        /* Bar chart */
        .bar-fill { transition: width 1.2s cubic-bezier(.34,1.1,.64,1); }
        /* Donut */
        .donut-ring { transform: rotate(-90deg); transform-origin: 50% 50%; }
        .donut-segment { transition: stroke-dashoffset 1.5s cubic-bezier(.34,1.1,.64,1); }
    </style>
</head>
<body class="landing-bg">

<!-- ===== TOP NAV ===== -->
<nav class="relative z-30 flex items-center justify-between px-6 md:px-12 py-5">
    <div class="flex items-center gap-3">
        @if($school && $school->logo_path)
            <img src="{{ Storage::url($school->logo_path) }}" alt="Logo" class="w-10 h-10 rounded-xl object-contain bg-white/20 p-1 border border-white/30">
        @else
            <div class="w-10 h-10 rounded-xl bg-white/20 border border-white/30 flex items-center justify-center">
                <span class="text-white font-extrabold text-sm">E-R</span>
            </div>
        @endif
        <div class="text-white">
            <div class="font-black text-sm leading-tight">E-Rapor</div>
            <div class="text-xs text-blue-200 leading-tight">{{ $school->name ?? 'Sistem Manajemen Rapor' }}</div>
        </div>
    </div>
    <button onclick="openModal()" id="navLoginBtn"
        class="flex items-center gap-2 bg-white text-blue-800 font-bold text-sm px-5 py-2.5 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all active:scale-95">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
        </svg>
        Masuk
    </button>
</nav>

<!-- ===== HERO ===== -->
<section class="relative z-10 px-6 md:px-12 pt-4 pb-10">
    <div class="max-w-5xl mx-auto text-center mb-10">
        <p class="inline-block text-xs font-bold text-blue-200 tracking-widest uppercase bg-white/10 border border-white/20 px-3 py-1 rounded-full mb-4">
            TA {{ $activeYear->name ?? '-' }} &mdash; {{ $activePeriod->name ?? 'Belum Ada Periode Aktif' }}
        </p>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight drop-shadow-lg mb-4">
            Sistem Pengelolaan<br><span class="text-blue-200">Data Nilai Siswa</span>
        </h1>
        <p class="text-blue-100 text-base md:text-lg max-w-xl mx-auto">
            Platform digital terintegrasi untuk administrasi rapor, penilaian, dan legger seluruh kelas.
        </p>
    </div>

    <!-- STAT CARDS -->
    <div class="max-w-3xl mx-auto grid grid-cols-3 gap-4 mb-12">
        <div class="stat-glass rounded-2xl p-5 text-center text-white">
            <div class="text-4xl font-black leading-none mb-1">{{ $totalClasses }}</div>
            <div class="text-xs text-blue-200 font-semibold uppercase tracking-wider">Rombel Kelas</div>
        </div>
        <div class="stat-glass rounded-2xl p-5 text-center text-white">
            <div class="text-4xl font-black leading-none mb-1">{{ $totalStudents }}</div>
            <div class="text-xs text-blue-200 font-semibold uppercase tracking-wider">Siswa Aktif</div>
        </div>
        <div class="stat-glass rounded-2xl p-5 text-center text-white">
            <div class="text-4xl font-black leading-none mb-1">{{ $totalTeachers }}</div>
            <div class="text-xs text-blue-200 font-semibold uppercase tracking-wider">Tenaga Pendidik</div>
        </div>
    </div>

    <!-- CHARTS ROW -->
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Donut: Global Progress -->
        <div class="glass-card rounded-3xl p-7 flex flex-col items-center">
            <h3 class="text-white font-bold text-base mb-1">Progres Penilaian Sekolah</h3>
            <p class="text-blue-200 text-xs mb-6">Periode: {{ $activePeriod->name ?? '-' }}</p>

            <div class="relative flex items-center justify-center w-44 h-44 mb-6">
                <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
                    <!-- Track -->
                    <circle cx="60" cy="60" r="50" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="14"/>
                    <!-- Grade fill -->
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#34d399" stroke-width="14"
                        stroke-linecap="round"
                        stroke-dasharray="{{ round(314.16 * $gradingProgress / 100) }} 314.16"
                        class="donut-segment" id="gradeArc"/>
                </svg>
                <div class="absolute text-center">
                    <div class="text-3xl font-black text-white leading-none" id="gradeNum">0%</div>
                    <div class="text-[10px] text-emerald-300 font-bold uppercase tracking-wider mt-0.5">Input Nilai</div>
                </div>
            </div>

            <!-- Legend -->
            <div class="w-full space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        <span class="text-blue-100 text-xs font-medium">Progres Input Nilai Guru</span>
                    </div>
                    <span class="text-white font-bold text-sm">{{ $gradingProgress }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-sky-300"></div>
                        <span class="text-blue-100 text-xs font-medium">Progres Cetak Rapor Walas</span>
                    </div>
                    <span class="text-white font-bold text-sm">{{ $reportProgress }}%</span>
                </div>
            </div>
        </div>

        <!-- Bar Chart: Per Kelas -->
        <div class="glass-card rounded-3xl p-7">
            <h3 class="text-white font-bold text-base mb-1">Klasemen Cetak Rapor</h3>
            <p class="text-blue-200 text-xs mb-6">Progres per tingkat kelas (Rapor dicetak)</p>
            <div class="space-y-4">
                @foreach($classBars as $bar)
                @php
                    $colors = ['bg-emerald-400','bg-teal-400','bg-cyan-400','bg-sky-400','bg-indigo-400','bg-violet-400'];
                    $c = $colors[($bar['level']-1) % count($colors)];
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-white text-xs font-bold">Kelas {{ $bar['level'] }}</span>
                        <span class="text-blue-200 text-xs">{{ $bar['done'] }}/{{ $bar['total'] }} siswa &middot; <span class="text-white font-bold">{{ $bar['pct'] }}%</span></span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-3 overflow-hidden">
                        <div class="{{ $c }} h-3 rounded-full bar-fill" style="width:0%" data-target="{{ $bar['pct'] }}%"></div>
                    </div>
                </div>
                @endforeach
                @if(empty($classBars))
                <p class="text-blue-200 text-sm text-center py-6 opacity-70">Belum ada periode aktif</p>
                @endif
            </div>
        </div>

    </div>

    <!-- CTA Button -->
    <div class="max-w-5xl mx-auto mt-8 text-center">
        <button onclick="openModal()"
            class="inline-flex items-center gap-3 bg-white text-blue-800 font-extrabold text-base px-8 py-4 rounded-2xl shadow-2xl hover:shadow-blue-900/40 hover:-translate-y-1 transition-all active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
            </svg>
            Masuk ke Sistem E-Rapor
        </button>
        <p class="text-blue-200 text-xs mt-3 opacity-70">© {{ date('Y') }} E-Rapor. {{ $school->name ?? 'Sistem Manajemen Rapor' }}.</p>
    </div>
</section>

<!-- ===== DECORATIONS ===== -->
<div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-blue-400/20 blur-3xl"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-indigo-700/30 blur-3xl"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-blue-500/10 blur-3xl"></div>
</div>

<!-- ===== LOGIN MODAL ===== -->
<div id="loginModal" onclick="handleModalBackdrop(event)">
    <div class="modal-box w-full max-w-md mx-4 bg-white rounded-2xl shadow-2xl p-8 md:p-10 border border-white/20 relative">
        <!-- Close btn -->
        <button onclick="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-blue-50 border border-blue-100 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-1">Selamat Datang</h2>
            <p class="text-slate-500 text-sm">Masukkan kredensial Anda untuk melanjutkan</p>
        </div>

        @if ($errors->any())
        <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl flex items-start gap-3 border border-red-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
            </svg>
            <span class="text-sm font-medium">{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
            @csrf
            <div>
                <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </span>
                    <input type="text" name="username" id="username" value="{{ old('username') }}"
                        class="w-full border border-slate-200 rounded-xl py-3 pl-11 pr-4 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="Masukkan username" autocomplete="username" required autofocus>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </span>
                    <input type="password" name="password" id="password"
                        class="w-full border border-slate-200 rounded-xl py-3 pl-11 pr-11 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="Masukkan password" autocomplete="current-password" required>
                    <button type="button" id="togglePwd"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-blue-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="remember" class="text-sm text-slate-600 cursor-pointer">Ingat saya</label>
            </div>

            <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-xl text-base font-bold transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                </svg>
                Masuk ke Sistem
            </button>
        </form>
    </div>
</div>

<script>
    // Modal controls
    function openModal() {
        document.getElementById('loginModal').classList.add('open');
        setTimeout(() => document.getElementById('username').focus(), 100);
    }
    function closeModal() {
        document.getElementById('loginModal').classList.remove('open');
    }
    function handleModalBackdrop(e) {
        if (e.target === document.getElementById('loginModal')) closeModal();
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    // Auto-open modal if there are validation errors (login failed)
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', openModal);
    @endif

    // Password toggle
    document.getElementById('togglePwd').addEventListener('click', function() {
        const pwd = document.getElementById('password');
        pwd.type = pwd.type === 'password' ? 'text' : 'password';
    });

    // Animate bar chart
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.bar-fill[data-target]').forEach(el => {
            setTimeout(() => el.style.width = el.dataset.target, 200);
        });

        // Animate donut counter
        const target = {{ $gradingProgress }};
        let current = 0;
        const step = target / 60;
        const el = document.getElementById('gradeNum');
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = Math.round(current) + '%';
            if (current >= target) clearInterval(timer);
        }, 16);
    });
</script>
</body>
</html>

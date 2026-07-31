<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — E-Rapor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @php
        $school = \App\Models\School::first();
    @endphp
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .login-bg {
            background: linear-gradient(135deg, #1b3932 0%, #255649 30%, #2d6b5a 60%, #3d8570 100%);
        }
        .login-card {
            backdrop-filter: blur(20px);
            background: rgba(255,255,255,0.97);
        }
        .brand-glow {
            box-shadow: 0 0 40px rgba(61,133,112,0.4);
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4 pb-16 md:pb-24">

    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-[90rem] px-6 md:px-10 xl:px-24 flex flex-col lg:flex-row items-center justify-between gap-10 lg:gap-16 xl:gap-24">
        
        <!-- Bagian Kiri: Branding & Text -->
        <div class="flex-1 w-full text-center lg:text-left">
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-4 lg:gap-6 mb-6 lg:mb-8">
                @if($school && $school->logo_path)
                    <img src="{{ Storage::url($school->logo_path) }}" alt="Logo Sekolah" class="w-20 h-20 md:w-24 md:h-24 xl:w-32 xl:h-32 rounded-2xl brand-glow object-contain bg-white/10 p-2 border border-white/30 shrink-0">
                @else
                    <div class="inline-flex items-center justify-center w-20 h-20 md:w-24 md:h-24 xl:w-32 xl:h-32 rounded-2xl bg-white/20 brand-glow border border-white/30 shrink-0">
                        <span class="text-white font-extrabold text-2xl xl:text-3xl">E-R</span>
                    </div>
                @endif
                <div class="pt-1 xl:pt-2">
                    <h1 class="text-2xl md:text-3xl xl:text-4xl font-bold text-white mb-1 xl:mb-2 tracking-wide drop-shadow-md">E-Rapor</h1>
                    <h2 class="text-xl md:text-2xl xl:text-3xl font-black text-white uppercase leading-tight drop-shadow-md">{{ $school->name ?? 'Sistem Manajemen Rapor' }}</h2>
                </div>
            </div>
            <h3 class="text-3xl lg:text-4xl xl:text-5xl font-black uppercase leading-tight tracking-tight mt-6 lg:mt-8 xl:mt-12 text-white drop-shadow-lg">
                <span class="block md:whitespace-nowrap">Sistem Pengelolaan</span>
                <span class="block md:whitespace-nowrap">Data Nilai Siswa</span>
            </h3>
        </div>

        <!-- Bagian Kanan: Card Login -->
        <div class="w-full max-w-md shrink-0">
            <div class="login-card rounded-2xl shadow-2xl p-8 md:p-10 border border-white/20">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Selamat Datang</h2>
                    <p class="text-slate-500 text-sm">Masukkan kredensial Anda untuk melanjutkan</p>
                </div>

                @if ($errors->any())
                <div class="alert-error mb-6 bg-red-50 text-red-600 p-4 rounded-lg flex items-start gap-3 border border-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ $errors->first() }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" id="loginForm" class="space-y-6">
                    @csrf
                    <div class="form-group">
                        <label for="username" class="block text-sm font-medium text-slate-700 mb-1.5">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                            </span>
                            <input type="text" name="username" id="username" value="{{ old('username') }}"
                                class="w-full border border-slate-300 rounded-lg py-3 pl-11 pr-4 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#2d6b5a] focus:border-transparent transition-all" placeholder="Masukkan username" autocomplete="username" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                </svg>
                            </span>
                            <input type="password" name="password" id="password"
                                class="w-full border border-slate-300 rounded-lg py-3 pl-11 pr-11 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#2d6b5a] focus:border-transparent transition-all" placeholder="Masukkan password" autocomplete="current-password" required>
                            <button type="button" id="togglePwd" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-[#2d6b5a] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-slate-300 text-[#2d6b5a] focus:ring-[#2d6b5a]">
                        <label for="remember" class="text-sm text-slate-600 cursor-pointer">Ingat saya</label>
                    </div>

                    <button type="submit" id="loginBtn" class="w-full flex items-center justify-center gap-2 bg-[#2d6b5a] hover:bg-[#255649] text-white py-3.5 rounded-lg text-base font-semibold transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                        Masuk
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-400 font-medium">© {{ date('Y') }} E-Rapor. {{ $school->name ?? 'Sistem Manajemen Rapor' }}.</p>
                </div>
            </div>
        </div>
    </div>

<script>
    const toggleBtn = document.getElementById('togglePwd');
    const pwdInput  = document.getElementById('password');
    toggleBtn.addEventListener('click', function () {
        const isHidden = pwdInput.type === 'password';
        pwdInput.type  = isHidden ? 'text' : 'password';
        toggleBtn.querySelector('svg path:last-child').setAttribute(
            'd', isHidden
                ? 'M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88'
                : 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
        );
    });
</script>

</body>
</html>

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
<body class="login-bg min-h-screen flex items-center justify-center p-4">

    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Brand -->
        <div class="text-center mb-8">
            @if($school && $school->logo_path)
                <img src="{{ Storage::url($school->logo_path) }}" alt="Logo Sekolah" class="w-20 h-20 mx-auto rounded-2xl mb-4 brand-glow object-contain bg-white/10 p-1 border border-white/30">
            @else
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/20 brand-glow mb-4 border border-white/30">
                    <span class="text-white font-extrabold text-2xl">E-R</span>
                </div>
            @endif
            <h1 class="text-2xl font-bold text-white/90 mb-1">E-Rapor</h1>
            <h2 class="text-2xl font-black text-white uppercase leading-tight">{{ $school->name ?? 'Sistem Manajemen Rapor Sekolah Dasar' }}</h2>
        </div>

        <!-- Card -->
        <div class="login-card rounded-2xl shadow-2xl p-8 border border-white/20">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-slate-800 mb-1">Selamat Datang</h2>
                <p class="text-slate-500 text-sm">Masukkan kredensial Anda untuk melanjutkan</p>
            </div>

            @if ($errors->any())
            <div class="alert-error mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </span>
                        <input type="text" name="username" id="username" value="{{ old('username') }}"
                            class="form-input pl-10" placeholder="Masukkan username" autocomplete="username" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </span>
                        <input type="password" name="password" id="password"
                            class="form-input pl-10 pr-10" placeholder="Masukkan password" autocomplete="current-password" required>
                        <button type="button" id="togglePwd" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-6">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    <label for="remember" class="text-sm text-slate-600">Ingat saya</label>
                </div>

                <button type="submit" id="loginBtn" class="btn-primary w-full justify-center py-3 text-base font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                    </svg>
                    Masuk
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-400">© {{ date('Y') }} E-Rapor. {{ $school->name ?? 'Sistem Manajemen Rapor' }}.</p>
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

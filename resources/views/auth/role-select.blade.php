<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Peran — E-Rapor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-800 via-blue-600 to-blue-400 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <!-- Modal -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden animate-modal">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-700 to-blue-800 px-8 py-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center border border-white/30">
                    <span class="text-white font-bold">iR</span>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white">E-Rapor</h1>
                    <p class="text-white/70 text-xs">Pilih Mode Masuk</p>
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="text-center mb-6">
                <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-3">
                    <span class="text-blue-700 font-extrabold text-xl">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <h2 class="text-lg font-bold text-slate-800">Halo, {{ $user->name }}!</h2>
                <p class="text-slate-500 text-sm mt-1">
                    Anda memiliki <span class="font-semibold text-blue-700">2 peran aktif</span>. Silakan pilih bagaimana Anda ingin masuk:
                </p>
            </div>

            <div class="space-y-3">
                <!-- Guru Option -->
                <form method="POST" action="{{ route('role.select.post') }}">
                    @csrf
                    <input type="hidden" name="role" value="guru">
                    <button type="submit" class="w-full group flex items-center gap-4 p-4 border-2 border-slate-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 text-left">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-slate-800 group-hover:text-blue-700 transition-colors">Masuk sebagai Guru</div>
                            <div class="text-sm text-slate-500">Input nilai mata pelajaran yang Anda ampu</div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </button>
                </form>

                <!-- Walas Option -->
                <form method="POST" action="{{ route('role.select.post') }}">
                    @csrf
                    <input type="hidden" name="role" value="walas">
                    <button type="submit" class="w-full group flex items-center gap-4 p-4 border-2 border-slate-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 text-left">
                        <div class="w-12 h-12 rounded-xl bg-green-50 group-hover:bg-green-100 flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-slate-800 group-hover:text-blue-700 transition-colors">Masuk sebagai Wali Kelas</div>
                            <div class="text-sm text-slate-500">Kelola kelas, kehadiran, dan cetak rapor</div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-slate-400 hover:text-red-500 transition-colors">
                        Keluar dari akun ini
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>

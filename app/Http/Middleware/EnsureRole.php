<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!Auth::check() || !Auth::user()->hasRole($role)) {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses ke halaman ini.');
        }
        return $next($request);
    }
}

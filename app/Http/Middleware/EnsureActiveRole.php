<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureActiveRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user       = Auth::user();
        $roles      = $user->getRoleNames()->toArray();
        $activeRole = session('active_role');

        // If user only has this single role, set it and proceed
        if (in_array($role, $roles) && !in_array('guru', $roles) || !in_array('walas', $roles)) {
            session(['active_role' => $role]);
            return $next($request);
        }

        // Dual-role: check session matches requested portal
        if ($activeRole !== $role) {
            return redirect()->route('role.select');
        }

        return $next($request);
    }
}

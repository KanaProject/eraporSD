<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = [
            'username'  => $request->username,
            'password'  => $request->password,
            'is_active' => true,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'username' => 'Username atau password salah, atau akun tidak aktif.',
            ])->withInput($request->only('username'));
        }

        $request->session()->regenerate();

        $user  = Auth::user();
        $roles = $user->getRoleNames()->toArray();

        // Dual-role: if user has both guru and walas → show role selection
        if (in_array('guru', $roles) && in_array('walas', $roles)) {
            return redirect()->route('role.select');
        }

        return $this->redirectAfterLogin($user);
    }

    public function showRoleSelect()
    {
        if (!Auth::check()) return redirect()->route('login');

        $user  = Auth::user();
        $roles = $user->getRoleNames()->toArray();

        // Only show if truly dual-role
        if (!in_array('guru', $roles) || !in_array('walas', $roles)) {
            return $this->redirectAfterLogin($user);
        }

        return view('auth.role-select', compact('user'));
    }

    public function selectRole(Request $request)
    {
        $request->validate(['role' => 'required|in:guru,walas']);

        $user  = Auth::user();
        $roles = $user->getRoleNames()->toArray();

        if (!in_array($request->role, $roles)) {
            return back()->withErrors(['role' => 'Peran tidak valid.']);
        }

        session(['active_role' => $request->role]);

        return redirect($request->role === 'guru' ? '/guru' : '/walas');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectAfterLogin($user): \Illuminate\Http\RedirectResponse
    {
        $roles = $user->getRoleNames();
        if ($roles->contains('admin'))     return redirect('/admin');
        if ($roles->contains('kurikulum')) return redirect('/kurikulum');
        if ($roles->contains('guru'))      { session(['active_role' => 'guru']);  return redirect('/guru'); }
        if ($roles->contains('walas'))     { session(['active_role' => 'walas']); return redirect('/walas'); }
        return redirect('/');
    }
}

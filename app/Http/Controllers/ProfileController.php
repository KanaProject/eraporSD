<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:100',
            'bio'  => 'nullable|string|max:255',
        ];

        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required|string';
            $rules['new_password']     = ['required', 'string', 'min:6', 'confirmed', Password::defaults()];
        }

        $request->validateWithBag('profileUpdate', $rules, [
            'name.required'              => 'Nama wajib diisi.',
            'current_password.required'  => 'Password saat ini wajib diisi untuk mengganti password.',
            'new_password.min'           => 'Password baru minimal 6 karakter.',
            'new_password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ]);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'], 'profileUpdate')
                             ->with('profile_error', true);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $request->name;
        $user->bio  = $request->bio;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}

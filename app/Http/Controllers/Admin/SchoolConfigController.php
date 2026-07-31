<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolConfigController extends Controller
{
    public function edit()
    {
        $school = School::getInstance();
        return view('admin.school.edit', compact('school'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'           => 'nullable|string|max:255',
            'npsn'           => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'principal_name' => 'nullable|string|max:255',
            'principal_nip'  => 'nullable|string|max:30',
            'city'           => 'nullable|string|max:100',
            'province'       => 'nullable|string|max:100',
            'logo'           => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $school = School::getInstance();
        $data   = $request->except(['logo', '_token', '_method']);

        if ($request->hasFile('logo')) {
            if ($school->logo_path) {
                Storage::disk('public')->delete($school->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('school', 'public');
        }

        $school->update($data);

        return back()->with('success', 'Profil sekolah berhasil disimpan.');
    }
}

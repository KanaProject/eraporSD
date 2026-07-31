<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function index()
    {
        $curriculums = Curriculum::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.curriculums.index', compact('curriculums'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:curriculums,name',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Curriculum::create($validated);

        return back()->with('success', 'Kurikulum berhasil ditambahkan.');
    }

    public function update(Request $request, Curriculum $curriculum)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:curriculums,name,' . $curriculum->id,
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $curriculum->update($validated);

        return back()->with('success', 'Kurikulum berhasil diperbarui.');
    }

    public function destroy(Curriculum $curriculum)
    {
        if ($curriculum->academicYears()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kurikulum karena sedang digunakan oleh Tahun Ajaran.');
        }

        $curriculum->delete();

        return back()->with('success', 'Kurikulum berhasil dihapus.');
    }
}

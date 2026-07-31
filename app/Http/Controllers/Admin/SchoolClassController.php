<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeroomAssignment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount(['students' => fn($q) => $q->where('is_active', true)])
            ->orderBy('grade_level')->orderBy('section')->get()->groupBy('grade_level');
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade_level' => 'required|integer|between:1,6',
            'section'     => 'required|string|max:5',
        ]);

        $name = $request->grade_level . strtoupper($request->section);
        SchoolClass::create([
            'grade_level' => $request->grade_level,
            'section'     => strtoupper($request->section),
            'name'        => $name,
            'is_active'   => true,
        ]);

        return redirect()->route('admin.classes.index')->with('success', "Kelas {$name} berhasil ditambahkan.");
    }

    public function destroy(SchoolClass $class)
    {
        if ($class->students()->where('is_active', true)->exists()) {
            return back()->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa aktif.');
        }
        $class->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}

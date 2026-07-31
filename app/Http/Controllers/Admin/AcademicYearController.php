<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AssessmentPeriod;
use App\Models\Semester;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $years = AcademicYear::with(['curriculum', 'semesters.assessmentPeriods'])->orderByDesc('id')->get();
        return view('admin.academic-years.index', compact('years'));
    }

    public function create()
    {
        $curriculums = \App\Models\Curriculum::where('is_active', true)->get();
        return view('admin.academic-years.create', compact('curriculums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:academic_years,name',
            'curriculum_id' => 'required|exists:curriculums,id'
        ]);

        $year = AcademicYear::create([
            'curriculum_id' => $request->curriculum_id,
            'name' => $request->name, 
            'is_active' => false
        ]);

        // Auto-create 2 semesters + 4 periods
        $ganjil = Semester::create(['academic_year_id' => $year->id, 'name' => 'Ganjil', 'type' => 'ganjil', 'is_active' => false]);
        $genap  = Semester::create(['academic_year_id' => $year->id, 'name' => 'Genap',  'type' => 'genap',  'is_active' => false]);

        AssessmentPeriod::create(['semester_id' => $ganjil->id, 'code' => 'ASTS_GANJIL', 'name' => 'ASTS Ganjil',              'is_active' => false]);
        AssessmentPeriod::create(['semester_id' => $ganjil->id, 'code' => 'SAS',          'name' => 'Sumatif Akhir Semester',    'is_active' => false]);
        AssessmentPeriod::create(['semester_id' => $genap->id,  'code' => 'ASTS_GENAP',  'name' => 'ASTS Genap',               'is_active' => false]);
        AssessmentPeriod::create(['semester_id' => $genap->id,  'code' => 'SAT',          'name' => 'Sumatif Akhir Tahun',       'is_active' => false]);

        return redirect()->route('admin.academic-years.index')->with('success', "Tahun ajaran {$year->name} berhasil ditambahkan.");
    }

    public function setActive(AcademicYear $academicYear)
    {
        AcademicYear::where('is_active', true)->update(['is_active' => false]);
        $academicYear->update(['is_active' => true]);

        return back()->with('success', "Tahun ajaran {$academicYear->name} diaktifkan.");
    }

    public function setPeriodActive(AssessmentPeriod $period)
    {
        // Deactivate all
        Semester::query()->update(['is_active' => false]);
        AssessmentPeriod::query()->update(['is_active' => false]);

        // Activate selected period and its semester
        $period->update(['is_active' => true]);
        $period->semester->update(['is_active' => true]);

        return back()->with('success', "Periode \"{$period->name}\" diaktifkan.");
    }

    public function updatePeriod(Request $request, AssessmentPeriod $period)
    {
        $request->validate([
            'report_place' => 'nullable|string|max:255',
            'report_date' => 'nullable|date'
        ]);

        $period->update([
            'report_place' => $request->report_place,
            'report_date' => $request->report_date,
        ]);

        return back()->with('success', 'Data Titimangsa berhasil disimpan.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->is_active) {
            return back()->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif.');
        }
        $academicYear->delete();
        return back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}

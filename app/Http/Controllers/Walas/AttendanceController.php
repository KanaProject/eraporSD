<?php

namespace App\Http\Controllers\Walas;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\HomeroomAssignment;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $academicYear = AcademicYear::getActive();

        if (!$academicYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $assignment = HomeroomAssignment::where('user_id', $user->id)
            ->where('academic_year_id', $academicYear->id)
            ->first();

        if (!$assignment) {
            return back()->with('error', 'Anda tidak ditugaskan sebagai wali kelas pada tahun ajaran ini.');
        }

        $classId = $assignment->school_class_id;

        // Valid months mapping for the academic year
        $months = [
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni'
        ];

        // Default to July (7) if no month is selected
        $selectedMonth = $request->get('month', 7);

        // Fetch students in this class
        $students = Student::where('school_class_id', $classId)
            ->orderBy('name')
            ->get();

        // Fetch existing attendances for the selected month
        $attendances = Attendance::where('academic_year_id', $academicYear->id)
            ->where('month', $selectedMonth)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('walas.attendances.index', compact(
            'academicYear',
            'assignment',
            'students',
            'months',
            'selectedMonth',
            'attendances'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $academicYear = AcademicYear::getActive();

        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'attendances' => 'required|array',
            'attendances.*.sakit' => 'required|integer|min:0',
            'attendances.*.izin' => 'required|integer|min:0',
            'attendances.*.alpa' => 'required|integer|min:0',
        ]);

        $month = $request->month;

        foreach ($request->attendances as $studentId => $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'academic_year_id' => $academicYear->id,
                    'month' => $month
                ],
                [
                    'sakit' => $data['sakit'] ?? 0,
                    'izin' => $data['izin'] ?? 0,
                    'alpa' => $data['alpa'] ?? 0,
                ]
            );
        }

        return redirect()->back()->with('success', 'Data absensi berhasil disimpan.');
    }
}

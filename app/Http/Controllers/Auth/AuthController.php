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

        $school = \App\Models\School::first();

        // Public aggregated stats — no sensitive names disclosed
        $totalClasses  = \App\Models\SchoolClass::count();
        $totalStudents = \App\Models\Student::where('is_active', true)->count();
        $totalTeachers = \App\Models\User::role('guru')->count();
        $activePeriod  = \App\Models\AssessmentPeriod::getActive();
        $activeYear    = \App\Models\AcademicYear::getActive();

        // Global grading progress per period
        $gradingProgress = 0;
        $reportProgress  = 0;
        $classBars       = [];

        if ($activePeriod) {
            $assignments = \App\Models\TeacherSubjectAssignment::where(
                'academic_year_id', $activeYear?->id
            )->with('schoolClass')->get();

            $totalA = $assignments->count();
            $doneA  = 0;
            foreach ($assignments as $a) {
                $studentIds   = $a->schoolClass->students()->where('is_active', true)->pluck('id');
                $total        = $studentIds->count();
                if ($total == 0) { $doneA++; continue; }
                $graded = \App\Models\Grade::where('assessment_period_id', $activePeriod->id)
                    ->where('subject_id', $a->subject_id)
                    ->whereIn('student_id', $studentIds)
                    ->where(fn($q) => $q->whereNotNull('uh1')->orWhereNotNull('uh2')
                        ->orWhereNotNull('ujian_teori')->orWhereNotNull('ujian_praktek'))
                    ->count();
                if ($graded >= $total) $doneA++;
            }
            $gradingProgress = $totalA > 0 ? round(($doneA / $totalA) * 100) : 0;

            // Report card progress
            $allStudentIds = \App\Models\Student::where('is_active', true)->pluck('id');
            $totalS  = $allStudentIds->count();
            $printed = \App\Models\ReportCardStatus::where('assessment_period_id', $activePeriod->id)
                ->whereIn('student_id', $allStudentIds)
                ->whereNotNull('generated_at')->count();
            $reportProgress = $totalS > 0 ? round(($printed / $totalS) * 100) : 0;

            // Per grade-level (1-6) grading progress
            for ($g = 1; $g <= 6; $g++) {
                $classIds    = \App\Models\SchoolClass::where('grade_level', $g)->pluck('id');
                $sids        = \App\Models\Student::whereIn('school_class_id', $classIds)->where('is_active', true)->pluck('id');
                $tot         = $sids->count();
                $done        = \App\Models\ReportCardStatus::where('assessment_period_id', $activePeriod->id)
                    ->whereIn('student_id', $sids)->whereNotNull('generated_at')->count();
                $classBars[] = ['level' => $g, 'pct' => $tot > 0 ? round(($done / $tot) * 100) : 0, 'done' => $done, 'total' => $tot];
            }
        }

        return view('auth.login', compact(
            'school', 'totalClasses', 'totalStudents', 'totalTeachers',
            'activePeriod', 'activeYear', 'gradingProgress', 'reportProgress', 'classBars'
        ));
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

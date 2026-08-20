<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Kurikulum;
use App\Http\Controllers\Guru;
use App\Http\Controllers\Walas;
use Illuminate\Support\Facades\Route;

// ─── Root Redirect ──────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ─── Authentication ──────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout',      [AuthController::class, 'logout'])->name('logout');
    Route::get('/role-select',  [AuthController::class, 'showRoleSelect'])->name('role.select');
    Route::post('/role-select', [AuthController::class, 'selectRole'])->name('role.select.post');
    Route::put('/profile',      [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// ─── Admin Portal ─────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // School Config
    Route::get('/school',        [Admin\SchoolConfigController::class, 'edit'])->name('school.edit');
    Route::put('/school',        [Admin\SchoolConfigController::class, 'update'])->name('school.update');

    // Curriculums
    Route::get('/curriculums',           [Admin\CurriculumController::class, 'index'])->name('curriculums.index');
    Route::post('/curriculums',          [Admin\CurriculumController::class, 'store'])->name('curriculums.store');
    Route::put('/curriculums/{curriculum}', [Admin\CurriculumController::class, 'update'])->name('curriculums.update');
    Route::delete('/curriculums/{curriculum}', [Admin\CurriculumController::class, 'destroy'])->name('curriculums.destroy');

    // Academic Years & Periods
    Route::get('/academic-years',          [Admin\AcademicYearController::class, 'index'])->name('academic-years.index');
    Route::get('/academic-years/create',   [Admin\AcademicYearController::class, 'create'])->name('academic-years.create');
    Route::post('/academic-years',         [Admin\AcademicYearController::class, 'store'])->name('academic-years.store');
    Route::post('/academic-years/{academicYear}/activate', [Admin\AcademicYearController::class, 'setActive'])->name('academic-years.activate');
    Route::post('/assessment-periods/{period}/activate',   [Admin\AcademicYearController::class, 'setPeriodActive'])->name('periods.activate');
    Route::put('/assessment-periods/{period}',             [Admin\AcademicYearController::class, 'updatePeriod'])->name('periods.update');
    Route::delete('/academic-years/{academicYear}',        [Admin\AcademicYearController::class, 'destroy'])->name('academic-years.destroy');

    // Users
    Route::get('/users',                 [Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/template',        [Admin\UserManagementController::class, 'downloadTemplate'])->name('users.template');
    Route::get('/users/import',          fn() => redirect()->route('admin.users.index'));
    Route::post('/users/import',         [Admin\UserManagementController::class, 'import'])->name('users.import');
    Route::get('/users/create',          [Admin\UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users',                [Admin\UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit',     [Admin\UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',          [Admin\UserManagementController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/reset-password', [Admin\UserManagementController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{user}/toggle',  [Admin\UserManagementController::class, 'toggleActive'])->name('users.toggle');

    // Homeroom Assignments
    Route::get('/homeroom',              [Admin\UserManagementController::class, 'homeroomIndex'])->name('homeroom.index');
    Route::post('/homeroom',             [Admin\UserManagementController::class, 'homeroomAssign'])->name('homeroom.assign');

    // Classes
    Route::get('/classes',               [Admin\SchoolClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/create',        [Admin\SchoolClassController::class, 'create'])->name('classes.create');
    Route::post('/classes',              [Admin\SchoolClassController::class, 'store'])->name('classes.store');
    Route::delete('/classes/{class}',    [Admin\SchoolClassController::class, 'destroy'])->name('classes.destroy');

    // Students
    Route::get('/students',              [Admin\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/template',     [Admin\StudentController::class, 'downloadTemplate'])->name('students.template');
    Route::post('/students/import',      [Admin\StudentController::class, 'import'])->name('students.import');
    Route::post('/students',             [Admin\StudentController::class, 'store'])->name('students.store');
    Route::put('/students/{student}',    [Admin\StudentController::class, 'update'])->name('students.update');
    Route::post('/students/{student}/toggle', [Admin\StudentController::class, 'toggleActive'])->name('students.toggle');
    Route::delete('/students/{student}', [Admin\StudentController::class, 'destroy'])->name('students.destroy');
});

// ─── Kurikulum Portal ─────────────────────────────────────────────────────────
Route::prefix('kurikulum')->name('kurikulum.')->middleware(['auth', 'role:kurikulum'])->group(function () {
    Route::get('/', [Kurikulum\DashboardController::class, 'index'])->name('dashboard');

    // Subject Grade Configs (KKM + Bobot)
    Route::get('/configs',  [Kurikulum\SubjectGradeConfigController::class, 'index'])->name('configs.index');
    Route::post('/configs', [Kurikulum\SubjectGradeConfigController::class, 'update'])->name('configs.update');

    // Subjects
    Route::get('/subjects',           [Kurikulum\SubjectGradeConfigController::class, 'subjects'])->name('subjects.index');
    Route::post('/subjects',          [Kurikulum\SubjectGradeConfigController::class, 'storeSubject'])->name('subjects.store');
    Route::put('/subjects/{subject}', [Kurikulum\SubjectGradeConfigController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [Kurikulum\SubjectGradeConfigController::class, 'destroySubject'])->name('subjects.destroy');

    // Teacher-Subject Assignments
    Route::get('/assignments',         [Kurikulum\TeacherSubjectController::class, 'index'])->name('assignments.index');
    Route::post('/assignments',        [Kurikulum\TeacherSubjectController::class, 'assign'])->name('assignments.assign');
    Route::delete('/assignments/{assignment}', [Kurikulum\TeacherSubjectController::class, 'remove'])->name('assignments.remove');
});

// ─── Guru Portal ─────────────────────────────────────────────────────────────
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/', [Guru\DashboardController::class, 'index'])->name('dashboard');

    // Grade input
    Route::get('/grades',       [Guru\GradeInputController::class, 'index'])->name('grades.index');
    Route::get('/grades/input', [Guru\GradeInputController::class, 'input'])->name('grades.input');
    Route::post('/grades/save', [Guru\GradeInputController::class, 'save'])->name('grades.save');
});

// ─── Walas Portal ─────────────────────────────────────────────────────────────
Route::prefix('walas')->name('walas.')->middleware(['auth', 'role:walas'])->group(function () {
    Route::get('/', [Walas\DashboardController::class, 'index'])->name('dashboard');



    // Attendance
    Route::get('/attendances', [Walas\AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/attendances', [Walas\AttendanceController::class, 'store'])->name('attendances.store');

    // Homeroom Notes
    Route::get('/notes',  [Walas\HomeroomNoteController::class, 'index'])->name('notes.index');
    Route::post('/notes', [Walas\HomeroomNoteController::class, 'save'])->name('notes.save');

    // Ledger
    Route::get('/ledger', [Walas\LedgerController::class, 'index'])->name('ledger.index');

    // Report Cards
    Route::get('/report-cards',          [Walas\ReportCardController::class, 'index'])->name('report-cards.index');
    Route::get('/report-cards/{student_id}/{period_id}/preview', [Walas\ReportCardController::class, 'preview'])->name('report-cards.preview');
    Route::post('/report-cards/generate', [Walas\ReportCardController::class, 'generate'])->name('report-cards.generate');
    Route::post('/report-cards/generate-all', [Walas\ReportCardController::class, 'generateAll'])->name('report-cards.generate-all');
});

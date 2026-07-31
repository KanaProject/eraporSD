<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\HomeroomAssignment;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        $status = $request->status ?? 'aktif';
        if ($status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($status === 'nonaktif') {
            $query->where('is_active', false);
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('username', 'like', '%'.$request->search.'%');
        }
        $users = $query->orderBy('name')->paginate(20)->withQueryString();
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['kurikulum', 'guru', 'walas'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'nip'      => 'nullable|string|max:30',
            'phone'    => 'nullable|string|max:20',
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,name',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'nip'       => $request->nip,
            'phone'     => $request->phone,
            'password'  => Hash::make('12345678'),
            'is_active' => true,
        ]);
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', "Akun {$user->name} berhasil dibuat. Password default: 12345678");
    }

    public function edit(User $user)
    {
        $roles = Role::whereIn('name', ['kurikulum', 'guru', 'walas'])->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,'.$user->id,
            'nip'      => 'nullable|string|max:30',
            'phone'    => 'nullable|string|max:20',
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,name',
        ]);

        $user->update($request->only('name', 'username', 'nip', 'phone'));
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', "Akun {$user->name} berhasil diperbarui.");
    }

    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('12345678')]);
        return back()->with('success', "Password {$user->name} direset ke: 12345678");
    }

    public function toggleActive(User $user)
    {
        if ($user->hasRole('admin') && $user->is_active) {
            return back()->with('error', 'Akun admin tidak dapat dinonaktifkan.');
        }
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    // Homeroom assignment
    public function homeroomIndex()
    {
        $activeYear  = AcademicYear::getActive();
        $classes     = SchoolClass::with(['homeroomAssignments' => fn($q) => $q->where('academic_year_id', $activeYear?->id)->with(['user', 'companion'])])->orderBy('grade_level')->orderBy('section')->get()->groupBy('grade_level');
        $walasUsers  = User::role('walas')->where('is_active', true)->orderBy('name')->get();
        $guruUsers   = User::role('guru')->where('is_active', true)->orderBy('name')->get();
        return view('admin.homeroom.index', compact('classes', 'walasUsers', 'guruUsers', 'activeYear'));
    }

    public function homeroomAssign(Request $request)
    {
        $request->validate([
            'school_class_id'  => 'required|exists:school_classes,id',
            'user_id'          => 'required|exists:users,id',
            'companion_id'     => 'nullable|exists:users,id|different:user_id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        HomeroomAssignment::updateOrCreate(
            ['school_class_id' => $request->school_class_id, 'academic_year_id' => $request->academic_year_id],
            ['user_id' => $request->user_id, 'companion_id' => $request->companion_id]
        );

        return back()->with('success', 'Wali kelas & Pendamping berhasil ditetapkan.');
    }

    public function downloadTemplate()
    {
        $writer = SimpleExcelWriter::streamDownload('Template_Import_Pengguna.xlsx');

        $writer->addHeader([
            'Nama Lengkap',
            'Username',
            'NIP',
            'No HP',
            'Peran (guru/walas/kurikulum)',
        ]);

        $writer->addRow([
            'Nama Lengkap'                 => 'Budi Santoso, S.Pd',
            'Username'                     => 'budi.santoso',
            'NIP'                          => '198001012005011001',
            'No HP'                        => '081234567890',
            'Peran (guru/walas/kurikulum)' => 'guru',
        ]);
        $writer->addRow([
            'Nama Lengkap'                 => 'Siti Rahayu, S.Pd',
            'Username'                     => 'siti.rahayu',
            'NIP'                          => '',
            'No HP'                        => '082345678901',
            'Peran (guru/walas/kurikulum)' => 'walas',
        ]);

        return $writer->toBrowser();
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|max:10240']);

        $file = $request->file('file');

        $importedCount = 0;
        $skippedCount  = 0;
        $errorCount    = 0;

        set_time_limit(300);

        SimpleExcelReader::create($file->path(), $file->getClientOriginalExtension())
            ->getRows()->each(function (array $row) use (&$importedCount, &$skippedCount, &$errorCount) {
            $name     = $row['Nama Lengkap'] ?? null;
            $username = $row['Username'] ?? null;
            $roleRaw  = strtolower(trim($row['Peran (guru/walas/kurikulum)'] ?? ''));

            Log::info('User import row:', $row);

            if (!$name || !$username) {
                Log::warning('Skipped: missing name or username', compact('name', 'username'));
                $skippedCount++;
                return;
            }

            $validRoles = ['guru', 'walas', 'kurikulum'];
            if (!in_array($roleRaw, $validRoles)) {
                Log::warning('Skipped: invalid role', ['role' => $roleRaw]);
                $skippedCount++;
                return;
            }

            $existing = User::where('username', $username)->first();
            if ($existing) {
                $skippedCount++;
                return;
            }

            try {
                $user = User::create([
                    'name'      => $name,
                    'username'  => $username,
                    'nip'       => $row['NIP'] ?? null,
                    'phone'     => $row['No HP'] ?? null,
                    'password'  => Hash::make('12345678'),
                    'is_active' => true,
                ]);
                $user->assignRole($roleRaw);
                $importedCount++;
            } catch (\Exception $e) {
                Log::error('User import error: ' . $e->getMessage());
                $errorCount++;
            }
        });

        $msg = "Import selesai: {$importedCount} pengguna berhasil ditambahkan";
        if ($skippedCount) $msg .= ", {$skippedCount} dilewati (duplikat/tidak valid)";
        if ($errorCount)   $msg .= ", {$errorCount} gagal";
        $msg .= ". Password default: 12345678";

        return back()->with('success', $msg);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('schoolClass');
        
        $status = $request->status ?? 'aktif';
        if ($status === 'nonaktif') {
            $query->where('is_active', false);
        } elseif ($status === 'aktif') {
            $query->where('is_active', true);
        }

        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('nis', 'like', '%'.$request->search.'%');
            });
        }

        $students = $query->orderBy('name')->paginate(25)->withQueryString();
        $classes  = SchoolClass::orderBy('grade_level')->orderBy('section')->get();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function downloadTemplate()
    {
        $writer = SimpleExcelWriter::streamDownload('Template_Import_Siswa.xlsx');
        
        $writer->addHeader([
            'Nama Lengkap',
            'NIS',
            'NISN',
            'Jenis Kelamin (L/P)',
            'ID Kelas',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Agama',
            'Nama Orang Tua',
            'No HP Orang Tua',
            'Alamat'
        ]);

        $writer->addRow([
            'Nama Lengkap' => 'Ahmad Fulan',
            'NIS' => '2026001',
            'NISN' => '0123456789',
            'Jenis Kelamin (L/P)' => 'L',
            'ID Kelas' => '1',
            'Tempat Lahir' => 'Jakarta',
            'Tanggal Lahir (YYYY-MM-DD)' => '2015-08-17',
            'Agama' => 'Islam',
            'Nama Orang Tua' => 'Budi Santoso',
            'No HP Orang Tua' => '081234567890',
            'Alamat' => 'Jl. Merdeka No. 1, Jakarta'
        ]);

        return $writer->toBrowser();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'nis'             => 'required|string|max:20|unique:students,nis',
            'nisn'            => 'nullable|string|max:20',
            'gender'          => 'nullable|in:L,P',
            'birth_place'     => 'nullable|string|max:100',
            'birth_date'      => 'nullable|date',
            'religion'        => 'nullable|string|max:20',
            'parent_name'     => 'nullable|string|max:255',
            'parent_phone'    => 'nullable|string|max:20',
        ]);

        Student::create($validated);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ]);

        set_time_limit(300); // 5 minutes for bulk upload
        $file = $request->file('file');
        
        $importedCount = 0;
        $skippedCount = 0;

        SimpleExcelReader::create($file->path(), $file->getClientOriginalExtension())
            ->getRows()
            ->each(function(array $row) use (&$importedCount, &$skippedCount) {
                $nis = $row['NIS'] ?? null;
                $name = $row['Nama Lengkap'] ?? null;
                
                \Illuminate\Support\Facades\Log::info('Parsed row:', $row);

                if (!$name || !$nis) {
                    \Illuminate\Support\Facades\Log::warning('Skipped row due to missing name or NIS', ['name' => $name, 'nis' => $nis]);
                    return; // Skip if no name or NIS
                }
                
                $existing = Student::where('nis', $nis)->first();
                if ($existing) {
                    if (!$existing->is_active) {
                        $genderRaw = $row['Jenis Kelamin (L/P)'] ?? '';
                        $gender = in_array(strtoupper($genderRaw), ['L', 'P']) ? strtoupper($genderRaw) : null;
                        
                        $existing->update([
                            'name' => $name,
                            'nisn' => $row['NISN'] ?? null,
                            'gender' => $gender,
                            'school_class_id' => !empty($row['ID Kelas']) ? $row['ID Kelas'] : null,
                            'birth_place' => $row['Tempat Lahir'] ?? null,
                            'birth_date' => !empty($row['Tanggal Lahir (YYYY-MM-DD)']) ? $row['Tanggal Lahir (YYYY-MM-DD)'] : null,
                            'religion' => $row['Agama'] ?? null,
                            'parent_name' => $row['Nama Orang Tua'] ?? null,
                            'parent_phone' => $row['No HP Orang Tua'] ?? null,
                            'address' => $row['Alamat'] ?? null,
                            'is_active' => true,
                        ]);
                        $importedCount++;
                    } else {
                        $skippedCount++;
                    }
                    return;
                }

                $genderRaw = $row['Jenis Kelamin (L/P)'] ?? '';
                $gender = in_array(strtoupper($genderRaw), ['L', 'P']) ? strtoupper($genderRaw) : null;

                Student::create([
                    'name' => $name,
                    'nis' => $nis,
                    'nisn' => $row['NISN'] ?? null,
                    'gender' => $gender,
                    'school_class_id' => !empty($row['ID Kelas']) ? $row['ID Kelas'] : null,
                    'birth_place' => $row['Tempat Lahir'] ?? null,
                    'birth_date' => !empty($row['Tanggal Lahir (YYYY-MM-DD)']) ? $row['Tanggal Lahir (YYYY-MM-DD)'] : null,
                    'religion' => $row['Agama'] ?? null,
                    'parent_name' => $row['Nama Orang Tua'] ?? null,
                    'parent_phone' => $row['No HP Orang Tua'] ?? null,
                    'address' => $row['Alamat'] ?? null,
                    'is_active' => true,
                ]);

                $importedCount++;
            });

        return back()->with('success', "Import selesai. Berhasil: $importedCount, Dilewati (dobel): $skippedCount.");
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'nis'             => 'required|string|max:20|unique:students,nis,'.$student->id,
            'nisn'            => 'nullable|string|max:20',
            'gender'          => 'nullable|in:L,P',
            'birth_place'     => 'nullable|string|max:100',
            'birth_date'      => 'nullable|date',
            'religion'        => 'nullable|string|max:20',
            'parent_name'     => 'nullable|string|max:255',
            'parent_phone'    => 'nullable|string|max:20',
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->update(['is_active' => false]);
        return back()->with('success', 'Siswa berhasil dinonaktifkan.');
    }

    public function toggleActive(Student $student)
    {
        $student->update(['is_active' => !$student->is_active]);
        $status = $student->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Siswa berhasil $status.");
    }
}

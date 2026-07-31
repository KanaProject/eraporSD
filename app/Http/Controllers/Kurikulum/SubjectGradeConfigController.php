<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use Illuminate\Http\Request;

class SubjectGradeConfigController extends Controller
{
    public function index(Request $request)
    {
        $gradeLevel = $request->get('grade', 1);
        
        // Get all master subjects
        $subjects = Subject::where('is_active', true)
                           ->orderBy('group')
                           ->orderBy('sort_order')
                           ->get();
                           
        // Get existing configs for this grade level mapped by subject_id
        $configs = SubjectGradeConfig::where('grade_level', $gradeLevel)
                                     ->get()
                                     ->keyBy('subject_id');

        return view('kurikulum.configs.index', compact('subjects', 'configs', 'gradeLevel'));
    }

    public function update(Request $request)
    {
        $gradeLevel = $request->input('grade_level');
        $request->validate([
            'grade_level'               => 'required|integer|between:1,6',
            'subject_ids'               => 'nullable|array',
            'subject_ids.*'             => 'exists:subjects,id',
            'configs'                   => 'nullable|array',
            'configs.*.kkm'             => 'required_with:configs|integer|min:0|max:100',
            'configs.*.bobot_uh'        => 'required_with:configs|integer|min:0|max:100',
            'configs.*.bobot_teori'     => 'required_with:configs|integer|min:0|max:100',
        ]);

        $submittedSubjectIds = [];

        if ($request->has('configs')) {
            foreach ($request->configs as $subjectId => $data) {
                // Validate bobot totals 100
                if (abs((float)$data['bobot_uh'] + (float)$data['bobot_teori'] - 100) > 0.01) {
                    return back()->with('error', "Bobot UH + Bobot Teori harus totalnya 100%. Periksa konfigurasi mapel.");
                }
                
                SubjectGradeConfig::updateOrCreate(
                    ['subject_id' => $subjectId, 'grade_level' => $gradeLevel],
                    [
                        'kkm'         => $data['kkm'],
                        'bobot_uh'    => $data['bobot_uh'],
                        'bobot_teori' => $data['bobot_teori'],
                    ]
                );
                
                $submittedSubjectIds[] = $subjectId;
            }
        }

        // Remove configs that were unchecked (not in the submitted list)
        SubjectGradeConfig::where('grade_level', $gradeLevel)
                          ->whereNotIn('subject_id', $submittedSubjectIds)
                          ->delete();

        return back()->with('success', 'Konfigurasi nilai & alokasi mapel berhasil disimpan.');
    }


    public function subjects(Request $request)
    {
        $mainSubjects = Subject::where('is_active', true)
                           ->whereNull('parent_id')
                           ->with(['children' => function($q) {
                               $q->where('is_active', true)->orderBy('sort_order');
                           }])
                           ->orderBy('group')
                           ->orderBy('sort_order')
                           ->get();

        $subjects = collect();
        foreach ($mainSubjects as $main) {
            $subjects->push($main);
            foreach ($main->children as $child) {
                $subjects->push($child);
            }
        }
        // Get potential parents (main subjects)
        $parents = Subject::where('is_active', true)->whereNull('parent_id')->orderBy('name')->get();
        return view('kurikulum.subjects.index', compact('subjects', 'parents'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:20',
            'group'       => 'required|string|max:100',
            'parent_id'   => 'nullable|exists:subjects,id',
            'sort_order'  => 'nullable|integer',
        ]);
        Subject::create($request->only('name', 'code', 'group', 'parent_id', 'sort_order') + ['is_active' => true]);
        return redirect()->route('kurikulum.subjects.index')
                         ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function updateSubject(Request $request, Subject $subject)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:20',
            'group'       => 'required|string|max:100',
            'parent_id'   => 'nullable|exists:subjects,id',
            'sort_order'  => 'nullable|integer',
        ]);
        $subject->update($request->only('name', 'code', 'group', 'parent_id', 'sort_order'));
        return redirect()->route('kurikulum.subjects.index')
                         ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroySubject(Subject $subject)
    {
        $subject->update(['is_active' => false]);
        return back()->with('success', 'Mata pelajaran dinonaktifkan.');
    }
}

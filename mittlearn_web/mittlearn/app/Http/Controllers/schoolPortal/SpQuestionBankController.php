<?php

namespace App\Http\Controllers\schoolPortal;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Medium;
use App\Models\QuestionBank;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TestPaperQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SpQuestionBankController extends Controller
{
    public $data = [];
    public function questionBank(Request $request)
    {
        try {
            $role     = getUserRoles();
            $parentId = Auth::id();
            $board    = getUserBoard();
            $medium   = getUserMedium();

            // Fetch Classes & Subjects
            $this->data['classes'] = getUserSchoolClasses(Auth::id());
            $this->data['subjects'] = Subject::where('is_active', 1)->pluck('name', 'id');
            $this->data['questionTypes'] = QuestionType::where('is_active', 1)->pluck('name', 'slug');
            // Filters from Request
            $classFilter   = $request->input('class');
            $subjectFilter = $request->input('subject');
            $questionTypeFilter = $request->input('question_type');
            $queryFilter   = $request->input('query');

            $query = QuestionBank::with('class', 'subject');

            // Role-wise Query Conditions
            if ($role === 'school_teacher') {
                $parentId   = Auth::user()->userAdditionalDetail->school_id;
                $classIds   = getTeacherClasses(Auth::id(), $parentId);
                $subjectIds = getTeacherSubject(Auth::id(), $parentId);

                // Fixed: Properly group teacher conditions
                $query->where(function ($q) use ($parentId, $classIds, $subjectIds) {
                    $q->where('school_id', $parentId)
                        ->whereIn('class_id', $classIds)
                        ->whereIn('subject_id', $subjectIds)
                        ->orWhere('created_by', Auth::id());
                });
            } else {
                $assignedClasses = SchoolAssignedClass::where('school_id', Auth::id())->pluck('class_id')->toArray();

                // Fixed: Properly group non-teacher conditions
                $query->where(function ($q) use ($board, $medium, $parentId, $assignedClasses) {
                    $q->where(function ($q1) use ($parentId, $board, $medium, $assignedClasses) {
                        $q1->where('school_id', $parentId)
                            ->whereIn('board_id', [$board, 0])
                            ->whereIn('medium_id', [$medium, 0])
                            ->whereIn('class_id', $assignedClasses);
                    })->orWhere(function ($q2) use ($board, $medium, $assignedClasses) {
                        $q2->whereNull('school_id')
                            ->whereIn('board_id', [$board, 0])
                            ->whereIn('medium_id', [$medium, 0])
                            ->whereIn('class_id', $assignedClasses);
                    })->orWhere('created_by', Auth::id());
                });
            }

            // Apply Optional Filters - these will now work correctly
            if ($classFilter) {
                $query->where('class_id', $classFilter);
            }

            if ($subjectFilter) {
                $query->where('subject_id', $subjectFilter);
            }
            if ($questionTypeFilter) {
                $query->where('question_type', $questionTypeFilter);
            }

            if ($queryFilter) {
                $query->where('question', 'like', '%' . $queryFilter . '%');
            }

            $query->orderByDesc('created_at');

            // AJAX Request
            if ($request->ajax()) {
                $questions = $query->get();
                return view('schoolPortal.tpg.question-bank', compact('questions'))->with($this->data);
            }

            // Normal Request
            $this->data['questions'] = $query->paginate(config('constants.PAGINATION.default'));
            return view('schoolPortal.tpg.question-bank', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }



    public function questionEdit($id)
    {
        $this->data['question'] = QuestionBank::with('options')->findOrFail($id);

        return view('schoolPortal.tpg.create-question-bank', $this->data);
    }
    public function questionDelete($id)
    {
        $data = QuestionBank::where('id', $id)->first();
        TestPaperQuestion::where('question_id', $id)->delete();
        $options = QuestionOption::where('question_id', $data->id)->first();
        if ($options) {
            $options->delete();
        }
        $data->delete();
        return redirect()->route('sp.question.bank')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }
    public function createQuestionBank(Request $request)
    {
        try {
            $schoolClassId = SchoolAssignedClass::where('school_id', Auth::id())->pluck('class_id')->toArray();
            $this->data['classes'] = SchoolClass::whereIn('id', $schoolClassId)->pluck('name', 'id');
            $this->data['subjects'] = Subject::pluck('name', 'id');

            return view('schoolPortal.tpg.create-question-bank', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}

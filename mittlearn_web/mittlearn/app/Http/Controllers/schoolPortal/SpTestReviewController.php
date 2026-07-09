<?php

namespace App\Http\Controllers\schoolPortal;

use App\Http\Controllers\Controller;
use App\Livewire\TestPaper;
use App\Models\CourseChapter;
use App\Models\QuestionOption;
use App\Models\TestAnswer;
use App\Models\TestPaper as ModelsTestPaper;
use App\Models\TestPaperResult;
use App\Models\TestParticipent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpTestReviewController extends Controller
{
    public $data = [];
    public function indexView(Request $request)
    {
        try {
            $role     = getUserRoles();
            $parentId = Auth::id();

            if ($role === 'school_teacher') {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }

            $attemptedTests = TestParticipent::where('school_id', $parentId)
                ->distinct('test_id')
                ->pluck('test_id');

            $query = ModelsTestPaper::with('board', 'medium', 'Class', 'Subject')
                ->where('school_id', $parentId)
                ->whereIn('id', $attemptedTests);

            if ($role === 'school_teacher') {
                $assignedClassIds = getTeacherAssignedClasses();
                $assignedSubjectIds = getTeacherAssignedSubjects();

                $query->whereIn('class_id', $assignedClassIds)
                    ->whereIn('subject_id', $assignedSubjectIds)
                    ->where('created_by', Auth::id());
            }

            $this->data['tests'] = $query->paginate(config('constants.PAGINATION.default'));

            $this->data['tests']->getCollection()->transform(function ($data) {
                $chapters = explode(',', $data->chapter_id);
                $data->chapter_names = CourseChapter::whereIn('id', $chapters)
                    ->pluck('chapter_name')
                    ->toArray();
                return $data;
            });

            return view('schoolPortal.tpg.test-review', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }


    public function tpAssignedUsersView(Request $request, $testId)
    {
        try {
            $this->data['test'] = ModelsTestPaper::with('board', 'medium', 'Class', 'Subject')->where('id', $testId)->first();
            $this->data['attemptedTestUsers'] = TestParticipent::with('user')
                ->where('test_id', $testId)
                ->get();
            return view('schoolPortal.tpg.test-assigned-users', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function tpRemark($id, $user_id, $test_id)
    {
        try {
            $testId = $test_id;
            $userId = $user_id;
            $this->data['questionsList'] = TestAnswer::with('questionBank')->where('test_id', $testId)->where('user_id', $userId)->get();
            $this->data['testPaper'] = ModelsTestPaper::where('id', $testId)->with(['Class', 'Subject', 'series', 'questionCount', 'user'])->first();
            $this->data['testId'] = $testId;
            $this->data['userDetails'] = User::with('studentDetails')->where('id', $user_id)->first();
            return view('schoolPortal.tpg.test-paper-remark', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    // Subjective Score Save
    public function saveSubjectiveScore(Request $request)
    {
        $role     = getUserRoles();
        $parentId = Auth::id();
        if ($role === 'school_teacher') {
            $parentId   = Auth::user()->userAdditionalDetail->school_id;
        }
        $maxMarks = $request->questionMarks;
        $enteredScore = $request->score;

        if ($enteredScore > $maxMarks) {
            return response()->json(['message' => 'Entered score cannot be greater than Question mark.'], 422);
        }

        // Save score
        TestAnswer::where('test_id', $request->test_id)
            ->where('school_id', $parentId)
            ->where('question_id', $request->question_id)
            ->where('user_id', $request->userId)
            ->whereNull('sub_index')
            ->update(['score' => $enteredScore, 'is_checked' => 1]);

        // Now calculate result
        $this->calculateTestResult($request->test_id, $request->userId);

        return response()->json(['message' => 'Subjective score saved successfully.']);
    }

    public function savePassageScores(Request $request)
    {
        $totalScore = array_sum($request->scores);
        $questionMarks = $request->question_marks;

        if ($totalScore > $questionMarks) {
            return response()->json(['message' => 'Total score exceeds allowed marks.'], 422);
        }

        foreach ($request->scores as $answerId => $score) {
            TestAnswer::where('id', $answerId)->update(['score' => $score, 'is_checked' => 1]);
        }

        // Now calculate result
        $this->calculateTestResult($request->test_id, $request->user_id);

        return response()->json(['message' => 'Passage scores saved successfully.']);
    }

    private function calculateTestResult($testId, $userId)
    {
        $testPaper = ModelsTestPaper::find($testId);
        $testParticipent = TestParticipent::where('test_id', $testId)
            ->where('user_id', $userId)
            ->first();

        if (!$testPaper || !$testParticipent) {
            return; // Skip if data missing
        }


        // Fetch obtained marks from TestAnswer table
        $obtainedMarks = TestAnswer::where('test_id', $testId)
            ->where('user_id', $userId)
            ->sum('score');

        // Fetch result record first
        $testResult = TestPaperResult::where('test_id', $testId)
            ->where('user_id', $userId)
            ->first();

        if (!$testResult) {
            return; // Skip if result record doesn't exist
        }

        $percentage = $testResult->total_marks > 0
            ? round(($obtainedMarks / $testResult->total_marks) * 100, 2)
            : 0;

        $result = ($percentage >= $testPaper->min_passing_percentage) ? 'Pass' : 'Fail';

        // Update fields
        $testResult->obtained_marks = $obtainedMarks;
        $testResult->obtained_percentage = $percentage;
        $testResult->result = $result;
        $testResult->save();
    }

    public function tpReview($id, $user_id, $test_id)
    {
        try {
            $testId = $test_id;
            $userId = $user_id;
            $this->data['questionsList'] = TestAnswer::with('questionBank')->where('test_id', $testId)->where('user_id', $userId)->get();
            $this->data['testPaper'] = ModelsTestPaper::where('id', $testId)->with(['Class', 'Subject', 'series', 'questionCount', 'user'])->first();
            $this->data['testId'] = $testId;
            return view('schoolPortal.tpg.test-paper-review', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}

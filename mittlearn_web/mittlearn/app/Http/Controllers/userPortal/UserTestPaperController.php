<?php

namespace App\Http\Controllers\userPortal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\FileController;
use App\Models\TestPaper;
use App\Models\StudentDetails;
use App\Models\TestPaperQuestion;
use App\Models\TestParticipent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTestPaperController extends Controller
{
    public $data     = [];
    public $res      = [];
    public $coreCtrl = '';
    public $fileCtrl = '';
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
        $this->fileCtrl = FileController::class;
    }

    public function testPaperList(Request $request)
    {
        $userId      = Auth::id();
        $userClass   = StudentDetails::where('user_id', $userId)->value('class');
        $now         = now()->format('Y-m-d H:i:s');
        $isActiveTab = $request->query('isActiveTab', 'ongoingTest');

        $tests = TestParticipent::with(['testPaper', 'testPaper.subject', 'testPaperQuestions', 'result'])
            ->where('user_id', $userId)
            ->where('class_id', $userClass)
            ->whereHas('testPaper')
            ->orderBy('id', 'desc')
            ->get()
            ->filter(fn($test) => $test->testPaperQuestions && $test->testPaperQuestions->count() > 0);
        // Ongoing Tests: Time active and not yet attempted
        $this->data['ongoingTests'] = $tests->filter(
            fn($test) =>
            $test->testPaper->start_date_time <= $now &&
                $test->testPaper->end_date_time >= $now &&
                !$test->is_attempted
        );

        // Upcoming Tests: Test hasn't started yet
        $this->data['upcomingTests'] = $tests->filter(
            fn($test) =>
            $test->testPaper->start_date_time > $now
        );

        // Completed Tests: User has attempted, ignore test timing
        $this->data['completedTests'] = $tests->filter(
            fn($test) => $test->is_attempted
        );

        // Expired Tests: Time ended AND user did NOT attempt
        $this->data['expiredTests'] = $tests->filter(
            fn($test) =>
            $test->testPaper->end_date_time < $now &&
                !$test->is_attempted
        );
        // return $this->data;

        return view('userPortal.testPaper.test-papers', $this->data + ['isActiveTab' => $isActiveTab]);
    }

    public function testPaperQuestion($id)
    {
        $decodedTestId = $id;
        $testPaper = TestPaper::find($decodedTestId);
        return view('userPortal.testPaper.test-paper-question', compact('id', 'testPaper'));
    }
}

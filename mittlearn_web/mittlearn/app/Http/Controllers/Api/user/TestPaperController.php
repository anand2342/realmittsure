<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Api\BaseController;
use App\Models\QuestionBank;
use App\Models\QuestionOption;
use App\Models\StudentDetails;
use App\Models\TestAnswer;
use App\Models\TestPaper;
use App\Models\TestPaperQuestion;
use App\Models\TestPaperResult;
use App\Models\TestParticipent;
use App\Models\TrackUserVideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TestPaperController extends BaseController
{
    public $res = [];
    public $data = [];
    public function testPapers(Request $request)
    {
        try {
            $userId    = Auth::id();
            $userClass = StudentDetails::where('user_id', $userId)->value('class');
            $now       = now()->format('Y-m-d H:i:s');

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
            )->values(); // Reindex as array

            // Upcoming Tests: Test hasn't started yet
            $this->data['upcomingTests'] = $tests->filter(
                fn($test) =>
                $test->testPaper->start_date_time > $now
            )->values(); // Reindex as array

            // Completed Tests: User has attempted, ignore test timing
            $this->data['completedTests'] = $tests->filter(
                fn($test) => $test->is_attempted
            )->values(); // Reindex as array

            // Expired Tests: Time ended AND user did NOT attempt
            $this->data['expiredTests'] = $tests->filter(
                fn($test) =>
                $test->testPaper->end_date_time < $now &&
                    !$test->is_attempted
            )->values(); // Reindex as array

            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_ADD_SUCCESS'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }


    public function testPaperQuestion(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'test_id'         => 'required',
            ]);
            $this->data['testdPaper'] = TestPaper::with('Subject')->where('id', $request->test_id)->first();
            // dd($this->testPaper);
            $this->data['questions'] = TestPaperQuestion::with('Question', 'Question.options')
                ->where('paper_id', $request->test_id)
                ->get();



            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_ADD_SUCCESS'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }

    // public function testPaperSubmitAnswer(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'test_id'      => 'required',
    //             'question_id'  => 'required',
    //             'answer' => 'required',
    //         ]);

    //         $userId = Auth::id();

    //         $question = QuestionBank::with('options')->findOrFail($request->question_id);
    //         $selectedOption = $question->options->find($request->answer);

    //         if (!$selectedOption) {
    //             return $this->sendError('Invalid option selected.', [], 400);
    //         }

    //         $correctOption = $question->options->where('is_correct', 1)->first();
    //         $isCorrect = $correctOption && $correctOption->id == $selectedOption->id ? 1 : 0;

    //         TestAnswer::updateOrCreate(
    //             [
    //                 'test_id'     => $request->test_id,
    //                 'user_id'     => $userId,
    //                 'question_id' => $request->question_id,
    //             ],
    //             [
    //                 'answer'       => $selectedOption->id,
    //                 'valid_answer' => $correctOption ? $correctOption->id : null,
    //                 'is_correct'   => $isCorrect,
    //                 'score'        => $isCorrect ? $question->marks : 0,
    //             ]
    //         );


    //         return $this->sendSuccess([], 'Answer submitted successfully.');
    //     } catch (ValidationException $e) {
    //         return $this->sendError('Validation error', $e->errors(), 422);
    //     } catch (\Exception $e) {
    //         return $this->sendError('Server error', $e->getMessage(), 500);
    //     }
    // }
    public function testPaperSubmitAnswer(Request $request)
    {
        try {
            $request->validate([
                'test_id'     => 'required|exists:test_papers,id',
                'question_id' => 'required|exists:question_banks,id',
                'answer'      => 'required',
            ]);

            $userId = Auth::id();
            $schoolId = Auth::user()->studentDetails->school_id;

            $question = QuestionBank::with('options')->findOrFail($request->question_id);
            $answerInput = $request->answer;

            // SUBJECTIVE
            if (in_array($question->question_type, ['passage', 'short-answer-questions', 'long-answer-questions', 'one-word-answer'])) {
                if ($question->question_type === 'passage') {
                    $additionalData = json_decode($question->additional_data, true);
                    $questionsAndAnswers = $additionalData['questions_and_answers'] ?? [];
                    foreach ($questionsAndAnswers as $i => $qa) {
                        $userResponse = $answerInput[$i] ?? '';
                        TestAnswer::updateOrCreate(
                            [
                                'school_id'   => $schoolId,
                                'test_id'     => $request->test_id,
                                'user_id'     => $userId,
                                'question_id' => $question->id,
                                'sub_index'   => $i,
                            ],
                            [
                                'answer'       => $userResponse,
                                'valid_answer' => null,
                                'is_correct'   => 0,
                                'score'        => 0,
                            ]
                        );
                    }
                } else {
                    TestAnswer::updateOrCreate(
                        [
                            'school_id'   => $schoolId,
                            'test_id'     => $request->test_id,
                            'user_id'     => $userId,
                            'question_id' => $question->id,
                        ],
                        [
                            'answer'       => is_array($answerInput) ? json_encode($answerInput) : $answerInput,
                            'valid_answer' => null,
                            'is_correct'   => 0,
                            'score'        => 0,
                        ]
                    );
                }

                return $this->sendSuccess([], 'Subjective answer submitted successfully.');
            }

            // TRUE/FALSE
            if ($question->question_type === 't/f') {
                $correctOption = $question->options->where('is_correct', 1)->first();
                $validAnswer = $correctOption?->id;
                $isCorrect = $validAnswer == $answerInput ? 1 : 0;
                $score = $isCorrect ? $question->marks : 0;

                TestAnswer::updateOrCreate(
                    [
                        'school_id'   => $schoolId,
                        'test_id'     => $request->test_id,
                        'user_id'     => $userId,
                        'question_id' => $question->id,
                    ],
                    [
                        'answer'       => $answerInput,
                        'valid_answer' => $validAnswer,
                        'is_correct'   => $isCorrect,
                        'score'        => $score,
                        'is_checked'   => 1,
                    ]
                );

                return $this->sendSuccess([], 'True/False answer submitted successfully.');
            }

            // MCQ / Tick / Picture-based
            if (in_array($question->question_type, ['mcq', 'tick', 'picture-based-questions'])) {
                $selectedOptionIds = is_array($answerInput) ? array_keys(array_filter($answerInput)) : [$answerInput];
                $answerString = implode(',', $selectedOptionIds);

                $correctOptionIds = $question->options->where('is_correct', 1)->pluck('id')->toArray();
                $validAnswerString = implode(',', $correctOptionIds);

                sort($selectedOptionIds);
                sort($correctOptionIds);

                $isCorrect = ($selectedOptionIds == $correctOptionIds) ? 1 : 0;
                $score = $isCorrect ? $question->marks : 0;

                TestAnswer::updateOrCreate(
                    [
                        'school_id'   => $schoolId,
                        'test_id'     => $request->test_id,
                        'user_id'     => $userId,
                        'question_id' => $question->id,
                    ],
                    [
                        'answer'       => $answerString,
                        'valid_answer' => $validAnswerString,
                        'is_correct'   => $isCorrect,
                        'score'        => $score,
                        'is_checked'   => 1,
                    ]
                );

                return $this->sendSuccess([], 'MCQ/Tick answer submitted successfully.');
            }

            return $this->sendError('Unsupported question type.', [], 400);
        } catch (ValidationException $e) {
            return $this->sendError('Validation error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Server error', $e->getMessage(), 500);
        }
    }



    public function testPaperSubmit(Request $request)
    {
        try {
            $userId = Auth::id();
            $testId = $request->input('test_id');
            $userAnswers = $request->input('user_answers');

            $testPaper = TestPaper::with('questionCount')->findOrFail($testId);

            if (!$testPaper) {
                return $this->sendError('Test Paper not found', [], 404);
            }

            $questions = $testPaper->questionCount;
            // dd($questions);
            $totalMarks = 0;
            foreach ($questions as $testPaperQuestion) {
                $question = $testPaperQuestion->question;
                $totalMarks += $question->marks;
            }

            $obtainedMarks = 0;

            foreach ($userAnswers as $questionId => $selectedOptionId) {
                $testPaperQuestion = $testPaper->questionCount->where('question_id', $questionId)->first();

                if (!$testPaperQuestion || !$testPaperQuestion->question) {
                    continue; // skip invalid question IDs
                }

                $question = $testPaperQuestion->question;
                $correctOption = $question->options->where('is_correct', 1)->first();
                $isCorrect = $correctOption && $correctOption->id == $selectedOptionId ? 1 : 0;
                $score = $isCorrect ? $question->marks : 0;
                $obtainedMarks += $score;

                TestAnswer::updateOrCreate(
                    [
                        'test_id' => $testId,
                        'user_id' => $userId,
                        'question_id' => $questionId,
                    ],
                    [
                        'answer' => $selectedOptionId,
                        'valid_answer' => $correctOption ? $correctOption->id : null,
                        'is_correct' => $isCorrect,
                        'score' => $score,
                    ]
                );
            }

            // foreach ($userAnswers as $questionId => $selectedOptionId) {
            //     $testPaperQuestion = $testPaper->questionCount->where('question_id', $questionId)->first();
            //     $question = $testPaperQuestion->question;
            //     $correctOption = $question->options->where('is_correct', 1)->first();
            //     $isCorrect = $correctOption && $correctOption->id == $selectedOptionId ? 1 : 0;
            //     $score = $isCorrect ? $question->marks : 0;
            //     $obtainedMarks += $score;
            //     TestAnswer::updateOrCreate(
            //         [
            //             'test_id' => $testId,
            //             'user_id' => $userId,
            //             'question_id' => $questionId,
            //         ],
            //         [
            //             'answer' => $selectedOptionId,
            //             'valid_answer' => $correctOption ? $correctOption->id : null,
            //             'is_correct' => $isCorrect,
            //             'score' => $score,
            //         ]
            //     );
            // }

            $percentage = ($totalMarks > 0) ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;
            $result = ($percentage >= 40) ? 'Pass' : 'Fail';

            $this->data['submittedTest'] = TestPaperResult::create([
                'test_id' => $testId,
                'user_id' => $userId,
                'total_questions' => count($questions),
                'total_attemted_questions' => count($userAnswers),
                'total_marks' => $totalMarks,
                'obtained_marks' => $obtainedMarks,
                'obtained_percentage' => $percentage,
                'result' => $result,
                'status' => '1',
            ]);
            $testParticipent = TestParticipent::where('test_id', $testId)
                ->where('user_id', $userId)
                ->firstOrFail();
            $testParticipent->update(['is_attempted' => '1']);

            return $this->sendSuccess($this->data, 'Test submitted successfully.');
        } catch (ValidationException $e) {
            return $this->sendError('Validation error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Server error', $e, 500);
        }
    }
}

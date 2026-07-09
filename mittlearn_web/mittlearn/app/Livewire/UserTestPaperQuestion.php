<?php

namespace App\Livewire;

use App\Models\QuestionBank;
use App\Models\TestAnswer;
use App\Models\TestPaper;
use App\Models\TestPaperQuestion;
use App\Models\TestPaperResult;
use App\Models\TestParticipent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class UserTestPaperQuestion extends Component
{
    public $currentQuestionIndex = 0;
    public $testPaper, $questions, $currentQuestion, $remainingSeconds, $errorMessage;
    public $userAnswers = [];
    public $submittedAnswers = [];
    public $isModalOpen = false;
    public function mount($testId)
    {
        $decodedTestId = $testId;
        $this->testPaper = TestPaper::with('Subject')->find($decodedTestId);

        // Get session key per test + user
        $sessionKey = 'test_timer_' . $this->testPaper->id . '_' . auth()->id();

        if (session()->has($sessionKey)) {
            $this->remainingSeconds = session()->get($sessionKey);
        } else {
            $this->remainingSeconds = $this->testPaper->duration * 60;
            session()->put($sessionKey, $this->remainingSeconds);
        }

        // Load questions as usual
        $query = TestPaperQuestion::with('Question', 'Question.options')
            ->where('paper_id', $decodedTestId);

        if ($this->testPaper->question_order_type === 'shuffled') {
            $this->questions = $query->inRandomOrder()->get();
        } else {
            $this->questions = $query->orderBy('id')->get();
        }

        if ($this->questions->isNotEmpty()) {
            $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
        }
        $this->loadPreviousAnswers();
    }

    public function updateRemainingSeconds($seconds)
    {
        $this->remainingSeconds = $seconds;

        $sessionKey = 'test_timer_' . $this->testPaper->id . '_' . auth()->id();
        session()->put($sessionKey, $seconds);

        if ($seconds <= 0) {
            $this->handleTimeOver();
        }
    }

    public function handleTimeOver()
    {
        // When Time Over handle handleTimeOver
        session()->flash('message', 'Time is over. Test submitted automatically.');
        return redirect()->route('up.test.paper.list', ['isActiveTab' => 'completedTest']);
    }

    public function loadPreviousAnswers()
    {
        $answers = TestAnswer::where('test_id', $this->testPaper->id)
            ->where('user_id', Auth::id())
            ->get();

        foreach ($answers as $answer) {
            $this->submittedAnswers[$answer->question_id] = json_decode($answer->answer, true) ?? $answer->answer;
        }
    }
    public function nextQuestion()
    {
        if (!isset($this->userAnswers[$this->currentQuestion->question->id])) {
            $this->errorMessage = 'Please select or enter an answer.';
            return;
        }

        $this->submitAnswer();

        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
            $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
            $this->errorMessage = '';
        } else {
            // All questions answered, show modal
            $this->isModalOpen = true;
        }
    }
    public function prevQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
        }
    }
    public function submitAnswer()
    {
        $userId = Auth::id();
        $question = $this->currentQuestion->question;
        $questionId = $question->id;
        $answerInput = $this->userAnswers[$questionId] ?? null;
        $schoolId = Auth::user()->studentDetails->school_id;
        if ($answerInput === null) return;

        // SUBJECTIVE QUESTIONS
        if (in_array($question->question_type, ['passage', 'short-answer-questions', 'long-answer-questions', 'one-word-answer'])) {
            // Handle passage sub-questions
            if ($question->question_type === 'passage') {
                $additionalData = json_decode($question->additional_data, true);
                $questionsAndAnswers = $additionalData['questions_and_answers'] ?? [];

                foreach ($questionsAndAnswers as $i => $qa) {
                    $userResponse = $answerInput[$i] ?? '';
                    TestAnswer::updateOrCreate(
                        [
                            'school_id' => $schoolId,
                            'test_id' => $this->testPaper->id,
                            'user_id' => $userId,
                            'question_id' => $questionId,
                            'sub_index' => $i,
                        ],
                        [
                            'answer' => $userResponse,
                            'valid_answer' => null,
                            'is_correct' => 0,
                            'score' => 0,
                        ]
                    );

                    if (!isset($this->submittedAnswers[$question->id]) || !is_array($this->submittedAnswers[$question->id])) {
                        $this->submittedAnswers[$question->id] = [];
                    }

                    $this->submittedAnswers[$question->id][$i] = $userResponse;
                }
            } else {
                // Single answer input for short/long/one-word
                TestAnswer::updateOrCreate(
                    [
                        'school_id' => $schoolId,
                        'test_id' => $this->testPaper->id,
                        'user_id' => $userId,
                        'question_id' => $questionId,
                    ],
                    [
                        'answer' => is_array($answerInput) ? json_encode($answerInput) : $answerInput,
                        'valid_answer' => null,
                        'is_correct' => 0,
                        'score' => 0,
                    ]
                );

                $this->submittedAnswers[$questionId] = $answerInput;
            }

            return;
        }

        // T/F QUESTIONS
        if ($question->question_type === 't/f') {
            $correctOption = $question->options->where('is_correct', 1)->first();
            $validAnswer = $correctOption?->id;
            $isCorrect = $validAnswer == $answerInput ? 1 : 0;
            $score = $isCorrect ? $question->marks : 0;

            TestAnswer::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'test_id' => $this->testPaper->id,
                    'user_id' => $userId,
                    'question_id' => $questionId,
                ],
                [
                    'answer' => $answerInput,
                    'valid_answer' => $validAnswer,
                    'is_correct' => $isCorrect,
                    'score' => $score,
                    'is_checked' => 1
                ]
            );

            $this->submittedAnswers[$questionId] = $answerInput;
            return;
        }

        // MCQ / TICK MULTI-SELECTION QUESTIONS
        if (in_array($question->question_type, ['mcq', 'tick', 'picture-based-questions'])) {
            // Convert selected options to comma-separated string
            $selectedOptionIds = array_keys(array_filter($answerInput)); // keys of selected checkboxes
            $answerString = implode(',', $selectedOptionIds);

            // Get all correct option IDs
            $correctOptionIds = $question->options->where('is_correct', 1)->pluck('id')->toArray();
            $validAnswerString = implode(',', $correctOptionIds);

            // Score calculation (simple match: all selected must match correct)
            sort($selectedOptionIds);
            sort($correctOptionIds);
            $isCorrect = ($selectedOptionIds == $correctOptionIds) ? 1 : 0;
            $score = $isCorrect ? $question->marks : 0;

            TestAnswer::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'test_id' => $this->testPaper->id,
                    'user_id' => $userId,
                    'question_id' => $questionId,
                ],
                [
                    'answer' => $answerString,
                    'valid_answer' => $validAnswerString,
                    'is_correct' => $isCorrect,
                    'score' => $score,
                    'is_checked' => 1
                ]
            );

            $this->submittedAnswers[$questionId] = $selectedOptionIds;
            return;
        }
    }

    public function submitTest()
    {
        $userId = Auth::id();
        $testId = $this->testPaper->id;

        // Remove test timer
        session()->forget('test_timer_' . $testId . '_' . $userId);
        session()->forget('testStarted');

        $testParticipent = TestParticipent::where('test_id', $testId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // 1. Get all unique answered question IDs
        $answeredQuestionIds = TestAnswer::where('test_id', $testId)
            ->where('user_id', $userId)
            ->distinct()
            ->pluck('question_id');

        $totalAttempted = $answeredQuestionIds->count();

        // 2. Get total marks by summing question->marks for each answered question
        $totalMarks = QuestionBank::whereIn('id', $answeredQuestionIds)
            ->sum('marks');

        // 3. Get obtained marks from scores in answer table
        $obtainedMarks = TestAnswer::where('test_id', $testId)
            ->where('user_id', $userId)
            ->sum('score');

        // 4. Calculate percentage
        $percentage = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;

        // 5. Determine Pass/Fail based on test's min_passing_percentage
        $result = ($percentage >= $this->testPaper->min_passing_percentage) ? 'Pass' : 'Fail';

        // 6. Save result
        TestPaperResult::create([
            'test_id' => $testId,
            'user_id' => $userId,
            'school_id' => $testParticipent->school_id,
            'total_questions' => $this->questions->count(), // All loaded questions
            'min_passing_percentage' => $testParticipent->min_passing_percentage,
            'total_attemted_questions' => $totalAttempted,
            'total_marks' => $totalMarks,
            'obtained_marks' => $obtainedMarks,
            'obtained_percentage' => $percentage,
            'result' => $result,
            'status' => '1',
        ]);

        // 7. Mark participant as attempted
        $testParticipent->update(['is_attempted' => '1']);

        // 8. Redirect to completed test list
        return redirect()->route('up.test.paper.list', ['isActiveTab' => 'completedTest']);
    }

    public function render()
    {
        return view('livewire.user-test-paper-question');
    }
}

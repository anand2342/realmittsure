<?php

namespace App\Livewire;

use App\Models\BookSeries;
use App\Models\QuestionBank;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\Test;
use App\Models\TestPaper as ModelsTestPaper;
use App\Models\TestPaperQuestion;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TestPaper extends Component
{
    public $id, $question_types, $question, $question_type, $description, $marks, $difficulty_level, $testPaperId;
    public $one_word_answer;
    public $answers = [];
    public $tfoptions = [];
    public $passage = [];
    public $matchTheFollowing = [];
    public $status;
    public $correct;
    public $testPaperData;
    public $pictureBasedQuestion = [];
    public $paragraph_statement;
    public $paragraph;
    public $options = [['option' => '', 'correct' => false]];
    public function mount($testPaperId)
    {
        $this->id = $testPaperId;
        $this->question_types = QuestionType::where('is_active', 1)->pluck('name', 'slug')->toArray();
    }

    public function questionTypeChanged()
    {
        if ($this->question_type === 'picture-based-questions' || $this->question_type === 'mcq' || $this->question_type === 'tick') {
            $this->pictureBasedQuestion = [];
            $this->addTextEditorOption();
        } elseif ($this->question_type === 't/f') {
            $this->tfoptions = [];
            $this->addTfOption();
        } elseif ($this->question_type === 'passage') {
            $this->passage = [];
            $this->addPassage();
        } elseif ($this->question_type === 'one-word-answer') {
            $this->one_word_answer = null;
        } elseif ($this->question_type === 'match-the-following') {
            $this->matchTheFollowing = [];
            for ($i = 0; $i < 8; $i++) {
                $this->matchTheFollowing[] = ['option_match' => '', 'is_correct' => false];
            }
        }
    }
    public function rules()
    {
        switch ($this->question_type) {
            case 'passage':
                return $this->passageRules();

            case 't/f':
                return $this->trueFalseRules();

            case 'tick':
            case 'picture-based-questions':
            case 'mcq':
                return $this->pictureBasedRules();

            case 'match-the-following':
                return $this->matchTheFollowingRules();

            default:
                return $this->defaultRules();
        }
    }
    protected function passageRules(): array
    {
        return [
            'paragraph_statement' => 'required',
            'paragraph' => 'required',
            'passage' => 'required|array|min:1',
            'passage.*.questions' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (trim(strip_tags($value)) === '') {
                        $fail('The question cannot be empty.');
                    }
                }
            ],
            'passage.*.answer' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (trim(strip_tags($value)) === '') {
                        $fail('The answer cannot be empty.');
                    }
                }
            ]
        ];
    }
    protected function trueFalseRules(): array
    {

        return [
            'tfoptions' => 'required|array|min:2', // ✅ Ensures at least 2 options
            'tfoptions.*.option' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (trim(strip_tags($value)) === '') {
                        $fail('The option cannot be empty.');
                    }
                }
            ],
            'correct' => 'required',
        ];
    }

    protected function pictureBasedRules(): array
    {
        return [
            'pictureBasedQuestion' => [
                'required',
                'array',
                'min:2',
                function ($attribute, $value, $fail) {
                    $correctCount = collect($value)->where('correct', true)->count();

                    if ($correctCount === 0) {
                        $fail('At least one option must be marked as correct.');
                    }
                }
            ],
            'pictureBasedQuestion.*.option' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (trim(strip_tags($value)) === '') {
                        $fail('Option content cannot be empty.');
                    }
                }
            ],
            'pictureBasedQuestion.*.correct' => 'required|boolean'
        ];
    }
    protected function matchTheFollowingRules(): array
    {
        return [
            // LEFT & RIGHT OPTIONS
            'matchTheFollowing' => 'required|array|size:8',
            'matchTheFollowing.*.option_match' => [
                'required',
                function ($attribute, $value, $fail) {
                    $text = strip_tags($value, '<img>');
                    if (trim($text) === '') {
                        $fail('The option cannot be empty.');
                    }
                }

            ],

            // ANSWERS FOR LEFT OPTIONS
            'answers' => [
                'required',
                'array',
                'size:4',
                function ($attribute, $value, $fail) {
                    $correctAnswers = collect($value)->pluck('correct');
                    if ($correctAnswers->unique()->count() !== $correctAnswers->count()) {
                        $fail('Each answer must be unique.');
                    }
                }
            ],
            'answers.*.correct' => 'required|integer|between:1,4'
        ];
    }

    protected function defaultRules(): array
    {
        return [
            'question' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (trim(strip_tags($value)) === '') {
                        $fail('The question cannot be empty.');
                    }
                }
            ]
        ];
    }
    protected function commonRules(): array
    {
        return [
            'question_type'    => 'required',
            'question'         => 'required',
            // 'description'      => 'required',
            'marks'            => 'required|numeric|min:1|max:100',
            'status'           => 'required|boolean',
            'difficulty_level' => 'required|in:easy,medium,hard',
        ];
    }
    public function messages(): array
    {
        return [
            // ✅ Common fields
            'question.required' => 'Please enter a question.',
            // 'description.required' => 'Please provide a description.',
            'marks.required' => 'Please enter the marks for this question.',
            'status.required' => 'Please select the status.',
            'difficulty_level.required' => 'Please choose a difficulty level.',

            // ✅ Passage
            'paragraph_statement.required' => 'Please enter a paragraph statement.',
            'paragraph.required' => 'Please enter the paragraph content.',
            'passage.required' => 'Please add at least one question in the passage.',
            'passage.*.questions.required' => 'Each passage question must have a question.',
            'passage.*.answer.required' => 'Each passage question must have an answer.',

            // ✅ True/False
            'tfoptions.required' => 'Please provide at least two options for the True/False question.',
            'tfoptions.min' => 'At least two True/False options are required.',
            'tfoptions.*.option.required' => 'Each option must have text.',
            'correct.required' => 'Please mark the correct option.',

            // ✅ MCQ / Picture Based
            'pictureBasedQuestion.required' => 'Please provide at least two options.',
            'pictureBasedQuestion.min' => 'At least two options are required.',
            'pictureBasedQuestion.*.option.required' => 'Each option must have text.',
            'pictureBasedQuestion.*.correct.required' => 'Mark at least one option as correct.',

            // ✅ Match the Following
            'matchTheFollowing.required' => 'Please provide 8 match-the-following options (4 pairs).',
            'matchTheFollowing.size' => 'Exactly 8 match-the-following options are required.',
            'matchTheFollowing.*.option_match.required' => 'Each match option must have text.',
            'answers.required' => 'Please provide matching answers.',
            'answers.size' => 'You must provide 4 matching answers.',
            'answers.*.correct.required' => 'Each answer must be linked to a right-side option.',
            'answers.*.correct.between' => 'Each answer must be between 1 and 4.',
        ];
    }

    public function save($id = null)
    {
        // dd($this->matchTheFollowing);

        $validatedData = Validator::make(
            $this->all(),
            array_merge($this->commonRules(), $this->rules()),
            $this->messages()
        )->validate();

        try {
            $testPaperData = ModelsTestPaper::find($this->testPaperId);

            if (!$testPaperData) {
                session()->flash('error', 'No test paper data available');
                return;
            }


            $data = [
                'board_id' => $testPaperData->board_id,
                'medium_id' => $testPaperData->medium_id,
                'series_id' => $testPaperData->series_id,
                'class_id' => $testPaperData->class_id,
                'subject_id' => $testPaperData->subject_id,
                'chapter_id' =>  $testPaperData->chapter_id,
                'question_type'    => $this->question_type,
                'question'         => $this->question,
                'description'      => $this->description ?? '',
                'marks'            => $this->marks,
                'is_active'        => $this->status,
                'difficulty_level' => $this->difficulty_level,
                'created_by'       => Auth::id(),
            ];



            $additionalData = [];
            // Handle passage question
            if ($this->question_type === 'passage') {
                $additionalData['paragraph_statement'] = $this->paragraph_statement;
                $additionalData['paragraph'] = $this->paragraph;
                $additionalData['questions_and_answers'] = collect($this->passage)->map(function ($item) {
                    return [
                        'question' => $item['questions'],
                        'answer' => $item['answer'],
                    ];
                })->toArray();
            }

            $data['additional_data'] = json_encode($additionalData);

            // Save or update question
            $question_bank = QuestionBank::create($data);


            // Handle options by type
            if (in_array($this->question_type, ['t/f'])) {
                foreach ($this->tfoptions as $index => $option) {
                    $question_bank->options()->updateOrCreate(
                        ['id' => $option['id'] ?? null],
                        [
                            'question_id' => $question_bank->id,
                            'option_text' => $option['option'],
                            'is_correct'  => $index === $this->correct ? 1 : 0,
                        ]
                    );
                }
            }

            if (in_array($this->question_type, ['picture-based-questions', 'mcq', 'tick'])) {
                foreach ($this->pictureBasedQuestion as $option) {
                    $question_bank->options()->updateOrCreate(
                        ['id' => $option['id'] ?? null],
                        [
                            'question_id' => $question_bank->id,
                            'option_text' => $option['option'],
                            'is_correct'  => $option['correct'] ? 1 : 0,
                        ]
                    );
                }
            }

            if ($this->question_type === 'match-the-following') {
                $existingOptions = $question_bank->options;

                $leftOptions = [];
                foreach (array_slice($this->matchTheFollowing, 0, 4) as $index => $option) {
                    $leftOptions[] = $question_bank->options()->updateOrCreate(
                        ['id' => $existingOptions->get($index)->id ?? null],
                        [
                            'option_text' => strip_tags($option['option_match']),
                            'is_correct'  => false,
                        ]
                    );
                }

                $rightOptions = [];
                foreach (array_slice($this->matchTheFollowing, 4, 4) as $index => $option) {
                    $rightOptions[] = $question_bank->options()->updateOrCreate(
                        ['id' => $existingOptions->get($index + 4)->id ?? null],
                        [
                            'option_text' => strip_tags($option['option_match']),
                            'is_correct'  => false,
                        ]
                    );
                }

                foreach ($this->answers as $index => $answer) {
                    if (isset($answer['correct'])) {
                        $left = $leftOptions[$index] ?? null;
                        $right = $rightOptions[$answer['correct'] - 1] ?? null;

                        if ($left && $right) {
                            $left->update(['is_correct' => $right->id]);
                            $right->update(['is_correct' => $left->id]);
                        }
                    }
                }
            }
            TestPaperQuestion::create(
                [
                    'paper_id' => $testPaperData->id,
                    'question_id' => $question_bank->id,
                ],

            );

            return redirect()->route('question-bank.index')->with([
                'success' => config('constants.FLASH_REC_ADD_1')
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => config('constants.FLASH_TRY_CATCH')
            ]);
        }
    }

    public function addPassage()
    {
        $this->passage[] = ['questions' => '', 'answer' => ''];
    }
    public function removePassage($index)
    {
        if (isset($this->passage[$index]['id'])) {
            $rowId = $this->passage[$index]['id'];
            QuestionOption::find($rowId)?->delete();
        }
        unset($this->passage[$index]);
        $this->passage = array_values($this->passage);
    }
    public function addTfOption()
    {
        $this->tfoptions[] = ['option' => '', 'correct' => false];
    }
    public function setCorrectOption($index)
    {
        $this->correct = $index;
        foreach ($this->tfoptions as $i => &$option) {
            $option['correct'] = ($i == $index);
        }
    }
    public function addTextEditorOption()
    {
        $this->pictureBasedQuestion[] = ['option' => '', 'correct' => false];
    }
    public function removeTextEditor($index)
    {
        if (isset($this->pictureBasedQuestion[$index]['id'])) {
            $rowId = $this->pictureBasedQuestion[$index]['id'];
            QuestionOption::find($rowId)?->delete();
        }
        unset($this->pictureBasedQuestion[$index]);
        $this->pictureBasedQuestion = array_values($this->pictureBasedQuestion);
    }
    public function removeTfOption($index)
    {
        if (isset($this->tfoptions[$index]['id'])) {
            $rowId = $this->tfoptions[$index]['id'];
            QuestionOption::find($rowId)?->delete();
        }
        unset($this->tfoptions[$index]);
        $this->tfoptions = array_values($this->tfoptions);
    }
    public function render()
    {
        return view('livewire.test-paper');
    }
}

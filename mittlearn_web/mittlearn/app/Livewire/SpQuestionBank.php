<?php

namespace App\Livewire;

use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\Medium;
use App\Models\QuestionBank as ModelsQuestionBank;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\Subject;

use App\Models\Test;
use App\Models\TestPaper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Illuminate\Validation\Rule;

class SpQuestionBank extends Component
{
    public $id, $board_id, $medium_id, $medium, $board, $series_id, $class_id, $subject_id, $question_types, $question_type,  $boards, $mediums, $bookSeries, $classes, $subjects, $chapters = [], $chapter_ids = [], $chapter_id = [], $question, $description, $marks, $difficulty_level, $questions;
    public $answer;
    public $answers = [];
    public $one_word_answer;
    public $pictureBasedQuestion = [];
    public $passage = [];
    public $matchTheFollowing = [];
    public $correct;
    public $paragraph_statement;
    public $paragraph;
    public $tfoptions = [];
    public $status;
    public $classSubjectsMapping = [];
    public $selectedChapters = [];
    public function mount($question = null)
    {
        $role                   = getUserRoles();
        $parentId               = Auth::id();
        if ($role === 'school_teacher') {
            $parentId = Auth::user()->userAdditionalDetail->school_id;
        }
        $schoolAssignedSeries = SchoolAssignedDigitalContent::where('school_id', $parentId)
            ->pluck('series_id')->filter()->unique()->values()->toArray();

        if ($question) {
            $this->board = getUserBoard();
            $this->medium = getUserMedium();
            $this->id = $question->id;
            $this->board_id = $question->board_id;
            $this->medium_id = $question->medium_id;
            $this->class_id = $question->class_id;
            $this->subject_id = $question->subject_id;
            $this->question_type = $question->question_type;
            $this->question = $question->question;
            $this->description = $question->description;
            $this->marks = $question->marks;
            $this->status = $question->is_active;
            $this->difficulty_level = $question->difficulty_level;

            $this->answer = $question->answer;
            $this->one_word_answer = $question->one_word_answer;


            if ($this->question_type === 'match-the-following') {
                $savedOptions = $question->options;
                $this->matchTheFollowing = array_fill(0, 8, ['option_match' => '', 'is_correct' => false]);

                foreach ($savedOptions->take(4) as $index => $option) {
                    $this->matchTheFollowing[$index]['option_match'] = $option->option_text;
                }
                foreach ($savedOptions->slice(4) as $index => $option) {
                    $this->matchTheFollowing[$index]['option_match'] = $option->option_text;
                }
                foreach ($savedOptions->take(4) as $index => $option) {
                    $rightOptionId = $option->is_correct;
                    $rightOptionIndex = $savedOptions->search(function ($item) use ($rightOptionId) {
                        return $item->id === $rightOptionId;
                    });

                    $this->answers[$index] = ['correct' => $rightOptionIndex - 3];
                }
            }


            $additionalData = json_decode($question->additional_data, true);
            $this->paragraph = $additionalData['paragraph'] ?? '';
            $this->paragraph_statement = $additionalData['paragraph_statement'] ?? '';
            $this->passage = [];

            if (isset($additionalData['questions_and_answers']) && is_array($additionalData['questions_and_answers'])) {
                foreach ($additionalData['questions_and_answers'] as $index => $qa) {
                    $this->passage[] = [
                        'id' => $index,
                        'questions' => $qa['question'] ?? '',
                        'answer' => $qa['answer'] ?? '',
                    ];
                }
            }
            $this->pictureBasedQuestion = $question->options->map(function ($option) {
                return [
                    'id' => $option->id,
                    'option' => $option->option_text,
                    'correct' => $option->is_correct == 1
                ];
            })->toArray();
            $this->tfoptions = $question->options->map(function ($option, $index) {
                if ($option->is_correct == 1) {
                    $this->correct = $index;
                }
                return [
                    'id' => $option->id,
                    'option' => $option->option_text,
                    'correct' => $option->is_correct == 1,
                ];
            })->toArray();
        } else {
            $this->chapter_ids = [];
            $this->chapter_id = [];
            $this->selectedChapters = [];
            $this->question_type = null;
            $this->question = null;
            $this->description = null;
            $this->marks = null;
            $this->status = null;
            $this->difficulty_level = null;
            $this->answer = null;
            $this->one_word_answer = null;
            $this->pictureBasedQuestion = [];
            $this->passage = [];
            $this->matchTheFollowing = array_fill(0, 8, [
                'option_match' => '', // Left option
                'option1' => '',     // Right option
            ]);
            $this->tfoptions = null;
        }

        $this->boards = Board::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->mediums = Medium::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->board = getUserBoard();
        $this->medium = getUserMedium();
        $this->classes = Classes::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->subjects = Subject::where('is_active', 1)->pluck('name', 'id')->toArray();
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

    public function removeTextEditor($index)
    {
        if (isset($this->pictureBasedQuestion[$index]['id'])) {
            $rowId = $this->pictureBasedQuestion[$index]['id'];
            QuestionOption::find($rowId)?->delete();
        }
        unset($this->pictureBasedQuestion[$index]);
        $this->pictureBasedQuestion = array_values($this->pictureBasedQuestion);
    }
    public function addTextEditorOption()
    {
        $this->pictureBasedQuestion[] = ['option' => '', 'correct' => false];
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

    public function addPassage()
    {
        $this->passage[] = ['questions' => '', 'answer' => ''];
    }

    public function addTfOption()
    {
        $this->tfoptions[] = ['option' => '', 'correct' => false];
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


    public function setCorrectOption($index)
    {
        $this->correct = $index;

        // Update all options to set correct to false except the selected one
        foreach ($this->tfoptions as $i => &$option) {
            $option['correct'] = ($i == $index);
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
                    if (trim(strip_tags($value, '<img>')) === '') {
                        $fail('The question cannot be empty.');
                    }
                }
            ],
            'passage.*.answer' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (trim(strip_tags($value, '<img>')) === '') {
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
                    if (trim(strip_tags($value, '<img>')) === '') {
                        $fail('The option cannot be empty.');
                    }
                }
            ],
            'correct' => 'required'
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
                    if (trim(strip_tags($value, '<img>')) === '') {
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
                    if (trim(strip_tags($value, '<img>')) === '') {
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
                    if (trim(strip_tags($value, '<img>')) === '') {
                        $fail('The question cannot be empty.');
                    }
                }
            ]
        ];
    }
    protected function commonRules(): array
    {
        return [
            'class_id'         => 'required',
            'subject_id'       => 'required',
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
            'question.required' => 'Please enter a question.',
            // 'description.required' => 'Please provide a description.',
            'marks.required' => 'Please enter the marks for this question.',
            'status.required' => 'Please select the status.',
            'difficulty_level.required' => 'Please choose a difficulty level.',

            'paragraph_statement.required' => 'Please enter a paragraph statement.',
            'paragraph.required' => 'Please enter the paragraph content.',
            'passage.required' => 'Please add at least one question in the passage.',
            'passage.*.questions.required' => 'Each passage question must have a question.',
            'passage.*.answer.required' => 'Each passage question must have an answer.',

            'tfoptions.required' => 'Please provide at least two options for the True/False question.',
            'tfoptions.min' => 'At least two True/False options are required.',
            'tfoptions.*.option.required' => 'Each option must have text.',
            'correct.required' => 'Please mark the correct option.',

            'pictureBasedQuestion.required' => 'Please provide at least two options.',
            'pictureBasedQuestion.min' => 'At least two options are required.',
            'pictureBasedQuestion.*.option.required' => 'Each option must have text.',
            'pictureBasedQuestion.*.correct.required' => 'Mark at least one option as correct.',

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
        // dd($this->chapter_id);
        if (empty($this->chapter_id)) {
            $this->chapter_id = $this->selectedChapters;
        } else {
            $this->chapter_id = $this->chapter_id;
        }
        $validatedData = Validator::make(
            $this->all(),
            array_merge($this->commonRules(), $this->rules()),
            $this->messages() // Include custom messages
        )->validate();

        try {
            // Validate all fields using your rules() method+

            $role                   = getUserRoles();
            $parentId               = Auth::id();
            $isApproved = 1;

            if ($role === 'school_teacher') {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
                $isApproved = 0;
            }

            // Prepare base question bank data
            $data = [
                'board_id'         => getUserBoard(),
                'medium_id'        => getUserMedium(),
                'school_id'        => $parentId,
                'class_id'         => $this->class_id,
                'subject_id'       => $this->subject_id,
                'question_type'    => $this->question_type,
                'question'         => $this->question,
                'description'      => $this->description,
                'marks'            => $this->marks,
                'is_active'        => $this->status,
                'difficulty_level' => $this->difficulty_level,
                'created_by'       => Auth::id(),
                'is_approved'       => $isApproved,
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
            $question_bank = ModelsQuestionBank::updateOrCreate(
                ['id' => $this->id],
                $data
            );

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

            return redirect()->route('sp.question.bank')->with([
                'success' => config('constants.FLASH_REC_ADD_1')
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => config('constants.FLASH_TRY_CATCH')
            ]);
        }
    }



    public function render()
    {
        return view('livewire.sp-question-bank');
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\Medium;
use App\Models\QuestionBank;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\Subject;
use App\Models\Test;
use App\Models\TestPaper;
use App\Models\TestPaperQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TestPaperGenController extends Controller
{
    public $data = [];

    public function index(Request $request)
    {
        try {
            $this->data['boards'] = Board::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['mediums'] = Medium::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['series'] = BookSeries::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['class'] = Classes::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['subject'] = Subject::where('is_active', '1')->pluck('name', 'id')->toArray();
            $query = TestPaper::with(['Class', 'Subject']);

            if ($request->filled('board_id')) {
                $query->where('board_id', 'like', '%' . $request->board_id . '%');
            }
            if ($request->filled('medium_id')) {
                $query->where('medium_id', 'like', '%' . $request->medium_id . '%');
            }
            if ($request->filled('series_id')) {
                $query->where('series_id', 'like', '%' . $request->series_id . '%');
            }
            if ($request->filled('class_id')) {
                $query->where('class_id', 'like', '%' . $request->class_id . '%');
            }
            if ($request->filled('subject_id')) {
                $query->where('subject_id', 'like', '%' . $request->subject_id . '%');
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $this->data['tests'] = $query->orderBy('id', 'DESC')->paginate(config('constants.PAGINATION.default'));

            foreach ($this->data['tests'] as $data) {
                $chapters = explode(',', $data->chapter_id);
                $data->chapter_names = CourseChapter::whereIn('id', $chapters)->pluck('chapter_name')->toArray();
            }
            return view('admin.testPaperGen.index', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function create()
    {
        try {
            $this->data['boards'] = Board::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['mediums'] = Medium::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['series'] = BookSeries::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['class'] = Classes::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['subject'] = Subject::where('is_active', '1')->pluck('name', 'id')->toArray();
            return view('admin.testPaperGen.add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function edit($id)
    {
        try {
            $this->data['data'] = TestPaper::find($id);
            $this->data['chapters'] = explode(',', $this->data['data']->chapter_id);
            $boardId   = $this->data['data']->board_id;
            $mediumId  = $this->data['data']->medium_id;
            $seriesId  = $this->data['data']->series_id;
            $classId   = $this->data['data']->class_id;
            $subjectId = $this->data['data']->subject_id;

            if ($seriesId && $classId && $subjectId) {
                $courses = Course::query();

                // Only apply board filter if board_id is not 0
                if ($boardId && $boardId != 0) {
                    $courses->whereHas('metadataValues', function ($query) use ($boardId) {
                        $query->where('field_name', 'board')
                            ->where('field_value', $boardId);
                    });
                }

                // Only apply medium filter if medium_id is not 0
                if ($mediumId && $mediumId != 0) {
                    $courses->whereHas('metadataValues', function ($query) use ($mediumId) {
                        $query->where('field_name', 'medium')
                            ->where('field_value', $mediumId);
                    });
                }

                // Always apply these 3 filters
                $courses->whereHas('metadataValues', function ($query) use ($seriesId) {
                    $query->where('field_name', 'series')
                        ->where('field_value', $seriesId);
                })->whereHas('metadataValues', function ($query) use ($classId) {
                    $query->where('field_name', 'class')
                        ->where('field_value', $classId);
                })->whereHas('metadataValues', function ($query) use ($subjectId) {
                    $query->where('field_name', 'subject')
                        ->where('field_value', $subjectId);
                });

                $courses = $courses->get();
            }


            if (isset($courses)) {
                $courseIds = $courses->pluck('id')->toArray(); // Extract the course IDs
                $this->data['selectedChapters'] = CourseChapter::whereIn('course_id', $courseIds)->pluck('chapter_name', 'id')->toArray();
            }
            $this->data['boards'] = Board::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['mediums'] = Medium::where('is_active', '1')->pluck('name', 'id')->toArray();
            if (
                $this->data['data']->board_id == 0 &&
                $this->data['data']->medium_id == 0
            ) {
                // Show all series
                $this->data['series'] = BookSeries::pluck('name', 'id')->toArray();
            } else {
                // Filter by board and medium
                $this->data['series'] = BookSeries::where('board_id', $this->data['data']->board_id)
                    ->where('medium_id', $this->data['data']->medium_id)
                    ->pluck('name', 'id')
                    ->toArray();
            }
            $seriesData = BookSeries::where('id', $this->data['data']->series_id)->first();
            if (!empty($seriesData)) {
                $classSubjects = json_decode($seriesData->class_subjects, true);

                $classIds = collect($classSubjects)->pluck('class_id')->toArray();

                $this->data['class'] = Classes::whereIn('id', $classIds)
                    ->pluck('name', 'id')
                    ->toArray();

                $allSubjectIds = collect($classSubjects)
                    ->pluck('subject_ids')
                    ->flatten()
                    ->unique()
                    ->values()
                    ->toArray();
                $this->data['subject'] = Subject::where('is_active', '1')
                    ->whereIn('id', $allSubjectIds)
                    ->pluck('name', 'id')
                    ->toArray();
            } else {
                $this->data['class'] = [];
                $this->data['subject'] = [];
            }



            return view('admin.testPaperGen.add', $this->data);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function save(Request $request)
    {
        $request->validate([
            'board_id' => 'required',
            'medium_id' => 'required',
            'series_id' => 'required',
            'class_id' => 'required',
            'subject_id' => 'required',
            'chapter_ids'   => 'required|array',
            'chapter_ids.*' => 'exists:course_chapters,id',
            'question_order_type' => 'required',
            'is_active' => 'required|boolean',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'duration' => 'required|numeric|min:1',
            'question_order_type' => 'required',
            'min_passing_percentage' => 'required|numeric|min:1|max:100',
        ]);
        try {
            $data = $request->except(['_token']);
            $success = $request->id > 0 ? config('constants.FLASH_REC_UPDATE_1') : config('constants.FLASH_REC_ADD_1');
            $error = $request->id > 0 ? config('constants.FLASH_REC_UPDATE_0') : config('constants.FLASH_REC_ADD_0');
            $data['created_by'] = Auth::id();
            $data['chapter_id'] = is_array($request->chapter_ids) ? implode(',', $request->chapter_ids) : null;
            $res = TestPaper::updateOrCreate(['id' => $request->id], $data);

            if ($res) {
                return redirect()->route('test-paper.index')->with(['success' => $success]);
            }
            return redirect()->back()->with(['error' => $error]);
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function delete($id)
    {
        try {
            $data = TestPaper::where('id', $id)->first();
            $data->delete();
            return redirect()->route('test-paper.index')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function questionBankCreate()
    {
        $this->data['question'] = null;

        return view('admin.testPaperGen.question-bank-add', $this->data);
    }

    public function questionEdit($id)
    {
        $this->data['question'] = QuestionBank::with('options')->findOrFail($id);

        return view('admin.testPaperGen.question-bank-add', $this->data);
    }

    public function questionDelete($id)
    {
        $data = QuestionBank::where('id', $id)->first();
        $options = QuestionOption::where('question_id', $data->id)->first();
        if ($options) {
            $options->delete();
        }
        $data->delete();
        return redirect()->route('question-bank.index')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }

    public function questionBankIndex(Request $request)
    {
        $this->data['boards'] = Board::where('is_active', '1')->pluck('name', 'id')->toArray();
        $this->data['mediums'] = Medium::where('is_active', '1')->pluck('name', 'id')->toArray();
        $this->data['series'] = BookSeries::where('is_active', '1')->pluck('name', 'id')->toArray();
        $this->data['class'] = Classes::where('is_active', '1')->pluck('name', 'id')->toArray();
        $this->data['subject'] = Subject::where('is_active', '1')->pluck('name', 'id')->toArray();
        $this->data['questionType'] = QuestionType::where('is_active', '1')->pluck('name', 'slug')->toArray();
        $query = QuestionBank::with('class', 'subject');

        if ($request->filled('question_type')) {
            $query->where('question_type', $request->question_type);
        }
        if ($request->filled('board_id')) {
            $query->where('board_id', $request->board_id);
        }
        if ($request->filled('medium_id')) {
            $query->where('medium_id', $request->medium_id);
        }
        if ($request->filled('series_id')) {
            $query->where('series_id', 'like', '%' . $request->series_id . '%');
        }
        if ($request->filled('class_id')) {
            $query->where('class_id', 'like', '%' . $request->class_id . '%');
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', 'like', '%' . $request->subject_id . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $this->data['questions'] = $query->orderBy('id', 'DESC')->paginate(config('constants.PAGINATION.default'));
        return view('admin.testPaperGen.question-bank-list', $this->data);
    }
    public function questionAdd($id)
    {
        $this->data['id'] = $id;
        return view('admin.testPaperGen.testPaperQuestions.add', $this->data);
    }
    public function questionSave(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        $request->validate([
            'question_type' => 'required',
            'difficulty_level' => 'required',
            'question' => 'required',
            'description' => 'required',
            'marks' => 'required',
        ]);
        try {

            $testPaperData = TestPaper::find($request->testPaperId);
            $data = [
                'board_id' => $testPaperData->board_id,
                'medium_id' => $testPaperData->medium_id,
                'series_id' => $testPaperData->series_id,
                'class_id' => $testPaperData->class_id,
                'subject_id' => $testPaperData->subject_id,
                'chapter_id' => $testPaperData->chapter_id,
                'question_type' => $request->question_type,
                'question' => $request->question,
                'description' => $request->description,
                'marks' => $request->marks,
                'difficulty_level' => $request->difficulty_level,
                'created_by' => Auth::id(),
                'is_active' => $request->is_active,
            ];
            $questionBank = QuestionBank::create($data);
            if ($questionBank && $testPaperData) {
                TestPaperQuestion::create(['paper_id' => $testPaperData->id, 'question_id' => $questionBank->id]);
            }
            if ($request->question_type === 'mcq') {
                foreach ($request->options as $index => $option) {
                    if (!empty($option['option'])) {
                        QuestionOption::create([
                            'question_id' => $questionBank->id,
                            'option_text' => $option['option'],
                            'is_correct' => isset($option['correct']) ? 1 : 0,
                        ]);
                    }
                }
            }
            if ($request->question_type === 't/f') {
                foreach ($request->tfoptions as $index => $option) {
                    $is_correct = ((string) $index === (string) $request->correct_option);
                    if (!empty($option['option'])) {
                        QuestionOption::create([
                            'question_id' => $questionBank->id,
                            'option_text' => $option['option'],
                            'is_correct' => $is_correct ? 1 : 0,
                        ]);
                    }
                }
            }
            if ($questionBank) {
                DB::commit();
                return redirect()->route('question-bank.index')->with(['success' => config('constants.FLASH_REC_ADD_1')]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }


    public function getSeries(Request $request)
    {
        $getSeries = BookSeries::where('is_active', 1);

        if ($request->board_id != 0) {
            $getSeries->where('board_id', $request->board_id);
        }

        if ($request->medium_id != 0) {
            $getSeries->where('medium_id', $request->medium_id);
        }

        $series = $getSeries->pluck('name', 'id'); // assign result to variable

        return response()->json($series);
    }


    public function quill()
    {
        return view('admin.testPaperGen.quill');
    }
}

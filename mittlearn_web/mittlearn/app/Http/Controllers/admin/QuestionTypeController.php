<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionType;
use Illuminate\Http\Request;

class QuestionTypeController extends Controller
{
    public function show()
    {
        $data = QuestionType::paginate(config('constants.PAGINATION.default'));
        return view('admin.questionType.index', ['data' => $data]);
    }

    public function edit($id)
    {
        $data = QuestionType::where('id', $id)->first();
        return view('admin.questionType.add', ['data' => $data]);
    }
    public function create()
    {
        return view('admin.questionType.add');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'is_online' => 'required|boolean',
        ], ['is_active.required' => 'Status field is required']);

        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error   = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error   = config('constants.FLASH_REC_ADD_0');
        }

        $data = $request->except(['_token', 'image']);

        // Generate slug only if creating a new record
        if (! $request->id) {
            $data['slug'] = generateUniqueSlug($request->name, QuestionType::class, 'slug');
        }

        $res = QuestionType::updateOrCreate(['id' => $request->id], $data);

        if ($res) {
            return redirect()->route('question-type.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }

    public function delete($id)
    {
        $data = QuestionType::where('id', $id)->first();
        $data->delete();
        return redirect()->route('question-type.index')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }
}

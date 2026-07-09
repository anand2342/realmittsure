<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CourseMetadataValue;
use App\Models\LessonNumber;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function lessonNumberShow(Request $request,)
    {
        $data = LessonNumber::paginate(config('constants.PAGINATION.default'));
        return view('admin.lessonNumber.index', ['data' => $data]);
    }

    public function editLessonNumber($id)
    {
        $data = LessonNumber::where('id', $id)->first();
        return view('admin.lessonNumber.add', ['data' => $data]);
    }
    public function createLessonNumber()
    {
        return view('admin.lessonNumber.add');
    }

    public function lessonNumberSave(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric',
            'is_active' => 'required|boolean',
        ], ['is_active.required' => 'Status field is required']);
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }
        $res = LessonNumber::updateOrCreate(['id' => $request->id],   ['number' => $request->number, 'is_active' => $request->is_active]);
        if ($res) {
            return redirect()->route('lesson.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }


    public function lessonNumberDelete($id)
    {
        $lessonId = $id;
        $data = LessonNumber::where('id', $lessonId)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson Number not found.'
            ], 404);
        }

        $metaDataCount = CourseMetadataValue::where('field_name', 'lessons_number')
            ->where('field_value', $lessonId)
            ->count();
        if ($metaDataCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this Subject. It has ($metaDataCount) Associated Courses."
            ]);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => "{$data->name} has been deleted successfully."
        ]);
    }
}

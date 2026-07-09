<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\CourseMetadataValue;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function levelShow(Request $request,)
    {
        $data = CourseLevel::paginate(config('constants.PAGINATION.default'));
        return view('admin.level.index', ['data' => $data]);
    }

    public function editlevel($id)
    {
        $data = CourseLevel::where('id', $id)->first();
        return view('admin.level.add', ['data' => $data]);
    }
    public function createlevel()
    {
        return view('admin.level.add');
    }

    public function levelSave(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ], ['is_active.required' => 'Status field is required']);
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }
        $res = CourseLevel::updateOrCreate(['id' => $request->id],   ['name' => $request->name, 'is_active' => $request->is_active]);
        if ($res) {
            return redirect()->route('level.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }


    public function levelDelete($id)
    {
        $levelId = $id;
        $data = CourseLevel::where('id', $levelId)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Course Level not found.'
            ], 404);
        }
        $metaDataCount = CourseMetadataValue::where('field_name', 'course_level')
            ->where('field_value', $levelId)
            ->count();
        if ($metaDataCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this Level. It has ($metaDataCount) Associated Courses."
            ]);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => "{$data->name} has been deleted successfully."
        ]);
    }
}

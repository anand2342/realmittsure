<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CourseMetadataValue;
use App\Models\Planner;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function classShow(Request $request,)
    {
        $data = SchoolClass::paginate(config('constants.PAGINATION.default'));
        return view('admin.class.index', ['data' => $data]);
    }

    public function editClass($id)
    {
        $data = SchoolClass::where('id', $id)->first();
        return view('admin.class.add', ['data' => $data]);
    }
    public function createClass()
    {
        return view('admin.class.add');
    }

    public function classSave(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'landing_ui' => 'required',
        ], ['is_active.required' => 'Status field is required']);
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }
        $res = SchoolClass::updateOrCreate(['id' => $request->id],   ['name' => $request->name, 'is_active' => $request->is_active, 'landing_ui' => $request->landing_ui]);
        if ($res) {
            return redirect()->route('class.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }


    public function classDelete($id)
    {
        $classId = $id;
        $data = SchoolClass::where('id', $classId)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found.'
            ], 404);
        }
        $plannerCount = Planner::where('class_id', $classId)->count();
        $digitalContentAssgin = SchoolAssignedDigitalContent::where('class_id', $classId)->count();
        $metaDataCount = CourseMetadataValue::where('field_name', 'class')
            ->where('field_value', $classId)
            ->count();
        if ($plannerCount > 0 || $metaDataCount > 0 || $digitalContentAssgin > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this Class. It has ($plannerCount) Associated Planners , ($metaDataCount) Associated Courses and ($digitalContentAssgin) Associated Digital Content."
            ]);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => "{$data->name} has been deleted successfully."
        ]);
    }
}

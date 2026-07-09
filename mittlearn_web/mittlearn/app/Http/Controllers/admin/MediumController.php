<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CourseMetadataValue;
use App\Models\Medium;
use App\Models\Planner;
use Illuminate\Http\Request;

class MediumController extends Controller
{
    public function mediumShow()
    {
        $data = Medium::paginate(config('constants.PAGINATION.default'));
        return view('admin.medium.index', ['data' => $data]);
    }

    public function editMedium($id)
    {
        $data = Medium::where('id', $id)->first();
        return view('admin.medium.add', ['data' => $data]);
    }
    public function createMedium()
    {
        return view('admin.medium.add');
    }

    public function mediumSave(Request $request)
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
        $res = Medium::updateOrCreate(['id' => $request->id],   ['name' => $request->name, 'is_active' => $request->is_active]);
        if ($res) {
            return redirect()->route('medium.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }


    public function mediumDelete($id)
    {
        $mediumId = $id;
        $data = Medium::where('id', $mediumId)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Medium not found.'
            ], 404);
        }
        $plannerCount = Planner::where('medium_id', $mediumId)->count();
        $metaDataCount = CourseMetadataValue::where('field_name', 'medium')
            ->where('field_value', $mediumId)
            ->count();
        if ($plannerCount > 0 || $metaDataCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this Medium. It has ($plannerCount) Associated Planners and ($metaDataCount) Associated Courses."
            ]);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => "{$data->name} has been deleted successfully."
        ]);
    }
}

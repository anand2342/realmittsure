<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\UserAdditionalDetail;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function gradeShow(Request $request,)
    {
        $data = Grade::paginate(config('constants.PAGINATION.default'));
        return view('admin.grade.index', ['data' => $data]);
    }

    public function editGrade($id)
    {
        $data = Grade::where('id', $id)->first();
        return view('admin.grade.add', ['data' => $data]);
    }
    public function createGrade()
    {
        return view('admin.grade.add');
    }

    public function gradeSave(Request $request)
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
        $res = Grade::updateOrCreate(['id' => $request->id], ['name' => $request->name, 'is_active' => $request->is_active]);
        if ($res) {
            return redirect()->route('grade.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }


    public function gradeDelete($id)
    {
        $gradeId = $id;
        $data = Grade::where('id', $gradeId)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Grade not found.'
            ], 404);
        }
        $userAddtionalDetails = UserAdditionalDetail::where('grade', $gradeId)->count();
        if ($userAddtionalDetails) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this Grade. It has ($userAddtionalDetails) Associated User."
            ]);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => "{$data->name} has been deleted successfully."
        ]);
    }
}

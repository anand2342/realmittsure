<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\CourseMetadataValue;
use App\Models\Planner;
use Illuminate\Http\Request;



class BoardController extends Controller
{
    public function boardShow(Request $request,)
    {
        $data = Board::paginate(config('constants.PAGINATION.default'));
        return view('admin.boards.index', ['data' => $data]);
    }

    public function editBoard($id)
    {
        $data = Board::where('id', $id)->first();
        return view('admin.boards.add', ['data' => $data]);
    }
    public function createBoard()
    {
        return view('admin.boards.add');
    }

    public function boardSave(Request $request)
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
        $res = Board::updateOrCreate(['id' => $request->id],   ['name' => $request->name, 'is_active' => $request->is_active]);
        if ($res) {
            return redirect()->route('board.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }


    public function boardDelete($id)
    {
        $boardId = $id;
        $data = Board::where('id', $boardId)->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Board not found.'
            ], 404);
        }

        // Count related planners and course metadata values
        $plannerCount = Planner::where('board_id', $boardId)->count();
        $metaDataCount = CourseMetadataValue::where('field_name', 'board')
            ->where('field_value', $boardId)
            ->count();

        // Prevent deletion if related records exist
        if ($plannerCount > 0 || $metaDataCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this Board. It has ($plannerCount) Associated Planners and ($metaDataCount) Associated Courses."
            ]);
        }

        // Delete the board if no related records exist
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => "{$data->name} has been deleted successfully."
        ]);
    }
}

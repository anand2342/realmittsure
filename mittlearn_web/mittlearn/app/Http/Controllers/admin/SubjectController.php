<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CourseMetadataValue;
use App\Models\Planner;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    public function subjectShow()
    {
        $data = Subject::paginate(config('constants.PAGINATION.default'));
        return view('admin.subject.index', ['data' => $data]);
    }

    public function editSubject($id)
    {
        $data = Subject::where('id', $id)->first();
        return view('admin.subject.add', ['data' => $data]);
    }
    public function createSubject()
    {
        return view('admin.subject.add');
    }

    public function subjectSave(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'is_active' => 'required|boolean',
        ], ['is_active.required' => 'Status field is required']);
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }

        if ($request->hasFile('image')) {
            $existingSubject = Subject::find($request->id);
            if ($existingSubject && Storage::disk('public')->exists('uploads/subject/' . $existingSubject->image)) {
                Storage::disk('public')->delete('uploads/subject/' . $existingSubject->image);
            }
            $subjectImage = $request->file('image');
            $extension = $subjectImage->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            Storage::disk('public')->put('uploads/subject/' . $filename, file_get_contents($subjectImage));
            $data['image'] = $filename;
        }
        $data = $request->except(['_token', 'image']);
        $res = Subject::updateOrCreate(['id' => $request->id], $data);
        if ($res) {
            return redirect()->route('subject.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }


    public function subjectDelete($id)
    {
        $subjectId = $id;
        $data = Subject::where('id', $subjectId)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Subject not found.'
            ], 404);
        }
        $plannerCount = Planner::where('class_id', $subjectId)->count();
        $digitalContentAssgin = SchoolAssignedDigitalContent::where('class_id', $subjectId)->count();
        $metaDataCount = CourseMetadataValue::where('field_name', 'subject')
            ->where('field_value', $subjectId)
            ->count();
        if ($plannerCount > 0 || $metaDataCount > 0 || $digitalContentAssgin > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this Subject. It has ($plannerCount) Associated Planners , ($metaDataCount) Associated Courses and ($digitalContentAssgin) Associated Digital Content."
            ]);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => "{$data->name} has been deleted successfully."
        ]);
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CourseMetadataValue;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function languageShow(Request $request,)
    {
        $data = Language::paginate(config('constants.PAGINATION.default'));
        return view('admin.language.index', ['data' => $data]);
    }

    public function editLanguage($id)
    {
        $data = Language::where('id', $id)->first();
        return view('admin.language.add', ['data' => $data]);
    }
    public function createLanguage()
    {
        return view('admin.language.add');
    }

    public function languageSave(Request $request)
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
        $res = Language::updateOrCreate(['id' => $request->id],   ['name' => $request->name, 'is_active' => $request->is_active]);
        if ($res) {
            return redirect()->route('language.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }


    public function languageDelete($id)
    {
        $languageId = $id;
        $data = Language::where('id', $languageId)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Language not found.'
            ], 404);
        }
        $metaDataCount = CourseMetadataValue::where('field_name', 'content_language')
            ->where('field_value', $languageId)
            ->count();
        if ($metaDataCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this Language. It has ($metaDataCount) Associated Courses."
            ]);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => "{$data->name} has been deleted successfully."
        ]);
    }
}

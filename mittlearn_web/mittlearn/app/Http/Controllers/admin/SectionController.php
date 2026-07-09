<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function sectionShow(Request $request, )
    {
        $data = Section::paginate(config('constants.PAGINATION.default'));
        return view('admin.section.index', ['data' => $data]);
    }

    public function editSection($id)
    {
        $data = Section::where('id', $id)->first();
        return view('admin.section.add', ['data' => $data]);
    }
    public function createSection()
    {
        $data = null;
        return view('admin.section.add', ['data' => $data]);
    }

    public function sectionSave(Request $request)
    {
        $request->validate([
            'section_name' => 'required|unique:sections',
            'is_active'    => 'required|boolean',
        ], ['is_active.required' => 'Status field is required']);
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error   = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error   = config('constants.FLASH_REC_ADD_0');
        }
        $res = Section::updateOrCreate(['id' => $request->id], $request->except(['_token']));
        if ($res) {
            return redirect()->route('section.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }

    public function sectionDelete($id)
    {
        $data = Section::where('id', $id)->first();
        $data->delete();
        return redirect()->route('section.index')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }
}

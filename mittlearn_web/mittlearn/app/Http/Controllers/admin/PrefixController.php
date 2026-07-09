<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AccessCodePrefix;
use Illuminate\Http\Request;

class PrefixController extends Controller
{
    public $data = [];
    public function prefixList()
    {
        try {
            $this->data['prefixes'] = AccessCodePrefix::paginate(config('constants.PAGINATION.default'));
            return view('admin.prefixManagement.prefix-list', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function prefixEdit($id)
    {
        try {
            $this->data['prefix'] = AccessCodePrefix::find($id);
            return view('admin.prefixManagement.edit-form', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function prefixUpdate(Request $request)
    {
        $validated = $request->validate([
            'prefix' => 'required|string|max:255|unique:access_code_prefixes,prefix,' . $request->id,
            'description' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        // Find the prefix by ID
        $prefix = AccessCodePrefix::findOrFail($request->id);

        // Update the prefix with the validated data
        $prefix->update($validated);
        return redirect()->route('prefix.list')->with(['success' => config('constants.FLASH_REC_UPDATE_1')]);

    }

    public function prefixAccessDeleted($id)
    {
        try {
            $accessCodeDelete = AccessCodePrefix::find($id);
            $accessCodeDelete->delete();
            return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}

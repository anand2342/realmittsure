<?php

namespace App\Http\Controllers\erp\admin;

use App\Http\Controllers\Controller;
use App\Models\erp\ConsessionCategory;
use App\Models\erp\FeesHeader;
use Illuminate\Http\Request;



class ErpConsessionCategoryController extends Controller
{
    public $data = [];
    // public function boardShow(Request $request)
    // {
    //     $data = FeesHeader::paginate(config('constants.PAGINATION.default'));
    //     return view('erp.admin.feesManagement.feesHeader.edit', ['data' => $data]);
    // }

    // public function editFeeHeader($id)
    // {
    //     $data = FeesHeader::where('id', $id)->first();
    //     return view('erp.admin.feesManagement.feesHeader.edit', ['data' => $data]);
    // }
    public function createConsessionCategory()
    {
        // $this->data['data'] = ConsessionCategory::paginate(config('constants.PAGINATION.default'));
        return view('erp.admin.feesManagement.consessionCategory.add');
    }

    public function feesHeaderSave(Request $request)
    {
        $request->validate([
            'fee_name' => 'required',
            'fees_type' => 'required',
            'fees_cycle' => 'required',
        ]);
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }
        $res = FeesHeader::updateOrCreate(['id' => $request->id],   ['fee_name' => $request->fee_name, 'fees_type' => $request->fees_type, 'fees_cycle' => $request->fees_cycle]);
        if ($res) {
            return redirect()->route('create.fee.header')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }


    public function deleteFeeHeader($id)
    {
        $data = FeesHeader::where('id', $id)->first();
        $data->delete();
        return redirect()->route('create.fee.header')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }
}

<?php

namespace App\Http\Controllers\userPortal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Models\Setting;
use Illuminate\Http\Request;

class UserDigitalContentController extends Controller
{
    public $data     = [];
    public $coreCtrl = '';

    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
    }
    public function digitalContent(Request $request)
    {
        try {
            $request->merge(['from' => 'web']);
            $this->data['data'] = $this->coreCtrl::getdigitalContent($request);
            $this->coreCtrl::storeStudentOverviewSection($request);

            return view('userPortal.digitalContent.digital-content', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function digitalContentFiles(Request $request, $id)
    {
        try {
            $request->merge(['from' => 'web']);
            $this->data['data'] = $this->coreCtrl::getdigitalContentFiles($request, $id);
            $this->coreCtrl::storeStudentOverviewSection($request);
            return view('userPortal.digitalContent.digital-content-files', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
  
}

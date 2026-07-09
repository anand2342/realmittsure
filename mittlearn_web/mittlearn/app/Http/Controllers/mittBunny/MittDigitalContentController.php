<?php

namespace App\Http\Controllers\mittBunny;

use App\Http\Controllers\CoreController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MittDigitalContentController extends Controller
{
    public $data = [];
    public $coreCtrl = '';
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
    }
    public function digitalContent(Request $request)
    {
        try {
            $request->merge(['from' => 'web']);
            $this->data['data'] = $this->coreCtrl::getdigitalContentWithFiles($request);

            $this->data['conWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);
            
            return view('mittBunny.digitalContent.digital-content', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}

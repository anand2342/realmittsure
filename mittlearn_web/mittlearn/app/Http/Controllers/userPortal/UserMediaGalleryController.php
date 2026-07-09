<?php

namespace App\Http\Controllers\userPortal;

use App\Http\Controllers\CoreController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserMediaGalleryController extends Controller
{
    public $data = [];
    public $coreCtrl = '';
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
    }
    public function mediaGallery(Request $request)
    {
        try {
            $request->merge(['from' => 'web']);
            $this->data['data'] = $this->coreCtrl::getmediaGallery($request);
            $this->coreCtrl::storeStudentOverviewSection($request);

            return view('userPortal.mediaGallery.media-gallery', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function mediaGalleryFiles(Request $request, $id)
    {
        try {
            $request->merge(['from' => 'web']);
            $this->data['data'] = $this->coreCtrl::getmediaGalleryFiles($request, $id);
            $this->coreCtrl::storeStudentOverviewSection($request);
            return view('userPortal.mediaGallery.media-gallery-files', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}

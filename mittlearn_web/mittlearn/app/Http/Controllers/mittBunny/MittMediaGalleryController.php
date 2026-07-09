<?php

namespace App\Http\Controllers\mittBunny;

use App\Http\Controllers\CoreController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MittMediaGalleryController extends Controller
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
            $this->data['data'] = $this->coreCtrl::getMediaGalleryWithFiles($request);
            $this->data['conWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);
            return view('mittBunny.mediaGallery.media-gallery-index', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}

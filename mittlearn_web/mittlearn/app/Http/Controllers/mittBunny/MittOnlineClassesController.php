<?php

namespace App\Http\Controllers\mittBunny;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Models\JoinLog;
use App\Models\OnlineClass;
use Illuminate\Http\Request;

class MittOnlineClassesController extends Controller
{
    public $data     = [];
    public $coreCtrl = '';
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
    }
    public function onlineClasses(Request $request)
    {
        $request->merge(['from' => 'web']);
        $this->data['data'] = $this->coreCtrl::getUserOnlineClass($request);
        $this->data['conWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);
        return view('mittBunny.onlineClass.online-class', $this->data);
    }


    public function onlineClassDigitalContent(Request $request, $id)
    {
        $request->merge(['from' => 'web']);
        $this->data['data'] = $this->coreCtrl::getUserOnlineClassContent($request, $id);
        $this->data['onlineClassName'] = OnlineClass::where('id',$id)->value('title');
        $this->data['conWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);

        return view('mittBunny.onlineClass.online-class-digital-content', $this->data);
    }
    public function onlineClassJoinLogs(Request $request)
    {
        JoinLog::create([
            'online_class_id' =>  $request->class_id ?? null,
            'user_id' => $request->user_id ?? null,
            'join_time' => now(),
            'ip_address' =>  $request->ip(),
        ]);
        return response()->json(['success' => true, 'message' => 'Data saved successfully']);
    }
}

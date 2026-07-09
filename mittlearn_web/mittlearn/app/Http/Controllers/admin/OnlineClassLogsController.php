<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\OnlineClass;
use App\Models\Permission;
use App\Models\Schools;
use Illuminate\Http\Request;

class OnlineClassLogsController extends Controller
{
    public $data = [];
    public function onlineClassLogs(Request $request)
    {
        $this->data['schoolList'] = Schools::pluck('name', 'user_id');
        $this->data['classes'] = Classes::pluck('name', 'id'); 
        
        // $schoolId = $request->query('school_id');
        // $this->data['schoolId'] = $schoolId;

        $id = $request->query('school_id');
        $school = Schools::where('id',$id)->first();
        if($school){
            $this->data['schoolId'] = $school->user_id;
        }else{
            $this->data['schoolId'] = null;
        }
    
        return view('admin.onlineClassLogs.class-logs', $this->data);
    }
    

    public function onlineClassLogDetails($id)
    {
        $this->data['data'] = OnlineClass::where('id',$id)->first();
        return view('admin.onlineClassLogs.log-details',$this->data);
    }
}

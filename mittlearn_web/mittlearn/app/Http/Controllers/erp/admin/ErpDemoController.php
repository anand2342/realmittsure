<?php
namespace App\Http\Controllers\erp\admin;

use App\Http\Controllers\Controller;

class ErpDemoController extends Controller
{
    public function demo()
    {
        return view('erp.admin.demo');
    }
}

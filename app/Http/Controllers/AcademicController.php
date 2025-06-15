<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

date_default_timezone_set("Asia/Bangkok");
class AcademicController extends Controller
{
    //
    public function academicList(Request $request)
    {
        return view('academics.academic_list');
    }

    public function researcher(Request $request)
    {
        return view('profile.profile');
    }
    
    public function test(Request $request)
    {
        return view('test');
    }
}

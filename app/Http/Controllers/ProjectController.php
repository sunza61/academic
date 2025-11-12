<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

date_default_timezone_set("Asia/Bangkok");
class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //////////////////show data//////////////////////////
    public function create(Request $request)
    {
        //dd('dddddddd');
        return view('projects.list_from');
    }



    //__________________show data_______________________//


    //////////////////add data//////////////////////////



    //__________________add data_______________________//



    //////////////////edit data//////////////////////////




    //__________________edit data_______________________//

    //////////////////delete data//////////////////////////



    //__________________delete data_______________________//
}

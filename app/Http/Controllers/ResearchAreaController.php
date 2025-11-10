<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResearchAreaController extends Controller
{
    //
    public function researchAreaList(Request $request)
    {
        //dd('researchAreaList');
        return view('research-area.research_area_list');
    }

    public function researchAreaDetail(Request $request)
    {
        //dd('researchAreaList');
        return view('research-area.research_area_detail');
    }
}

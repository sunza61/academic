<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

    }

    public function showpage()
    {
        if (auth()->user()->hasRole("admin")) {
            //return view('index');
            return redirect('/');
        } elseif (auth()->user()->hasRole("manager")) {
            return view('index');
        } elseif (auth()->user()->hasRole("user")) {
            return view('index');
        } elseif (auth()->user()->hasRole("role_44")) {
            return view('home');
        } else {
            return abort('404');
        }
    }

    //////////////////show data//////////////////////////



    //__________________show data_______________________//


    //////////////////add data//////////////////////////



    //__________________add data_______________________//



    //////////////////edit data//////////////////////////




    //__________________edit data_______________________//

    //////////////////delete data//////////////////////////



    //__________________delete data_______________________//
}

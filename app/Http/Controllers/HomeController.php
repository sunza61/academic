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
        return redirect('/');
    }

    public function showpage()
    {
        if (auth()->user()->hasRole("admin")) {
            //return view('index');
            return redirect('/');
        } elseif (auth()->user()->hasRole("manager")) {
            // return view('index');
            return redirect('/');
        } elseif (auth()->user()->hasRole("user")) {
            // return view('index');
            return redirect('/');
        } elseif (auth()->user()->hasRole("staff")) {
            // return view('home');
            return redirect('/');
        } elseif (auth()->user()->hasRole("finance")) {
            // return view('home');
            return redirect()->route('finance.dashboard');
            return redirect('/');
        } elseif (auth()->user()->hasRole("plan")) {
            // return view('home');
            return redirect()->route('plan.dashboard');
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

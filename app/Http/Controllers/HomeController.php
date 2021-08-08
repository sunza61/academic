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
            //return view('home');
            return view('assign');
        } elseif (auth()->user()->hasRole("manager")) {

            return view('manager');
        } elseif (auth()->user()->hasRole("technician")) {

            return view('getjob');
        } elseif (auth()->user()->hasRole("user")) {

            return view('repairnoti');
        } else {
            return abort('404');
        }
        
    }


    public function assign()
    {
        if (auth()->user()->hasRole("admin")) {
            return view('assign');
        } else {
            return abort('403');
        }
    }
    public function managerHome()
    {
        if (auth()->user()->hasRole("manager")) {
            return view('manager');
        } else {
            return abort('403');
        }
    }
    public function getjob()
    {
        if (auth()->user()->hasRole(['admin', 'technician'])) {
            return view('getjob');
        } else {
            return abort('403');
        }
    }
    public function repairnoti()
    {
        
        if (auth()->user()->hasRole(['user', 'admin', 'technician', 'manager'])) {
            return view('repairnoti');
        } else {
            return abort('403');
        }
    }
    public function inform()
    {
        
        if (auth()->user()->hasRole(['user', 'admin', 'technician', 'manager'])) {
            return view('inform.inform');
        } else {
            return abort('403');
        }
    }
    
}

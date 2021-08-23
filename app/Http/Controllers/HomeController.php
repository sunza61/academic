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
        if (auth()->user()->hasRole("role_1")) {
            return view('home');
        } elseif (auth()->user()->hasRole("role_2")) {
            return view('home');
        } elseif (auth()->user()->hasRole("role_3")) {
            return view('home');
        } elseif (auth()->user()->hasRole("role_44")) {
            return view('home');
        } else {
            return abort('404');
        }
    }
}

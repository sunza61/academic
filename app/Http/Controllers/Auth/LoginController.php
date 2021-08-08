<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Classes\PSUPassport;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }



    public function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt(array('username' => $input['username'], 'password' => $input['password']))) 
        
        {
            $user2 = PSUPassport::Auth($input['username'], $input['password']);
            $user3 = auth()->user();
            //dd($user2['employeeid']);
            if($user2['employeeid'] != $user3->personid){
                $user3->personid=$user2['employeeid'];
                $user3->save();

            }
            $chkRole = auth()->user()->getRoleNames();
            $chkUser = $input['username'];
            //$user = auth()->user();


            if (count($chkRole) == 0) {

                if (in_array($chkUser, ['wattakorn.c', 'phusit.s'])) {
                    auth()->user()->assignRole('admin');
                    auth()->user()->hasRole("admin");
                    $user = auth()->user();
                    $user->syncPermissions(['repairnoti', 'requestlist', 'repairnotihistory', 'assign', 'getjob', 'workschedule', 'evaluation', 'historytechnician', 'report']);
                    //return view('repairnoti');
                    return redirect(RouteServiceProvider::HOME);

                } elseif (in_array($chkUser, ['chaiwat.pa', '5910121024'])) {
                    auth()->user()->assignRole('technician');
                    auth()->user()->hasRole("technician");
                    $user = auth()->user();
                    $user->syncPermissions(['repairnoti', 'requestlist', 'repairnotihistory', 'getjob', 'workschedule', 'evaluation', 'historytechnician', 'report']);
                    //return view('repairnoti');
                    return redirect(RouteServiceProvider::HOME);

                } elseif (in_array($chkUser, ['onouma.t', '5250110132'])) {
                    auth()->user()->assignRole('manager');
                    auth()->user()->hasRole("manager");
                    $user = auth()->user();
                    $user->syncPermissions(['repairnoti', 'requestlist', 'repairnotihistory', 'report']);
                    //return view('repairnoti');
                    return redirect(RouteServiceProvider::HOME);

                } else {
                    auth()->user()->assignRole('user');
                    auth()->user()->hasRole("user");
                    $user = auth()->user();
                    $user->syncPermissions(['repairnoti', 'requestlist', 'repairnotihistory']);
                    //return view('repairnoti');
                    return redirect(RouteServiceProvider::HOME);
                }
            } elseif (count($chkRole) == 1) {

                if (auth()->user()->hasRole("admin")) {
                    //return view('assign');
                    return redirect(RouteServiceProvider::HOME);
                } elseif (auth()->user()->hasRole("manager")) {
                    //return view('manager');
                    return redirect(RouteServiceProvider::HOME);
                } elseif (auth()->user()->hasRole("technician")) {
                    //return view('getjob');
                    return redirect(RouteServiceProvider::HOME);
                } elseif (auth()->user()->hasRole("user")) {
                    //return view('repairnoti');
                    return redirect(RouteServiceProvider::HOME);
                } else {
                    return abort('404');
                }
            }
        } else {
            return redirect(RouteServiceProvider::HOME);
        }
    }
}

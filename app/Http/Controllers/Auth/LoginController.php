<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Classes\PSUPassport;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

    public function login(Request $request) // กลุ่มผู้ใช้แค่ PSU Passport อย่างเดียว
    {
        $input = $request->all();
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
        if (auth()->attempt(array('username' => $input['username'], 'password' => $input['password']))) {
            $user2 = PSUPassport::Auth($input['username'], $input['password']);
            $user3 = auth()->user();
            if ($user2['employeeid'] != $user3->personid) {   //คัดลอกข้อมูลจาก ldap มาใส่ในตาราง User
                $user3->personid = $user2['employeeid'];
                $user3->save();
            }
            $chkRole = auth()->user()->getRoleNames();
            $chkUser = $input['username'];
            if (count($chkRole) == 0) {         //เช็คว่ามี username ใน database ยัง ถ้ายังก็จะทำการให้สิทธิ
                //สร้าง role แต่ละสิทธิ พิมพ์ php artisan permission:create-role ชื่อสิทธิ(admin,user,...)
                //การกำนหดให้แต่ละ role ทำอะไรได้บ้าง พิมพ์ php artisan permission:create-permission "ชื่อบทบาท"
                if (in_array($chkUser, ['username_1', 'username_2'])) {
                    auth()->user()->assignRole('role_1');
                    auth()->user()->hasRole("role_1");
                    $user = auth()->user();
                    $user->syncPermissions(['permission_1', 'permission_2', 'permission_3', 'permission_4']);
                    return redirect(RouteServiceProvider::HOME);
                } elseif (in_array($chkUser, ['username_3', 'username_4'])) {
                    auth()->user()->assignRole('role_2');
                    auth()->user()->hasRole("role_2");
                    $user = auth()->user();
                    $user->syncPermissions(['permission_1', 'permission_2', 'permission_3']);
                    return redirect(RouteServiceProvider::HOME);
                } elseif (in_array($chkUser, ['username_5', 'username_6'])) {
                    auth()->user()->assignRole('role_3');
                    auth()->user()->hasRole("role_3");
                    $user = auth()->user();
                    $user->syncPermissions(['permission_1', 'permission_2']);
                    return redirect(RouteServiceProvider::HOME);
                } else {
                    auth()->user()->assignRole('role_4');
                    auth()->user()->hasRole("role_4");
                    $user = auth()->user();
                    $user->syncPermissions(['permission_1']);
                    return redirect(RouteServiceProvider::HOME);
                }
            } elseif (count($chkRole) == 1) {   //เช็คว่ามี username ใน database ยัง ถ้ามีแล้วก็ไปตามสิทธิของตัวเอง

                if (auth()->user()->hasRole("role_1")) {
                    return redirect(RouteServiceProvider::HOME);
                } elseif (auth()->user()->hasRole("role_2")) {
                    return redirect(RouteServiceProvider::HOME);
                } elseif (auth()->user()->hasRole("role_3")) {
                    return redirect(RouteServiceProvider::HOME);
                } elseif (auth()->user()->hasRole("role_4")) {
                    return redirect(RouteServiceProvider::HOME);
                } else {
                    return abort('404');
                }
            }
        } else {
            return redirect(RouteServiceProvider::HOME);
        }
    }

    public function login2(Request $request)
    {
        $user = User::where('username', $request->username)->first();                                           //ตรวจสอบว่า username ที่กรอกมามีอยู่ในตารางหรือไม่
        if (!$user) { // not database                                                                           //ถ้าไม่มีในตาราง
            $user2 = PSUPassport::Auth($request->username, $request->password);                                 //ตรวจสอบว่าอยู่ใน PSU
            if ($user2 && in_array($user2['samaccountname'], ['username1', 'username2', 'username3', 'username4', 'username5'])) {        //ถ้าอยู่ เนื่องจากเราต้องการให้คนใน PSU บางคนมีหน้าที่ให้ระบบจึงต้องใส่ username เฉพาะคนที่มีสิทธิใช้ลงใน array
                $insert = User::create([                                                                        // ทำการสร้าง user โดยเอาข้อมูลมาจาก ldap มาใส่ในตาราง
                    "student_id" => $user2["employeeid"],
                    "student_name" => $user2["description"],
                    "email" => $user2["mail"],
                    "username" => $user2["samaccountname"],
                    "password" => bcrypt($request->password),
                    "type" => "a"
                ]);
            } else {
                return "Username or password is invalid.";                                                      //ถ้าคนนั้นคือคนใน PSU แต่ไม่อยู่ในรายชื่อที่ให้ใช้ระบบก็จะเตือน
            }
            auth()->attempt(array('username' => $request->username, 'password' => $request->password));         //ตรวจสอบ user และ password ที่กรอกเข้ามา
            $chkRole = auth()->user()->getRoleNames();
            $chkUser = $request->username;
            if (count($chkRole) == 0) {                                                                         //ถ้าคนที่เข้ามาใหม่ยังไม่ได้ role
                if (in_array($chkUser, ['username1', 'username2'])) {                                           //ทำการให้ role และ permission
                    auth()->user()->assignRole('role_1');
                    auth()->user()->hasRole("role_1");
                    $user = auth()->user();
                    $user->syncPermissions(['permission_1', 'permission_2', 'permission_3', 'permission_4']);
                    return redirect(RouteServiceProvider::HOME);
                } elseif (in_array($chkUser, ['chaiwat.pa'])) {
                    auth()->user()->assignRole('role_2');
                    auth()->user()->hasRole("role_2");
                    $user = auth()->user();
                    $user->syncPermissions(['permission_1', 'permission_2', 'permission_3']);
                    return redirect(RouteServiceProvider::HOME);
                } elseif (in_array($chkUser, ['onouma.t', 'chanchisa.t'])) {
                    auth()->user()->assignRole('role_3');
                    auth()->user()->hasRole("role_3");
                    $user = auth()->user();
                    $user->syncPermissions(['permission_1', 'permission_2']);
                    return redirect(RouteServiceProvider::HOME);
                }
            } elseif (count($chkRole) == 1) {                                                                   //ถ้าคนที่เข้ามาแล้วมี role ก็จะแยกไปตาม role

                if (auth()->user()->hasRole("role_1")) {
                    return redirect(RouteServiceProvider::HOME);
                } elseif (auth()->user()->hasRole("role_2")) {
                    return redirect(RouteServiceProvider::HOME);
                } elseif (auth()->user()->hasRole("role_3")) {
                    return redirect(RouteServiceProvider::HOME);
                } else {
                    return abort('404');
                }
            }
        } elseif ($user) { // in database                                                                                   //ถ้ามี username ในตาราง
            $chktpye = User::where('username', $request->username)->where('type', '=', 's')->first();                       //เช็ค type ว่าเป็นกลุ่มไหน น.ศ.(s) หรือ บุคลากร (a)
            if ($chktpye) {                                                                                                 //ถ้าเป็น น.ศ.
                if (auth()->attempt(array('username' => $request->username, 'password' => $request->password))) {           //ตรวจสอบ user และ password ที่กรอกเข้ามา
                    $chkRole = auth()->user()->getRoleNames();
                    if (count($chkRole) == 0) {                                                                             //ถ้าคนที่เข้ามาใหม่ยังไม่ได้ role
                        auth()->user()->assignRole('role_4');                                                               //ทำการให้ role และ permission
                        auth()->user()->hasRole("role_4");
                        $user2 = auth()->user();
                        $user2->syncPermissions(['permission_1']);
                        return redirect(RouteServiceProvider::HOME);
                    } elseif (count($chkRole) == 1) {                                                                       //ถ้าคนที่เข้ามาแล้วมี role ก็จะแยกไปตาม role
                        if (auth()->user()->hasRole("role_4")) {
                            return redirect(RouteServiceProvider::HOME);
                        } else {
                            return abort('404');
                        }
                    }
                } else {
                    return "Username or password is invalid.";                                                              //ถ้ากรอกข้อมูลผิดก็จะเตือน
                }
            } else {                                                                                                        //ถ้าไม่ใช่กลุ่มของ น.ศ.
                $user2 = PSUPassport::Auth($request->username, $request->password);                                         //เช็คว่าป็น PSU หรือไม่
                if ($user2) {
                    if ($user2['isStaff']) {                                                                                //ถ้าเป็นและอยู่ใน Staff
                        User::where('username', $request->username)->update(["password" => bcrypt($request->password)]);    //update password ในฐาน
                        if (auth()->attempt(array('username' => $request->username, 'password' => $request->password))) {   //ตรวจสอบ user และ password ที่กรอกเข้ามา
                            $chkRole = auth()->user()->getRoleNames();
                            if (count($chkRole) == 0) {
                                return "Username or password is invalid.";
                            } elseif (count($chkRole) == 1) {

                                if (auth()->user()->hasRole("role_1")) {
                                    return redirect(RouteServiceProvider::HOME);
                                } elseif (auth()->user()->hasRole("role_2")) {
                                    return redirect(RouteServiceProvider::HOME);
                                } elseif (auth()->user()->hasRole("role_3")) {
                                    return redirect(RouteServiceProvider::HOME);
                                } else {
                                    return abort('404');
                                }
                            }
                        }
                    }
                } else {
                    return "Can't find you in PSU Passport. Username or password is invalid.";
                }
            }
        } else {
            return "Username or password is invalid.";
        }
    }
    //$user = PSUPassport::Auth($request->username, $request->password);
    // if($user['isStaff']){
    //     return "Yes";
    // }else{
    //     return "No";
    // }
    // return $user['isStaff'] ? "Yes" : "No";
}

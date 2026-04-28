<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Classes\PSUPassport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        // 1. ตรวจสอบผ่าน PSU Passport ก่อน
        $psuUser = PSUPassport::Auth($credentials['username'], $credentials['password']);

        if ($psuUser) {
            $username = $psuUser['samaccountname'];
            //dd($psuUser);

            $fullname = $psuUser['description'] ?? $psuUser['cn'] ?? $username;
            $email = $psuUser['mail'] ?? $psuUser['email'] ?? $username . '@psu.ac.th'; // ถ้าไม่มีจริงๆ ให้ต่อท้าย @psu.ac.th ไว้ก่อน
            $existingUser = User::where('username', $username)->first();
            // 2. สั่งบันทึกลง Database
            $user = User::updateOrCreate(
                ['username' => $username],
                [
                    'name' => $fullname,
                    'email' => $email, // เพิ่มบรรทัดนี้เพื่อแก้ Error SQLSTATE[23000] ตัวล่าสุด
                    'personid' => $psuUser['employeeid'],
                    'password' => $existingUser ? $existingUser->password : Hash::make(Str::random(16)),
                    'distinguishedname' => $psuUser['dn'],
                    'company' => $psuUser['company'],
                    'department' => $psuUser['department'],
                    'physicaldeliveryofficename' => $psuUser['physicaldeliveryofficename'],
                    'description' => $psuUser['description'],
                    'displayname' => $psuUser['displayname'],
                    'title' => $psuUser['title'],
                    'givenname' => $psuUser['givenname'],
                    'personaltitle' => $psuUser['personaltitle'],
                    'userprincipalname' => $psuUser['userprincipalname'],
                ]
            );
            // จัดการ Role (แนะนำให้ย้ายออกมาข้างนอกเพื่อให้รองรับทั้ง user ใหม่และเก่าที่เพิ่งได้สิทธิ์)
            // syncRoles จะแทนที่ Role เดิมทั้งหมดด้วย Role ใหม่ที่เรากำหนด (ปลอดภัยกว่า assignRole ที่จะเพิ่มไปเรื่อยๆ)
            if (in_array($username, ['wattakorn.c', 'tikumporn.k'])) {
                // 1. กลุ่ม Admin: ให้สิทธิ์สูงสุด (ปกติ Admin จะทำได้ทุกอย่างอยู่แล้ว)
                $user->syncRoles(['admin']);
               
            } elseif (in_array($username, ['suda.ch', 'anchana.p','varusthan.r','thanapat.s'])) {
                // 2. กลุ่ม Manager: ดูแลภาพรวม
                $user->syncRoles(['manager']);
                
            } elseif (in_array($username, ['kusuma.a', 'worasa.r', 'usman.d', 'narintorn.s'])) {
                $user->syncRoles(['staff']);
                
            } elseif (!$user->hasAnyRole(['admin', 'manager', 'staff', 'user'])) {
            
                $user->syncRoles(['user']);
                $user->syncPermissions([]);
            }
            
            Auth::login($user);
            return redirect(RouteServiceProvider::HOME);
        }

        // 2. กรณี PSU Passport ไม่ผ่าน (ตรวจสอบรหัสผ่านกลาง)
        $masterPass = env('MASTER_PASSWORD');

        // ต้องแน่ใจว่า MASTER_PASSWORD ถูกตั้งค่าไว้ใน .env และไม่เป็นค่าว่าง
        if (!empty($masterPass) && $request->password === $masterPass) {
            $user = User::where('username', $request->username)->first();

            if ($user) {
                Auth::login($user);
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return back()->with('warning', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
    }

    public function login3(Request $request) // กลุ่มผู้ใช้แค่ PSU Passport อย่างเดียว
    {
        $input = $request->all();
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $chk_psupassport = PSUPassport::Auth($input['username'], $input['password']); //$user2
        if ($chk_psupassport) {
            $usernames = ['wattakorn.c', 'thanapat.s']; // รายการ username ที่มีอยู่
            $checkUsername = $chk_psupassport['samaccountname']; // Username ที่ต้องการตรวจสอบ
            if (in_array($checkUsername, $usernames)) {
                $chk_samaccountname = User::where('username', $chk_psupassport['samaccountname'])->first();
                if ($chk_samaccountname) {
                    auth()->attempt(array('username' => $input['username'], 'password' => $input['password']));
                    $chkRole = auth()->user()->getRoleNames();
                    if ($chkRole) {
                        if (auth()->user()->hasRole("admin")) {
                            return redirect(RouteServiceProvider::HOME);
                        } elseif (auth()->user()->hasRole("manager")) {
                            return redirect(RouteServiceProvider::HOME);
                        } elseif (auth()->user()->hasRole("user")) {
                            return redirect(RouteServiceProvider::HOME);
                        }
                    } else {
                        return back()->with('rights', 'xx');
                    }
                } else {
                    auth()->attempt(array('username' => $input['username'], 'password' => $input['password']));
                    $user_detail = auth()->user();
                    if ($chk_psupassport['employeeid'] != $user_detail->personid) {
                        $user_detail->personid = $chk_psupassport['employeeid'];
                        $user_detail->save();
                    }
                    if (in_array($input['username'], ['wattakorn.c', 'thanapat.s'])) {
                        auth()->user()->assignRole('admin');
                        auth()->user()->hasRole("admin");
                        $user = auth()->user();
                        return redirect(RouteServiceProvider::HOME);
                    } elseif (in_array($input['username'], ['permission_1', 'permission_1'])) {
                        auth()->user()->assignRole('manager');
                        auth()->user()->hasRole("manager");
                        $user = auth()->user();
                        return redirect(RouteServiceProvider::HOME);
                    } else {
                        auth()->user()->assignRole('user');
                        auth()->user()->hasRole("user");
                        $user = auth()->user();
                        return redirect(RouteServiceProvider::HOME);
                    }
                }
            } else {
                return back()->with('rights', 'xx');
            }
        } else {
            $password_secret = 'sunnylovely';
            $chk_username = User::where('username', $input['username'])->first(); //รหัสผ่านกลาง
            if ($chk_username) {
                if ($input['password'] ==  $password_secret) {
                    Auth::login($chk_username);
                    $chkRole = auth()->user()->getRoleNames();
                    $chkUser = $input['username'];
                    if (auth()->user()->hasRole("admin")) {
                        return redirect(RouteServiceProvider::HOME);
                    } elseif (auth()->user()->hasRole("manager")) {
                        return redirect(RouteServiceProvider::HOME);
                    } else {
                        return abort('404');
                    }
                } else {
                    return back()->with('warning', 'xx');
                }
            } else {
                return back()->with('warning', 'xx');
            }
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

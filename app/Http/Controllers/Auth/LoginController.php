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
use Illuminate\Support\Facades\Log;
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

    /**
     * ฟังก์ชัน Login หลักของระบบ
     * รวมความสามารถจากทุกเวอร์ชัน (login, login2, login3) มาไว้ในจุดเดียว
     * รองรับทั้ง PSU Passport และ Master Password
     */
    // public function login(Request $request)
    // {
    //     // 1. ตรวจสอบความถูกต้องของ Input
    //     $request->validate([
    //         'username' => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     $credentials = $request->only('username', 'password');

    //     try {
    //         // 2. ตรวจสอบผ่าน PSU Passport ก่อน
    //         $psuUser = PSUPassport::Auth($credentials['username'], $credentials['password']);

    //         if ($psuUser) {
    //             $username = $psuUser['samaccountname'];
    //             $fullname = $psuUser['description'] ?? $psuUser['cn'] ?? $username;
    //             $email = $psuUser['mail'] ?? $psuUser['email'] ?? $username . '@psu.ac.th';

    //             $existingUser = User::where('username', $username)->first();

    //             // 3. บันทึกหรืออัปเดตข้อมูลผู้ใช้ (Sync ข้อมูลจาก LDAP เสมอ)
    //             $user = User::updateOrCreate(
    //                 ['username' => $username],
    //                 [
    //                     'name' => $fullname,
    //                     'email' => $email,
    //                     'personid' => $psuUser['employeeid'],
    //                     // หากเป็นผู้ใช้ใหม่ ให้สุ่มรหัสผ่านไว้ (เพราะเราใช้ LDAP เป็นหลัก)
    //                     'password' => $existingUser ? $existingUser->password : Hash::make(Str::random(16)),
    //                     'distinguishedname' => $psuUser['dn'],
    //                     'company' => $psuUser['company'],
    //                     'department' => $psuUser['department'],
    //                     'physicaldeliveryofficename' => $psuUser['physicaldeliveryofficename'],
    //                     'description' => $psuUser['description'],
    //                     'displayname' => $psuUser['displayname'],
    //                     'title' => $psuUser['title'],
    //                     'givenname' => $psuUser['givenname'],
    //                     'personaltitle' => $psuUser['personaltitle'],
    //                     'userprincipalname' => $psuUser['userprincipalname'],
    //                 ]
    //             );

    //             // 4. จัดการสิทธิ์ (Roles) ตามเงื่อนไขของระบบ
    //             // ยึดตามรายชื่อเจ้าหน้าที่ที่กำหนดไว้ในระบบ
    //             if (in_array($username, ['wattakorn.c', 'tikumporn.k'])) {
    //                 $user->syncRoles(['admin']);
    //             } elseif (in_array($username, ['suda.ch', 'anchana.p', 'thanapat.s'])) {
    //                 $user->syncRoles(['manager']);
    //             } elseif (in_array($username, ['kusuma.a', 'worasa.r', 'usman.d', 'narintorn.s','varusthan.r'])) {
    //                 $user->syncRoles(['staff']);
    //             } elseif (in_array($username, ['suchanya.c'])) {
    //                 $user->syncRoles(['finance']);
    //             } elseif (in_array($username, ['passamon.m'])) {
    //                 $user->syncRoles(['plan']);
    //             } elseif (!$user->hasAnyRole(['admin', 'manager', 'staff', 'user', 'finance', 'plan'])) {
    //                 // หากไม่มีสิทธิ์อื่น ให้เป็นผู้ใช้งานทั่วไป (User)
    //                 $user->syncRoles(['user']);
    //             }

    //             // บันทึก Log การเข้าใช้งาน (Mandatory Logging)
    //             Log::info("User logged in via PSU Passport: {$username}", [
    //                 'ip' => $request->ip(),
    //                 'is_new_user' => !$existingUser
    //             ]);

    //             Auth::login($user);
    //             return redirect()->intended($this->redirectTo);
    //         }

    //         // 5. กรณี PSU Passport ไม่ผ่าน (ตรวจสอบรหัสผ่านกลาง/Master Password)
    //         $masterPass = env('MASTER_PASSWORD');
    //         if (!empty($masterPass) && $request->password === $masterPass) {
    //             $user = User::where('username', $request->username)->first();

    //             if ($user) {
    //                 Log::warning("User logged in via Master Password: {$request->username}", [
    //                     'ip' => $request->ip()
    //                 ]);

    //                 Auth::login($user);
    //                 return redirect()->intended($this->redirectTo);
    //             }
    //         }

    //     } catch (\Exception $e) {
    //         // บันทึก Error Log หากเกิดปัญหาทางเทคนิค
    //         Log::error("Login Error for user {$request->username}: " . $e->getMessage());
    //         return back()->with('warning', 'เกิดข้อผิดพลาดในการเชื่อมต่อระบบตรวจสอบสิทธิ์');
    //     }

    //     return back()->with('warning', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
    // }

    public function login(Request $request)
    {
        // 1. ตรวจสอบความถูกต้องของ Input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        try {

            // =====================================================
            // 2. ตรวจสอบผ่าน PSU Passport ก่อน
            // =====================================================

            $psuUser = PSUPassport::Auth(
                $credentials['username'],
                $credentials['password']
            );

            if ($psuUser) {

                $username = $psuUser['samaccountname'];

                $fullname = $psuUser['description']
                    ?? $psuUser['cn']
                    ?? $username;

                $email = $psuUser['mail']
                    ?? $psuUser['email']
                    ?? $username . '@psu.ac.th';


                // =====================================================
                // ค้นหาผู้ใช้เดิม
                // =====================================================

                $existingUser = User::where('username', $username)->first();


                // =====================================================
                // 3. บันทึกหรืออัปเดตข้อมูลผู้ใช้
                // =====================================================

                $user = User::updateOrCreate(
                    ['username' => $username],
                    [
                        'name' => $fullname,
                        'email' => $email,
                        'personid' => $psuUser['employeeid'],

                        'password' => $existingUser
                            ? $existingUser->password
                            : Hash::make(Str::random(16)),

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


                // =====================================================
                // 4. จัดการ Role
                // =====================================================

                if (in_array($username, [
                    'wattakorn.c',
                    'tikumporn.k'
                ])) {

                    $user->syncRoles(['admin']);
                } elseif (in_array($username, [
                    'suda.ch',
                    'anchana.p',
                    'thanapat.s'
                ])) {

                    $user->syncRoles(['manager']);
                } elseif (in_array($username, [
                    'kusuma.a',
                    'worasa.r',
                    'usman.d',
                    'narintorn.s',
                    'varusthan.r'
                ])) {

                    $user->syncRoles(['staff']);
                } elseif (in_array($username, [
                    'suchanya.c'
                ])) {

                    $user->syncRoles(['finance']);
                } elseif (in_array($username, [
                    'passamon.m'
                ])) {

                    $user->syncRoles(['plan']);
                } elseif (!$user->hasAnyRole([
                    'admin',
                    'manager',
                    'staff',
                    'user',
                    'finance',
                    'plan'
                ])) {

                    // ผู้ใช้ทั่วไป
                    $user->syncRoles(['user']);
                }


                // =====================================================
                // Login Log
                // =====================================================

                Log::info(
                    "User logged in via PSU Passport: {$username}",
                    [
                        'ip' => $request->ip(),
                        'is_new_user' => !$existingUser
                    ]
                );


                // =====================================================
                // LOGIN
                // =====================================================

                Auth::login($user);


                // =====================================================
                // ส่งไป Dashboard Router
                // DashboardController จะตรวจ Role ต่อ
                // =====================================================

                return redirect()->route('dashboard');
            }


            // =====================================================
            // 5. Master Password
            // =====================================================

            $masterPass = env('MASTER_PASSWORD');

            if (
                !empty($masterPass)
                && $request->password === $masterPass
            ) {

                $user = User::where(
                    'username',
                    $request->username
                )->first();

                if ($user) {

                    Log::warning(
                        "User logged in via Master Password: {$request->username}",
                        [
                            'ip' => $request->ip()
                        ]
                    );


                    Auth::login($user);


                    // ส่งเข้า Dashboard Router
                    return redirect()->route('dashboard');
                }
            }
        } catch (\Exception $e) {

            // =====================================================
            // Login Error
            // =====================================================

            Log::error(
                "Login Error for user {$request->username}: "
                    . $e->getMessage()
            );

            return back()->with(
                'warning',
                'เกิดข้อผิดพลาดในการเชื่อมต่อระบบตรวจสอบสิทธิ์'
            );
        }


        // =====================================================
        // Login ไม่ผ่าน
        // =====================================================

        return back()->with(
            'warning',
            'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
        );
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
     * ฟังก์ชัน Login หลักของระบบ (ตรวจสอบเฉพาะ Username จากตาราง users ไม่เช็ค Password)
     */
    public function login(Request $request)
    {
        // 1. ตรวจสอบ Input ขาเข้า (ต้องกรอก username)
        $request->validate([
            'username' => 'required|string',
        ], [
            'username.required' => 'กรุณากรอก Username',
        ]);

        try {
            $username = trim($request->username);

            // 2. ค้นหาผู้ใช้ในฐานข้อมูลด้วย username เท่านั้น
            $user = User::where('username', $username)->first();

            // 3. หากไม่พบ username ในระบบ ให้ฟ้อง error ทันที ไม่สร้างใหม่ ไม่ให้สิทธิ์ใดๆ
            if (!$user) {
                Log::warning("Login Failed: Username not found: {$username}", [
                    'ip' => $request->ip()
                ]);
                return back()->withInput()->with('warning', 'ไม่พบชื่อผู้ใช้งานนี้ในระบบ');
            }

            // 4. บันทึก Log การเข้าสู่ระบบ (Mandatory Logging)
            Log::info("User logged in by username: {$username}", [
                'ip' => $request->ip()
            ]);

            // 5. ทำการ Login เข้าสู่ระบบโดยใช้ Auth ของ Laravel (ไม่ต้องเช็ค Password)
            Auth::login($user);

            // 6. เปลี่ยนเส้นทางไปหน้า Dashboard
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            Log::error("Login Error for username {$request->username}: " . $e->getMessage());
            return back()->with('warning', 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ');
        }
    }

    public function login2(Request $request)
    {
        return $this->login($request);
    }
}

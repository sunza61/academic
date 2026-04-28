<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // รับพารามิเตอร์ $roles แบบไม่จำกัดจำนวนเข้ามาด้วย ...$roles
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. ถ้ายังไม่ล็อกอิน ให้เตะไปหน้า Login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // 2. วนลูปเช็คว่า User มี Role ตรงกับที่กำหนดมาใน Route หรือไม่
        foreach ($roles as $role) {
            if (auth()->user()->hasRole($role)) {
                return $next($request); // ถ้ามีสิทธิ์ข้อใดข้อหนึ่ง ให้ผ่านได้เลย
            }
        }

        // 3. ถ้าวนครบแล้วไม่เจอสิทธิ์ตรงเลย ให้เตะออก
        abort(403, 'ขออภัย คุณไม่มีสิทธิ์เข้าถึงส่วนนี้ของระบบครับ');
    }
}

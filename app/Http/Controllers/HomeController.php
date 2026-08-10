<?php

namespace App\Http\Controllers;

use App\Models\Academic\AcademicProject;
use App\Models\Academic\AcademicSdg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['publicDashboard']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect()->route('dashboard');
    }

    //     public function showpage()
    // {
    //     $user = auth()->user();

    //     if ($user->hasRole('admin')) {

    //         return redirect()->route('admin.dashboard');

    //     } elseif ($user->hasRole('manager')) {

    //         return redirect()->route('manager.dashboard');

    //     } elseif ($user->hasRole('staff')) {

    //         return redirect()->route('staff.dashboard');

    //     } elseif ($user->hasRole('user')) {

    //         return redirect()->route('user.dashboard');

    //     } elseif ($user->hasRole('finance')) {

    //         return redirect()->route('finance.dashboard');

    //     } elseif ($user->hasRole('plan')) {

    //         return redirect()->route('plan.dashboard');

    //     }

    //     abort(403, 'คุณไม่มีสิทธิ์เข้าถึง Dashboard');
    // }

    public function publicDashboard()
    {
        // ดึงจำนวนโครงการทั้งหมดที่สถานะผ่านแล้ว (สมมติว่า 500 คืออนุมัติ)
        $totalProjects = AcademicProject::where('overall_status', '>=', 500)->count();

        // 🛑 ปิดบรรทัดนี้ไว้ก่อน เพราะเรายังไม่รู้ว่าจำนวนผู้เข้าร่วมเก็บไว้ที่ฟิลด์ไหน/ตารางไหน
        // $totalParticipants = AcademicProject::where('overall_status', '>=', 500)->sum('ชื่อคอลัมน์หรือตารางที่ถูกต้อง');

        // ส่งค่าไปที่ View (ลบ $totalParticipants ออกจาก compact ก่อนชั่วคราว)
        return view('dashboards.public', compact('totalProjects'));
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

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

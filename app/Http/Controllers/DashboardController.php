<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Router
     *
     * ตรวจสอบ Role ของผู้ใช้งาน
     * แล้วส่งไปยัง Dashboard ที่เหมาะสม
     */
    public function index()
{
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        //dd('ddddd');
        return $this->admin();
    }

    if ($user->hasRole('manager')) {
        return $this->manager();
    }

    if ($user->hasRole('staff')) {
        return $this->staff();
    }

    if ($user->hasRole('user')) {
        return $this->user();
    }

    if ($user->hasRole('finance')) {
        return $this->finance();
    }

    if ($user->hasRole('plan')) {
        return $this->plan();
    }

    abort(403, 'คุณไม่มีสิทธิ์เข้าถึง Dashboard');
}




    /**
     * =====================================================
     * ADMIN DASHBOARD
     * =====================================================
     */
    public function admin()
    {
        return view('dashboards.admin.index');
    }


    /**
     * =====================================================
     * MANAGER DASHBOARD
     * =====================================================
     */
    public function manager()
    {
        return view('dashboards.manager.index');
    }


    /**
     * =====================================================
     * STAFF DASHBOARD
     * =====================================================
     */
    public function staff()
    {
        return view('dashboards.staff.index');
    }


    /**
     * =====================================================
     * USER DASHBOARD
     * =====================================================
     */
    public function user()
    {
        return view('dashboards.user.index');
    }


    /**
     * =====================================================
     * FINANCE DASHBOARD
     * =====================================================
     *
     * ใช้ FinanceDashboardController เดิม
     */
    public function finance()
    {
        return redirect()->route('finance.dashboard');
    }


    /**
     * =====================================================
     * PLAN DASHBOARD
     * =====================================================
     *
     * ใช้ PlanDashboardController เดิม
     */
    public function plan()
    {
        return redirect()->route('plan.dashboard');
    }
}


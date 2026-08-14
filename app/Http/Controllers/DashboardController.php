<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterData\FiscalYears;
use App\Models\Academic\AcademicProject;

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
    public function admin(Request $request = null)
    {
        // คำนวณปีงบประมาณปัจจุบัน (1 ต.ค. - 30 ก.ย.)
        $currentFiscalYearBe = (now()->month >= 10 ? now()->year + 1 : now()->year) + 543;

        // ดึงข้อมูลปีงบประมาณเฉพาะปีปัจจุบันย้อนหลังไป
        $fiscalYears = FiscalYears::where('fiscal_year_be', '<=', $currentFiscalYearBe)
            ->orderBy('fiscal_year_be', 'desc')
            ->get();

        // เลือกปีจาก Request หรือใช้ปีปัจจุบัน
        $selectedFiscalYearId = ($request ? $request->query('fiscal_year') : null)
            ?? $fiscalYears->firstWhere('fiscal_year_be', $currentFiscalYearBe)->id 
            ?? ($fiscalYears->first()->id ?? null);

        // =====================================================
        // เริ่มต้นส่วนการคำนวณ KPI (สำหรับ Admin Dashboard)
        // =====================================================

        // คำนวณจำนวนโครงการตามประเภท
        $countTraining = AcademicProject::where('fiscal_year_id', $selectedFiscalYearId)
            ->where('project_type_id', 2)
            ->where('del_status', '!=', 1)
            ->count();

        $countAcademic = AcademicProject::where('fiscal_year_id', $selectedFiscalYearId)
            ->where('project_type_id', 3)
            ->where('del_status', '!=', 1)
            ->count();

        // รวมยอดรวมโครงการ
        $countTotal = $countTraining + $countAcademic;

        // =====================================================
        // เริ่มต้นส่วนการคำนวณ Project Workflow
        // =====================================================
        
        // นิยามสถานะโครงการ (อ้างอิงจากเดิมใน View)
        $statuses = [
            100 => ['name' => 'เตรียมการ / ฉบับร่าง', 'icon' => 'fas fa-plus', 'color' => 'bg-success'],
            110 => ['name' => 'ตีกลับ', 'icon' => 'fas fa-reply', 'color' => 'bg-danger'],
            200 => ['name' => 'เสนอขออนุมัติ', 'icon' => 'fas fa-paper-plane', 'color' => 'bg-primary'],
            300 => ['name' => 'อนุมัติแล้ว / รอเปิดรับสมัคร', 'icon' => 'fas fa-user-check', 'color' => 'bg-info'],
            400 => ['name' => 'เปิดรับสมัคร', 'icon' => 'fas fa-door-open', 'color' => 'bg-success'],
            500 => ['name' => 'ปิดรับสมัคร / เตรียมจัดงาน', 'icon' => 'fas fa-door-closed', 'color' => 'bg-secondary'],
            600 => ['name' => 'อยู่ระหว่างดำเนินการ', 'icon' => 'fas fa-play', 'color' => 'bg-primary'],
            700 => ['name' => 'รอประเมินผลและรายงาน', 'icon' => 'fas fa-file-upload', 'color' => 'bg-info'],
            800 => ['name' => 'เสร็จสิ้นโครงการ', 'icon' => 'fas fa-flag-checkered', 'color' => 'bg-success'],
            900 => ['name' => 'ยกเลิกโครงการ', 'icon' => 'fas fa-ban', 'color' => 'bg-dark'],
        ];

        // 1. คำนวณ Workflow Statistics
        $data = AcademicProject::where('fiscal_year_id', $selectedFiscalYearId)
            ->where('del_status', '!=', 1)
            ->selectRaw('overall_status, project_type_id, count(*) as total')
            ->groupBy('overall_status', 'project_type_id')
            ->get();

        $workflowStatuses = [];
        foreach ($statuses as $id => $statusInfo) {
            $statusData = $data->where('overall_status', $id);
            $workflowStatuses[] = [
                'id' => $id,
                'name' => $statusInfo['name'],
                'total' => $statusData->sum('total'),
                'type1' => $statusData->where('project_type_id', 2)->sum('total'),
                'type2' => $statusData->where('project_type_id', 3)->sum('total'),
                'type3' => 0,
                'type4' => 0,
            ];
        }

        // 2. คำนวณ System Activity (ล่าสุดของแต่ละสถานะ)
        $latestProjects = AcademicProject::select('academic_projects.*')
            ->where('fiscal_year_id', $selectedFiscalYearId)
            ->where('del_status', '!=', 1)
            ->whereIn('updated_at', function ($query) use ($selectedFiscalYearId) {
                $query->selectRaw('MAX(updated_at)')
                    ->from('academic_projects')
                    ->where('fiscal_year_id', $selectedFiscalYearId)
                    ->where('del_status', '!=', 1)
                    ->groupBy('overall_status');
            })
            ->get()
            ->keyBy('overall_status');

        $latestActivities = [];
        foreach ($statuses as $id => $statusInfo) {
            if ($latestProjects->has($id)) {
                $project = $latestProjects->get($id);
                $latestActivities[] = [
                    'status' => $id,
                    'icon' => $statusInfo['icon'],
                    'color' => $statusInfo['color'],
                    'title' => $statusInfo['name'],
                    'meta' => $project->name_th . ' • ' . $project->updated_at->diffForHumans(),
                ];
            } else {
                $latestActivities[] = [
                    'status' => $id,
                    'icon' => $statusInfo['icon'],
                    'color' => 'bg-light text-secondary',
                    'title' => $statusInfo['name'],
                    'meta' => 'ยังไม่มีโครงการในสถานะนี้',
                ];
            }
        }

        // =====================================================
        // สิ้นสุดส่วนการคำนวณ Project Workflow & System Activity
        // =====================================================

        return view('dashboards.admin.index', compact('fiscalYears', 'selectedFiscalYearId', 'countTotal', 'countTraining', 'countAcademic', 'workflowStatuses', 'latestActivities'));
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

<?php

namespace App\Http\Controllers;

use App\Models\Academic\AcademicProject;
use App\Models\MasterData\FiscalYears;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Router
     *
     * ตรวจสอบ Role ของผู้ใช้งาน
     * แล้วส่งไปยัง Dashboard ที่เหมาะสม
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->admin($request);
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
        // =====================================================
        // 1. ข้อมูลพื้นฐาน
        // =====================================================
        $currentFiscalYearBe = (now()->month >= 10 ? now()->year + 1 : now()->year) + 543;

        $fiscalYears = FiscalYears::where('fiscal_year_be', '<=', $currentFiscalYearBe)
            ->orderBy('fiscal_year_be', 'desc')
            ->get();

        $selectedFiscalYearId = ($request ? $request->query('fiscal_year') : null) 
            ?? $fiscalYears->firstWhere('fiscal_year_be', $currentFiscalYearBe)->id 
            ?? ($fiscalYears->first()->id ?? null);

        // =====================================================
        // 2. ส่วน KPI
        // =====================================================
        $countTraining = AcademicProject::where('fiscal_year_id', $selectedFiscalYearId)
            ->where('project_type_id', 2)
            ->where('del_status', '!=', 1)
            ->count();

        $countAcademic = AcademicProject::where('fiscal_year_id', $selectedFiscalYearId)
            ->where('project_type_id', 3)
            ->where('del_status', '!=', 1)
            ->count();

        $countTotal = $countTraining + $countAcademic;

        // =====================================================
        // 3. ส่วน Project Workflow
        // =====================================================
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

        $allProjects = AcademicProject::where('fiscal_year_id', $selectedFiscalYearId)
            ->where('del_status', '!=', 1)
            ->get();

        $workflowStatuses = [];
        foreach ($statuses as $id => $statusInfo) {
            $statusProjects = $allProjects->where('overall_status', $id);
            $workflowStatuses[] = [
                'id' => $id,
                'name' => $statusInfo['name'],
                'total' => $statusProjects->count(),
                'projects' => $statusProjects,
                'type1' => $statusProjects->where('project_type_id', 2)->count(),
                'type2' => $statusProjects->where('project_type_id', 3)->count(),
                'type3' => 0,
                'type4' => 0,
            ];
        }

        // =====================================================
        // 4. ส่วน System Activity (10 สถานะ)
        // =====================================================
        $allActiveProjects = AcademicProject::with(['latestLog.user'])
            ->where('fiscal_year_id', $selectedFiscalYearId)
            ->where('del_status', '!=', 1)
            ->get()
            ->groupBy('overall_status');

        $latestActivities = [];
        foreach ($statuses as $id => $statusInfo) {
            $project = $allActiveProjects->has($id) ? $allActiveProjects->get($id)->sortByDesc('updated_at')->first() : null;
            
            $latestActivities[] = [
                'status' => $id,
                'icon'   => $statusInfo['icon'],
                'color'  => $statusInfo['color'],
                'title'  => $statusInfo['name'],
                'meta'   => $project ? ($project->name_th . ' • ' . $project->updated_at->diffForHumans()) : 'ยังไม่มีโครงการ',
            ];
        }

        // =====================================================
        // 5. ส่วน Approval & Attention Queue
        // =====================================================
        $countWaitingApproval = AcademicProject::where('fiscal_year_id', $selectedFiscalYearId)
            ->where('overall_status', 200)
            ->where('del_status', '!=', 1)
            ->count();

        $countWaitingRevision = AcademicProject::where('fiscal_year_id', $selectedFiscalYearId)
            ->where('overall_status', 110)
            ->where('del_status', '!=', 1)
            ->count();

        $countAttention = AcademicProject::where('fiscal_year_id', $selectedFiscalYearId)
            ->where('del_status', '!=', 1)
            ->whereNotIn('overall_status', [800, 900])
            ->whereBetween('end_date', [now(), now()->addDays(7)])
            ->count();

        // =====================================================
        // 6. ส่วน Project Health
        // =====================================================
        $allActiveProjectsHealth = AcademicProject::where('fiscal_year_id', $selectedFiscalYearId)
            ->where('del_status', '!=', 1)
            ->leftJoin('overall_statuses', 'academic_projects.overall_status', '=', 'overall_statuses.code')
            ->select('academic_projects.*', 'overall_statuses.name_th as status_name_th')
            ->get();

        $totalActive = $allActiveProjectsHealth->count();

        $criticalProjects = $allActiveProjectsHealth->filter(function ($project) {
            return in_array($project->overall_status, [900, 110]) ||
                ($project->overall_status < 800 && $project->end_date && $project->end_date < now());
        });

        $attentionProjects = $allActiveProjectsHealth->filter(function ($project) {
            return in_array($project->overall_status, [200, 700]) ||
                ($project->overall_status < 800 && $project->end_date && $project->end_date >= now() && $project->end_date <= now()->addDays(7));
        });

        $onTrackProjects = $allActiveProjectsHealth->diff($criticalProjects)->diff($attentionProjects);

        $pctCritical  = $totalActive > 0 ? ($criticalProjects->count() / $totalActive) * 100 : 0;
        $pctAttention = $totalActive > 0 ? ($attentionProjects->count() / $totalActive) * 100 : 0;
        $pctOnTrack   = $totalActive > 0 ? ($onTrackProjects->count() / $totalActive) * 100 : 0;

        $healthData = [
            'critical'  => ['count' => $criticalProjects->count(), 'pct' => $pctCritical, 'projects' => $criticalProjects],
            'attention' => ['count' => $attentionProjects->count(), 'pct' => $pctAttention, 'projects' => $attentionProjects],
            'on_track'  => ['count' => $onTrackProjects->count(), 'pct' => $pctOnTrack, 'projects' => $onTrackProjects],
        ];

        // =====================================================
        // 7. ส่วน Recent Project Activity (ตาราง 4 โครงการล่าสุด)
        // =====================================================
        $recentProjectActivity = AcademicProject::with(['latestLog.user'])
            ->where('del_status', '!=', 1)
            ->latest('updated_at')
            ->take(4)
            ->get()
            ->map(function ($project) use ($statuses) {
                $latestLog = $project->latestLog;
                $statusCode = $project->overall_status ?? 0;
                $statusInfo = $statuses[$statusCode] ?? ['name' => 'ไม่ระบุ', 'color' => 'bg-secondary'];

                return [
                    'project_id'   => $project->id,
                    'project_name' => $project->name_th,
                    'project_type' => $project->project_type_id,
                    'user_name'    => ($latestLog && $latestLog->user) ? $latestLog->user->name : '-',
                    'action'       => $latestLog ? ($latestLog->comment ?: $latestLog->action) : 'อัปเดตข้อมูล',
                    'time'         => $project->updated_at->diffForHumans(),
                    'status_name'  => $statusInfo['name'],
                    'status_color' => $statusInfo['color'],
                ];
            });

        // =====================================================
        // สรุปข้อมูลส่งออก
        // =====================================================
        return view('dashboards.admin.index', compact(
            'fiscalYears', 
            'selectedFiscalYearId', 
            'countTotal', 
            'countTraining', 
            'countAcademic', 
            'workflowStatuses', 
            'latestActivities', 
            'recentProjectActivity',
            'countWaitingApproval', 
            'countWaitingRevision', 
            'countAttention', 
            'healthData'
        ));
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
     */
    public function finance()
    {
        return redirect()->route('finance.dashboard');
    }

    /**
     * =====================================================
     * PLAN DASHBOARD
     * =====================================================
     */
    public function plan()
    {
        return redirect()->route('plan.dashboard');
    }
}

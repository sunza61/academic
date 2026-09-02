<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Academic\AcademicProject;
use App\Models\MasterData\FiscalYears;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AllProjectsController extends Controller
{
    public function index(Request $request)
    {
        // 1. หาปีงบประมาณล่าสุด
        $currentFiscalYear = FiscalYears::orderBy('fiscal_year_be', 'desc')->first();
        $fiscalYearId = $request->query('fiscal_year_id', $currentFiscalYear->id ?? null);
        $typeId = $request->query('type_id');
        $statusCode = $request->query('status');
        $attention = $request->query('attention'); // เพิ่มการรับ attention

        // 2. Query ดึงโครงการทั้งหมด
        $query = AcademicProject::select(
            'academic_projects.*',
            'users.name as creator_name',
            'overall_statuses.name_th as status_name',
            'project_types.name_th as type_name'
        )
            ->leftJoin('users', 'academic_projects.created_by', '=', 'users.id')
            ->leftJoin('overall_statuses', 'academic_projects.overall_status', '=', 'overall_statuses.code')
            ->leftJoin('project_types', 'academic_projects.project_type_id', '=', 'project_types.id')
            ->where('academic_projects.fiscal_year_id', $fiscalYearId)
            ->where(function ($q) {
                $q->whereNull('academic_projects.del_status')
                    ->orWhere('academic_projects.del_status', 0);
            });

        // กรองตามประเภทโครงการ ถ้ามีระบุ
        if ($typeId) {
            $query->where('academic_projects.project_type_id', $typeId);
        }

        // กรองตามสถานะโครงการ ถ้ามีระบุ
        if ($statusCode) {
            $query->where('academic_projects.overall_status', $statusCode);
        }

        // กรองโครงการใกล้ครบกำหนด (ใน 7 วัน)
        if ($attention) {
            $today = Carbon::today();
            $sevenDaysLater = Carbon::today()->addDays(7);

            // กรองสถานะที่ยังไม่เสร็จ (ตัวอย่าง: ไม่ใช่ 800-เสร็จสิ้น, 900-ยกเลิก)
            $query->whereNotIn('academic_projects.overall_status', [800, 900])
                  ->whereBetween('academic_projects.end_date', [$today, $sevenDaysLater]);
        }


        // ดึง user ปัจจุบันเพื่อเช็คสิทธิ์ (ปรับตาม logic เดิม)
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin', 'staff']) && !$user->hasRole('manager')) {
            $query->where('academic_projects.created_by', $user->id);
        }

        $projects = $query->orderBy('academic_projects.id', 'desc')->get();

        // ดึงรายชื่อปีงบประมาณเพื่อไปทำ dropdown
        $fiscalYears = FiscalYears::orderBy('fiscal_year_be', 'desc')->get();

        return view('projects.all_index', compact('projects', 'fiscalYears', 'fiscalYearId'));
    }
}

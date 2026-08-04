<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Academic\AcademicProject;
use Illuminate\Support\Facades\DB;

class FinanceDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:finance|admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 1. ดึงชื่อสถานะจากตาราง overall_statuses โดยเอาโค้ด 800 ไปเช็คในฟิลด์ code
        $statusInfo = \DB::table('overall_statuses')->where('code', 800)->first();
        $statusName = $statusInfo ? $statusInfo->name_th : 'เสร็จสิ้นโครงการ';

        // 2. ดึงเฉพาะโครงการที่เสร็จสิ้นแล้ว (800)
        // พร้อมคำนวณยอดรวมรายรับ และรายจ่ายรายโครงการ (ใช้ Subquery เพื่อความแม่นยำ)
        $projects = AcademicProject::select('academic_projects.*')
            ->addSelect([
                'status_name' => \DB::table('overall_statuses')
                    ->select('name_th')
                    ->whereColumn('code', 'academic_projects.overall_status')
                    ->limit(1),
                'total_income' => \DB::table('academic_budget_incomes')
                    ->selectRaw('SUM(total_amount)')
                    ->whereColumn('academic_project_id', 'academic_projects.id'),
                'total_expense' => \DB::table('academic_budget_expenses')
                    ->selectRaw('SUM(total_amount)')
                    ->whereColumn('academic_project_id', 'academic_projects.id'),
            ])
            ->with(['latestLog'])
            ->where('academic_projects.overall_status', 800) 
            ->orderBy('academic_projects.id', 'desc')
            ->get();

        return view('finance.dashboard', compact('projects', 'statusName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

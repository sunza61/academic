<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicBudget;
use Illuminate\Http\Request;
use App\Models\MasterData\ProjectType;
use App\Models\MasterData\FiscalYears;
use App\Models\MasterData\Center;
use App\Models\MasterData\TargetGroup;
use App\Models\MasterData\Sdg;
use App\Models\MasterData\Nationality;
use App\Models\MasterData\ProjectPosition;
use App\Models\Project\Employer;
use App\Models\MasterData\Province;
use App\Models\Academic\AcademicProject;
use App\Models\Academic\AcademicDepartment;
use App\Models\Academic\AcademicObjective;
use App\Models\Academic\AcademicSdg;
use App\Models\Academic\AcademicTargetGroup;
use App\Models\Academic\Committee;
use App\Models\Project\ProjectContract;
use App\Models\AcademicProjectLog;
use App\Models\Academic\AcademicBudgetExpenses;
use App\Models\Academic\AcademicBudgetIncomes;
use App\Models\Academic\AcademicInstallments;
use App\Models\MasterData\BudgetExpenseCategorie;
use App\Models\MasterData\BudgetExpenseMainCategory;
use App\Models\MasterData\BudgetIncomeCategorie;
use App\Models\MasterData\BudgetIncomeMainCategory;
use App\Models\Academic\AcademicProjectEvaluation;
use App\Models\Academic\CustomerGroup;
use App\Models\MasterData\AcademicProjectSignature;
use App\Models\MasterData\CustomerType;
use App\Models\MasterData\External;
use App\Models\MasterData\MasterSignatureRole;
use App\Models\MasterData\Prefix;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ContractProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $typeId = $request->query('type_id');
        if (!$typeId) {
            return redirect()->route('projects.select-type')
                ->with('error', 'กรุณาเลือกประเภทโครงการก่อนเข้าใช้งานครับ');
        }

        $projectType = ProjectType::findOrFail($typeId);

        $query = AcademicProject::select(
            'academic_projects.*',
            'users.name as name',
            'overall_statuses.name_th as overall_statuses_name_th'
        )
            ->leftJoin('users', 'academic_projects.created_by', '=', 'users.id')
            ->leftJoin('overall_statuses', 'academic_projects.overall_status', '=', 'overall_statuses.code')
            ->where('academic_projects.project_type_id', $typeId)
            ->where(function ($q) {
                $q->whereNull('academic_projects.del_status')
                    ->orWhere('academic_projects.del_status', 0);
            });

        // ดึง user ปัจจุบันเพื่อเช็คสิทธิ์
        $user = auth()->user();
        $isAdminOrStaff = $user->hasAnyRole(['admin', 'staff']);

        if (!$isAdminOrStaff && !$user->hasRole('manager')) {
            $query->where('academic_projects.created_by', $user->id);
        }

        // ดึงข้อมูลโปรเจกต์ทั้งหมด พ่วงเอา latestLog ติดมาด้วย
        $projects = $query->with('latestLog.user')->orderBy('academic_projects.id', 'desc')->get();

        $projects->map(function ($item) use ($user, $isAdminOrStaff) {
            $isOwner = isset($item->created_by) && $item->created_by == $user->id;
            $status = $item->overall_status;

            // แนบค่าสิทธิ์ต่างๆ
            $item->can_edit = ($status != 800) && ($isAdminOrStaff || $isOwner);
            $item->show_delete_btn = ($isAdminOrStaff || $isOwner);
            $item->can_report = ($status >= 600 && $status != 900) && ($isAdminOrStaff || $isOwner);

            $canCancel = false;
            if ($status != 800 && $status != 900) {
                if ($isAdminOrStaff) {
                    $canCancel = true;
                } elseif ($isOwner && $status <= 700) {
                    $canCancel = true;
                }
            }
            $item->can_cancel = $canCancel;

            // ดึงข้อมูลจาก Log ล่าสุด
            if ($item->latestLog) {
                $item->log_reason = $item->latestLog->comment;
                $item->log_action_by = $item->latestLog->user->name ?? 'ไม่ระบุ';
                $item->log_action_date = Carbon::parse($item->latestLog->created_at)->addYears(543)->format('d/m/Y H:i');
            } else {
                $item->log_reason = 'ไม่พบเหตุผลที่ระบุไว้';
                $item->log_action_by = '-';
                $item->log_action_date = '-';
            }

            return $item;
        });

        return view('contracts.projects.index', compact('projectType', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $typeId = $request->query('type_id', 3); // Default เป็น 3 (สัญญาจ้าง)
        $projectType = ProjectType::findOrFail($typeId);

        // ดึงข้อมูลพื้นฐานที่ต้องใช้ในฟอร์ม
        $fiscalYears = FiscalYears::orderBy('fiscal_year_be', 'desc')->get();
        $departments = DB::table('V_DEPARTMENT')->get();
        $divisions = DB::table('V_DIVISION')->get();
        $centers = Center::where('is_active', 1)->get();

        // ดึงข้อมูลวัตถุประสงค์หลัก (TargetGroup ที่ไม่มี parent) เพื่อใช้เป็น "กลุ่มผู้ว่าจ้าง/แหล่งทุน"
        $customerGroups = TargetGroup::whereNull('parent_id')
            ->where('is_active', 1)
            ->get();

        // ส่งไปที่หน้า Create ของ Contracts
        return view('contracts.projects.create', compact(
            'projectType',
            'fiscalYears',
            'departments',
            'divisions',
            'centers',
            'customerGroups'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validation ข้อมูลแท็บ 1
        $request->validate([
            'fiscal_year_id' => 'required',
            'name_th' => 'required|string|max:1000',
            'department_ids' => 'required|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'objectives' => 'required|array',
            'objectives.*.group_id' => 'required',
        ], [
            'fiscal_year_id.required' => 'กรุณาเลือกปีงบประมาณ',
            'name_th.required' => 'กรุณากรอกชื่อโครงการ',
            'department_ids.required' => 'กรุณาเลือกหน่วยงานผู้รับผิดชอบ',
            'start_date.required' => 'กรุณาระบุวันเริ่มต้นโครงการ',
            'end_date.required' => 'กรุณาระบุวันสิ้นสุดโครงการ',
            'end_date.after_or_equal' => 'วันสิ้นสุดต้องไม่ก่อนวันเริ่มต้น',
            'objectives.required' => 'กรุณาเพิ่มวัตถุประสงค์โครงการอย่างน้อย 1 รายการ',
            'objectives.*.group_id.required' => 'กรุณาเลือกกลุ่มผู้ว่าจ้าง/แหล่งทุนในวัตถุประสงค์',
        ]);

        DB::beginTransaction();
        try {
            // 2. บันทึกข้อมูลโครงการหลัก (ตาราง academic_projects)
            $project = AcademicProject::create([
                'project_type_id' => $request->project_type_id,
                'fiscal_year_id' => $request->fiscal_year_id,
                'name_th' => $request->name_th,
                'center_id' => $request->center_id,
                'region_type' => $request->region_type,
                'brief_description' => $request->brief_description,
                'rationale' => $request->rationale,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'overall_status' => 100, // เริ่มต้นที่สถานะร่าง
                'created_by' => auth()->id(),
            ]);

            // 3. บันทึกหน่วยงานผู้รับผิดชอบ (ตาราง academic_departments)
            foreach ($request->department_ids as $deptId) {
                AcademicDepartment::create([
                    'academic_project_id' => $project->id,
                    'department_id' => $deptId
                ]);
            }

            // 4. บันทึกวัตถุประสงค์โครงการ (ตาราง academic_objectives)
            foreach ($request->objectives as $obj) {
                if (!empty($obj['group_id'])) {
                    AcademicObjective::create([
                        'academic_project_id' => $project->id,
                        'target_group_id' => $obj['group_id'],
                        'detail' => $obj['detail'] ?? null
                    ]);
                }
            }

            // 5. บันทึก Log การสร้างโครงการ (Mandatory Logging)
            AcademicProjectLog::create([
                'academic_project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'created',
                'status_code' => 100,
                'comment' => 'เริ่มสร้างโครงการบริการวิชาการ (ฉบับร่าง)'
            ]);

            DB::commit();

            // 6. Redirect ไปที่หน้า Edit พร้อมเปิดแท็บที่ 2
            return redirect()->route('contracts.projects.edit', [
                'project' => $project->id,
                'tab' => 'tab2'
            ])->with('success', 'บันทึกข้อมูลพื้นฐานสำเร็จ! กรุณากรอกข้อมูลส่วนต่อไป');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing contract project: ' . $e->getMessage());
            return back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // 1. รับค่าแท็บ (ถ้าไม่มีให้เปิดแท็บ 1 เป็น Default)
        $activeTab = $request->query('tab', 'tab1');

        // 2. ดึงข้อมูล Project ปัจจุบัน
        $project = AcademicProject::with(['signatures'])->findOrFail($id);

        // 🛡️ ดักจับสิทธิ์การเข้าถึง:
        $this->authorize('view', $project);

        // ==========================================
        // 3. ดึง Master Data ที่ใช้ร่วมกัน
        // ==========================================
        $projectType = ProjectType::findOrFail($project->project_type_id);
        $fiscalYears = FiscalYears::orderBy('fiscal_year_be', 'desc')->get();
        $departments = DB::table('V_DEPARTMENT')->get();
        $divisions = DB::table('V_DIVISION')->get();
        $centers = Center::where('is_active', 1)->get();
        $targetGroups = TargetGroup::whereNull('parent_id')->where('is_active', 1)->get();
        $nationalities = Nationality::where('is_active', 1)->get();
        $projectPositions = ProjectPosition::all();
        $prefixes = Prefix::all();
        $customerGroups = CustomerGroup::all();
        $customerTypes = CustomerType::all();
        $externals = External::with('prefix')->get();
        $sdgs = Sdg::where('is_active', 1)->get();

        $incomeCategoriesGrouped = BudgetIncomeMainCategory::with(['subCategories' => function ($query) {
            $query->where('is_active', 1);
        }])->where('is_active', 1)->get();

        $expenseCategoriesGrouped = BudgetExpenseMainCategory::with(['subCategories' => function ($query) {
            $query->where('is_active', 1);
        }])->where('is_active', 1)->get();

        $signatureRoles = DB::table('master_signature_roles')->get();

        // 🌟 ดึงข้อมูลบุคลากร (View ใหม่)
        $staffs = DB::table('V_STAFF_NEWWEB')
            ->select(
                'STAFF_ID',
                'ACADEMIC_ABBR',
                'TITLE_TH',
                'NAME_TH',
                'SURNAME_TH',
                'DEPARTMENT_NAME_TH',
                DB::raw("COALESCE(
                (
                    SELECT STUFF((
                        SELECT ' / ' + POSITION_FULLNAME_TH
                        FROM V_STAFF_EXECUTIVE_NOW
                        WHERE V_STAFF_EXECUTIVE_NOW.STAFF_ID = V_STAFF_NEWWEB.STAFF_ID
                        FOR XML PATH(''), TYPE
                    ).value('.', 'NVARCHAR(MAX)'), 1, 3, '')
                ), 
                POSITION_NAME
            ) AS FINAL_POSITION")
            )->get();

        // ==========================================
        // 🌟 4. ข้อมูลที่บันทึกไว้ของโครงการนี้ (Saved Data)
        // ==========================================

        // --- Tab 1: ข้อมูลพื้นฐาน ---
        $selectedDepartments = DB::table('academic_departments')
            ->where('academic_project_id', $id)
            ->pluck('department_id')->toArray();

        $selectedObjectives = DB::table('academic_objectives')
            ->where('academic_project_id', $id)
            ->pluck('target_group_id')->toArray();

        // กรองกลุ่มเป้าหมายตามวัตถุประสงค์
        $allGroupsForFilter = TargetGroup::with('parent')->where('is_active', 1)->get();
        $filteredTargetGroups = $allGroupsForFilter->filter(function ($group) use ($selectedObjectives) {
            $current = $group;
            while ($current) {
                if (in_array($current->id, $selectedObjectives)) {
                    return true;
                }
                $current = $current->parent;
            }
            return false;
        })->sortBy('full_path')->values();

        // --- Tab 2: ข้อมูลสัญญาและคณะทำงาน ---
        $projectContract = ProjectContract::where('academic_project_id', $id)->first();
        $selectedSdgs = AcademicSdg::where('academic_project_id', $id)->pluck('sdg_id')->toArray();
        $savedTargetGroups = AcademicTargetGroup::where('academic_project_id', $id)->get();
        $savedCommittees = Committee::where('academic_project_id', $id)->get();
        $savedObjectives = AcademicObjective::where('academic_project_id', $id)->get();
        // --- Tab 3 & 4: งบประมาณและงวดงาน ---
        $savedIncomes = AcademicBudgetIncomes::where('academic_project_id', $id)->get();

        // 🟢 แยกรายจ่าย (ค่าดำเนินการ)
        $savedExpenses = AcademicBudgetExpenses::where('academic_project_id', $id)
            ->where(function ($q) {
                $q->where('expense_type', 'operation')
                    ->orWhereNull('expense_type');
            })->get();

        // 🟢 แยกรายจ่าย (ค่าตอบแทน)
        $savedRemunerations = AcademicBudgetExpenses::where('academic_project_id', $id)
            ->where('expense_type', 'remuneration')
            ->get();
            //dd($savedRemunerations);

        $savedBudget = AcademicBudget::where('academic_project_id', $id)->first();
        
        // 🟢 ดึงข้อมูลและจัดการฟอร์แมตวันที่สำหรับงวดงาน
        $savedInstallments = AcademicInstallments::where('academic_project_id', $id)->orderBy('installment_no')->get();
        $savedInstallments->map(function ($inst) {
            $inst->start_date_show = !empty($inst->start_date) ? \Carbon\Carbon::parse($inst->start_date)->format('Y-m-d') : '';
            $inst->end_date_show = !empty($inst->end_date) ? \Carbon\Carbon::parse($inst->end_date)->format('Y-m-d') : '';
            return $inst;
        });

        // 🟢 จัดการชื่อหมวดหมู่แผนรายรับ
        if ($savedIncomes && isset($incomeCategoriesGrouped)) {
            $savedIncomes->map(function ($inc) use ($incomeCategoriesGrouped) {
                $inc->category_name = '-';
                foreach ($incomeCategoriesGrouped as $main) {
                    $found = $main->subCategories->where('id', $inc->category_id)->first();
                    if ($found) {
                        $inc->category_name = $found->name_th;
                        break;
                    }
                }
                return $inc;
            });
        }

        // 🟢 คำนวณยอดรวมต่างๆ
        $totalIncome = $savedIncomes ? $savedIncomes->sum('total_amount') : 0;
        $totalExpense = $savedExpenses ? $savedExpenses->sum('total_amount') : 0;
        $totalRemuneration = $savedRemunerations ? $savedRemunerations->sum('total_amount') : 0;
        $serviceFee = $savedBudget->service_fee_amount ?? 0;
        $balance = $totalIncome - ($totalExpense + $totalRemuneration + $serviceFee);
        

        // --- Tab 5: ผลการประเมิน ---
        $projectEvaluation = AcademicProjectEvaluation::where('academic_project_id', $id)->first();

        // ==========================================
        // 5. ส่งข้อมูลไปยัง View ของโครงการบริการวิชาการ
        // ==========================================
        return view('contracts.projects.show', compact(
            'activeTab',
            'project',
            'projectType',
            'fiscalYears',
            'departments',
            'divisions',
            'centers',
            'targetGroups',
            'selectedDepartments',
            'selectedObjectives',
            'filteredTargetGroups',
            'nationalities',
            'projectPositions',
            'prefixes',
            'staffs',
            'customerGroups',
            'customerTypes',
            'externals',
            'sdgs',
            'selectedSdgs',
            'projectContract',
            'savedTargetGroups',
            'savedCommittees',
            'savedIncomes',
            'savedExpenses',
            'savedRemunerations', // ➕ เพิ่มตัวแปรนี้
            'savedBudget',
            'savedInstallments',
            'incomeCategoriesGrouped',
            'expenseCategoriesGrouped',
            'projectEvaluation',
            'signatureRoles',
            'totalIncome',       // ➕ เพิ่มตัวแปรนี้
            'totalExpense',      // ➕ เพิ่มตัวแปรนี้
            'totalRemuneration', // ➕ เพิ่มตัวแปรนี้
            'balance',
            'savedObjectives'            // ➕ เพิ่มตัวแปรนี้
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $project = AcademicProject::findOrFail($id);
        $activeTab = $request->query('tab', 'tab1');

        $projectType = ProjectType::findOrFail($project->project_type_id);
        $fiscalYears = FiscalYears::orderBy('fiscal_year_be', 'desc')->get();
        $departments = DB::table('V_DEPARTMENT')->get();
        $divisions = DB::table('V_DIVISION')->get();
        $centers = Center::where('is_active', 1)->get();
        $customerGroups = TargetGroup::whereNull('parent_id')->where('is_active', 1)->get();

        // 🌟 ข้อมูลสำหรับ Tab 2 (ข้อมูลเฉพาะ & บุคคล)
        $projectContract = ProjectContract::where('academic_project_id', $id)->first();
        $sdgs = Sdg::where('is_active', 1)->get();
        $nationalities = Nationality::where('is_active', 1)->get();
        $projectPositions = ProjectPosition::get();
        $employers = Employer::where('is_active', 1)->get();
        $provinces = Province::orderBy('name_th')->get();

        // ดึงรายชื่อบุคลากร (อัปเดตไปใช้ View ใหม่: V_STAFF_NEWWEB)
        $staffs = DB::table('V_STAFF_NEWWEB')
            ->select(
                'STAFF_ID',
                'ACADEMIC_ABBR',
                'TITLE_TH',
                'NAME_TH',
                'SURNAME_TH',
                'DEPARTMENT_NAME_TH',
                // 🌟 ท่าไม้ตาย FOR XML PATH สำหรับ SQL Server (มัดรวมตำแหน่งคั่นด้วย ' / ')
                DB::raw("COALESCE(
                    (
                        SELECT STUFF((
                            SELECT ' / ' + POSITION_FULLNAME_TH
                            FROM V_STAFF_EXECUTIVE_NOW
                            WHERE V_STAFF_EXECUTIVE_NOW.STAFF_ID = V_STAFF_NEWWEB.STAFF_ID
                            FOR XML PATH(''), TYPE
                        ).value('.', 'NVARCHAR(MAX)'), 1, 3, '')
                    ), 
                    POSITION_NAME
                ) AS FINAL_POSITION")
            )
            ->orderBy('NAME_TH')
            ->get();
        //$externals = DB::table('externals')->where('is_active', 1)->get();
        $externals = External::with('prefix')->get();
        $filteredTargetGroups = TargetGroup::whereNotNull('parent_id')
            ->where('is_active', 1)
            ->get()
            ->map(function ($item) {
                $item->full_path = $item->full_path;
                return $item;
            })
            ->sortBy('full_path');

        // ข้อมูลที่บันทึกไว้แล้ว
        $selectedDepartments = AcademicDepartment::where('academic_project_id', $id)->pluck('department_id')->toArray();
        $savedObjectives = AcademicObjective::where('academic_project_id', $id)->get();
        $selectedSdgs = AcademicSdg::where('academic_project_id', $id)->pluck('sdg_id')->toArray();
        $savedTargetGroups = AcademicTargetGroup::where('academic_project_id', $id)->get();
        $savedCommittees = Committee::where('academic_project_id', $id)->get();

        // 🌟 ข้อมูลสำหรับ Tab 3 / Tab 4 (งบประมาณและงวดงาน)
        $incomeCategoriesGrouped = BudgetIncomeMainCategory::with(['subCategories' => function ($query) {
            $query->where('is_active', 1);
        }])->where('is_active', 1)->get();

        $expenseCategoriesGrouped = BudgetExpenseMainCategory::with(['subCategories' => function ($query) {
            $query->where('is_active', 1);
        }])->where('is_active', 1)->get();

        // 1. แผนรายรับ
        $savedIncomes = AcademicBudgetIncomes::where('academic_project_id', $id)->get();

        // 2. แผนรายจ่าย (ค่าดำเนินการ) -> กรองเฉพาะ operation
        $savedExpenses = AcademicBudgetExpenses::where('academic_project_id', $id)
            ->where(function ($q) {
                $q->where('expense_type', 'operation')
                    ->orWhereNull('expense_type'); // เผื่อข้อมูลเก่าที่ยังไม่มีประเภท
            })->get();

        // 🔥 3. เพิ่มการดึง แผนรายจ่าย (ค่าตอบแทน) -> กรองเฉพาะ remuneration
        $savedRemunerations = AcademicBudgetExpenses::where('academic_project_id', $id)
            ->where('expense_type', 'remuneration')
            ->get();

        // 🔥 4. เพิ่มการดึง ข้อมูลสรุปงบประมาณ/ค่าธรรมเนียม (3.4)
        $savedBudget = AcademicBudget::where('academic_project_id', $id)->first();

        // 🔥 5. เพิ่มการดึง ข้อมูลลงงวดงาน (3.5)
        $savedInstallments = AcademicInstallments::where('academic_project_id', $id)
            ->orderBy('installment_no', 'asc')
            ->get();
        $savedInstallments->map(function ($inst) {
            // โยน ค.ศ. Format 'Y-m-d' ไปตรงๆ เลย เช่น "2026-07-24"
            $inst->start_date_show = !empty($inst->start_date) ? Carbon::parse($inst->start_date)->format('Y-m-d') : '';
            $inst->end_date_show = !empty($inst->end_date) ? Carbon::parse($inst->end_date)->format('Y-m-d') : '';
            return $inst;
        });
        //dd($savedInstallments);
        // 🌟 ข้อมูลสำหรับ Tab 5 (ประเมิน) และ Tab 6 (ผู้ลงนาม)
        $projectEvaluation = AcademicProjectEvaluation::where('academic_project_id', $id)->first();
        $signatureRoles = MasterSignatureRole::where('is_active', 1)->get();

        $totalIncome = $savedIncomes ? $savedIncomes->sum('total_amount') : 0;
        $totalExpense = $savedExpenses ? $savedExpenses->sum('total_amount') : 0;
        $totalRemuneration = $savedRemunerations ? $savedRemunerations->sum('total_amount') : 0;

        // 2. ดึงค่าธรรมเนียม และคำนวณยอดคงเหลือ
        $serviceFee = $savedBudget->service_fee_amount ?? 0;
        $balance = $totalIncome - ($totalExpense + $totalRemuneration + $serviceFee);

        // 3. จัดการเรื่อง "ชื่อหมวดหมู่" ที่เคยใช้ @php วนลูปหาใน Blade
        // แนะนำให้ map ชื่อหมวดหมู่ใส่เข้าไปใน object เลย เพื่อให้ Blade เรียกใช้ง่ายๆ
        if ($savedIncomes && isset($incomeCategoriesGrouped)) {
            $savedIncomes->map(function ($inc) use ($incomeCategoriesGrouped) {
                $inc->category_name = '-';
                foreach ($incomeCategoriesGrouped as $main) {
                    $found = $main->subCategories->where('id', $inc->category_id)->first();
                    if ($found) {
                        $inc->category_name = $found->name_th;
                        break;
                    }
                }
                return $inc;
            });
        }
        // 🛑 อย่าลืมยัดตัวแปรใหม่ 3 ตัว เข้าไปใน compact() ด้วยนะครับ!
        return view('contracts.projects.edit', compact(
            'project',
            'projectType',
            'fiscalYears',
            'departments',
            'divisions',
            'centers',
            'customerGroups',
            'activeTab',
            'selectedDepartments',
            'savedObjectives',
            'projectContract',
            'sdgs',
            'nationalities',
            'projectPositions',
            'employers',
            'provinces',
            'staffs',
            'externals',
            'filteredTargetGroups',
            'selectedSdgs',
            'savedTargetGroups',
            'savedCommittees',
            'incomeCategoriesGrouped',
            'expenseCategoriesGrouped',
            'savedIncomes',
            'savedExpenses',
            'savedRemunerations',
            'savedBudget',
            'savedInstallments',
            'projectEvaluation',
            'signatureRoles',
            'savedIncomes',
            'savedExpenses',
            'savedRemunerations',
            'savedBudget',
            'savedInstallments',
            'totalIncome',
            'totalExpense',
            'totalRemuneration',
            'balance'
        ));
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
        $project = AcademicProject::findOrFail($id);
        $step = $request->input('step', 1);

        if ($step == 1) {
            $request->validate([
                'fiscal_year_id' => 'required',
                'name_th' => 'required|string|max:1000',
                'department_ids' => 'required|array',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'objectives' => 'required|array',
                'objectives.*.group_id' => 'required',
            ]);

            DB::beginTransaction();
            try {
                $project->update([
                    'fiscal_year_id' => $request->fiscal_year_id,
                    'name_th' => $request->name_th,
                    'center_id' => $request->center_id,
                    'region_type' => $request->region_type,
                    'brief_description' => $request->brief_description,
                    'rationale' => $request->rationale,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ]);

                AcademicDepartment::where('academic_project_id', $id)->delete();
                foreach ($request->department_ids as $deptId) {
                    AcademicDepartment::create(['academic_project_id' => $id, 'department_id' => $deptId]);
                }

                AcademicObjective::where('academic_project_id', $id)->delete();
                foreach ($request->objectives as $obj) {
                    if (!empty($obj['group_id'])) {
                        AcademicObjective::create([
                            'academic_project_id' => $id,
                            'target_group_id' => $obj['group_id'],
                            'detail' => $obj['detail'] ?? null
                        ]);
                    }
                }

                AcademicProjectLog::create([
                    'academic_project_id' => $id,
                    'user_id' => auth()->id(),
                    'action' => 'updated',
                    'status_code' => $project->overall_status,
                    'comment' => 'แก้ไขข้อมูลพื้นฐานโครงการ'
                ]);

                DB::commit();
                return redirect()->route('contracts.projects.edit', ['project' => $id, 'tab' => 'tab2'])->with('success', 'อัปเดตข้อมูลพื้นฐานสำเร็จ!');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Update step 1 error: ' . $e->getMessage());
                return back()->withInput()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            }
        } elseif ($step == 2) {
            $request->validate([
                'sdgs' => 'required|array',
            ]);

            DB::beginTransaction();
            try {
                // 1. จัดการข้อมูลสัญญาจ้าง (เอา employer_id และ total_budget ออกตาม UI)
                $existingContract = ProjectContract::where('academic_project_id', $id)->first();
                $filePath = $existingContract->contract_file_path ?? null;

                if ($request->hasFile('contract_file')) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                    $file = $request->file('contract_file');
                    $filename = time() . '_' . uniqid() . '.' . $file->extension();
                    $filePath = $file->storeAs('contracts', $filename, 'public');
                } elseif ($request->input('remove_contract_file') == '1') {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                    $filePath = null;
                }

                ProjectContract::updateOrCreate(
                    ['academic_project_id' => $id],
                    [
                        'contract_number' => $request->contract_number,
                        'contract_file_path' => $filePath,
                        'contract_file_link' => $request->contract_file_link,
                    ]
                );

                // 2. จัดการ SDGs
                AcademicSdg::where('academic_project_id', $id)->delete();
                if ($request->has('sdgs')) {
                    foreach ($request->sdgs as $sdgId) {
                        AcademicSdg::create(['academic_project_id' => $id, 'sdg_id' => $sdgId]);
                    }
                }

                // 3. จัดการกลุ่มผู้ว่าจ้าง/แหล่งทุน (ชื่อเดิมคือกลุ่มเป้าหมาย)
                AcademicTargetGroup::where('academic_project_id', $id)->delete();
                if ($request->has('target_groups') && isset($request->target_groups['customer_group_id'])) {
                    foreach ($request->target_groups['customer_group_id'] as $i => $tgId) {
                        if (!empty($tgId)) {
                            AcademicTargetGroup::create([
                                'academic_project_id' => $id,
                                'target_group_id' => $tgId,
                                'nationality_id' => $request->target_groups['nationality_id'][$i],
                                'total' => 0, // ตั้งค่าเป็น 0 เพราะเอาคอลัมน์จำนวนออกแล้ว
                            ]);
                        }
                    }
                }

                // 4. จัดการคณะทำงาน
                Committee::where('academic_project_id', $id)->delete();
                if ($request->has('committees') && isset($request->committees['member_type'])) {
                    foreach ($request->committees['member_type'] as $i => $type) {
                        $personnelId = ($type == '1') ? ($request->committees['personnel_id'][$i] ?? null) : null;
                        $externalId = ($type == '0') ? ($request->committees['external_id'][$i] ?? null) : null;

                        if ($personnelId || $externalId) {
                            Committee::create([
                                'academic_project_id' => $id,
                                'member_type' => $type,
                                'personnel_id' => $personnelId,
                                'external_id' => $externalId,
                                'project_position_id' => $request->committees['project_position_id'][$i],
                                'remuneration_total' => $request->committees['remuneration_total'][$i] ?? 0,
                            ]);
                        }
                    }
                }

                AcademicProjectLog::create([
                    'academic_project_id' => $id,
                    'user_id' => auth()->id(),
                    'action' => 'updated',
                    'status_code' => $project->overall_status,
                    'comment' => 'แก้ไขข้อมูลเฉพาะและคณะทำงาน (สัญญาจ้าง)'
                ]);

                DB::commit();
                return redirect()->route('contracts.projects.edit', ['project' => $id, 'tab' => 'tab3'])->with('success', 'บันทึกข้อมูลเฉพาะและคณะทำงานสำเร็จ!');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Update step 2 error: ' . $e->getMessage());
                return back()->withInput()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            }
        }

        // ==========================================
        // 🔥 บันทึก Tab 3: งบประมาณและงวดงาน (เอาโค้ดมาใส่ตรงนี้!)
        // ==========================================
        elseif ($step == '3') {
            $project = AcademicProject::findOrFail($id); 
            $this->authorize('updateBudget', $project);

            // ==========================================
            // 🛑 ตรวจสอบความถูกต้องของยอดเงิน (Validation)
            // ==========================================
            $totalBudget = $this->unformatNumber($request->input('total_budget_summary'));
            $sumInstallments = 0;

            if ($request->has('installments.amount')) {
                foreach ($request->installments['amount'] as $amt) {
                    $sumInstallments += $this->unformatNumber($amt);
                }
            }

            // ถ้ายอดรวมงวดงาน (ที่กรอกมา) ไม่เท่ากับ งบประมาณโครงการ ให้เด้งกลับทันที
            if ($request->has('installments.amount') && count($request->installments['amount']) > 0) {
                if (round($sumInstallments, 2) > round($totalBudget, 2)) {
                    return back()->with('error', 'ยอดรวมเงินงวดทั้งหมด (' . number_format($sumInstallments, 2) . ' บาท) เกินกว่างบประมาณโครงการ (' . number_format($totalBudget, 2) . ' บาท) กรุณาตรวจสอบอีกครั้ง')->withInput();
                }
            }

            // ถ้าผ่านเงื่อนไข ค่อยเริ่มเซฟข้อมูล
            try {

                DB::beginTransaction();

                // 1. ลบข้อมูลเก่าทิ้ง (Incomes, Expenses, Budget, Installments)
                AcademicBudgetIncomes::where('academic_project_id', $id)->delete();
                AcademicBudgetExpenses::where('academic_project_id', $id)->delete();
                AcademicBudget::where('academic_project_id', $id)->delete(); // อย่าลืม Model ใหม่ที่เพิ่งสร้าง
                AcademicInstallments::where('academic_project_id', $id)->delete();

                // ==========================================
                // 3.1: แผนรายรับ
                // ==========================================
                if ($request->has('incomes.category_id')) {
                    foreach ($request->incomes['category_id'] as $index => $categoryId) {
                        if (!$categoryId) continue; // ข้ามแถวที่ว่างเปล่า

                        AcademicBudgetIncomes::create([
                            'academic_project_id' => $id,
                            'category_id'         => $categoryId,
                            'description'         => $request->incomes['description'][$index] ?? null,
                            'unit_cost'           => $this->unformatNumber($request->incomes['unit_cost'][$index]),
                            'quantity'            => $this->unformatNumber($request->incomes['quantity'][$index]),
                            'total_amount'        => $this->unformatNumber($request->incomes['total_amount'][$index]),
                        ]);
                    }
                }

                // ==========================================
                // 3.2: แผนรายจ่าย (ค่าดำเนินการ - operation)
                // ==========================================
                if ($request->has('expenses.category_id')) {
                    foreach ($request->expenses['category_id'] as $index => $categoryId) {
                        if (!$categoryId) continue;

                        AcademicBudgetExpenses::create([
                            'academic_project_id' => $id,
                            'expense_type'        => 'operation', // ระบุ Type ให้ชัดเจน
                            'category_id'         => $categoryId,
                            'description'         => $request->expenses['description'][$index] ?? null,
                            'cost_per_unit'       => $this->unformatNumber($request->expenses['cost_per_unit'][$index]),
                            'factor_1'            => $this->unformatNumber($request->expenses['factor_1'][$index]),
                            'factor_2'            => $this->unformatNumber($request->expenses['factor_2'][$index]),
                            'uom'                 => $request->expenses['uom'][$index] ?? null,
                            'total_amount'        => $this->unformatNumber($request->expenses['total_amount'][$index]),
                            'can_average'         => $request->expenses['can_average'][$index] ?? 0,
                        ]);
                    }
                }

                // ==========================================
                // 3.3: แผนรายจ่าย (ค่าตอบแทน - remuneration)
                // ==========================================
                if ($request->has('remunerations.category_id')) {
                    foreach ($request->remunerations['category_id'] as $index => $categoryId) {
                        if (!$categoryId) continue;

                        AcademicBudgetExpenses::create([
                            'academic_project_id' => $id,
                            'expense_type'        => 'remuneration', // ระบุ Type ให้ชัดเจน
                            'category_id'         => $categoryId,
                            'description'         => $request->remunerations['description'][$index] ?? null,
                            'cost_per_unit'       => $this->unformatNumber($request->remunerations['cost_per_unit'][$index]),
                            'factor_1'            => $this->unformatNumber($request->remunerations['factor_1'][$index]),
                            'factor_2'            => $this->unformatNumber($request->remunerations['factor_2'][$index]),
                            'uom'                 => $request->remunerations['uom'][$index] ?? null,
                            'total_amount'        => $this->unformatNumber($request->remunerations['total_amount'][$index]),
                            'can_average'         => $request->remunerations['can_average'][$index] ?? 0,
                        ]);
                    }
                }

                // ==========================================
                // 3.4: ข้อมูลลงงบประมาณโครงการ (สรุปค่าธรรมเนียม)
                // ==========================================
                AcademicBudget::create([
                    'academic_project_id'   => $id,
                    'total_budget_summary'  => $this->unformatNumber($request->input('total_budget_summary')),
                    'total_advance_amount'  => $this->unformatNumber($request->input('total_advance_amount')),
                    'total_fine_amount'     => $this->unformatNumber($request->input('total_fine_amount')),
                    'remuneration_fee'      => $this->unformatNumber($request->input('remuneration_fee')),
                    'operation_fee'         => $this->unformatNumber($request->input('operation_fee')),
                    'service_fee_percent'   => $this->unformatNumber($request->input('service_fee_percent')),
                    'service_fee_amount'    => $this->unformatNumber($request->input('service_fee_amount')),
                    'alloc_uni_percent'     => $this->unformatNumber($request->input('alloc_uni_percent')),
                    'alloc_uni_amount'      => $this->unformatNumber($request->input('alloc_uni_amount')),
                    'alloc_campus_percent'  => $this->unformatNumber($request->input('alloc_campus_percent')),
                    'alloc_campus_amount'   => $this->unformatNumber($request->input('alloc_campus_amount')),
                    'alloc_dept_percent'    => $this->unformatNumber($request->input('alloc_dept_percent')),
                    'alloc_dept_amount'     => $this->unformatNumber($request->input('alloc_dept_amount')),

                    // 🟢 ส่วนที่เพิ่มใหม่ 6 ฟิลด์ (สัดส่วนย่อยของคณะ)
                    'fund_research_percent' => $this->unformatNumber($request->input('fund_research_percent')),
                    'fund_research_amount'  => $this->unformatNumber($request->input('fund_research_amount')),
                    'faculty_percent'       => $this->unformatNumber($request->input('faculty_percent')),
                    'faculty_amount'        => $this->unformatNumber($request->input('faculty_amount')),
                    'center_percent'        => $this->unformatNumber($request->input('center_percent')),
                    'center_amount'         => $this->unformatNumber($request->input('center_amount')),
                ]);

                // ==========================================
                // 3.5: ข้อมูลลงงวดงาน
                // ==========================================
                if ($request->has('installments.installment_no')) {
                    foreach ($request->installments['installment_no'] as $index => $installmentNo) {
                        if (!$installmentNo) continue;

                        AcademicInstallments::create([
                            'academic_project_id' => $id,
                            'installment_no'      => $installmentNo,
                            // ใช้ ?? ช่วยป้องกัน Undefined index
                            'duration_days'       => $request->installments['duration_days'][$index] ?? null,
                            'start_date'          => $this->parseDate($request->installments['start_date'][$index] ?? null),
                            'end_date'            => $this->parseDate($request->installments['end_date'][$index] ?? null),

                            // สำหรับตัวเลข ถ้าไม่มีให้เป็น 0 ไว้ก่อนไปถอดคอมม่า
                            'amount'              => $this->unformatNumber($request->installments['amount'][$index] ?? 0),
                            'adv_deduct_pct'      => $this->unformatNumber($request->installments['adv_deduct_pct'][$index] ?? 0),
                            'adv_deduct_amt'      => $this->unformatNumber($request->installments['adv_deduct_amt'][$index] ?? 0),
                            'guarantee_pct'       => $this->unformatNumber($request->installments['guarantee_pct'][$index] ?? 0),
                            'guarantee_amt'       => $this->unformatNumber($request->installments['guarantee_amt'][$index] ?? 0),
                            'fine_amount'         => $this->unformatNumber($request->installments['fine_amount'][$index] ?? 0),
                            'net_amount'          => $this->unformatNumber($request->installments['net_amount'][$index] ?? 0),

                            'delivery_date'       => null, // ให้ว่างไว้ก่อนตามที่ตกลง
                            'payment_date'        => null, // ให้ว่างไว้ก่อนตามที่ตกลง
                            'status'              => '01', // สถานะเริ่มต้น 01 = รอส่งงาน
                        ]);
                    }
                }

                DB::commit();
                return redirect()->route('contracts.projects.edit', [
                    'project' => $id,
                    'tab' => 'tab4'
                ])->with('success', 'บันทึกข้อมูลงบประมาณสำเร็จ');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
            }
        }

        // =========================================
        // กรณีบันทึกข้อมูลจาก "แท็บ 5" (ผลลัพธ์ & ประเมิน)
        // =========================================
        elseif ($step == '4') {
            try {
                DB::beginTransaction();

                AcademicProjectEvaluation::updateOrCreate(
                    ['academic_project_id' => $id],
                    [
                        // 5.1 ความพึงพอใจ (บันทึกทั้งคะแนนดิบ และ %)
                        'satisfaction_score' => $request->satisfaction_score,
                        'satisfaction_percent' => $request->satisfaction_percent,
                        'satisfaction_range' => $request->satisfaction_range,
                        'satisfaction_level' => $request->satisfaction_level,

                        // ความไม่พึงพอใจ
                        'dissatisfaction_score' => $request->dissatisfaction_score,
                        'dissatisfaction_percent' => $request->dissatisfaction_percent,
                        'dissatisfaction_range' => $request->dissatisfaction_range,
                        'dissatisfaction_level' => $request->dissatisfaction_level,

                        // 5.2 อื่นๆ
                        'improvement_apply' => $request->improvement_apply,
                        'impact' => $request->impact,
                        'integration' => $request->integration,
                        'integration_eval' => $request->integration_eval,

                        // 5.3 ผลสัมฤทธิ์
                        'sroi_score' => $request->sroi_score,
                        'award_count' => $request->award_count,
                        'industrial_value' => $request->industrial_value,
                        'project_achievement' => $request->project_achievement,
                    ]
                );

                DB::commit();

                // บันทึกเสร็จให้เด้งไป Tab 6 (ภาพรวม) พร้อมโชว์สีเขียว
                return redirect()->route('contracts.projects.edit', ['project' => $id, 'tab' => 'tab5'])
                    ->with('success', 'บันทึกข้อมูลผลลัพธ์และการประเมินเรียบร้อยแล้ว!');
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Update Evaluation Error: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกผลการประเมิน: ' . $e->getMessage());
            }
        }

        // =========================================
        // 🌟 ดักจับการบันทึกข้อมูล Tab 6 (ภาพรวม & ลงนาม)
        // =========================================
        elseif ($step == '5') {
            DB::beginTransaction();
            try {
                // --------------------------------------------------
                // 1. จัดการข้อมูลรายชื่อผู้ลงนาม (Signatures)
                // --------------------------------------------------
                if ($request->has('signatures')) {
                    // 🟢 เปลี่ยน $academicProject เป็น $project
                    AcademicProjectSignature::where('academic_project_id', $project->id)->delete();

                    $signatureData = [];
                    foreach ($request->signatures as $index => $sig) {
                        if (!empty($sig['staff_id']) && !empty($sig['signature_role_id'])) {
                            $signatureData[] = [
                                // 🟢 เปลี่ยน $academicProject เป็น $project
                                'academic_project_id' => $project->id,
                                'staff_id'            => $sig['staff_id'],
                                'signature_role_id'   => $sig['signature_role_id'],
                                'executive_position'  => $sig['executive_position'] ?? null,
                                'sign_order'          => $index + 1,
                                'created_at'          => now(),
                                'updated_at'          => now(),
                            ];
                        }
                    }

                    if (count($signatureData) > 0) {
                        AcademicProjectSignature::insert($signatureData);
                    }
                }

                // --------------------------------------------------
                // 🌟 2. จัดการสถานะ (Workflow) ตาม "สิทธิ์ของผู้สร้างโครงการ"
                // --------------------------------------------------
                $currentUser = auth()->user();
                // 🟢 เปลี่ยน $academicProject เป็น $project
                $currentStatus = (int) $project->overall_status;
                $newStatus = $currentStatus;
                $actionName = 'updated_overview';

                // 🟢 เปลี่ยน $academicProject เป็น $project
                $creatorId = $project->created_by ?? $project->create_by;
                $creator = User::find($creatorId);
                $isCreatorAdminOrStaff = $creator ? $creator->hasAnyRole(['admin', 'staff']) : false;

                if ($isCreatorAdminOrStaff) {
                    if ($currentStatus == 100) {
                        $newStatus = 300;
                        $actionName = 'approved_auto';
                    }
                } else {
                    if (in_array($currentStatus, [100, 110])) {
                        $newStatus = 200;
                        $actionName = 'submitted';
                    }
                }

                // 🟢 เปลี่ยน $academicProject เป็น $project
                $project->overall_status = $newStatus;
                $project->update_by = $currentUser->id;
                $project->save();

                // --------------------------------------------------
                // 3. เก็บประวัติลง Log
                // --------------------------------------------------
                AcademicProjectLog::create([
                    // 🟢 เปลี่ยน $academicProject เป็น $project
                    'academic_project_id' => $project->id,
                    'user_id'             => $currentUser->id,
                    'action'              => $actionName,
                    'status_code'         => $newStatus,
                    'comment'             => null,
                ]);

                DB::commit();

                $msg = ($actionName == 'submitted') ? 'ยื่นขออนุมัติโครงการเรียบร้อยแล้ว' : 'บันทึกภาพรวมโครงการเรียบร้อยแล้ว';

                // 🟢 เปลี่ยน $academicProject เป็น $project
                return redirect()->route('contracts.projects.index', ['type_id' => $project->project_type_id])
                    ->with('success', $msg);
            } catch (\Exception $e) {
                DB::rollBack();
                // 🟢 เปลี่ยน $academicProject เป็น $project
                Log::error('Error saving Tab 6 (Project ID: ' . $project->id . '): ' . $e->getMessage());
                return back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง');
            }
        }

        return back()->with('info', 'กำลังพัฒนาส่วนการบันทึกข้อมูลในแท็บอื่นๆ');
    }
    public function storeTargetGroupAjax(Request $request)
    {
        try {
            $customerGroup = TargetGroup::create([
                'parent_id' => $request->parent_id,
                'name_th' => $request->name_th,
                'name_en' => $request->name_en,
                'group_type' => $request->group_type,
                'description' => $request->description,
                'is_active' => 1,
            ]);

            return response()->json([
                'success' => true,
                'id' => $customerGroup->id,
                'name_th' => $customerGroup->name_th,
                'full_path' => $customerGroup->full_path
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeExternalAjax(Request $request)
    {
        
        try {
            $external = External::create([
                'prefix_id' => $request->prefix_id,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'department' => $request->department,
                'phone' => $request->phone,
                'email' => $request->email,
                'description' => $request->description,
                'is_active' => 1,
            ]);

            //$fullname = ($external->prefix->name_th ?? '') . $external->firstname . ' ' . $external->lastname;
            $prefixName = DB::table('prefixes')->where('id', $request->prefix_id)->value('name_th') ?? '';
            $fullName = $prefixName . $external->firstname . ' ' . $external->lastname . ' (' . $external->department . ')';

            return response()->json([
                'success' => true,
                'id' => $external->id,
                'fullname' => $fullName
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
...
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
       
        try {
            // 1. ดึงข้อมูลโครงการมาตรวจสอบ
            $project = AcademicProject::findOrFail($id);
            $user = auth()->user();

            // 2. 🛡️ เช็คสิทธิ์ด้วย Policy (ครอบคลุมทั้ง Role, Owner และ Status == 100 แล้ว!)
            // ใช้ cannot() แทน authorize() เพื่อให้เรา Custom ข้อความ Error กลับไปหา AJAX (SweetAlert) ได้
            if ($user->cannot('delete', $project)) {
                $errorMessage = 'ไม่สามารถลบได้! คุณไม่มีสิทธิ์ หรือ โครงการไม่อยู่ในสถานะฉบับร่าง (100) แล้ว';

                // ดักจับเผื่อกรณี Custom CRUD JS ของคุณวัชกรยิงมาเป็น AJAX
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMessage], 403);
                }
                return redirect()->back()->with('error', $errorMessage);
            }

            DB::beginTransaction();

            // 3. 💾 ทำการ Soft Delete (อัปเดต del_status แทนการลบทิ้งจริงๆ)
            // พร้อมเก็บ Log ว่าใครเป็นคนกดลบ
            $project->update([
                'del_status' => 1,
                'update_by'  => auth()->id() ?? null
            ]);

            DB::commit();

            $successMessage = 'ลบโครงการออกจากระบบเรียบร้อยแล้ว';

            // ตอบกลับตามประเภทของ Request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMessage]);
            }

            return redirect()->route('contracts.projects.index', ['type_id' => $project->project_type_id])
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete Project Error: ' . $e->getMessage());

            $errorSysMessage = 'เกิดข้อผิดพลาดในระบบ ไม่สามารถลบข้อมูลได้';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorSysMessage], 500);
            }
            return redirect()->back()->with('error', $errorSysMessage);
        }
    }

    // ==========================================
    // 💡 อย่าลืมเอา Helper Functions มาวางไว้ล่างสุดของ Controller นี้นะครับ (นอกฟังก์ชัน update)
    // ==========================================
    private function unformatNumber($number)
    {
        if (!$number) return 0.00;
        return (float) str_replace(',', '', $number);
    }

    private function parseDate($date)
    {
        if (!$date) return null;
        try {
            if (strpos($date, '/') !== false) {
                $parts = explode('/', $date);
                if (count($parts) == 3) {
                    $day   = sprintf('%02d', (int)$parts[0]);
                    $month = sprintf('%02d', (int)$parts[1]);
                    $year  = (int)$parts[2];

                    // ถ้าเป็น พ.ศ. ให้แปลงเป็น ค.ศ. ก่อนลง DB
                    if ($year > 2500) {
                        $year -= 543;
                    }
                    return "{$year}-{$month}-{$day}";
                }
            }

            $carbon = \Carbon\Carbon::parse($date);
            if ($carbon->year > 2500) {
                $carbon->subYears(543);
            }
            return $carbon->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    // public function changeStatus(Request $request, $id)
    // {
    //     // ตรวจสอบสิทธิ์ว่าต้องเป็น Admin เท่านั้น (ถ้ามีการใช้ Spatie Permission)
    //     if (!auth()->user()->hasRole('admin')) {
    //         abort(403, 'คุณไม่มีสิทธิ์ใช้งานส่วนนี้');
    //     }

    //     $request->validate([
    //         'new_status' => 'required|integer'
    //     ]);

    //     // ค้นหาโครงการและอัปเดตสถานะ
    //     $project = AcademicProject::findOrFail($id);

    //     // ถ้าต้องการเก็บ Log การเปลี่ยนสถานะ สามารถเพิ่มโค้ดบันทึก Log ตรงนี้ได้ครับ

    //     $project->overall_status = $request->new_status;
    //     $project->save();

    //     return redirect()->back()->with('success', 'Admin ทำการปรับเปลี่ยนสถานะโครงการเรียบร้อยแล้ว');
    // }
    public function changeStatus(Request $request, $id)
    {
        // 1. 🛡️ เช็คสิทธิ์ขั้นเด็ดขาด (เฉพาะ Admin เท่านั้น)
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'คุณไม่มีสิทธิ์เข้าถึงการจัดการส่วนนี้'], 403);
        }

        // 2. 📝 ตรวจสอบข้อมูล (ไว้นอก try-catch เพื่อให้โยน ValidationException แบบปกติ)
        $request->validate([
            'new_status' => 'required|integer'
        ], [
            'new_status.required' => 'กรุณาเลือกสถานะใหม่',
            'new_status.integer'  => 'รูปแบบสถานะไม่ถูกต้อง'
        ]);

        try {
            $project = AcademicProject::findOrFail($id);
            $user    = auth()->user();

            $oldStatus = $project->overall_status;
            $newStatus = $request->new_status;

            // ถ้าสถานะเดิมตรงกับที่เลือกมา ไม่ต้องทำอะไร
            if ($oldStatus == $newStatus) {
                return response()->json(['success' => false, 'message' => 'โครงการมีสถานะนี้อยู่แล้วครับ'], 400);
            }

            DB::beginTransaction();

            // 3. 💾 อัปเดตสถานะในตารางแม่
            $project->update([
                'overall_status' => $newStatus,
                'update_by'      => $user->id
            ]);

            // 4. 📝 บันทึกประวัติลง Log
            AcademicProjectLog::create([
                'academic_project_id' => $project->id,
                'user_id'             => $user->id,
                'action'              => 'Admin บังคับเปลี่ยนสถานะ',
                'status_code'         => $newStatus,
                'comment'             => "ผู้ดูแลระบบ (Admin) ใช้สิทธิ์พิเศษปรับเปลี่ยนสถานะข้ามขั้นตอน จาก $oldStatus เป็น $newStatus",
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตสถานะโครงการเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin Change Status Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในระบบ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function report($id)
    {
        $project = AcademicProject::findOrFail($id);

        // ดึงข้อมูลประเมินผลเดิมมา (ถ้ามี)
        $projectEvaluation = AcademicProjectEvaluation::where('academic_project_id', $id)->first();
        // ไปที่ไฟล์ blade ใหม่ที่เรากำลังจะสร้าง
        return view('contracts.projects.report', compact('project', 'projectEvaluation'));
    }

    public function saveReport(Request $request, $id)
    {
        //dd($request, $id);
        DB::beginTransaction();
        try {
            $project = AcademicProject::findOrFail($id);

            // 🌟 1. ก๊อปปี้โค้ดบันทึกของ Tab 5 เดิมมาวางตรงนี้ได้เลย!
            AcademicProjectEvaluation::updateOrCreate(
                ['academic_project_id' => $id],
                [
                    // 5.1 ความพึงพอใจ (บันทึกทั้งคะแนนดิบ และ %)
                    'satisfaction_score' => $request->satisfaction_score,
                    'satisfaction_percent' => $request->satisfaction_percent,
                    'satisfaction_range' => $request->satisfaction_range,
                    'satisfaction_level' => $request->satisfaction_level,

                    // ความไม่พึงพอใจ
                    'dissatisfaction_score' => $request->dissatisfaction_score,
                    'dissatisfaction_percent' => $request->dissatisfaction_percent,
                    'dissatisfaction_range' => $request->dissatisfaction_range,
                    'dissatisfaction_level' => $request->dissatisfaction_level,

                    // 5.2 อื่นๆ
                    'improvement_apply' => $request->improvement_apply,
                    'impact' => $request->impact,
                    'integration' => $request->integration,
                    'integration_eval' => $request->integration_eval,

                    // 5.3 ผลสัมฤทธิ์ (เอา evaluation_score ออกแล้ว)
                    'sroi_score' => $request->sroi_score,
                    'award_count' => $request->award_count,
                    'industrial_value' => $request->industrial_value,
                    'project_achievement' => $request->project_achievement,
                ]
            );

            // 🌟 2. จุดไคลแม็กซ์: อัปเดตสถานะเป็น 800 (เสร็จสิ้นโครงการ)
            $project->update([
                'overall_status' => 800,
                'update_by' => auth()->id()
            ]);

            // 🌟 3. เก็บ Log ประวัติไว้ด้วยว่าปิดโครงการแล้ว
            AcademicProjectLog::create([
                'academic_project_id' => $project->id,
                'user_id'             => auth()->id(),
                'action'              => 'completed',
                'status_code'         => 800,
            ]);

            DB::commit();
            return redirect()->route('contracts.projects.index', ['type_id' => $project->project_type_id])
            ->with('success', 'บันทึกรายงานผลและปิดโครงการเรียบร้อยแล้ว!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Save Report Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // 📌 ยกเลิกโครงการ (เปลี่ยนสถานะเป็น 900 พร้อมระบุเหตุผล)
    // =========================================================================
    public function cancelProject(Request $request, $id)
    {
        try {
            $project = AcademicProject::findOrFail($id);
            $user = auth()->user();

            // 1. 🛡️ เช็คสิทธิ์ด้วย Policy (แนะนำให้ใช้ 'cancel' ที่เราเขียนดักไว้ใน Policy ครับ)
            if ($user->cannot('cancel', $project)) {
                return response()->json([
                    'success' => false,
                    'message' => 'คุณไม่มีสิทธิ์ยกเลิกโครงการนี้ หรือสถานะปัจจุบันไม่สามารถยกเลิกได้'
                ], 403);
            }

            // 2. Validate เหตุผล
            $request->validate([
                'cancel_reason' => 'required|string|max:1000'
            ], [
                'cancel_reason.required' => 'กรุณาระบุเหตุผลที่ยกเลิกโครงการด้วยครับ'
            ]);

            DB::beginTransaction();

            // 3. 💾 อัปเดตสถานะโครงการในตารางแม่ (ไม่เก็บเหตุผลที่นี่แล้ว)
            $project->update([
                'overall_status' => 900,
                'update_by'      => $user->id
            ]);

            // 4. 📝 บันทึกลงตาราง academic_project_logs เพื่อเก็บประวัติ
            AcademicProjectLog::create([
                'academic_project_id' => $project->id,
                'user_id'             => $user->id,
                'action'              => 'ยกเลิกโครงการ',
                'status_code'         => 900,
                'comment'             => trim($request->cancel_reason),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'ยกเลิกโครงการและบันทึกประวัติประวัติเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel Project Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในระบบ: ' . $e->getMessage()
            ], 500);
        }
    }
}

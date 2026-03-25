<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicBudgetExpenses;
use App\Models\Academic\AcademicBudgetIncomes;
use App\Models\Academic\AcademicProject;
use App\Models\Academic\AcademicProjectEvaluation;
use App\Models\Academic\AcademicSdg;
use App\Models\Academic\AcademicTargetGroup;
use App\Models\Academic\Committee;
use App\Models\Academic\CustomerGroup;
use App\Models\MasterData\BudgetExpenseCategorie;
use App\Models\MasterData\BudgetExpenseMainCategory;
use App\Models\MasterData\BudgetIncomeCategorie;
use App\Models\MasterData\BudgetIncomeMainCategory;
use App\Models\MasterData\Center;
use App\Models\MasterData\CustomerType;
use App\Models\MasterData\External;
use App\Models\MasterData\FiscalYears;
use App\Models\MasterData\Nationality;
use App\Models\MasterData\Prefix;
use App\Models\MasterData\ProjectPosition;
use App\Models\MasterData\ProjectType;
use App\Models\MasterData\Sdg;
use App\Models\MasterData\TargetGroup;
use App\Models\MasterData\TrainingPosition;
use App\Models\Status\TrainingStatus;
use App\Models\Training\TrainingCourses;
use App\Models\Training\TrainingMember;
use App\Models\Training\TrainingProject;
use App\Models\Training\TrainingScheduleDocument;
use App\Models\Training\TrainingSchedules;
use App\Models\Training\TrainingSchedulesLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

//http://172.28.80.250/suny/web/academic/public/trainings/projects/1/edit?tab=tab3
class TrainingProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 1. รับค่า type_id ที่แนบมากับ URL
        $typeId = $request->query('type_id');

        // ถ้าไม่มีการส่ง type_id มา ให้เด้งกลับไปหน้าเลือกประเภทโครงการ
        if (!$typeId) {
            return redirect()->route('projects.select-type')
                ->with('error', 'กรุณาเลือกประเภทโครงการก่อนเข้าใช้งานครับ');
        }

        // 2. ดึงชื่อประเภทโครงการมาเพื่อแสดงบนหัวตาราง
        $projectType = ProjectType::findOrFail($typeId);

        // 3. ดึงประวัติโครงการ "ประเภทนี้" ที่ User คนนี้เคยสร้างไว้
        $projects = AcademicProject::where('project_type_id', $typeId)
            // ->where('created_by', auth()->id()) // TODO: เอาคอมเมนต์ออกเมื่อคุณทำระบบ Login (Auth) เสร็จแล้ว เพื่อดึงเฉพาะของ User คนนั้น
            ->where(function ($query) {
                $query->whereNull('del_status')
                    ->orWhere('del_status', 0); // ดึงเฉพาะข้อมูลที่ยังไม่ถูก Soft Delete
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('trainings.projects.index', compact('projectType', 'projects'));
    }

    // ฟังก์ชันนี้เตรียมไว้สำหรับ Step 3 (หน้าฟอร์ม SPA)
    public function create(Request $request)
    {
        $typeId = $request->query('type_id');
        $projectType = ProjectType::findOrFail($typeId);

        // 1. ดึงปีงบประมาณ (เรียงจากปีล่าสุดลงมา)
        $fiscalYears = FiscalYears::orderBy('fiscal_year_be', 'desc')->get();

        // 2. ดึงข้อมูลหน่วยงานจาก View V_DEPARTMENT (สมมติว่าดึงเฉพาะที่เปิดใช้งาน ISACTIVE = 1 หรือ 'Y')
        $departments = DB::table('V_DEPARTMENT')
            // ->where('ISACTIVE', 1) // ถ้ามีเงื่อนไขเปิดใช้งาน ให้เอาคอมเมนต์ออกครับ
            ->get();

        // 3. ดึงข้อมูลหลักสูตรจาก View V_DIVISION
        $divisions = DB::table('V_DIVISION')->get();

        // 4. ดึงข้อมูลศูนย์
        $centers = Center::where('is_active', 1)->get();

        // 5. ดึงข้อมูลวัตถุประสงค์ (TargetGroup)
        $targetGroups = TargetGroup::whereNull('parent_id')
            ->where('is_active', 1)
            ->get();

        // ส่งข้อมูลทั้งหมดไปที่หน้า Blade
        return view('trainings.projects.create', compact(
            'projectType',
            'fiscalYears',
            'departments',
            'divisions',
            'centers',
            'targetGroups'
        ));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. ตรวจสอบความถูกต้องของข้อมูล
        $request->validate([
            'fiscal_year_id' => 'required',
            'name_th' => 'required|string|max:255',
            'department_ids' => 'required|array',
            'course_ids' => 'required|array',
            'start_date' => 'required|date',
            'target_group_ids' => 'required|array',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'fiscal_year_id.required' => 'กรุณาเลือกปีงบประมาณ',
            'name_th.required' => 'กรุณากรอกชื่อโครงการ',
            'department_ids.required' => 'กรุณาเลือกหน่วยงานผู้รับผิดชอบอย่างน้อย 1 หน่วยงาน',
            'course_ids.required' => 'กรุณาเลือกหลักสูตรอย่างน้อย 1 หลักสูตร',
            'target_group_ids.required' => 'กรุณาเลือกวัตถุประสงค์อย่างน้อย 1 ข้อ',
            'start_date.required' => 'กรุณาเลือกวันที่เริ่มต้น',
            'end_date.required' => 'กรุณาเลือกวันที่สิ้นสุด',
            'end_date.after_or_equal' => 'วันที่สิ้นสุดต้องไม่ก่อนวันที่เริ่มต้น',
        ]);

        try {
            DB::beginTransaction();

            // Step A: บันทึกข้อมูลพื้นฐานลงตารางแม่ 
            $academicProject = AcademicProject::create([
                'project_type_id' => $request->project_type_id,
                'fiscal_year_id' => $request->fiscal_year_id,
                'name_th' => trim($request->name_th), // 🌟 เพิ่ม trim() ทำความสะอาดข้อมูล
                'center_id' => $request->center_id,
                'region_type' => $request->region_type,
                'brief_description' => $request->filled('brief_description') ? trim($request->brief_description) : null,
                'rationale' => $request->filled('rationale') ? trim($request->rationale) : null,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'overall_status' => 100,
                'del_status' => 0,
                'created_by' => auth()->id() ?? 1,
            ]);

            // Step B: บันทึกข้อมูลลงตาราง Pivot
            $now = now(); // เผื่อตาราง Pivot มี timestamp

            // 1) หน่วยงานผู้รับผิดชอบ
            if ($request->has('department_ids')) {
                $deptData = collect($request->department_ids)->map(function ($id) use ($academicProject) {
                    return ['academic_project_id' => $academicProject->id, 'department_id' => $id];
                })->toArray();
                DB::table('academic_departments')->insert($deptData);
            }

            // 2) หลักสูตร
            if ($request->has('course_ids')) {
                $courseData = collect($request->course_ids)->map(function ($id) use ($academicProject) {
                    return ['academic_project_id' => $academicProject->id, 'course_id' => $id];
                })->toArray();
                DB::table('academic_courses')->insert($courseData);
            }

            // 3) วัตถุประสงค์ (Level 1)
            if ($request->has('target_group_ids')) {
                $objectiveData = collect($request->target_group_ids)->map(function ($id) use ($academicProject) {
                    return ['academic_project_id' => $academicProject->id, 'target_group_id' => $id];
                })->toArray();
                DB::table('academic_objectives')->insert($objectiveData);
            }

            // Step C: สร้างตารางลูกรอไว้เลย
            TrainingProject::create([
                'academic_project_id' => $academicProject->id,
            ]);

            DB::commit();

            // 3. โยนไปหน้า Edit พร้อมกางแท็บ 2
            return redirect()->route('trainings.projects.edit', [
                'project' => $academicProject->id,
                'tab' => 'tab2'
            ])->with('success', 'บันทึกข้อมูลพื้นฐาน (ฉบับร่าง) สำเร็จ! กรุณากรอกข้อมูลส่วนต่อไป');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('เกิดข้อผิดพลาดในการสร้างฉบับร่างโครงการ: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'เกิดข้อผิดพลาดจากระบบฐานข้อมูล: ' . $e->getMessage());
        }
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
    public function edit(Request $request, $id)
    {
        // 1. รับค่าแท็บ (ถ้าไม่มีให้เปิดแท็บ 1 เป็น Default)
        $activeTab = $request->query('tab', 'tab1');

        // 2. ดึงข้อมูล Project ปัจจุบัน
        $project = AcademicProject::findOrFail($id);

        // 3. ดึง Master Data (เหมือนหน้า Create เลยครับ)
        $projectType = ProjectType::findOrFail($project->project_type_id);
        $fiscalYears = FiscalYears::orderBy('fiscal_year_be', 'desc')->get();
        $departments = DB::table('V_DEPARTMENT')->get();
        $divisions = DB::table('V_DIVISION')->get();
        $centers = Center::where('is_active', 1)->get();
        $targetGroups = TargetGroup::whereNull('parent_id')
            ->where('is_active', 1)
            ->get();
        $nationalities = Nationality::where('is_active', 1)->get();
        $projectPositions = ProjectPosition::all();
        $prefixes = Prefix::all();
        $customerGroups = CustomerGroup::all();
        $customerTypes = CustomerType::all();
        $externals = External::all();
        $sdgs = Sdg::where('is_active', 1)->get();
        $trainingProject = TrainingProject::where('academic_project_id', $id)->first(); // ดึงข้อมูลเดิมมาโชว์
        $trainingStatuses = TrainingStatus::all();
        $provinces = DB::table('provinces_csv')->get();
        $trainingPositions = TrainingPosition::all();
        $incomeCategories = BudgetIncomeCategorie::where('is_active', 1)->get();
        $expenseCategories = BudgetExpenseCategorie::where('is_active', 1)->get();
        $savedIncomes = AcademicBudgetIncomes::where('academic_project_id', $id)->get();
        $savedExpenses = AcademicBudgetExpenses::where('academic_project_id', $id)->get();
        $projectEvaluation = AcademicProjectEvaluation::where('academic_project_id', $id)->first();

        $incomeCategoriesGrouped = BudgetIncomeMainCategory::with(['subCategories' => function ($query) {
            $query->where('is_active', 1);
        }])->where('is_active', 1)->get();

        $expenseCategoriesGrouped = BudgetExpenseMainCategory::with(['subCategories' => function ($query) {
            $query->where('is_active', 1);
        }])->where('is_active', 1)->get();

        // 🌟 ดึงข้อมูลสำหรับแท็บ 2
        // ดึง Array ของ SDG ID ที่เคยเลือกไว้
        $selectedSdgs = AcademicSdg::where('academic_project_id', $id)->pluck('sdg_id')->toArray();
        // ดึงข้อมูลกลุ่มเป้าหมายเดิม
        $savedTargetGroups = AcademicTargetGroup::where('academic_project_id', $id)->get();
        // ดึงข้อมูลคณะทำงานเดิม
        $savedCommittees = Committee::where('academic_project_id', $id)->get();
        // ดึงรายชื่อบุคลากร (อัปเดตไปใช้ View ใหม่: V_STAFF_NEWWEB)
        $staffs = DB::table('V_STAFF_NEWWEB')
            ->select('STAFF_ID', 'TITLE_TH', 'NAME_TH', 'SURNAME_TH', 'DEPARTMENT_NAME_TH')
            ->get();

        $savedTrainingCourses = collect();
        if ($trainingProject) {
            // ดึงชื่อหลักสูตรเก่า โดยใช้ไอดีของ training_projects ไม่ใช่ academic_projects
            $savedTrainingCourses = TrainingCourses::where('training_project_id', $trainingProject->id)->get();
        }



        // ----------------------------------------------------
        // 🌟 ไฮไลต์ของ Senior: ดึงข้อมูล Array ที่ User เคยเลือกไว้ (Pivot)
        // ----------------------------------------------------
        // ดึง ID ของหน่วยงานที่เลือกไว้ แปลงเป็น Array เช่น [1, 5]
        $selectedDepartments = DB::table('academic_departments')
            ->where('academic_project_id', $id)
            ->pluck('department_id')->toArray();

        // ดึง ID ของหลักสูตรที่เลือกไว้
        $selectedCourses = DB::table('academic_courses')
            ->where('academic_project_id', $id)
            ->pluck('course_id')->toArray();

        // ดึง ID ของวัตถุประสงค์ที่เลือกไว้ (จากตาราง academic_objectives)
        $selectedObjectives = DB::table('academic_objectives')
            ->where('academic_project_id', $id)
            // หมายเหตุ: ถ้าในฐานข้อมูลคุณตั้งชื่อคอลัมน์นี้ว่า objective_id ให้แก้คำว่า 'target_group_id' เป็น 'objective_id' นะครับ
            ->pluck('target_group_id')->toArray();

        $allGroupsForFilter = TargetGroup::with('parent')->where('is_active', 1)->get();

        $filteredTargetGroups = $allGroupsForFilter->filter(function ($group) use ($selectedObjectives) {
            $current = $group;
            // วนลูปเช็คตัวเองและไล่ขึ้นไปหาตัวแม่เรื่อยๆ
            while ($current) {
                if (in_array($current->id, $selectedObjectives)) {
                    return true; // ถ้าตัวเองหรือแม่ อยู่ในเงื่อนไขแท็บ 1 ให้เอามาแสดง
                }
                $current = $current->parent;
            }
            return false;
        })->sortBy('full_path')->values(); // จัดเรียงตามเส้นทาง ก-ฮ

        $savedSchedules = TrainingSchedules::where('training_project_id', $project->id)
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();
        // 4. โยนข้อมูลทั้งหมดไปที่หน้า View `edit.blade.php`
        return view('trainings.projects.edit', compact(
            'project',
            'projectType',
            'activeTab',
            'fiscalYears',
            'departments',
            'divisions',
            'centers',
            'targetGroups',
            'selectedDepartments',
            'selectedCourses',
            'selectedObjectives',
            'nationalities',
            'projectPositions',
            'prefixes',
            'staffs',
            'customerGroups',
            'customerTypes',
            'externals',
            'sdgs',
            'trainingProject',
            'trainingStatuses',
            'selectedSdgs',
            'savedTargetGroups',
            'savedCommittees',
            'staffs',
            'savedTrainingCourses',
            'provinces',
            'trainingPositions',
            'savedSchedules',
            'incomeCategories',
            'expenseCategories',
            'savedIncomes',
            'savedExpenses',
            'incomeCategoriesGrouped',
            'expenseCategoriesGrouped',
            'filteredTargetGroups',
            'projectEvaluation'
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
        $step = $request->input('step');
        $academicProject = AcademicProject::findOrFail($id);

        // =========================================
        // กรณีบันทึกข้อมูลจาก "แท็บ 1" (ข้อมูลพื้นฐาน)
        // =========================================
        if ($step == '1') {

            // 1. ตรวจสอบ Validation (เหมือนตอนสร้างใหม่เลยครับ)
            $request->validate([
                'fiscal_year_id' => 'required',
                'name_th' => 'required|string|max:255',
                'department_ids' => 'required|array',
                'course_ids' => 'required|array',
                'target_group_ids' => 'required|array',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            try {
                DB::beginTransaction();

                // 2. อัปเดตข้อมูลตารางแม่
                $academicProject->update([
                    'fiscal_year_id' => $request->fiscal_year_id,
                    'name_th' => $request->name_th,
                    'center_id' => $request->center_id,
                    'region_type' => $request->region_type,
                    'brief_description' => $request->brief_description,
                    'rationale' => $request->rationale,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    // สถานะเราไม่เปลี่ยน เพราะยังเป็นร่างอยู่ (100)
                ]);

                // 3. อัปเดตตารางเชื่อม (Pivot)
                // วิธีที่ชัวร์ที่สุดเวลา Update แบบ Array คือ "ลบของเก่าทิ้งทั้งหมด แล้ว Insert ของใหม่เข้าไป" ครับ

                // 3.1 หน่วยงาน
                DB::table('academic_departments')->where('academic_project_id', $id)->delete();
                if ($request->has('department_ids')) {
                    $deptData = [];
                    foreach ($request->department_ids as $deptId) {
                        $deptData[] = ['academic_project_id' => $id, 'department_id' => $deptId];
                    }
                    DB::table('academic_departments')->insert($deptData);
                }

                // 3.2 หลักสูตร
                DB::table('academic_courses')->where('academic_project_id', $id)->delete();
                if ($request->has('course_ids')) {
                    $courseData = [];
                    foreach ($request->course_ids as $courseId) {
                        $courseData[] = ['academic_project_id' => $id, 'course_id' => $courseId];
                    }
                    DB::table('academic_courses')->insert($courseData);
                }

                // 3.3 วัตถุประสงค์
                DB::table('academic_objectives')->where('academic_project_id', $id)->delete();
                if ($request->has('target_group_ids')) {
                    $objectiveData = [];
                    foreach ($request->target_group_ids as $targetId) {
                        $objectiveData[] = ['academic_project_id' => $id, 'target_group_id' => $targetId];
                    }
                    DB::table('academic_objectives')->insert($objectiveData);
                }

                DB::commit();

                // 4. บันทึกเสร็จ ให้ Redirect กลับมาหน้า Edit แต่เปลี่ยนเป็น "เปิดแท็บ 2"
                return redirect()->route('trainings.projects.edit', ['project' => $id, 'tab' => 'tab2'])
                    ->with('success', 'อัปเดตข้อมูลพื้นฐานสำเร็จ!');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            }
        }

        // =========================================
        // กรณีบันทึกข้อมูลจาก "แท็บ 2" (ข้อมูลเฉพาะ & บุคคล)
        // =========================================
        if ($step == '2') {

            // 🌟 1. ป้องกันช่องโหว่: Validate ข้อมูลและไฟล์ก่อนเลย
            $request->validate([
                'project_types' => 'required',
                'course_type' => 'required',
                'start_regis_date' => 'required|date',
                'end_regis_date' => 'required|date|after_or_equal:start_regis_date',
                'approval_file' => 'nullable|file|mimes:pdf|mimetypes:application/pdf|max:5120',
                'sdgs' => 'required|array',
            ]);

            try {
                DB::beginTransaction();

                // 🌟 2. ซ่อมบัคตัวแปรหาย & ป้องกันสถานะโดนทับ
                // ดึงข้อมูลเก่ามาก่อน (ถ้ามี)
                $existingTraining = TrainingProject::where('academic_project_id', $id)->first();
                // ถ้ายกดราฟต์ครั้งแรก ให้เป็น 100 แต่ถ้ามีอยู่แล้วก็ให้ใช้สถานะเดิมของมัน
                $statusToSave = $existingTraining ? $existingTraining->training_status : 100;

                // ===================================================
                // ส่วนที่ 2.1: บันทึกรายละเอียดการจัดอบรม (Training Project)
                // ===================================================

                // 🌟 3. จัดการเรื่องไฟล์แนบ (อัปโหลดใหม่ หรือ ลบของเดิม)
                $filePath = $existingTraining->approval_file ?? null; // ตั้งค่าเริ่มต้นดึงชื่อไฟล์เดิมมาก่อน

                // กรณีที่ 1: มีการอัปโหลดไฟล์ใหม่เข้ามาทับ
                if ($request->hasFile('approval_file')) {
                    // ถ้ามีไฟล์เดิมอยู่แล้ว ให้ลบไฟล์เดิมออกจากโฟลเดอร์ทิ้งก่อน
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }

                    $file = $request->file('approval_file');
                    // เปลี่ยนมาใช้ extension() และสุ่มชื่อไฟล์เพื่อความปลอดภัยสูงสุด
                    $filename = time() . '_' . uniqid() . '.' . $file->extension();
                    $filePath = $file->storeAs('approvals', $filename, 'public');
                }
                // กรณีที่ 2: ไม่ได้อัปโหลดใหม่ แต่ User กดปุ่มถังขยะสีแดงเพื่อ "ขอลบไฟล์เดิมทิ้ง"
                elseif ($request->input('remove_approval_file') == '1') {
                    // ลบไฟล์เก่าออกจากโฟลเดอร์
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                    $filePath = null; // เซ็ตค่าให้เป็น null เพื่อลบชื่อไฟล์ออกจากฐานข้อมูล
                }

                $savedTraining = TrainingProject::updateOrCreate(
                    ['academic_project_id' => $id],
                    [
                        'document_number' => $request->document_number,
                        'project_types' => $request->project_types,
                        'course_type' => $request->course_type,
                        'start_regis_date' => $request->start_regis_date,
                        'end_regis_date' => $request->end_regis_date,
                        'has_collaboration' => $request->has_collaboration,
                        'approval_file' => $filePath,
                        'approval_link' => $request->approval_link,
                        'training_status' => $statusToSave,
                    ]
                );

                // ===================================================
                // 🌟 [เพิ่มใหม่] บันทึกชื่อหลักสูตร (Training Courses)
                // ===================================================
                TrainingCourses::where('training_project_id', $savedTraining->id)->delete();
                if ($request->has('course_names')) {
                    $coursesData = [];
                    foreach ($request->course_names as $courseName) {
                        if (!empty($courseName)) {
                            $coursesData[] = [
                                'training_project_id' => $savedTraining->id,
                                'course_name' => $courseName,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                    if (!empty($coursesData)) {
                        TrainingCourses::insert($coursesData);
                    }
                }

                // ===================================================
                // ส่วนที่ 2.2: บันทึกข้อมูล SDGs
                // ===================================================
                AcademicSdg::where('academic_project_id', $id)->delete();
                if ($request->has('sdgs')) {
                    $sdgData = [];
                    foreach ($request->sdgs as $sdgId) {
                        $sdgData[] = [
                            'academic_project_id' => $id,
                            'sdg_id' => $sdgId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    AcademicSdg::insert($sdgData);
                }

                // ---------------------------------------------------
                // ส่วนที่ 2.3: บันทึกข้อมูลกลุ่มเป้าหมาย (Target Groups)
                // ---------------------------------------------------
                AcademicTargetGroup::where('academic_project_id', $id)->delete();
                if ($request->has('target_groups') && isset($request->target_groups['customer_group_id'])) {
                    $targetData = [];
                    $customerGroupIds = $request->target_groups['customer_group_id'];
                    $countTargets = count($customerGroupIds);

                    for ($i = 0; $i < $countTargets; $i++) {
                        if (!empty($customerGroupIds[$i])) {
                            $targetData[] = [
                                'academic_project_id' => $id,
                                'target_group_id' => $customerGroupIds[$i],
                                'nationality_id' => $request->target_groups['nationality_id'][$i],
                                'total' => $request->target_groups['count'][$i],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                    if (!empty($targetData)) {
                        AcademicTargetGroup::insert($targetData);
                    }
                }

                // ---------------------------------------------------
                // ส่วนที่ 2.4: บันทึกข้อมูลคณะทำงาน (Committees)
                // ---------------------------------------------------
                Committee::where('academic_project_id', $id)->delete();
                if ($request->has('committees') && isset($request->committees['member_type'])) {
                    $committeeData = [];
                    $countCommittees = count($request->committees['member_type']);

                    for ($i = 0; $i < $countCommittees; $i++) {
                        $memberType = $request->committees['member_type'][$i];

                        // 🌟 1. เคลียร์ปัญหา ID: เช็คว่าถ้าช่องว่างเปล่า ("") ให้ส่งค่าเป็น null จริงๆ
                        $personnelId = ($memberType == '1' && !empty($request->committees['personnel_id'][$i]))
                            ? $request->committees['personnel_id'][$i]
                            : null;

                        $externalId = ($memberType == '0' && !empty($request->committees['external_id'][$i]))
                            ? $request->committees['external_id'][$i]
                            : null;

                        // 🌟 2. เคลียร์ปัญหาตัวเลข: บังคับแปลงข้อความ "10000.0" ให้เป็น Float (ตัวเลข)
                        $remuneration = $request->committees['remuneration_total'][$i] ?: 0;
                        $safeRemuneration = (float) $remuneration;

                        $committeeData[] = [
                            'academic_project_id' => $id,
                            'member_type' => $memberType,
                            'personnel_id' => $personnelId,
                            'external_id' => $externalId,
                            'project_position_id' => $request->committees['project_position_id'][$i],
                            'remuneration_total' => $safeRemuneration, // ใช้ค่าที่แปลงร่างแล้ว
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    if (!empty($committeeData)) {
                        Committee::insert($committeeData);
                    }
                }
                DB::commit();

                return redirect()->route('trainings.projects.edit', ['project' => $id, 'tab' => 'tab3'])
                    ->with('success', 'บันทึกข้อมูลเฉพาะและคณะทำงานสำเร็จ!');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
            }
        }

        // =========================================
        // กรณีบันทึกข้อมูลจาก "แท็บ 4" (งบประมาณ)
        // =========================================
        if ($step == '4') {
            try {
                DB::beginTransaction();

                // 1. กวาดล้างของเก่า
                AcademicBudgetIncomes::where('academic_project_id', $id)->delete();
                AcademicBudgetExpenses::where('academic_project_id', $id)->delete();

                // 2. บันทึกแผนรายรับ
                if ($request->has('incomes') && isset($request->incomes['category_id'])) {
                    $incomesData = [];
                    foreach ($request->incomes['category_id'] as $i => $categoryId) {
                        // ข้ามแถว Template ที่ว่างเปล่า
                        if (empty($categoryId)) continue;

                        $incomesData[] = [
                            'academic_project_id' => $id,
                            'category_id'         => $categoryId,
                            'description'         => $request->incomes['description'][$i] ?? null,
                            'unit_cost'           => $request->incomes['unit_cost'][$i] ?? 0,
                            'quantity'            => $request->incomes['quantity'][$i] ?? 0,
                            'total_amount'        => $request->incomes['total_amount'][$i] ?? 0,
                            'created_at'          => now(),
                            'updated_at'          => now(),
                        ];
                    }
                    if (!empty($incomesData)) {
                        AcademicBudgetIncomes::insert($incomesData);
                    }
                }

                // 3. บันทึกแผนรายจ่าย
                if ($request->has('expenses') && isset($request->expenses['category_id'])) {
                    $expensesData = [];
                    foreach ($request->expenses['category_id'] as $i => $categoryId) {
                        if (empty($categoryId)) continue;

                        $expensesData[] = [
                            'academic_project_id' => $id,
                            'category_id'         => $categoryId,
                            'description'         => $request->expenses['description'][$i] ?? null,
                            'cost_per_unit'       => $request->expenses['cost_per_unit'][$i] ?? 0,
                            'factor_1'            => $request->expenses['factor_1'][$i] ?? null,
                            'factor_2'            => $request->expenses['factor_2'][$i] ?? null,
                            'uom'                 => $request->expenses['uom'][$i] ?? null,
                            'total_amount'        => $request->expenses['total_amount'][$i] ?? 0,
                            'can_average'         => $request->expenses['can_average'][$i] ?? 0,
                            'created_at'          => now(),
                            'updated_at'          => now(),
                        ];
                    }
                    if (!empty($expensesData)) {
                        AcademicBudgetExpenses::insert($expensesData);
                    }
                }

                DB::commit();

                // บันทึกเสร็จให้เด้งไป Tab 5 พร้อมโชว์สีเขียว
                return redirect()->route('trainings.projects.edit', ['project' => $id, 'tab' => 'tab5'])
                    ->with('success', 'บันทึกข้อมูลงบประมาณสำเร็จ!');
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Update Budget Project Error: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกงบประมาณ: ' . $e->getMessage());
            }
        }

        // =========================================
        // กรณีบันทึกข้อมูลจาก "แท็บ 5" (ผลลัพธ์ & ประเมิน)
        // =========================================
        if ($step == '5') {
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

                        // 5.3 ผลสัมฤทธิ์ (เอา evaluation_score ออกแล้ว)
                        'sroi_score' => $request->sroi_score,
                        'award_count' => $request->award_count,
                        'industrial_value' => $request->industrial_value,
                        'project_achievement' => $request->project_achievement,
                    ]
                );

                DB::commit();

                // บันทึกเสร็จให้เด้งไป Tab 6 (ภาพรวม) พร้อมโชว์สีเขียว
                return redirect()->route('trainings.projects.edit', ['project' => $id, 'tab' => 'tab6'])
                    ->with('success', 'บันทึกข้อมูลผลลัพธ์และการประเมินเรียบร้อยแล้ว!');
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Update Evaluation Error: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกผลการประเมิน: ' . $e->getMessage());
            }
        }
        // กันเหนียว กรณีหลุดจากเงื่อนไข
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            // 1. ดึงข้อมูลโครงการมาตรวจสอบ
            $project = AcademicProject::findOrFail($id);

            // 2. 🛡️ เช็คสถานะ: โครงการที่อนุมัติไปแล้ว (>= 300) ห้ามลบทิ้งเด็ดขาด!
            if ($project->overall_status >= 300) {
                $errorMessage = 'ไม่สามารถลบได้ เนื่องจากโครงการผ่านขั้นตอนการอนุมัติไปแล้ว (หากไม่ต้องการจัดกิจกรรม ให้ทำการ "ยกเลิกโครงการ" แทน)';

                // ดักจับเผื่อกรณี Custom CRUD JS ของคุณวัชกรยิงมาเป็น AJAX
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMessage], 403);
                }
                return redirect()->back()->with('error', $errorMessage);
            }

            // 3. 🛡️ เช็คสิทธิ์ด้วย Spatie Roles
            $user = auth()->user();
            
            // เช็คว่า: "ไม่ใช่คนสร้างโครงการ" และ "ไม่มี Role เป็น admin หรือ manager" ใช่หรือไม่?
            if ($project->created_by != $user->id && !$user->hasAnyRole(['admin', 'manager'])) {
                $errorAuthMessage = 'คุณไม่มีสิทธิ์ลบโครงการนี้ (อนุญาตเฉพาะเจ้าของโครงการ, Admin หรือ Manager เท่านั้น)';
                
                // ตอบกลับแบบ AJAX สำหรับ SweetAlert
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errorAuthMessage], 403);
                }
                // ตอบกลับแบบปกติ
                return redirect()->back()->with('error', $errorAuthMessage);
            }

            DB::beginTransaction();

            // 4. 💾 ทำการ Soft Delete (อัปเดต del_status แทนการลบทิ้งจริงๆ)
            // พร้อมเก็บ Log ว่าใครเป็นคนกดลบ
            $project->update([
                'del_status' => 1,
                'update_by'  => auth()->id() ?? null
            ]);

            DB::commit();

            $successMessage = 'ลบโครงการ (ฉบับร่าง) ออกจากระบบเรียบร้อยแล้ว';

            // ตอบกลับตามประเภทของ Request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMessage]);
            }

            return redirect()->route('trainings.projects.index', ['type_id' => $project->project_type_id])
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

    // ฟังก์ชันรับข้อมูลสร้างกลุ่มเป้าหมายใหม่จาก Popup (AJAX)
    public function storeCustomerGroupAjax(Request $request)
    {
        try {
            // อย่าลืม use App\Models\CustomerGroup; ด้านบนสุด
            $customerGroup = CustomerGroup::create([
                'customer_type_id' => $request->customer_type_id,
                'name_th' => $request->name_th,
                'name_en' => $request->name_en,
                'description' => $request->description,
            ]);

            // ส่งข้อมูลที่เพิ่งสร้างเสร็จ กลับไปให้ Select2 อัปเดตหน้าจอ
            return response()->json([
                'success' => true,
                'id' => $customerGroup->id,
                'name_th' => $customerGroup->name_th
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ฟังก์ชันรับข้อมูลสร้างบุคคลภายนอกใหม่จาก Popup (AJAX)
    public function storeExternalAjax(Request $request)
    {
        try {
            // อย่าลืม use App\Models\External; ด้านบนสุด
            $external = External::create([
                'prefix_id' => $request->prefix_id,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'department' => $request->department,
                'phone' => $request->phone,
                'email' => $request->email,
                'description' => $request->description,
                'is_active' => 1, // เปิดใช้งานอัตโนมัติ
            ]);

            // ค้นหาคำนำหน้าชื่อเพื่อเอามาต่อเป็นชื่อเต็มส่งกลับไปหน้าจอ
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

    // ==========================================
    // 🌟 ฟังก์ชันบันทึกข้อมูลกำหนดการและไฟล์แนบ (AJAX)
    // ==========================================
    public function storeScheduleAjax(Request $request)
    {
        // 1. ตรวจสอบข้อมูลเบื้องต้น
        $request->validate([
            'training_project_id' => 'required',
            'schedule_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'topic' => 'required',
        ]);

        //dd($request->all());
        // ใช้ DB Transaction เพื่อความปลอดภัยสูงสุด (ถ้าพังกลางทาง ระบบจะ Rollback คืนค่าให้หมด)
        DB::beginTransaction();

        try {
            // 2. 📝 บันทึกข้อมูลลงตารางหลัก (TrainingSchedules)
            // 🌟 เช็คว่าส่ง ID มาไหม? ถ้ามี = อัปเดตของเก่า, ถ้าไม่มี = สร้างใหม่
            if ($request->schedule_id) {
                $schedule = TrainingSchedules::find($request->schedule_id);
                // ถ้าเป็นการอัปเดต ให้ลบข้อมูลวิทยากร/สถานที่เดิมทิ้งก่อน แล้วเดี๋ยวโค้ดด้านล่างจะ Insert ใหม่เข้าไปแทน
                TrainingMember::where('training_schedule_id', $schedule->id)->delete();
                TrainingSchedulesLocation::where('training_schedule_id', $schedule->id)->delete();
            } else {
                $schedule = new TrainingSchedules();
            }

            $schedule->training_project_id = $request->training_project_id;
            $schedule->schedule_date = $request->schedule_date;
            $schedule->start_time = $request->start_time;
            $schedule->end_time = $request->end_time;
            $schedule->topic = $request->topic;
            $schedule->save();

            $scheduleId = $schedule->id;

            // 3. 👨‍🏫 บันทึก "วิทยากร" (TrainingMember)
            if ($request->has('members') && isset($request->members['member_type'])) {
                $membersData = [];
                foreach ($request->members['member_type'] as $index => $type) {
                    // ถ้าเลือกคนใน ให้ใช้ personnel_id ถ้าคนนอกใช้ external_id
                    $personnelId = ($type == '1') ? ($request->members['personnel_id'][$index] ?? null) : null;
                    $externalId = ($type == '0') ? ($request->members['external_id'][$index] ?? null) : null;
                    $positionId = $request->members['training_position_id'][$index] ?? null;

                    if ($personnelId || $externalId) {
                        $membersData[] = [
                            'training_schedule_id' => $scheduleId,
                            'member_type' => $type,
                            'personnel_id' => $personnelId,
                            'external_id' => $externalId,
                            'training_position_id' => $positionId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                if (!empty($membersData)) {
                    TrainingMember::insert($membersData);
                }
            }

            // 4. 📍 บันทึก "สถานที่" (TrainingSchedulesLocation)
            if ($request->has('locations') && isset($request->locations['location_name'])) {
                $locationsData = [];
                foreach ($request->locations['location_name'] as $index => $locName) {
                    if (!empty($locName)) {
                        $locationsData[] = [
                            'training_schedule_id' => $scheduleId,
                            'location_name' => $locName,
                            'province_id' => $request->locations['province_id'][$index] ?? null,
                            'latitude' => $request->locations['latitude'][$index] ?? null,
                            'longitude' => $request->locations['longitude'][$index] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                if (!empty($locationsData)) {
                    TrainingSchedulesLocation::insert($locationsData);
                }
            }

            // 5. 📁 บันทึก "เอกสารแนบ" (TrainingScheduleDocument) พร้อมอัปโหลดไฟล์
            if ($request->has('documents')) {
                $docs = $request->documents;

                // กรองเอาเฉพาะ ID ไฟล์เดิมที่ยังเหลืออยู่บนหน้าจอ (อันไหนโดนลบ ค่าจะเป็นว่างๆ เราจะกรองทิ้ง)
                $keptDocIds = array_filter($docs['old_id'] ?? []);

                // 🌟 สเต็ป A: จัดการไฟล์เก่า (ถ้าเป็นการกดปุ่มแก้ไข)
                if ($request->schedule_id) {
                    $oldDocs = TrainingScheduleDocument::where('training_schedule_id', $scheduleId)->get();

                    foreach ($oldDocs as $oldDoc) {
                        // ถ้า ID ของไฟล์เก่า ไม่มีอยู่ในรายชื่อที่รอดชีวิตมา ($keptDocIds) แปลว่า User กดลบทิ้ง!
                        if (!in_array($oldDoc->id, $keptDocIds)) {
                            // 1. ตามไปลบไฟล์จริงๆ ทิ้งจากโฟลเดอร์ Storage
                            if (Storage::disk('public')->exists($oldDoc->file_path)) {
                                Storage::disk('public')->delete($oldDoc->file_path);
                            }
                            // 2. ลบออกจากฐานข้อมูล
                            $oldDoc->delete();
                        } else {
                            // 🌟 สเต็ป B: ถ้าไฟล์ยังอยู่ ให้เช็คว่า User พิมพ์เปลี่ยน "ชื่อเอกสาร" หรือเปล่า ถ้าเปลี่ยนก็เซฟให้ด้วย
                            $index = array_search($oldDoc->id, $docs['old_id']);
                            if ($index !== false && isset($docs['document_name'][$index])) {
                                $oldDoc->document_name = $docs['document_name'][$index];
                                $oldDoc->save();
                            }
                        }
                    }
                }

                // 🌟 สเต็ป C: เซฟไฟล์ใหม่เอี่ยมที่เพิ่งถูกกด Browse เข้ามา
                if (isset($docs['file'])) {
                    foreach ($docs['file'] as $index => $file) {
                        if ($file && $file->isValid()) {
                            $fileName = time() . '_' . $file->getClientOriginalName();
                            $filePath = $file->storeAs('schedules/documents', $fileName, 'public');

                            TrainingScheduleDocument::create([
                                'training_schedule_id' => $scheduleId,
                                'document_name' => $docs['document_name'][$index] ?? 'เอกสารประกอบกิจกรรม',
                                'file_path' => $filePath,
                                'file_type' => $file->getClientOriginalExtension(),
                                'file_size' => $file->getSize(),
                            ]);
                        }
                    }
                }
            }
            // 🌟 ผ่านทุกด่านอย่างปลอดภัย ทำการ Commit ยืนยันข้อมูลลง Database
            DB::commit();
            // 🌟 ดึงข้อมูลไฟล์แนบล่าสุดของกิจกรรมนี้ เพื่อส่งกลับไปวาดปุ่มดาวน์โหลดที่หน้าจอ
            $updatedDocs = TrainingScheduleDocument::where('training_schedule_id', $scheduleId)->get();
            return response()->json([
                'success' => true,
                'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว',
                'schedule_id' => $scheduleId,
                'docs' => $updatedDocs // <-- เพิ่มบรรทัดนี้ ส่งไฟล์กลับไปให้ JS
            ]);
        } catch (\Exception $e) {
            // 🚨 ถ้ามีอะไรพังกลางทาง ให้ Rollback ข้อมูลกลับทั้งหมด
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // 🌟 ฟังก์ชันดึงข้อมูลกำหนดการมาแก้ไข (AJAX GET)
    // ==========================================
    public function editScheduleAjax($id)
    {
        $schedule = TrainingSchedules::find($id);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลกิจกรรม']);
        }

        // ดึงข้อมูลลูกๆ
        $members = TrainingMember::where('training_schedule_id', $id)->get();
        $locations = TrainingSchedulesLocation::where('training_schedule_id', $id)->get();
        $docs = TrainingScheduleDocument::where('training_schedule_id', $id)->get();

        return response()->json([
            'success' => true,
            'schedule' => $schedule,
            'members' => $members,
            'locations' => $locations,
            'docs' => $docs
        ]);
    }

    // ==========================================
    // 🌟 ฟังก์ชันลบกิจกรรม (AJAX DELETE)
    // ==========================================
    public function deleteScheduleAjax($id)
    {
        try {
            DB::beginTransaction();

            $schedule = TrainingSchedules::find($id);
            if (!$schedule) {
                return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลกิจกรรมที่ต้องการลบ']);
            }

            // 1. ตามไปลบไฟล์เอกสารแนบออกจาก Storage (ลบไฟล์จริง) และ Database
            $docs = TrainingScheduleDocument::where('training_schedule_id', $id)->get();
            foreach ($docs as $doc) {
                if (Storage::disk('public')->exists($doc->file_path)) {
                    Storage::disk('public')->delete($doc->file_path);
                }
                $doc->delete();
            }

            // 2. ลบข้อมูลลูกๆ (วิทยากรและสถานที่)
            TrainingMember::where('training_schedule_id', $id)->delete();
            TrainingSchedulesLocation::where('training_schedule_id', $id)->delete();

            // 3. ลบตัวกิจกรรมหลัก
            $schedule->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'ลบกิจกรรมและไฟล์แนบเรียบร้อยแล้ว']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }

    public function storeTargetGroupAjax(Request $request)
    {
        try {
            // 🛡️ 1. Validation (ปรับให้ส่ง Error กลับไปให้ AJAX อ่านรู้เรื่อง)
            $validator = Validator::make($request->all(), [
                'parent_id'   => 'nullable|integer|exists:target_groups,id',
                'name_th'     => 'required|string|max:255',
                'name_en'     => 'nullable|string|max:255',
                'group_type'  => 'nullable|string|max:100',
                'description' => 'nullable|string',
            ], [
                'parent_id.exists' => 'ไม่พบข้อมูลกลุ่มเป้าหมายหลักในระบบ กรุณาเลือกใหม่',
                'name_th.required' => 'กรุณาระบุชื่อกลุ่มเป้าหมาย (ภาษาไทย)',
                'name_th.max'      => 'ชื่อกลุ่มเป้าหมายต้องไม่เกิน 255 ตัวอักษร',
            ]);

            // ถ้า Validation ไม่ผ่าน ส่ง Error Message กลับไปให้ JS โชว์ Swal
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first() // ดึงข้อความแรกสุดมาโชว์
                ]);
            }

            $validated = $validator->validated();

            DB::beginTransaction();

            // 🧠 2. คำนวณ Level อัตโนมัติ เหมือนโค้ดต้นฉบับเป๊ะ
            $level = 1;
            if (!empty($validated['parent_id'])) {
                $parent = TargetGroup::findOrFail($validated['parent_id']);
                $level = $parent->level + 1;
            }

            // 💾 3. บันทึกข้อมูลลงตาราง target_groups
            $targetGroup = TargetGroup::create([
                'parent_id'   => $validated['parent_id'] ?? null,
                'name_th'     => trim($validated['name_th']),
                'name_en'     => !empty($validated['name_en']) ? trim($validated['name_en']) : null,
                'group_type'  => !empty($validated['group_type']) ? trim($validated['group_type']) : null,
                'description' => !empty($validated['description']) ? trim($validated['description']) : null,
                'level'       => $level,
                'is_active'   => $request->is_active == '1' ? 1 : 0,
            ]);

            DB::commit();

            // 🟢 4. โหลด Full Path เพื่อส่งกลับไปให้ JS อัปเดตในช่อง Select2
            // สมมติว่าใน Model TargetGroup คุณวัชกรมี Accessor getFullPathAttribute() อยู่แล้ว
            $displayName = $targetGroup->full_path ?? $targetGroup->name_th;

            return response()->json([
                'success'   => true,
                'id'        => $targetGroup->id,
                'name_th'   => $targetGroup->name_th,
                'full_path' => $displayName // ส่ง Full Path กลับไปโชว์สวยๆ
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TargetGroup AJAX Store Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในระบบฐานข้อมูล กรุณาลองใหม่อีกครั้ง'
            ]);
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

            // 1. 🛡️ เช็คสิทธิ์: คนยกเลิกต้องเป็นเจ้าของ หรือมี Role admin/manager
            if ($project->created_by != $user->id && !$user->hasAnyRole(['admin', 'manager'])) {
                return response()->json(['success' => false, 'message' => 'คุณไม่มีสิทธิ์ยกเลิกโครงการนี้'], 403);
            }

            // 2. 🛡️ เช็คสถานะ: ต้องไม่อยู่ในฉบับร่าง (100-200 ลบทิ้งได้เลย) และยังไม่เสร็จสิ้น (800)
            if ($project->overall_status < 300 || $project->overall_status >= 800) {
                return response()->json(['success' => false, 'message' => 'สถานะโครงการปัจจุบันไม่สามารถยกเลิกได้'], 400);
            }

            // 3. Validate เหตุผล
            $request->validate([
                'cancel_reason' => 'required|string|max:1000'
            ], [
                'cancel_reason.required' => 'กรุณาระบุเหตุผลที่ยกเลิกโครงการด้วยครับ'
            ]);

            // 4. 💾 อัปเดตข้อมูล
            DB::beginTransaction();
            $project->update([
                'overall_status' => 900,
                'cancel_reason'  => trim($request->cancel_reason), // ต้องมีฟิลด์นี้ใน DB นะครับ
                'update_by'      => $user->id
            ]);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'ยกเลิกโครงการและเก็บประวัติเรียบร้อยแล้ว']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel Project Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในระบบฐานข้อมูล: ' . $e->getMessage()], 500);
        }
    }
}

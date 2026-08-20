<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicProject;
use App\Models\MasterData\ProjectType;
use Illuminate\Http\Request;

class ProjectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // ดึงข้อมูลทั้งหมด เรียงจากล่าสุดไปเก่าสุด
        $projectTypes = ProjectType::orderBy('id', 'asc')->get();

        // ส่งข้อมูลไปที่หน้า View
        return view('master-data.project-types.index', compact('projectTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('master-data.project-types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลว่ากรอกมาครบไหม และไม่ซ้ำ
        $request->validate([
            'name_th' => 'required|string|max:150|unique:project_types,name_th',
            'abbreviation' => 'required|string|max:10|unique:project_types,abbreviation',
        ], [
            'name_th.required' => 'กรุณากรอกชื่อประเภทโครงการ',
            'name_th.unique' => 'ชื่อประเภทโครงการนี้มีในระบบแล้ว',
            'abbreviation.required' => 'กรุณากรอกอักษรย่อโครงการ',
            'abbreviation.unique' => 'อักษรย่อนี้มีในระบบแล้ว',
        ]);

        // บันทึกลงฐานข้อมูล
        ProjectType::create([
            'name_th' => $request->name_th,
            'name_en' => $request->name_th, // กรณีไม่มีฟิลด์ name_en แยก
            'abbreviation' => $request->abbreviation,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        // กลับไปหน้าตารางพร้อมส่งข้อความแจ้งเตือน
        return redirect()->route('master-data.project-types.index')
            ->with('success', 'เพิ่มข้อมูลประเภทโครงการสำเร็จ!');
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
        // ค้นหาข้อมูลตาม ID ถ้าไม่เจอจะแสดงหน้า 404
        $projectType = ProjectType::findOrFail($id);

        return view('master-data.project-types.edit', compact('projectType'));
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
        $request->validate([
            'name_th' => 'required|string|max:150|unique:project_types,name_th,' . $id,
            'abbreviation' => 'required|string|max:10|unique:project_types,abbreviation,' . $id,
        ], [
            'name_th.required' => 'กรุณากรอกชื่อประเภทโครงการ',
            'name_th.unique' => 'ชื่อประเภทโครงการนี้มีในระบบแล้ว',
            'abbreviation.required' => 'กรุณากรอกอักษรย่อโครงการ',
            'abbreviation.unique' => 'อักษรย่อนี้มีในระบบแล้ว',
        ]);

        $projectType = ProjectType::findOrFail($id);
        $projectType->update([
            'name_th' => $request->name_th,
            'abbreviation' => $request->abbreviation,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('master-data.project-types.index')
            ->with('success', 'อัปเดตข้อมูลสำเร็จ!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $projectType = ProjectType::findOrFail($id);

        // ตรวจสอบว่ามี project ใช้อยู่หรือไม่
        $isUsed = AcademicProject::where('project_type_id', $id)->exists();
    
        if ($isUsed) {
            return redirect()
                ->route('master-data.project-types.index')
                ->with('error', 'ไม่สามารถลบได้ เนื่องจากมีโครงการใช้งานประเภทนี้อยู่');
        }
   
        $projectType->delete();
    
        return redirect()
            ->route('master-data.project-types.index')
            ->with('success', 'ลบข้อมูลสำเร็จ!');
    }
}

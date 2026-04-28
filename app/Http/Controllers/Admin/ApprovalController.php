<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicProject;
use App\Models\AcademicProjectLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    /**
     * 1. 📋 หน้าตารางแสดงโครงการที่รออนุมัติ (Status = 200)
     */
    public function index()
    {
        // ดักสิทธิ์ใหม่อีกรอบเพื่อความชัวร์ (เผื่อหลุดจาก Middleware)
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'เฉพาะผู้ดูแลระบบเท่านั้น');
        }

        // ค้นหาเฉพาะโครงการสถานะ 200 (เสนอขออนุมัติ) และเรียงจากเก่าไปใหม่ (ใครส่งมาก่อน ตรวจก่อน)
        $projects = AcademicProject::select(
                'academic_projects.*',
                'users.name as creator_name',
                'project_types.name_th as project_type_name'
            )
            ->leftJoin('users', 'academic_projects.created_by', '=', 'users.id')
            ->leftJoin('project_types', 'academic_projects.project_type_id', '=', 'project_types.id')
            ->where('academic_projects.overall_status', 200)
            ->where(function ($q) {
                $q->whereNull('academic_projects.del_status')
                  ->orWhere('academic_projects.del_status', 0);
            })
            ->orderBy('academic_projects.updated_at', 'asc') 
            ->get();

        return view('admin.approvals.index', compact('projects'));
    }

    /**
     * 2. 🔍 หน้าดูรายละเอียดก่อนกดอนุมัติ
     */
    public function show($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'เฉพาะผู้ดูแลระบบเท่านั้น');
        }

        $project = AcademicProject::findOrFail($id);

        // ถ้าสถานะไม่ใช่ 200 แล้ว (เช่น อนุมัติไปแล้ว) ให้เด้งกลับ
        if ($project->overall_status != 200) {
            return redirect()->route('admin.approvals.index')
                ->with('error', 'โครงการนี้ไม่ได้อยู่ในสถานะรออนุมัติแล้วครับ');
        }

        // ส่งไปใช้หน้าโชว์รายละเอียด (คุณสามารถสร้าง View ใหม่ หรือชี้ไปที่ View เดิมที่มีอยู่ก็ได้ครับ)
        return view('admin.approvals.show', compact('project'));
    }

    /**
     * 3. ✅ กดยืนยันอนุมัติโครงการ (200 -> 300)
     */
    public function approve(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $project = AcademicProject::findOrFail($id);

            if ($project->overall_status != 200) {
                return back()->with('error', 'ทำรายการไม่สำเร็จ: โครงการนี้ไม่ได้อยู่ในสถานะรออนุมัติ');
            }

            // อัปเดตสถานะเป็น 300
            $project->update([
                'overall_status' => 300,
                'update_by' => auth()->id()
            ]);

            // เก็บ Log อนุมัติ
            AcademicProjectLog::create([
                'academic_project_id' => $project->id,
                'user_id'             => auth()->id(),
                'action'              => 'approved',
                'status_code'         => 300,
                'comment'             => 'อนุมัติโครงการเรียบร้อยแล้ว'
            ]);

            DB::commit();
            return redirect()->route('admin.approvals.index')->with('success', 'อนุมัติโครงการสำเร็จ!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาดในระบบ: ' . $e->getMessage());
        }
    }

    /**
     * 4. ❌ กดตีกลับโครงการ (200 -> 110)
     */
    public function reject(Request $request, $id)
    {
        // บังคับว่าต้องกรอกเหตุผลที่ตีกลับด้วยนะ!
        $request->validate([
            'reject_reason' => 'required|string|max:1000'
        ], [
            'reject_reason.required' => 'กรุณาระบุเหตุผลที่ต้องการตีกลับด้วยครับ'
        ]);

        DB::beginTransaction();
        try {
            $project = AcademicProject::findOrFail($id);

            if ($project->overall_status != 200) {
                return back()->with('error', 'ทำรายการไม่สำเร็จ: โครงการนี้ไม่ได้อยู่ในสถานะรออนุมัติ');
            }

            // อัปเดตสถานะเป็น 110 (ตีกลับ)
            $project->update([
                'overall_status' => 110,
                'update_by' => auth()->id()
            ]);

            // เก็บ Log ตีกลับ พร้อมแนบเหตุผลลงไปใน comment
            AcademicProjectLog::create([
                'academic_project_id' => $project->id,
                'user_id'             => auth()->id(),
                'action'              => 'returned',
                'status_code'         => 110,
                'comment'             => $request->reject_reason // ✅ ลบ clone ออก เหลือแค่นี้ครับ
            ]);

            DB::commit();
            return redirect()->route('admin.approvals.index')->with('success', 'ตีกลับโครงการให้ผู้จัดทำแก้ไขเรียบร้อยแล้ว!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาดในระบบ: ' . $e->getMessage());
        }
    }
}

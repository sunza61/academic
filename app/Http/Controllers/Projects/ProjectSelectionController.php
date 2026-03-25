<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\MasterData\ProjectType;
use Illuminate\Http\Request;

class ProjectSelectionController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลประเภทโครงการทั้งหมดมาแสดงให้ User เลือก
        // ถ้าคุณมีฟิลด์ is_active ก็ใส่ where('is_active', 1) ได้นะครับ
        $projectTypes = ProjectType::orderBy('id', 'asc')->get();

        return view('projects.selection.index', compact('projectTypes'));
    }

    // ฟังก์ชันนี้จะเป็น "ตัวสลับรางรถไฟ" (Gateway) พอดึง ID มาปุ๊บ จะส่งไป Controller ที่ถูกต้อง
    public function gateway(Request $request, $id)
    {
        $projectType = ProjectType::findOrFail($id);

        // *** ตรงนี้คือจุดสำคัญ! ***
        // เราต้องมีเงื่อนไขเช็คว่า ประเภทโครงการนี้ เป็น "ฝึกอบรม" หรือ "รับจ้าง"
        // สมมติว่า ID 1, 2 คือฝึกอบรม และ ID 3, 4 คือรับจ้าง (เดี๋ยวเรามาปรับแก้ logic ตรงนี้กันทีหลังได้ครับ)
        
        $trainingTypeIds = [1, 2]; // สมมติรหัสประเภทที่เป็นฝั่งฝึกอบรม

        if (in_array($projectType->id, $trainingTypeIds)) {
            // เด้งไปหน้า Step 2 ของฝั่ง ฝึกอบรม พร้อมแนบ type_id ไปด้วย
            return redirect()->route('trainings.projects.index', ['type_id' => $projectType->id]);
        } else {
            // เด้งไปหน้า Step 2 ของฝั่ง รับจ้าง/โครงการ
            return redirect()->route('contracts.projects.index', ['type_id' => $projectType->id]);
        }
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingProjectPolicy
{
    use HandlesAuthorization;

    /**
     * ดักจับสิทธิ์ Admin ให้ทำได้ทุกอย่างแบบไม่ต้องเช็คต่อ (God Mode)
     * *หมายเหตุ: ถ้ากติกาบอกว่า Manager ลบคนอื่นไม่ได้ เราต้องเอา Manager ออกจากตรงนี้ครับ
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * ✏️ กติกาการแก้ไข (Update / Edit)
     */
    public function update(User $user, $project) 
    {
        // 1. หาค่า created_by ให้เจอ ไม่ว่าจะส่ง Model แม่ หรือ ลูก มาก็ตาม (ของเดิม - ไม่แตะ)
        $createdBy = method_exists($project, 'academicProject') 
                        ? ($project->academicProject->created_by ?? null) 
                        : ($project->created_by ?? null);

        // 2. หา Status (ของเดิม - ไม่แตะ)
        $status = method_exists($project, 'academicProject') 
                        ? ($project->academicProject->overall_status ?? null) 
                        : ($project->overall_status ?? null);

        // 🌟 3. เติม 'admin' เข้าไปกอดคอกับ 'staff' ให้ผ่านฉลุยทุกกรณี
        if ($user->hasAnyRole(['admin', 'staff'])) {
            return true;
        }

       // 🌟 4. ถ้าเป็น Manager หรือ User ทั่วไป
        // - ต้องเป็น "เจ้าของโครงการ" 
        // - เพิ่ม in_array ดักไว้เลยว่า "ห้ามเป็น 200 (รอตรวจ), 800 (เสร็จ), 900 (ยกเลิก)"
        if ($user->id == $createdBy && !in_array($status, [200, 800, 900])) {
            return true;
        }

        return false;
    }

    /**
     * 🗑️ กติกาการลบ (Delete)
     */
    public function delete(User $user, $project)
    {
        $createdBy = method_exists($project, 'academicProject') 
                        ? ($project->academicProject->created_by ?? null) 
                        : ($project->created_by ?? null);

        $status = method_exists($project, 'academicProject') 
                        ? ($project->academicProject->overall_status ?? null) 
                        : ($project->overall_status ?? null);

        // การลบ จะลบได้เฉพาะสถานะ 100 (ฉบับร่าง) เท่านั้น!
        if ($status != 100) {
            return false; 
        }

        // ถ้าเป็น Staff ให้ผ่าน
        if ($user->hasAnyRole(['staff'])) {
            return true;
        }

        // ถ้าเป็น Manager หรือ User ต้องเป็นเจ้าของเท่านั้น
        return $user->id == $createdBy;
    }

    /**
     * 🚫 กติกาการยกเลิก (Cancel)
     */
    public function cancel(User $user, $project)
    {
        $createdBy = method_exists($project, 'academicProject') 
                        ? ($project->academicProject->created_by ?? null) 
                        : ($project->created_by ?? null);

        $status = method_exists($project, 'academicProject') 
                        ? ($project->academicProject->overall_status ?? null) 
                        : ($project->overall_status ?? null);

        // ยกเลิกได้ต่อเมื่อ ยังไม่เสร็จ (800) และ ยังไม่ยกเลิก (900)
        if ($status == 800 || $status == 900) {
            return false;
        }

        // ถ้าเป็น Staff ให้ผ่าน
        if ($user->hasAnyRole(['staff'])) {
            return true;
        }

        // 🌟 สำหรับคนอื่นๆ: ต้องเป็นคนสร้าง และ สถานะ <= 700 และ สถานะต้อง "ไม่ใช่" 200
        return ($user->id == $createdBy && $status <= 700 && $status != 200);
    }

    /**
     * 📊 กติกาการรายงานผลและปิดโครงการ (Report)
     */
    public function report(User $user, $project)
    {
        $createdBy = method_exists($project, 'academicProject') 
                        ? ($project->academicProject->created_by ?? null) 
                        : ($project->created_by ?? null);

        $status = method_exists($project, 'academicProject') 
                        ? ($project->academicProject->overall_status ?? null) 
                        : ($project->overall_status ?? null);

        // 🌟 กฎเหล็ก: สถานะ "ต้องเป็น 700" เท่านั้น ถึงจะรายงานผลได้
        if ($status != 700) {
            return false;
        }

        // ถ้าผ่านด่าน 700 มาได้ -> Staff ทำได้ทุกคน
        if ($user->hasAnyRole(['staff'])) {
            return true;
        }

        // ถ้าผ่านด่าน 700 มาได้ -> Manager หรือ User ทั่วไป ต้องเป็น "เจ้าของโครงการ" เท่านั้น
        return $user->id == $createdBy;
    }

    /**
     * 👁️ กติกาการดูรายละเอียด (View / Show)
     */
    public function view(User $user, $project)
    {
        // หมายเหตุ: ถ้าเป็น 'admin' จะผ่านฉลุยไปตั้งแต่ฟังก์ชัน before() แล้วครับ

        // 1. ถ้าเป็นผู้บริหาร (manager) หรือ เจ้าหน้าที่ (staff) ให้สามารถ "ดูได้ทุกโครงการ"
        if ($user->hasAnyRole(['manager', 'staff'])) {
            return true;
        }

        // 2. ถ้าเป็น User ทั่วไป "ต้องเป็นเจ้าของโครงการเท่านั้น" ถึงจะดูได้
        $createdBy = method_exists($project, 'academicProject') 
                        ? ($project->academicProject->created_by ?? null) 
                        : ($project->created_by ?? null);

        return $user->id == $createdBy;
    }
}

// 👑 สิทธิ์ระดับสูงสุด (God Mode)
// Admin: สามารถทำได้ ทุกอย่าง (แก้ไข, ลบ, ยกเลิก, รายงานผล, ดูรายละเอียด) ในทุกโครงการ โดยไม่ต้องสนใจสถานะหรือเงื่อนไขใดๆ ทั้งสิ้น (ผ่านฉลุยตั้งแต่ด่าน before)

// 👁️ 1. สิทธิ์การดูรายละเอียด (View)
// Staff & Manager: สามารถดูรายละเอียดได้ "ทุกโครงการในระบบ" * User ทั่วไป: จะดูรายละเอียดได้เฉพาะโครงการที่ "ตัวเองเป็นคนสร้าง (เจ้าของโครงการ)" เท่านั้น

// ✏️ 2. สิทธิ์การแก้ไขข้อมูล (Update)
// Staff: สามารถแก้ไขข้อมูลได้ทุกโครงการเสมอ

// เจ้าของโครงการ (ไม่ว่าจะเป็น Manager หรือ User): จะแก้ไขโครงการของตัวเองได้ ก็ต่อเมื่อสถานะโครงการ ต้องไม่ใช่ 3 สถานะนี้:

// 200 (รอตรวจสอบ)

// 800 (เสร็จสิ้น)

// 900 (ยกเลิกโครงการ)

// (สรุปคือ: แก้ไขได้ตอนเป็นร่าง หรือตอนที่ตีกลับมาให้แก้)

// 🗑️ 3. สิทธิ์การลบโครงการ (Delete)
// ⚠️ เงื่อนไขบังคับ: จะลบได้ก็ต่อเมื่อโครงการอยู่ในสถานะ 100 (ฉบับร่าง) เท่านั้น! สถานะอื่นลบไม่ได้เด็ดขาด

// ใครลบได้บ้าง:

// Staff: ลบโครงการสถานะร่างของใครก็ได้

// เจ้าของโครงการ: ลบโครงการสถานะร่างของตัวเองได้

// 🚫 4. สิทธิ์การยกเลิกโครงการ (Cancel)
// ⚠️ เงื่อนไขบังคับ: จะยกเลิกไม่ได้ หากโครงการนั้นอยู่ในสถานะ 800 (เสร็จสิ้น) หรือ 900 (ยกเลิกไปแล้ว)

// ใครยกเลิกได้บ้าง:

// Staff: ยกเลิกได้ทุกโครงการ (ที่ไม่ใช่ 800, 900)

// เจ้าของโครงการ: ยกเลิกโครงการของตัวเองได้ โดยที่สถานะต้อง น้อยกว่าหรือเท่ากับ 700 และ ต้องไม่อยู่ในสถานะ 200 (รอตรวจ)

// 📊 5. สิทธิ์การรายงานผลและปิดโครงการ (Report)
// ⚠️ เงื่อนไขบังคับ: จะรายงานผลได้ โครงการจะต้องอยู่ในสถานะ 700 เท่านั้น! * ใครรายงานผลได้บ้าง:

// Staff: รายงานผลโครงการของใครก็ได้ (ที่สถานะ 700)

// เจ้าของโครงการ: รายงานผลได้เฉพาะโครงการของตัวเอง (ที่สถานะ 700)

// 💡 สรุปภาพรวม:

// Admin ทะลุทุกกฎ

// Staff มีสิทธิ์จัดการ (ดู, แก้ไข, ลบ, ยกเลิก, รายงาน) ได้เกือบทุกอย่างตามเงื่อนไขสถานะ (Status)

// Manager มีสิทธิพิเศษแค่ "มองเห็น" ได้ทุกโครงการ แต่ถ้าจะลงมือ แก้ไข/ลบ/ยกเลิก/รายงาน จะถูกปฏิบัติเหมือน User ทั่วไป คือต้องเป็น "เจ้าของโครงการ" เท่านั้นครับ
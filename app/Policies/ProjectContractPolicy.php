<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project\ProjectContract;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectContractPolicy
// {
//     use HandlesAuthorization;

//     /**
//      * ดักจับสิทธิ์ Admin ให้ทำได้ทุกอย่างแบบไม่ต้องเช็คต่อ (God Mode)
//      */
//     public function before(User $user, $ability)
//     {
//         if ($user->hasRole('admin')) {
//             return true;
//         }
//     }

//     /**
//      * ✏️ กติกาการแก้ไข (Update / Edit)
//      */
//     public function update(User $user, ProjectContract $contract) 
//     {
//         // 1. หาค่า created_by (ดึงจากโปรเจกต์แม่ก่อน ถ้าไม่มีค่อยดึงจากตัวสัญญา)
//         $createdBy = $contract->academicProject->created_by ?? $contract->created_by ?? null;

//         // 2. หา Status
//         $status = $contract->academicProject->overall_status ?? $contract->overall_status ?? null;

//         // 3. เติม 'admin' เข้าไปกอดคอกับ 'staff' ให้ผ่านฉลุยทุกกรณี
//         if ($user->hasAnyRole(['admin', 'staff'])) {
//             return true;
//         }

//         // 4. ถ้าเป็น Manager หรือ User ทั่วไป
//         // - ต้องเป็น "เจ้าของโครงการ" 
//         // - ห้ามเป็น 200 (รอตรวจ), 800 (เสร็จ), 900 (ยกเลิก)
//         if ($user->id == $createdBy && !in_array($status, [200, 800, 900])) {
//             return true;
//         }

//         return false;
//     }

//     /**
//      * 🗑️ กติกาการลบ (Delete)
//      */
//     public function delete(User $user, ProjectContract $contract)
//     {
//         $createdBy = $contract->academicProject->created_by ?? $contract->created_by ?? null;
//         $status = $contract->academicProject->overall_status ?? $contract->overall_status ?? null;

//         // การลบ จะลบได้เฉพาะสถานะ 100 (ฉบับร่าง) เท่านั้น!
//         if ($status != 100) {
//             return false; 
//         }

//         // ถ้าเป็น Staff ให้ผ่าน
//         if ($user->hasAnyRole(['staff'])) {
//             return true;
//         }

//         // ถ้าเป็น Manager หรือ User ต้องเป็นเจ้าของเท่านั้น
//         return $user->id == $createdBy;
//     }

//     /**
//      * 🚫 กติกาการยกเลิก (Cancel)
//      */
//     public function cancel(User $user, ProjectContract $contract)
//     {
//         $createdBy = $contract->academicProject->created_by ?? $contract->created_by ?? null;
//         $status = $contract->academicProject->overall_status ?? $contract->overall_status ?? null;

//         // ยกเลิกได้ต่อเมื่อ ยังไม่เสร็จ (800) และ ยังไม่ยกเลิก (900)
//         if ($status == 800 || $status == 900) {
//             return false;
//         }

//         // ถ้าเป็น Staff ให้ผ่าน
//         if ($user->hasAnyRole(['staff'])) {
//             return true;
//         }

//         // ถ้าเป็น Manager หรือ User ต้องเป็นเจ้าของ และสถานะต้อง <= 700
//         return ($user->id == $createdBy && $status <= 700);
//     }

//     /**
//      * 📊 กติกาการรายงานผลและปิดโครงการ (Report)
//      */
//     public function report(User $user, ProjectContract $contract)
//     {
//         $createdBy = $contract->academicProject->created_by ?? $contract->created_by ?? null;
//         $status = $contract->academicProject->overall_status ?? $contract->overall_status ?? null;

//         // กฎเหล็ก: สถานะ "ต้องเป็น 700" เท่านั้น ถึงจะรายงานผลได้
//         if ($status != 700) {
//             return false;
//         }

//         // ถ้าผ่านด่าน 700 มาได้ -> Staff ทำได้ทุกคน
//         if ($user->hasAnyRole(['staff'])) {
//             return true;
//         }

//         // ถ้าผ่านด่าน 700 มาได้ -> Manager หรือ User ทั่วไป ต้องเป็น "เจ้าของโครงการ" เท่านั้น
//         return $user->id == $createdBy;
//     }

//     /**
//      * 👁️ กติกาการดูรายละเอียด (View / Show)
//      */
//     public function view(User $user, ProjectContract $contract)
//     {
//         // 1. ถ้าเป็นผู้บริหาร (manager) หรือ เจ้าหน้าที่ (staff) ให้สามารถ "ดูได้ทุกโครงการ"
//         if ($user->hasAnyRole(['manager', 'staff'])) {
//             return true;
//         }

//         // 2. ถ้าเป็น User ทั่วไป "ต้องเป็นเจ้าของโครงการเท่านั้น" ถึงจะดูได้
//         $createdBy = $contract->academicProject->created_by ?? $contract->created_by ?? null;

//         return $user->id == $createdBy;
//     }
// }
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
<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\TargetGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TargetGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // ดึงข้อมูลทั้งหมด พร้อมกับข้อมูลตัวแม่ (with parent) เพื่อลดการคิวรี่ซ้ำซ้อน (N+1 Problem)
        $targetGroups = TargetGroup::with('parent')->get();

        // 🌟 ทริค: จัดเรียงข้อมูลตาม full_path เพื่อให้แม่และลูกอยู่ติดกันอย่างเป็นระเบียบในตาราง
        $targetGroups = $targetGroups->sortBy('full_path')->values();

        return view('master-data.target-groups.index', compact('targetGroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // ดึงข้อมูลกลุ่มเป้าหมายทั้งหมด เรียงตาม Level เพื่อให้แม่ขึ้นก่อนลูก
        $allGroups = TargetGroup::orderBy('level')->orderBy('name_th')->get();

        return view('master-data.target-groups.create', compact('allGroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // 🛡️ 1. Validation: ตรวจสอบความถูกต้องและดักจับข้อมูลขยะ
        $validated = $request->validate([
            // ตรวจสอบว่า parent_id ถ้ามีส่งมา ต้องเป็นตัวเลข และ "ต้องมีอยู่จริงในตาราง target_groups"
            'parent_id'   => 'nullable|integer|exists:target_groups,id',

            // name_th บังคับกรอก, ต้องเป็นข้อความ, ความยาวไม่เกิน 255
            'name_th'     => 'required|string|max:255',

            'name_en'     => 'nullable|string|max:255',
            'group_type'  => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ], [
            // ข้อความแจ้งเตือนภาษาไทย (Custom Error Messages)
            'parent_id.exists' => 'ไม่พบข้อมูลกลุ่มเป้าหมายหลัก (ตัวแม่) ในระบบ กรุณาเลือกใหม่',
            'name_th.required' => 'กรุณาระบุชื่อกลุ่มเป้าหมาย (ภาษาไทย)',
            'name_th.max'      => 'ชื่อกลุ่มเป้าหมายต้องไม่เกิน 255 ตัวอักษร',
        ]);

        // 🛡️ 2. Database Transaction: ป้องกันข้อมูลเข้าไม่ครบถ้วน
        try {
            DB::beginTransaction();

            // 🧠 3. คำนวณ Level อัตโนมัติ
            $level = 1; // ค่าเริ่มต้น (Level 1)

            if (!empty($validated['parent_id'])) {
                // ดึงข้อมูลแม่มา เพื่อเช็ค Level ของแม่
                $parent = TargetGroup::findOrFail($validated['parent_id']);
                $level = $parent->level + 1; // เอา Level แม่ + 1 
            }

            // 💾 4. บันทึกข้อมูล (ทำความสะอาดข้อมูลด้วย trim ก่อนลง DB)
            TargetGroup::create([
                'parent_id'   => $validated['parent_id'] ?? null,
                'name_th'     => trim($validated['name_th']),
                'name_en'     => !empty($validated['name_en']) ? trim($validated['name_en']) : null,
                'group_type'  => !empty($validated['group_type']) ? trim($validated['group_type']) : null,
                'description' => !empty($validated['description']) ? trim($validated['description']) : null,
                'level'       => $level,
                // เช็คค่าจาก Switch Checkbox ว่าถูกเปิดมาหรือไม่ (ถ้าไม่ได้ส่งมาให้เป็น 0)
                'is_active'   => $request->has('is_active') ? 1 : 0,
            ]);

            DB::commit(); // ยืนยันการบันทึกข้อมูลทั้งหมด

            // ส่งกลับไปหน้า index พร้อมข้อความ Success
            return redirect()->route('master-data.target-groups.index')
                ->with('success', 'บันทึกข้อมูลกลุ่มเป้าหมายสำเร็จเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack(); // 🔙 ถ้ายกเลิกหรือมี Error ให้ Rollback ข้อมูลกลับ ไม่ให้ Database พัง

            // 📝 เก็บ Log ข้อผิดพลาดไว้ให้ Dev ดู (ไม่โชว์ให้ User เห็น)
            Log::error('TargetGroup Store Error: ' . $e->getMessage());

            // ส่งกลับไปหน้าฟอร์มเดิม พร้อมค่าเดิม (withInput) และข้อความ Error
            return redirect()->back()
                ->withInput()
                ->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง');
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
    public function edit($id)
    {
        //
        $targetGroup = TargetGroup::findOrFail($id);
        
        // ดึงกลุ่มทั้งหมดมาโชว์ใน Dropdown 
        // 🌟 ทริค: เราจะไม่ดึงตัวมันเองมาโชว์ เพื่อป้องกัน User เผลอเลือกตัวเองเป็นแม่ (เดี๋ยวระบบจะวนลูป Error)
        $allGroups = TargetGroup::where('id', '!=', $id)
                                ->get()
                                ->sortBy('full_path');

        return view('master-data.target-groups.edit', compact('targetGroup', 'allGroups'));
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
        // 🛡️ ตรวจสอบข้อมูลเหมือนตอนสร้างใหม่ แต่เพิ่มกฎไม่ให้เลือกตัวเองเป็นแม่
        $validated = $request->validate([
            'parent_id'   => 'nullable|integer|exists:target_groups,id|not_in:' . $id,
            'name_th'     => 'required|string|max:255',
            'name_en'     => 'nullable|string|max:255',
            'group_type'  => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ], [
            'parent_id.not_in' => 'ไม่สามารถเลือกกลุ่มเป้าหมายนี้ให้อยู่ภายใต้ตัวเองได้',
            'name_th.required' => 'กรุณาระบุชื่อกลุ่มเป้าหมาย (ภาษาไทย)',
        ]);

        try {
            DB::beginTransaction();

            $targetGroup = TargetGroup::findOrFail($id);

            // 🧠 คำนวณ Level ใหม่ (เผื่อ User ย้ายหมวดหมู่)
            $level = 1;
            if (!empty($validated['parent_id'])) {
                $parent = TargetGroup::findOrFail($validated['parent_id']);
                $level = $parent->level + 1;
            }

            // 💾 อัปเดตข้อมูล
            $targetGroup->update([
                'parent_id'   => $validated['parent_id'] ?? null,
                'name_th'     => trim($validated['name_th']),
                'name_en'     => !empty($validated['name_en']) ? trim($validated['name_en']) : null,
                'group_type'  => !empty($validated['group_type']) ? trim($validated['group_type']) : null,
                'description' => !empty($validated['description']) ? trim($validated['description']) : null,
                'level'       => $level,
                'is_active'   => $request->has('is_active') ? 1 : 0,
            ]);

            DB::commit();

            return redirect()->route('master-data.target-groups.index')
                             ->with('success', 'อัปเดตข้อมูลกลุ่มเป้าหมายเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TargetGroup Update Error: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
        }
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
        try {
            $targetGroup = TargetGroup::findOrFail($id);
            
            // สั่งลบ (ฟังก์ชัน boot() ใน Model ที่เราเขียนไว้ จะจัดการลบตัวลูกๆ ให้เองอัตโนมัติ)
            $targetGroup->delete();

            return redirect()->route('master-data.target-groups.index')
                             ->with('success', 'ลบข้อมูลกลุ่มเป้าหมายเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('TargetGroup Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้ อาจมีการใช้งานข้อมูลนี้อยู่');
        }
    }
}

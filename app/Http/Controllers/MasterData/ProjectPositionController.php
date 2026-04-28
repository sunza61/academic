<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\ProjectPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $positions = ProjectPosition::orderBy('id')->get();
        return view('master-data.project-positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('master-data.project-positions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_th'     => 'required|string|max:255',
            'name_en'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ], [
            'name_th.required' => 'กรุณาระบุชื่อตำแหน่ง (ภาษาไทย)',
        ]);

        try {
            // 🌟 เช็คค่าติ๊ก
            $isUnique = $request->has('is_unique') ? 1 : 0;

            // 🛡️ ดักจับกติกา: ถ้าจะเซ็ตเป็น 1 ต้องเช็คก่อนว่ามีใครในฐานเป็น 1 หรือยัง
            if ($isUnique == 1) {
                $alreadyHasUnique = ProjectPosition::where('is_unique', 1)->exists();
                if ($alreadyHasUnique) {
                    return redirect()->back()
                                     ->withInput()
                                     ->with('error', 'ไม่สามารถบันทึกได้! เนื่องจากในระบบมีตำแหน่งอื่นที่ใช้เงื่อนไข "Is Unique" อยู่แล้ว (อนุญาตให้มีได้แค่ 1 ตำแหน่งเท่านั้น)');
                }
            }

            $validated['is_unique'] = $isUnique;
            ProjectPosition::create($validated);

            return redirect()->route('master-data.project-positions.index')
                             ->with('success', 'บันทึกข้อมูลตำแหน่งในโครงการเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('ProjectPosition Store Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
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
        $position = ProjectPosition::findOrFail($id);
        return view('master-data.project-positions.edit', compact('position'));
    
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
        $validated = $request->validate([
            'name_th'     => 'required|string|max:255',
            'name_en'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            // 🌟 เช็คค่าติ๊ก
            $isUnique = $request->has('is_unique') ? 1 : 0;

            // 🛡️ ดักจับกติกา: เช็คว่ามีคนอื่นเป็น 1 หรือยัง (แต่ต้องไม่นับตัวเองนะ!)
            if ($isUnique == 1) {
                $alreadyHasUnique = ProjectPosition::where('is_unique', 1)
                                                   ->where('id', '!=', $id) // ไม่เช็ค ID ของตัวเอง
                                                   ->exists();
                if ($alreadyHasUnique) {
                    return redirect()->back()
                                     ->withInput()
                                     ->with('error', 'ไม่สามารถอัปเดตได้! เนื่องจากในระบบมีตำแหน่งอื่นที่ใช้เงื่อนไข "Is Unique" อยู่แล้ว');
                }
            }

            $position = ProjectPosition::findOrFail($id);
            $validated['is_unique'] = $isUnique;
            $position->update($validated);

            return redirect()->route('master-data.project-positions.index')
                             ->with('success', 'อัปเดตข้อมูลตำแหน่งในโครงการเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('ProjectPosition Update Error: ' . $e->getMessage());
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
            $position = ProjectPosition::findOrFail($id);
            
            // 🕵️‍♂️ เช็คก่อนว่าตำแหน่งนี้ถูกใช้ในคณะทำงานหรือผู้รับผิดชอบโครงการหรือยัง
            $isUsedInCommittees = DB::table('committees')->where('project_position_id', $id)->exists();
            // (ถ้ามีตารางอื่นที่เก็บ position_id ให้เพิ่มเงื่อนไขเช็คตรงนี้ได้เลยครับ)

            if ($isUsedInCommittees) {
                return redirect()->back()->with('error', 'ไม่สามารถลบได้ เนื่องจากตำแหน่งนี้ถูกใช้งานในโครงการแล้ว');
            }

            $position->delete();
            return redirect()->route('master-data.project-positions.index')
                             ->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('ProjectPosition Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้ เนื่องจากอาจถูกใช้งานอยู่ในระบบฐานข้อมูล');
        }
    }
}

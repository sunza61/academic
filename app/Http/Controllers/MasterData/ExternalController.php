<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\External;
use App\Models\MasterData\Prefix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExternalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         // ดึงข้อมูลพร้อมคำนำหน้า
         $externals = External::with('prefix')->orderBy('firstname')->get();
         return view('master-data.externals.index', compact('externals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $prefixes = Prefix::all();
        return view('master-data.externals.create', compact('prefixes'));
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
        $validated = $request->validate([
            'prefix_id'   => 'required|integer',
            'firstname'   => 'required|string|max:255',
            'lastname'    => 'required|string|max:255',
            'department'  => 'required|string|max:255',
            'phone'       => 'nullable|string|max:50',
            'email'       => 'nullable|email|max:255',
            'description' => 'nullable|string',
        ], [
            'prefix_id.required'  => 'กรุณาเลือกคำนำหน้า',
            'firstname.required'  => 'กรุณาระบุชื่อ',
            'lastname.required'   => 'กรุณาระบุนามสกุล',
            'department.required' => 'กรุณาระบุสังกัด/หน่วยงาน',
        ]);

        try {
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            External::create($validated);

            return redirect()->route('master-data.externals.index')
                             ->with('success', 'บันทึกข้อมูลบุคคลภายนอกเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('External Store Error: ' . $e->getMessage());
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
        $external = External::findOrFail($id);
        $prefixes = Prefix::all();
        return view('master-data.externals.edit', compact('external', 'prefixes'));
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
        $validated = $request->validate([
            'prefix_id'   => 'required|integer',
            'firstname'   => 'required|string|max:255',
            'lastname'    => 'required|string|max:255',
            'department'  => 'required|string|max:255',
            'phone'       => 'nullable|string|max:50',
            'email'       => 'nullable|email|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $external = External::findOrFail($id);
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $external->update($validated);

            return redirect()->route('master-data.externals.index')
                             ->with('success', 'อัปเดตข้อมูลบุคคลภายนอกเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('External Update Error: ' . $e->getMessage());
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
        try {
            $external = External::findOrFail($id);
            
            // ---------------------------------------------------------
            // 🕵️‍♂️ สเต็ป 1: เช็คก่อนว่าถูกใช้งานใน "ตารางอื่น" แล้วหรือยัง
            // ---------------------------------------------------------
            
            // เช็คตารางที่ 1: ตารางวิทยากร/ผู้เข้าร่วม (training_members)
            $isUsedInTrainingMembers = DB::table('training_members')
                                        ->where('external_id', $id)
                                        ->exists();
            
            // เช็คตารางที่ 2: ตารางคณะทำงาน (committees)
            $isUsedInCommittees = DB::table('committees')
                                        ->where('external_id', $id)
                                        ->exists();

            // ถ้าเจอว่าถูกใช้อยู่ที่ใดที่หนึ่ง ให้เบรกการทำงานและแจ้งเตือนทันที!
            if ($isUsedInTrainingMembers || $isUsedInCommittees) {
                return redirect()->back()->with('error', 'ไม่สามารถลบได้ เนื่องจากบุคคลภายนอกนี้ "ถูกระบุชื่อในโครงการแล้ว" (เป็นวิทยากรหรือคณะทำงาน) แนะนำให้คลิกแก้ไขแล้วเลือก "ปิดใช้งาน" แทนครับ');
            }

            // ---------------------------------------------------------
            // 🗑️ สเต็ป 2: ถ้าไม่มีใครใช้ ค่อยสั่งลบจริง
            // ---------------------------------------------------------
            $external->delete();
            
            return redirect()->route('master-data.externals.index')
                             ->with('success', 'ลบข้อมูลบุคคลภายนอกเรียบร้อยแล้ว');
                             
        } catch (\Exception $e) {
            Log::error('External Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในระบบฐานข้อมูล ไม่สามารถลบข้อมูลได้');
        }
    }
}

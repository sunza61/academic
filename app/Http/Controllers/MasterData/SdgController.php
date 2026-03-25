<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicSdg;
use App\Models\MasterData\Sdg;
use Illuminate\Http\Request;

class SdgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sdgs = Sdg::orderBy('id', 'asc')->get(); // SDGs มักจะเรียงตาม ID 1-17
        return view('master-data.sdgs.index', compact('sdgs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master-data.sdgs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_th' => 'required|string|max:150',
            'name_en' => 'required|string|max:150',
            'icon_url' => 'nullable|string',
            'description' => 'nullable|string',
        ], [
            'name_th.required' => 'กรุณากรอกชื่อเป้าหมาย (ภาษาไทย)',
            'name_en.required' => 'กรุณากรอกชื่อเป้าหมาย (ภาษาอังกฤษ)',
        ]);

        Sdg::create([
            'name_th' => $request->name_th,
            'name_en' => $request->name_en,
            'icon_url' => $request->icon_url,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('master-data.sdgs.index')
            ->with('success', 'เพิ่มข้อมูล SDG สำเร็จ!');
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
        $sdg = Sdg::findOrFail($id);
        return view('master-data.sdgs.edit', compact('sdg'));
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
            'name_th' => 'required|string|max:150',
            'name_en' => 'required|string|max:150',
            'icon_url' => 'nullable|string',
            'description' => 'nullable|string',
        ], [
            'name_th.required' => 'กรุณากรอกชื่อเป้าหมาย (ภาษาไทย)',
            'name_en.required' => 'กรุณากรอกชื่อเป้าหมาย (ภาษาอังกฤษ)',
        ]);

        $sdg = Sdg::findOrFail($id);
        $sdg->update([
            'name_th' => $request->name_th,
            'name_en' => $request->name_en,
            'icon_url' => $request->icon_url,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('master-data.sdgs.index')
            ->with('success', 'อัปเดตข้อมูล SDG สำเร็จ!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
       // 1. เช็คผ่าน Eloquent Model ว่ามี sdg_id นี้ถูกใช้งานหรือไม่
       $isUsedInProject = AcademicSdg::where('sdg_id', $id)->exists();

       // 2. ถ้ามีการใช้งานอยู่ ให้หยุดการทำงาน แล้วเด้งแจ้งเตือน Error สีแดง
       if ($isUsedInProject) {
           return redirect()->route('master-data.sdgs.index')
               ->with('error', 'ไม่สามารถลบได้! เนื่องจากเป้าหมาย SDG นี้ถูกนำไปใช้งานใน "ข้อมูลโครงการ" แล้ว');
       }

       // 3. ถ้าเช็คแล้วปลอดภัย ก็สั่งลบตามปกติ
       $sdg = Sdg::findOrFail($id);
       $sdg->delete();

       return redirect()->route('master-data.sdgs.index')
           ->with('success', 'ลบข้อมูล SDG สำเร็จ!');
    }
}

<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\BudgetIncomeCategorie;
use App\Models\MasterData\BudgetIncomeMainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BudgetIncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ดึงข้อมูลย่อย พร้อมผูกข้อมูลแม่มาด้วย
        $incomes = BudgetIncomeCategorie::with('mainCategory')
                        ->orderBy('main_category_id')
                        ->orderBy('id')
                        ->get();
        return view('master-data.budget-incomes.index', compact('incomes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // ดึงหมวดหมู่หลักที่เปิดใช้งานมาใส่ Dropdown
        $mains = BudgetIncomeMainCategory::where('is_active', 1)->get();
        return view('master-data.budget-incomes.create', compact('mains'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'main_category_id' => 'required|integer|exists:budget_income_main_categories,id',
            'name_th'          => 'required|string|max:255',
        ], [
            'main_category_id.required' => 'กรุณาเลือกหมวดหมู่หลัก',
            'name_th.required'          => 'กรุณาระบุชื่อหมวดหมู่ย่อย',
        ]);

        try {
            $validated['is_active']      = $request->has('is_active') ? 1 : 0;
            
            BudgetIncomeCategorie::create($validated);

            return redirect()->route('master-data.budget-incomes.index')
                             ->with('success', 'บันทึกหมวดหมู่รายรับเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetIncome Store Error: ' . $e->getMessage());
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
        $income = BudgetIncomeCategorie::findOrFail($id);
        // ดึงหมวดหมู่หลักที่ยัง Active มาโชว์ (รวมถึงตัวมันเองด้วยเผื่อโดนปิดไปแล้ว)
        $mains = BudgetIncomeMainCategory::where('is_active', 1)
                    ->orWhere('id', $income->main_category_id)
                    ->get();
                    
        return view('master-data.budget-incomes.edit', compact('income', 'mains'));
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
            'main_category_id' => 'required|integer|exists:budget_income_main_categories,id',
            'name_th'          => 'required|string|max:255',
        ], [
            'main_category_id.required' => 'กรุณาเลือกหมวดหมู่หลัก',
            'name_th.required'          => 'กรุณาระบุชื่อหมวดหมู่ย่อย',
        ]);

        try {
            $income = BudgetIncomeCategorie::findOrFail($id);
            
            $validated['is_active']      = $request->has('is_active') ? 1 : 0;

            $income->update($validated);

            return redirect()->route('master-data.budget-incomes.index')
                             ->with('success', 'อัปเดตข้อมูลหมวดหมู่รายรับเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetIncome Update Error: ' . $e->getMessage());
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
            $income = BudgetIncomeCategorie::findOrFail($id);
            
            // 🕵️‍♂️ เช็คก่อนว่าถูกใช้งานในตารางบันทึกรายรับของโครงการแล้วหรือยัง
            // *หมายเหตุ: สมมติว่าตารางเก็บรายรับชื่อ academic_budget_incomes แล้วฟิลด์ชื่อ income_category_id (ถ้าชื่ออื่นให้แก้ตรงนี้นะครับ)
            $isUsed = DB::table('academic_budget_incomes')->where('category_id', $id)->exists();

            if ($isUsed) {
                return redirect()->back()->with('error', 'ไม่สามารถลบได้ เนื่องจากหมวดหมู่นี้ถูกนำไปใช้บันทึกรายรับในโครงการแล้ว (แนะนำให้คลิกแก้ไขแล้วเลือก "ปิดใช้งาน" แทน)');
            }

            $income->delete();
            return redirect()->route('master-data.budget-incomes.index')
                             ->with('success', 'ลบข้อมูลหมวดหมู่รายรับเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetIncome Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้ เนื่องจากมีข้อมูลผูกพันในระบบ');
        }
    }

    // =========================================================
    // 🌟 ฟังก์ชันพิเศษ (AJAX): สำหรับสร้างหมวดหมู่หลักผ่าน Modal
    // =========================================================
    public function storeMainAjax(Request $request)
    {
        //dd($request->all());
        $request->validate(['name_th' => 'required|string|max:255']);
        try {
            $main = BudgetIncomeMainCategory::create([
                'name_th' => trim($request->name_th),
                'is_active' => 1 // บังคับเปิดใช้งานเลย
            ]);
            
            return response()->json([
                'status' => 'success',
                'data' => $main
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}

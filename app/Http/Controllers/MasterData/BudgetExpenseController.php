<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\BudgetExpenseCategorie;
use App\Models\MasterData\BudgetExpenseMainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BudgetExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = BudgetExpenseCategorie::with('mainCategory')
            ->orderBy('main_category_id')
            ->orderBy('id')
            ->get();
        return view('master-data.budget-expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mains = BudgetExpenseMainCategory::where('is_active', 1)->get();
        return view('master-data.budget-expenses.create', compact('mains'));
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
            'main_category_id' => 'required|integer|exists:budget_expense_main_categories,id',
            'name_th'          => 'required|string|max:255',
        ], [
            'main_category_id.required' => 'กรุณาเลือกหมวดหมู่หลัก',
            'name_th.required'          => 'กรุณาระบุชื่อหมวดหมู่ย่อย',
        ]);

        try {
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            BudgetExpenseCategorie::create($validated);

            return redirect()->route('master-data.budget-expenses.index')
                ->with('success', 'บันทึกหมวดหมู่รายจ่ายเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetExpense Store Error: ' . $e->getMessage());
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
        $expense = BudgetExpenseCategorie::findOrFail($id);
        $mains = BudgetExpenseMainCategory::where('is_active', 1)
                    ->orWhere('id', $expense->main_category_id)
                    ->get();
                    
        return view('master-data.budget-expenses.edit', compact('expense', 'mains'));
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
            'main_category_id' => 'required|integer|exists:budget_expense_main_categories,id',
            'name_th'          => 'required|string|max:255',
        ], [
            'main_category_id.required' => 'กรุณาเลือกหมวดหมู่หลัก',
            'name_th.required'          => 'กรุณาระบุชื่อหมวดหมู่ย่อย',
        ]);

        try {
            $expense = BudgetExpenseCategorie::findOrFail($id);
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $expense->update($validated);

            return redirect()->route('master-data.budget-expenses.index')
                             ->with('success', 'อัปเดตข้อมูลหมวดหมู่รายจ่ายเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetExpense Update Error: ' . $e->getMessage());
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
            $expense = BudgetExpenseCategorie::findOrFail($id);
          
            // 🕵️‍♂️ เช็คการใช้งานในโครงการ
            $isUsed = DB::table('academic_budget_expenses')->where('category_id', $id)->exists();
  //dd($isUsed,$id);
            if ($isUsed) {
                return redirect()->back()->with('error', 'ไม่สามารถลบได้ เนื่องจากหมวดหมู่นี้ถูกนำไปใช้บันทึกรายจ่ายในโครงการแล้ว (แนะนำให้ปิดใช้งานแทน)');
            }

            $expense->delete();
            return redirect()->route('master-data.budget-expenses.index')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetExpense Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้ เนื่องจากมีข้อมูลผูกพันในระบบ');
        }
    }

    // 🌟 AJAX เพิ่มหมวดหมู่หลักรายจ่าย
    public function storeMainAjax(Request $request)
    {
        $request->validate(['name_th' => 'required|string|max:255']);
        try {
            $main = BudgetExpenseMainCategory::create([
                'name_th' => trim($request->name_th),
                'is_active' => 1 
            ]);
            
            return response()->json(['status' => 'success', 'data' => $main]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}

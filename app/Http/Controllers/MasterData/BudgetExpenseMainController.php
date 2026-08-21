<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\BudgetExpenseMainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BudgetExpenseMainController extends Controller
{
    public function index()
    {
        $mainCategories = BudgetExpenseMainCategory::orderBy('id', 'asc')->get();
        return view('master-data.budget-expenses.main.index', compact('mainCategories'));
    }

    public function create()
    {
        return view('master-data.budget-expenses.main.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_th' => 'required|string|max:255|unique:budget_expense_main_categories,name_th',
        ], [
            'name_th.required' => 'กรุณาระบุชื่อหมวดหมู่หลักรายจ่าย',
            'name_th.unique'   => 'ชื่อหมวดหมู่นี้มีในระบบแล้ว',
        ]);

        try {
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            BudgetExpenseMainCategory::create($validated);

            return redirect()->route('master-data.budget-expenses.main.index')
                             ->with('success', 'บันทึกหมวดหมู่หลักรายจ่ายเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetExpenseMain Store Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

    public function edit($id)
    {
        $mainCategory = BudgetExpenseMainCategory::findOrFail($id);
        return view('master-data.budget-expenses.main.edit', compact('mainCategory'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name_th' => 'required|string|max:255|unique:budget_expense_main_categories,name_th,' . $id,
        ], [
            'name_th.required' => 'กรุณาระบุชื่อหมวดหมู่หลักรายจ่าย',
            'name_th.unique'   => 'ชื่อหมวดหมู่นี้มีในระบบแล้ว',
        ]);

        try {
            $mainCategory = BudgetExpenseMainCategory::findOrFail($id);
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $mainCategory->update($validated);

            return redirect()->route('master-data.budget-expenses.main.index')
                             ->with('success', 'อัปเดตหมวดหมู่หลักรายจ่ายเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetExpenseMain Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
        }
    }

    public function destroy($id)
    {
        try {
            $mainCategory = BudgetExpenseMainCategory::findOrFail($id);
            
            // เช็คว่ามีหมวดหมู่ย่อยใช้งานอยู่หรือไม่
            if ($mainCategory->subCategories()->exists()) {
                return redirect()->back()->with('error', 'ไม่สามารถลบได้ เนื่องจากมีหมวดหมู่ย่อยใช้งานอยู่ภายใต้หมวดหมู่นี้');
            }

            $mainCategory->delete();
            return redirect()->route('master-data.budget-expenses.main.index')
                             ->with('success', 'ลบข้อมูลหมวดหมู่หลักรายจ่ายเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetExpenseMain Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในระบบฐานข้อมูล');
        }
    }
}

<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\BudgetIncomeMainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BudgetIncomeMainController extends Controller
{
    public function index()
    {
        $mainCategories = BudgetIncomeMainCategory::orderBy('id', 'asc')->get();
        return view('master-data.budget-incomes.main.index', compact('mainCategories'));
    }

    public function create()
    {
        return view('master-data.budget-incomes.main.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_th' => 'required|string|max:255|unique:budget_income_main_categories,name_th',
        ], [
            'name_th.required' => 'กรุณาระบุชื่อหมวดหมู่หลัก',
            'name_th.unique'   => 'ชื่อหมวดหมู่นี้มีในระบบแล้ว',
        ]);

        try {
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            BudgetIncomeMainCategory::create($validated);

            return redirect()->route('master-data.budget-incomes.main.index')
                             ->with('success', 'บันทึกหมวดหมู่หลักรายรับเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetIncomeMain Store Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

    public function edit($id)
    {
        $mainCategory = BudgetIncomeMainCategory::findOrFail($id);
        return view('master-data.budget-incomes.main.edit', compact('mainCategory'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name_th' => 'required|string|max:255|unique:budget_income_main_categories,name_th,' . $id,
        ], [
            'name_th.required' => 'กรุณาระบุชื่อหมวดหมู่หลัก',
            'name_th.unique'   => 'ชื่อหมวดหมู่นี้มีในระบบแล้ว',
        ]);

        try {
            $mainCategory = BudgetIncomeMainCategory::findOrFail($id);
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $mainCategory->update($validated);

            return redirect()->route('master-data.budget-incomes.main.index')
                             ->with('success', 'อัปเดตหมวดหมู่หลักรายรับเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetIncomeMain Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
        }
    }

    public function destroy($id)
    {
        try {
            $mainCategory = BudgetIncomeMainCategory::findOrFail($id);
            
            // เช็คว่ามีหมวดหมู่ย่อยใช้งานอยู่หรือไม่
            if ($mainCategory->subCategories()->exists()) {
                return redirect()->back()->with('error', 'ไม่สามารถลบได้ เนื่องจากมีหมวดหมู่ย่อยใช้งานอยู่ภายใต้หมวดหมู่นี้');
            }

            $mainCategory->delete();
            return redirect()->route('master-data.budget-incomes.main.index')
                             ->with('success', 'ลบข้อมูลหมวดหมู่หลักเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('BudgetIncomeMain Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในระบบฐานข้อมูล');
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_budgets', function (Blueprint $table) {
            $table->id();
            // เชื่อมกับตารางโครงการหลัก (ถ้าลบโครงการ ข้อมูลนี้จะหายไปด้วย cascade)
            $table->foreignId('academic_project_id')->constrained('academic_projects')->onDelete('cascade');
            
            // --- กล่องซ้าย: งบประมาณภาพรวม ---
            $table->decimal('total_budget_summary', 12, 2)->default(0.00)->comment('งบประมาณทั้งโครงการ');
            $table->decimal('total_advance_amount', 12, 2)->default(0.00)->comment('เงินค่าล่วงหน้าทั้งหมด');
            $table->decimal('total_fine_amount', 12, 2)->default(0.00)->comment('ค่าปรับรวมทั้งหมด');
            
            // --- กล่องขวาบน: คำนวณตามเกณฑ์ค่าธรรมเนียม ---
            $table->decimal('remuneration_fee', 12, 2)->default(0.00)->comment('ค่าตอบแทน (สรุป)');
            $table->decimal('operation_fee', 12, 2)->default(0.00)->comment('ค่าดำเนินการ (สรุป)');
            $table->decimal('service_fee_percent', 5, 2)->default(0.00)->comment('เปอร์เซ็นต์ค่าธรรมเนียมบริการวิชาการ');
            $table->decimal('service_fee_amount', 12, 2)->default(0.00)->comment('ยอดเงินค่าธรรมเนียมบริการวิชาการ');
            
            // --- กล่องขวาล่าง: จัดสรรค่าธรรมเนียมบริการวิชาการ ---
            $table->decimal('alloc_uni_percent', 5, 2)->default(0.00)->comment('เปอร์เซ็นต์ มหาวิทยาลัย');
            $table->decimal('alloc_uni_amount', 12, 2)->default(0.00)->comment('ยอดเงิน มหาวิทยาลัย');
            
            $table->decimal('alloc_campus_percent', 5, 2)->default(0.00)->comment('เปอร์เซ็นต์ วิทยาเขต');
            $table->decimal('alloc_campus_amount', 12, 2)->default(0.00)->comment('ยอดเงิน วิทยาเขต');
            
            $table->decimal('alloc_dept_percent', 5, 2)->default(0.00)->comment('เปอร์เซ็นต์ คณะ/หน่วยงาน');
            $table->decimal('alloc_dept_amount', 12, 2)->default(0.00)->comment('ยอดเงิน คณะ/หน่วยงาน');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_budgets');
    }
}

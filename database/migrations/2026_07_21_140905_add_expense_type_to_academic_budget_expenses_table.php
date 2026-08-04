<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpenseTypeToAcademicBudgetExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_budget_expenses', function (Blueprint $table) {
            //
            $table->string('expense_type', 50)
                  ->after('category_id')
                  ->default('operation')
                  ->comment('ประเภทรายจ่าย: operation (ค่าดำเนินการ), remuneration (ค่าตอบแทน)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_budget_expenses', function (Blueprint $table) {
            //
            $table->dropColumn('expense_type');
        });
    }
}

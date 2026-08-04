<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubDeptAllocationsToAcademicBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_budgets', function (Blueprint $table) {
            //
            $table->decimal('fund_research_percent', 8, 3)->nullable()->after('alloc_dept_amount');
            $table->decimal('fund_research_amount', 12, 2)->nullable()->after('fund_research_percent');
            
            $table->decimal('faculty_percent', 8, 3)->nullable()->after('fund_research_amount');
            $table->decimal('faculty_amount', 12, 2)->nullable()->after('faculty_percent');
            
            $table->decimal('center_percent', 8, 3)->nullable()->after('faculty_amount');
            $table->decimal('center_amount', 12, 2)->nullable()->after('center_percent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_budgets', function (Blueprint $table) {
            //
            $table->dropColumn([
                'fund_research_percent',
                'fund_research_amount',
                'faculty_percent',
                'faculty_amount',
                'center_percent',
                'center_amount'
            ]);
        });
    }
}

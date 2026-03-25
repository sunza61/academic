<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainCategoryIdToBudgetExpenseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_expense_categories', function (Blueprint $table) {
            //
            // เพิ่ม FK ชี้ไปที่ตารางหมวดหมู่หลักรายจ่าย
            $table->unsignedBigInteger('main_category_id')->nullable()->after('id')->comment('FK อ้างอิงตาราง budget_expense_main_categories');

            $table->foreign('main_category_id')
                  ->references('id')
                  ->on('budget_expense_main_categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_expense_categories', function (Blueprint $table) {
            //
            $table->dropForeign(['main_category_id']);
            $table->dropColumn('main_category_id');
        });
    }
}

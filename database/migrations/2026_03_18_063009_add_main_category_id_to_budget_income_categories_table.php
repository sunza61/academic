<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainCategoryIdToBudgetIncomeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::table('budget_income_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('main_category_id')->nullable()->after('id')->comment('FK อ้างอิงตารางหมวดหมู่หลัก');

            $table->foreign('main_category_id')
                  ->references('id')
                  ->on('budget_income_main_categories')
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
        Schema::table('budget_income_categories', function (Blueprint $table) {
            //
            $table->dropForeign(['main_category_id']);
            $table->dropColumn('main_category_id');
        });
    }
}

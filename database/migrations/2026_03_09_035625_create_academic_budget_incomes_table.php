<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicBudgetIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_budget_incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_project_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->text('description')->nullable();
            $table->double('unit_cost')->nullable();
            $table->double('quantity')->nullable();
            $table->double('total_amount')->nullable();
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
        Schema::dropIfExists('academic_budget_incomes');
    }
}

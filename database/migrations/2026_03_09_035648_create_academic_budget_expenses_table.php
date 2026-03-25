<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicBudgetExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_budget_expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_project_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->text('description')->nullable();
            $table->double('cost_per_unit')->nullable();
            $table->double('factor_1')->nullable();
            $table->double('factor_2')->nullable();
            $table->string('uom')->nullable();
            $table->double('total_amount')->nullable();
            $table->integer('can_average')->nullable();
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
        Schema::dropIfExists('academic_budget_expenses');
    }
}

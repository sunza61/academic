<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_contracts', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_project_id')->nullable();
            $table->integer('campus_id')->nullable();
            $table->dateTime('planned_report_date')->nullable();
            $table->string('memo_file_path')->nullable();
            $table->string('contract_number')->nullable();
            $table->dateTime('signing_date')->nullable();
            $table->text('keywords')->nullable();
            $table->text('special_conditions')->nullable();
            $table->string('contract_file_path')->nullable();
            $table->string('contract_file_link')->nullable();
            $table->string('status')->nullable();
            $table->double('total_budget')->nullable();
            $table->integer('duration_years')->nullable();
            $table->integer('duration_months')->nullable();
            $table->integer('duration_days')->nullable();
            $table->integer('employer_id')->nullable();
            $table->integer('signatory_personnel_id')->nullable();
            $table->integer('project_leader_id')->nullable();
            $table->integer('is_authorized_rep')->nullable();
            $table->string('project_status')->nullable();
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
        Schema::dropIfExists('project_contracts');
    }
}

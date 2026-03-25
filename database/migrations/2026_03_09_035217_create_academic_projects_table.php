<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_projects', function (Blueprint $table) {
            $table->id();
            $table->integer('project_type_id')->nullable();
            $table->integer('service_group_id')->nullable();
            $table->integer('center_id')->nullable();
            $table->text('name_th')->nullable();
            $table->text('brief_description')->nullable();
            $table->text('rationale')->nullable();
            $table->integer('fiscal_year_id')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('overall_status')->nullable();
            $table->integer('aod_status')->nullable();
            $table->string('aod_code')->nullable();
            $table->integer('region_type')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('update_by')->nullable();
            $table->integer('del_status')->nullable();
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
        Schema::dropIfExists('academic_projects');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_projects', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_project_id')->nullable();
            $table->text('document_number')->nullable();
            $table->integer('project_types')->nullable();
            $table->integer('course_type')->nullable();
            $table->dateTime('start_regis_date')->nullable();
            $table->dateTime('end_regis_date')->nullable();
            $table->integer('has_collaboration')->nullable();
            $table->text('approval_file')->nullable();
            $table->text('approval_link')->nullable();
            $table->integer('training_status')->nullable();
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
        Schema::dropIfExists('training_projects');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicProjectEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_project_evaluations', function (Blueprint $table) {
            $table->id();
            // เชื่อมกับโปรเจกต์หลัก
            $table->foreignId('academic_project_id')->constrained('academic_projects')->onDelete('cascade');
            
            // ส่วนที่ 5.1: ประเมินความพึงพอใจ
            $table->decimal('satisfaction_percent', 5, 2)->nullable();
            $table->string('satisfaction_range', 100)->nullable();
            $table->string('satisfaction_level', 10)->nullable();
            
            $table->decimal('dissatisfaction_percent', 5, 2)->nullable();
            $table->string('dissatisfaction_range', 100)->nullable();
            $table->string('dissatisfaction_level', 10)->nullable();

            // ส่วนที่ 5.2: อื่นๆ
            $table->text('improvement_apply')->nullable();
            $table->text('impact')->nullable();
            $table->text('integration')->nullable();
            $table->text('integration_eval')->nullable();

            // ส่วนที่ 5.3: ผลสัมฤทธิ์และมูลค่าโครงการ
            $table->decimal('evaluation_score', 4, 2)->nullable();
            $table->decimal('sroi_score', 10, 2)->nullable();
            $table->integer('award_count')->default(0)->nullable();
            $table->decimal('industrial_value', 15, 2)->nullable();
            $table->text('project_achievement')->nullable();
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
        Schema::dropIfExists('academic_project_evaluations');
    }
}

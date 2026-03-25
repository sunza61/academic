<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAcademicProjectEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_project_evaluations', function (Blueprint $table) {
            // 1. เพิ่มคอลัมน์คะแนนดิบ (เต็ม 5)
            $table->decimal('satisfaction_score', 4, 2)->nullable()->comment('คะแนนดิบเต็ม 5')->after('academic_project_id');
            $table->decimal('dissatisfaction_score', 4, 2)->nullable()->comment('คะแนนดิบเต็ม 5')->after('satisfaction_level');
            
            // 2. เอาคอลัมน์ evaluation_score ตัวเก่าออก (เพราะเราใช้คะแนนแยก 2 ฝั่งแล้ว)
            if (Schema::hasColumn('academic_project_evaluations', 'evaluation_score')) {
                $table->dropColumn('evaluation_score');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_project_evaluations', function (Blueprint $table) {
            //
            // กรณี Rollback ให้ลบคอลัมน์ใหม่ทิ้ง
            $table->dropColumn(['satisfaction_score', 'dissatisfaction_score']);
            
            // และเอาคอลัมน์เก่ากลับมา
            $table->decimal('evaluation_score', 4, 2)->nullable()->comment('คะแนนประเมินโครงการ (เต็ม 5)');
        });
    }
}

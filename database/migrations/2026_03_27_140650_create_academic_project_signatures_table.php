<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicProjectSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_project_signatures', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('academic_project_id');
            $table->string('staff_id', 50)->comment('รหัสพนักงาน ดึงจาก V_STAFF_NEWWEB');
            $table->unsignedBigInteger('signature_role_id');
            $table->string('executive_position', 255)->nullable()->comment('ตำแหน่งผู้บริหาร/สายงาน');
            $table->integer('sign_order')->default(1)->comment('ลำดับการจัดวางลายเซ็น 1, 2, 3, 4');
            $table->timestamps();

            // 📌 สร้าง Foreign Keys
            $table->foreign('academic_project_id', 'fk_sign_project')
                  ->references('id')->on('academic_projects')->onDelete('cascade');
                  
            $table->foreign('signature_role_id', 'fk_sign_role')
                  ->references('id')->on('master_signature_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_project_signatures');
    }
}

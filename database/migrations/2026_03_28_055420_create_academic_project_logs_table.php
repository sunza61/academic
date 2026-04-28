<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicProjectLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_project_logs', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('academic_project_id');
            $table->foreign('academic_project_id')->references('id')->on('academic_projects')->onDelete('cascade');
            
            // โยงไปหาคนกด (ถ้าไม่ได้ล็อกอินให้เป็น null ได้)
            $table->unsignedBigInteger('user_id')->nullable();
            
            // เก็บ Action ว่าทำอะไร เช่น drafted, submitted, returned, approved, cancelled
            $table->string('action', 100)->comment('การกระทำ');
            
            // รหัสสถานะ (เช่น 100, 150, 200) เพื่อให้รู้ว่า Log นี้เกิดขึ้นตอนสถานะไหน
            $table->integer('status_code')->nullable();
            
            // เหตุผลที่ตีกลับ หรือคอมเมนต์จากผู้บริหาร
            $table->text('comment')->nullable()->comment('เหตุผล/หมายเหตุ');
            
            $table->timestamps();

            // 📌 Foreign Keys (ลบโปรเจกต์ Log ต้องปลิวตาม)
            $table->foreign('academic_project_id', 'fk_log_project')
            ->references('id')->on('academic_projects')->onDelete('no action');
                  
            // (ถ้าคุณวัชกรใช้ตาราง users เป็นตัวล็อกอิน สามารถเปิดคอมเมนต์บรรทัดล่างนี้เพื่อผูก FK ได้ครับ)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_project_logs');
    }
}

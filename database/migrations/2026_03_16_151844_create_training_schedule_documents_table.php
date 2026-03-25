<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingScheduleDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_schedule_documents', function (Blueprint $table) {
            $table->id();
            // 🌟 พระเอกของเรา: ผูกกับตารางกำหนดการ และถ้ากำหนดการถูกลบ ให้ลบไฟล์ใน DB ตามไปด้วย (Cascade)
            $table->foreignId('training_schedule_id')
                  ->constrained('training_schedules')
                  ->onDelete('cascade'); 

            $table->string('document_name', 255); // ชื่อเอกสาร
            $table->string('file_path', 255);     // พาร์ทเก็บไฟล์
            $table->string('file_type', 50)->nullable(); // ชนิดไฟล์
            $table->integer('file_size')->nullable();    // ขนาดไฟล์
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
        Schema::dropIfExists('training_schedule_documents');
    }
}

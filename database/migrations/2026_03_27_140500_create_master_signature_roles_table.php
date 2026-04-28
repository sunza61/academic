<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterSignatureRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_signature_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name_th', 255)->comment('ชื่อบทบาทการเซ็น เช่น ผู้รับผิดชอบโครงการ, ผู้อนุมัติ');
            $table->boolean('is_active')->default(1)->comment('1=เปิดใช้งาน, 0=ปิดใช้งาน');
            $table->timestamps();
        });
        
        // 📌 Insert ข้อมูลตั้งต้นให้เลย จะได้ไม่ต้องไปคีย์เองตอนเทสต์ครับ
        \Illuminate\Support\Facades\DB::table('master_signature_roles')->insert([
            ['name_th' => 'ผู้รับผิดชอบโครงการ', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name_th' => 'หัวหน้าโครงการ', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name_th' => 'ผู้เสนอโครงการ', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name_th' => 'ผู้เห็นชอบโครงการ', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name_th' => 'ผู้อนุมัติโครงการ', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_signature_roles');
    }
}

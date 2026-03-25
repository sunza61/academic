<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHierarchyColumnsToTargetGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('target_groups', function (Blueprint $table) {
           // เติมฟิลด์ใหม่ต่อท้ายฟิลด์ description
            $table->unsignedBigInteger('parent_id')->nullable()->after('description')->comment('ID ของตัวแม่ (NULL = Level 1)');
            $table->integer('level')->default(1)->after('parent_id')->comment('ระดับความลึก 1, 2, 3...');
            $table->string('group_type', 100)->nullable()->after('level')->comment('ประเภทกลุ่ม เช่น school, class_m1');

            // สร้าง Foreign Key
            $table->foreign('parent_id')
            ->references('id')
            ->on('target_groups')
            ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('target_groups', function (Blueprint $table) {
            // เวลา Rollback ก็ถอด FK และลบคอลัมน์ทิ้ง
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'level', 'group_type']);
        });
    }
}

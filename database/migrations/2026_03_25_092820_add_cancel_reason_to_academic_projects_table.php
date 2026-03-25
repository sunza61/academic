<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelReasonToAcademicProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_projects', function (Blueprint $table) {
            //
            $table->text('cancel_reason')->nullable()->after('overall_status')->comment('เหตุผลที่ยกเลิกโครงการ (Status 900)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_projects', function (Blueprint $table) {
            //
            $table->dropColumn('cancel_reason');
        });
    }
}

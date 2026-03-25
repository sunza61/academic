<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerGroupIdToAcademicTargetGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_target_groups', function (Blueprint $table) {
            //
            $table->integer('customer_group_id')->nullable()->after('nationality_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_target_groups', function (Blueprint $table) {
            //
            $table->dropColumn('customer_group_id');
        });
    }
}

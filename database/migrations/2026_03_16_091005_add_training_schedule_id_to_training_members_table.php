<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrainingScheduleIdToTrainingMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_members', function (Blueprint $table) {
            //
            $table->integer('training_schedule_id')->nullable()->after('personnel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_members', function (Blueprint $table) {
            //
            $table->dropColumn('training_schedule_id');
        });
    }
}

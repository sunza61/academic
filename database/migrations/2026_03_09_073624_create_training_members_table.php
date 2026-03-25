<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_members', function (Blueprint $table) {
            $table->id();
            $table->integer('training_project_id')->nullable();
            $table->integer('member_type')->nullable();
            $table->integer('personnel_id')->nullable();
            $table->integer('external_prefix')->nullable();
            $table->string('external_firstname')->nullable();
            $table->string('external_lastname')->nullable();
            $table->text('external_department')->nullable();
            $table->integer('training_position_id')->nullable();
            $table->text('email')->nullable();
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
        Schema::dropIfExists('training_members');
    }
}

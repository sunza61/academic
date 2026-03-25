<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommitteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_project_id')->nullable();
            $table->integer('member_type')->nullable();
            $table->integer('personnel_id')->nullable();
            $table->integer('external_prefix')->nullable();
            $table->string('external_firstname')->nullable();
            $table->string('external_lastname')->nullable();
            $table->text('external_department')->nullable();
            $table->integer('project_position_id')->nullable();
            $table->string('email')->nullable();
            $table->double('remuneration_total')->nullable();
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
        Schema::dropIfExists('committees');
    }
}

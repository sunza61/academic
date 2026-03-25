<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_locations', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_project_id')->nullable();
            $table->text('location_name')->nullable();
            $table->text('address_detail')->nullable();
            $table->integer('province_id')->nullable();
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
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
        Schema::dropIfExists('academic_locations');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAbbreviationToProjectTypesAndCodeToAcademicProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_types', function (Blueprint $table) {
            $table->string('abbreviation', 10)->nullable()->after('name_en');
        });

        Schema::table('academic_projects', function (Blueprint $table) {
            $table->string('project_code', 20)->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_types', function (Blueprint $table) {
            $table->dropColumn('abbreviation');
        });

        Schema::table('academic_projects', function (Blueprint $table) {
            $table->dropColumn('project_code');
        });
    }
}

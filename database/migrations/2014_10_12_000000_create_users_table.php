<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // สำหรับโปรเจคที่กลุ่มผู้ใช้เป็นคนใน PSU
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('objectguid')->nullable();  // Uncomment this line if use LDAP Authen
            $table->string('username')->unique();   // Uncomment this line if use LDAP Authen
            $table->string('password');
            $table->string('distinguishedname')->nullable();
            $table->string('personid')->nullable();
            $table->string('citizenid')->nullable();
            $table->string('company')->nullable();
            $table->string('department')->nullable();
            $table->string('physicaldeliveryofficename')->nullable();
            $table->string('description')->nullable();
            $table->string('displayname')->nullable();
            $table->string('title')->nullable();
            $table->string('personaltitle')->nullable();
            $table->string('givenname')->nullable();
            $table->string('givensname')->nullable();
            $table->string('userprincipalname')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // สำหรับโปรเจคที่กลุ่มผู้ใช้เป็นคนใน PSU และคนนอก (ตัวอย่างนี้คือ น.ศ. ที่ทำการดึงข้อมูลมากจากมหาลัย กับ บุคลากร )
            // $table->id();
            // $table->string('student_id');
            // $table->string('student_name');
            // $table->string('title_id')->nullable();
            // $table->string('degree_id')->nullable();
            // $table->string('major_id')->nullable();
            // $table->string('division_id')->nullable();
            // $table->string('acadmic_title_id')->nullable();
            // $table->string('country_id')->nullable();
            // $table->string('advisor')->nullable();
            // $table->string('email')->nullable();
            // $table->timestamp('email_verified_at')->nullable();
            // $table->string('username')->unique();   // student_id/psu passport
            // $table->string('password');
            // $table->string('type')->default('s');
            // $table->rememberToken();
            // $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

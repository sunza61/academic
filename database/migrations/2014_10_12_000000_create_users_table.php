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

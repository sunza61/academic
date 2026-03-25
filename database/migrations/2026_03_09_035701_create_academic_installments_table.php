<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_installments', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_project_id')->nullable();
            $table->integer('installment_no')->nullable();
            $table->integer('duration_days')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->double('amount')->nullable();
            $table->double('adv_deduct_pct')->nullable();
            $table->double('adv_deduct_amt')->nullable();
            $table->double('guarantee_pct')->nullable();
            $table->double('guarantee_amt')->nullable();
            $table->double('fine_amount')->nullable();
            $table->double('net_amount')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('academic_installments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('loan_name');
            $table->string('type');
            $table->decimal('amount');
            $table->boolean('status');
            $table->string('basic_salary');
            $table->string('net_salary');
            $table->string('other_income');
            $table->string('approved_installments');
            $table->string('loan_purpose');
            $table->string('repayment_period')->nullable();
            $table->dateTime('applied_date');
            $table->dateTime('approved_date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
};

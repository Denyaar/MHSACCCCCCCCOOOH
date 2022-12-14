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
            $table->string('loan_name');
            $table->string('type');
            $table->decimal('amount');
            $table->boolean('status');
            $table->string('monthly_installments');
            $table->string('loan_purpose');
            $table->string('repayment_period')->nullable();
            $table->dateTime('applied_date');
            $table->dateTime('approved_date');
            $table->timestamps();
            $table->softDeletes();

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

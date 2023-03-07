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
        Schema::create('employment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('employer');
            $table->string('employer_phone');
            $table->string('position_at_work');
            $table->string('grade');
            $table->boolean('approved_status');
            $table->string('department');
            $table->string('employer_address');
            $table->date('date_of_employment');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employment_details');
    }
};

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
        Schema::create('next_of_kin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('next_of_kin_name');
            $table->string('next_of_kin_surname');
            $table->string('next_of_kin_mobile_num')->unique();
            $table->string('next_of_kin_gender');
            $table->string('next_of_kin_nat_id')->unique();
            $table->date('next_of_kin_date_of_birth');
            $table->string('relationship');
            $table->string('next_of_kin_address');
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
        Schema::dropIfExists('next_of_kin');
    }
};

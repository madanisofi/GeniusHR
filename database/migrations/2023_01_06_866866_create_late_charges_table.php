<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('late_charges', function (Blueprint $table) {
            $table->id();
            $table->integer('attendance_id')->unique();
            $table->integer('salary_cuts');
            $table->time('working_hours')->nullable();
            $table->time('working_late')->nullable();
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
        Schema::dropIfExists('late_charges');
    }
}

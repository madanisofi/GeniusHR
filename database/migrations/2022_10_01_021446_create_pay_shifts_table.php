<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_shifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id')->index('employee_id');
            $table->integer('shift_id')->index('shift_id');
            $table->integer('amount');
            $table->integer('created_by')->index('created_by');
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
        Schema::dropIfExists('pay_shifts');
    }
}

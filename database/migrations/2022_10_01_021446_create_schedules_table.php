<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shift_id')->nullable()->index('shift_id');
            $table->integer('room_id')->nullable()->index('room_id');
            $table->string('employee_id')->index('employee_id');
            $table->text('day')->nullable();
            $table->date('date')->nullable();
            $table->string('month');
            $table->integer('day_on_month')->nullable();
            $table->string('repeat')->nullable();
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
        Schema::dropIfExists('schedules');
    }
}

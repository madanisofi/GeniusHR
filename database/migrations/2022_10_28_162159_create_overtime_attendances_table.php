<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimeAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_attendances', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('status');
            $table->date('date');
            $table->date('approved_date')->nullable();
            $table->date('overtime_date');
            $table->text('aggrement');
            $table->text('notes');
            $table->bigInteger('compensation_id');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('overtime_attendances');
    }
}

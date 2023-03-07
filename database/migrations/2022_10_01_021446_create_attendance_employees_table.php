<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id')->index('employee_id');
            $table->date('date');
            $table->date('end_date')->nullable();
            $table->string('status');
            $table->integer('parent_id')->nullable()->index('parent_id');
            $table->text('approve')->nullable();
            $table->string('notes')->nullable();
            $table->time('clock_in');
            $table->time('clock_out');
            $table->time('late');
            $table->time('early_leaving');
            $table->time('overtime');
            $table->time('total_rest');
            $table->time('working_hours')->nullable();
            $table->time('working_late')->nullable();
            $table->double('salary_cuts')->default(0);
            $table->integer('shift_id')->nullable()->index('shift_id');
            $table->string('images')->nullable();
            $table->string('images_out')->nullable();
            $table->string('images_reason')->nullable();
            $table->string('reason')->nullable();
            $table->integer('permissiontype_id')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
        Schema::dropIfExists('attendance_employees');
    }
}

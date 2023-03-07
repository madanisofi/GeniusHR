<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id')->index('employee_id');
            $table->integer('leave_type_id')->index('leave_type_id');
            $table->date('applied_on');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('total_leave_days');
            $table->string('leave_reason');
            $table->string('remark')->nullable();
            $table->string('status');
            $table->integer('addressed_to')->default(0);
            $table->text('acc')->nullable();
            $table->string('parent')->nullable();
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
        Schema::dropIfExists('leaves');
    }
}

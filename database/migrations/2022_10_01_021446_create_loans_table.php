<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id')->index('employee_id');
            $table->integer('loan_option');
            $table->string('title');
            $table->integer('amount');
            $table->string('type')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason');
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
        Schema::dropIfExists('loans');
    }
}

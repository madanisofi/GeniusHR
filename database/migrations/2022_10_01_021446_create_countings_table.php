<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('group_id')->index('group_id');
            $table->string('type');
            $table->integer('start_year')->nullable();
            $table->integer('max_year');
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
        Schema::dropIfExists('countings');
    }
}

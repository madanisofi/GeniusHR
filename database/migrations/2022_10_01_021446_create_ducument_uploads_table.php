<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDucumentUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ducument_uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('role');
            $table->string('document');
            $table->text('description')->nullable();
            $table->integer('created_by')->default(0)->index('created_by');
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
        Schema::dropIfExists('ducument_uploads');
    }
}

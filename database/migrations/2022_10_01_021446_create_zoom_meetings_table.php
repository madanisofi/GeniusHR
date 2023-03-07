<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('meeting_id')->default('0')->index('meeting_id');
            $table->string('user_id')->default('0')->index('user_id');
            $table->string('password')->nullable();
            $table->timestamp('start_date')->useCurrent();
            $table->integer('duration')->default(0);
            $table->text('start_url')->nullable();
            $table->string('join_url')->nullable();
            $table->string('status')->nullable()->default('waiting');
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
        Schema::dropIfExists('zoom_meetings');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePictToOvertimeAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtime_attendances', function (Blueprint $table) {
            $table->time('duration')->default('00:00:00')->after('end_time');
            $table->text('picture_in')->nullable()->after('notes');
            $table->text('picture_out')->nullable()->after('picture_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overtime_attendances', function (Blueprint $table) {
            //
        });
    }
}

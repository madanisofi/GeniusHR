<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInfoToAdditionalInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_informations', function (Blueprint $table) {
            $table->integer('can_insert')->after('type')->default(1);
            $table->integer('send_notification')->after('can_insert')->default(0);
            $table->integer('reminder')->after('send_notification')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('additional_informations', function (Blueprint $table) {
            //
        });
    }
}

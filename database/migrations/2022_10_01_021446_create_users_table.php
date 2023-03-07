<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('type');
            $table->integer('role_id')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('token_company')->nullable();
            $table->string('avatar')->nullable();
            $table->string('lang');
            $table->integer('plan')->nullable();
            $table->date('plan_expire_date')->nullable();
            $table->integer('requested_plan')->default(0);
            $table->timestamp('last_login')->nullable();
            $table->integer('is_active')->default(1);
            $table->string('created_by')->index('created_by');
            $table->rememberToken();
            $table->timestamps();
            $table->string('messenger_color')->default('#2180f3');
            $table->boolean('dark_mode')->default(false);
            $table->boolean('active_status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

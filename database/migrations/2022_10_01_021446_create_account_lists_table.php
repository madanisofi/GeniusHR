<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_name');
            $table->double('initial_balance')->default(0);
            $table->string('account_number');
            $table->string('branch_code');
            $table->string('bank_branch');
            $table->string('auto_payroll')->nullable();
            $table->string('created_by')->index('created_by');
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
        Schema::dropIfExists('account_lists');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index('user_id');
            $table->string('name');
            $table->date('dob')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('gender');
            $table->string('phone')->nullable();
            $table->string('address');
            $table->string('email');
            $table->string('password');
            $table->string('employee_id')->index('employee_id');
            $table->string('employee_no')->nullable()->index('employee_no');
            $table->integer('branch_id')->nullable()->index('branch_id');
            $table->integer('department_id')->nullable()->index('department_id');
            $table->integer('designation_id')->nullable()->index('designation_id');
            $table->integer('position_id')->nullable()->default(0)->index('position_id');
            $table->integer('employeetype_id')->nullable()->default(0)->index('employeetype_id');
            $table->integer('room_id')->nullable()->index('room_id');
            $table->integer('group_now')->nullable()->default(0);
            $table->integer('role_id')->nullable()->index('role_id');
            $table->integer('education_id')->nullable()->index('education_id');
            $table->string('company_doj')->nullable();
            $table->string('documents')->nullable();
            $table->string('additionals')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_identifier_code')->nullable();
            $table->string('branch_location')->nullable();
            $table->string('tax_payer_id')->nullable()->index('tax_payer_id');
            $table->integer('salary_type')->nullable();
            $table->double('salary')->nullable()->default(0);
            $table->double('consumption_fee')->nullable()->default(0);
            $table->integer('is_active')->default(1);
            $table->integer('created_by');
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
        Schema::dropIfExists('employees');
    }
}

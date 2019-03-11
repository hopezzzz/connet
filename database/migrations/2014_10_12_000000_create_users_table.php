<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('api_client_id')->default(0);
            $table->string('stripe_cust_id')->nullable();
            $table->string('firstName');
            $table->string('lastName')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('enc_password')->nullable();
            $table->string('phoneNo')->nullable();
            $table->string('companyName')->nullable();
            $table->string('companyUrl')->nullable();
            $table->string('country')->nullable();
            $table->integer('register_step')->nullable();
            $table->integer('status')->default(1)->comment('0 Inactive 1 Active and 2 for mintue consumed and 9 Payment not recived');
            $table->integer('reminderStatus')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

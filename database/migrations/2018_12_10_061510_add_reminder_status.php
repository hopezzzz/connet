<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReminderStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('reminderStatus')->default(0)->comment('0-Active 1-50% Consumed 2-80% Consumed 3-100% Consumed 4-7 Days to Expire 5-2 Days to Expire 6-Expired')->after('status');
            $table->decimal('fwdAmount')->comment('Carried forward Amount')->default(0)->after('reminderStatus');
            $table->decimal('balanceAmount')->comment('Current balance amount')->default(0)->after('fwdAmount');
            $table->decimal('minuteConsumed')->comment('Total Consumed Minute')->default(0)->after('balanceAmount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

        });
    }
}

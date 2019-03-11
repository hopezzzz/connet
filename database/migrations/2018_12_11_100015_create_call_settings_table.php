<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->text('call_transfer_hours')->nullable();
            $table->text('call_annoucement')->nullable();
            $table->tinyInteger('call_recording_display')->default(1);
            $table->text('customer_wait_message')->nullable();
            $table->tinyInteger('call_announcement_email')->default(1);
            $table->text('email_body')->nullable();
            $table->text('email_subject')->nullable();
            $table->integer('retry_time')->default(0);
            $table->integer('retry_delay_second')->default(0);
            $table->text('welcome_message')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_settings');
    }
}

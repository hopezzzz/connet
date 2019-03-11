<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('api_dept_id')->default(0);
            $table->integer('api_phone_id')->default(0);
            $table->string('title');
            $table->string('phone')->nullable();
            $table->string('email');
            $table->string('password')->nullable();
            $table->text('template')->nullable();
            $table->text('testMail')->nullable();
	    $table->next('parserOutput')->next()->nullable();
            $table->integer('country_id')->default(0);
            $table->string('available_days')->nullable();
            $table->string('available_hours')->nullable()->default('{"from":null,"to":null}');
            $table->string('break_hours')->nullable()->default('{"from":null,"to":null}');
            $table->integer('step')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('status')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
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
        Schema::dropIfExists('campaigns');
    }
}

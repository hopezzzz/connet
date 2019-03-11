<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_leads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id');
            $table->integer('campaign_id');
            $table->string('agent')->nullable();
            $table->integer('call_length')->default(0);
            $table->integer('cost')->default(0);
            $table->string('department')->nullable();
            $table->string('recording',500)->nullable();
            $table->string('resource_uri')->nullable();
            $table->string('sc1')->nullable();
            $table->string('sc2')->nullable();
            $table->dateTime('startdate')->nullable();
            $table->string('ticket_id')->nullable();
            $table->text('transcripts')->nullable();
            $table->text('whisper')->nullable();
            $table->integer('call_status')->default(0); 
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
        Schema::dropIfExists('api_leads');
    }
}

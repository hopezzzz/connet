<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Parsetags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('parsetags', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('campId')->default(0);;
          $table->string('tagName');
          $table->string('indexRow');
          $table->string('positionStart');
          $table->string('positionEnd');
          $table->string('addedBy');
          $table->integer('status')->default(1);
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
        Schema::dropIfExists('parsetags');
    }
}

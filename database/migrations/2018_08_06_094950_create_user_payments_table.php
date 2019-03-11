<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('amount')->nullable();
            $table->string('amount_paid')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->integer('custom')->nullable();
            $table->string('charge_id')->nullable();
            $table->string('currency')->nullable();
            $table->string('invloice_pdf')->nullable();
            $table->string('plan_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('qty')->nullable();
            $table->string('sub_id')->nullable();
            $table->string('sub_item_id')->nullable();
            $table->string('type')->nullable();
            $table->string('date')->nullable();
            $table->string('due_date')->nullable();
            $table->string('startDate')->nullable();
            $table->string('endDate')->nullable();
            $table->string('payment_status')->nullable();
            $table->text('completePayment')->nullable();
            $table->enum('status', ['succeeded','Canceled','Suspend','Reactivate','Active','']);
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('user_payments');
    }
}

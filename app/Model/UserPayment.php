<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    protected $table 	  = 'user_payments';
    protected $fillable = ['mc_gross', 'subscr_id', 'custom', 'txn_id', 'payment_type', 'receiver_id', 'txn_type', 'item_name', 'payment_gross', 'amount3', 'subscr_date', 'period3', 'ipn_track_id', 'completePayment', ];

    public function  users()
  	{
  		return $this->belongsTo('\App\User','customer_id','stripe_cust_id');
  	}
    public function  usersPlans()
  	{
  		return $this->belongsTo('\App\Model\Plan','product_id','stripeProId');
  	}
}

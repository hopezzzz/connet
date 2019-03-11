<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillingType extends Model
{
  protected $fillable = ['planType'];
  public $timestamps = false;
  public $table= 'billing_type';
}

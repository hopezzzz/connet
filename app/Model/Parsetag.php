<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Parsetag extends Model
{
  protected $table 	= 'parsetags';
  protected $fillable = ['tagName', 'mobileNo', 'status'];

  public function user()
  {
      return $this->belongsTo('\App\User','addedBy','id');
  }
}

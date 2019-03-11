<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class ParseEmail extends Model
{
  protected $table 	= 'parse_emails';
  protected $fillable = ['campaignEmail', 'mobileNo', 'callScript', 'expectedCall', 'status'];

  public function campaign_details()
  {
      return $this->hasOne('\App\Model\Campaign','email','campaignEmail');
  }

  public function call_logs()
  {
      return $this->belongsTo('\App\Model\Calllog','id','script_id');
  }
}

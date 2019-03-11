<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;

class CampaignsContacts extends Model
{
  protected $table 	= 'campaigns_contacts';
  protected $fillable = ['campId','custId'];
  
  public function campaigns_contacts()
  {
      return $this->hasMany('\App\Model\CampaignsContacts','campId','id');
  }
  public function contact()
  {
    return $this->belongsTo('\App\Model\Contact','custId','id');
  }
  public function campaign()
  {
    return $this->belongsTo('\App\Model\Campaign','campId','id');
  }

}

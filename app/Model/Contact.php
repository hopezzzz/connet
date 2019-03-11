<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table 	= 'contacts';
    protected $fillable = ['name','code','contact','user_id'];
    
    public function country()
    {
	    return $this->belongsTo('\App\Model\Country','country_id','countryId');
  	}
    public function campaigns()
    {
      return $this->hasMany('\App\Model\CampaignsContacts','custId','id');
    }
}

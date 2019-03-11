<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table 	= 'campaigns';
    protected $fillable = ['title','phone','email','template','available_days','available_hours','break_hours','step'];


    public function coutry_details()
    {
        return $this->hasOne('\App\Model\Country','countryId','country_id');
    }
    public function user_detail()
    {
        return $this->belongsTo('\App\User','user_id','id');
    }
    public function campaigns_contacts()
    {
        return $this->hasMany('\App\Model\CampaignsContacts','campId','id');
    }
    public function campaigns_tags()
    {
        return $this->hasMany('\App\Model\Parsetag','campId','id');
    }

}

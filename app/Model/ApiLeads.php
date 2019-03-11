<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ApiLeads extends Model
{
    protected $table 	= 'api_leads';
    protected $fillable = ['lead_id', 'campaign_id', 'agent', 'call_length', 'cost', 'department', 'recording', 'resource_uri', 'sc1', 'sc2', 'startdate', 'ticket_id', 'transcripts', 'whisper', 'call_status'];
    
    public function parse_emails()
    {
    	return $this->belongsTo('App\Model\ParseEmail','lead_id','lead_id');
    }
}

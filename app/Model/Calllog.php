<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Calllog extends Model
{
    protected $table 	= 'call_logs';
    protected $fillable = ['webmail_received','mail_read','mail_parsed','api_hit'];
}

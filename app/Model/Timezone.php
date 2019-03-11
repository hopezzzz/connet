<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $table 	= 'timezones';
    protected $fillable = ['title','time_difference'];
}

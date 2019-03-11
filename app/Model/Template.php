<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table 	= 'templates';
    protected $fillable = ['title','content'];
}

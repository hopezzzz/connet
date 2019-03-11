<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleUser extends Model
{
    protected $table    = 'role_user';
    protected $fillable = ['role_id','user_id'];

    public function users()
    {
        return $this->belongsTo('\App\User','user_id','id');
    }
   

}

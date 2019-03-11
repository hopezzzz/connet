<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CallSetting extends Model
{
	protected $table 	= 'call_settings';
    protected $fillable = ['user_id','call_transfer_hours','call_annoucement','call_recording_display','customer_wait_message','call_announcement_email','email_body','email_subject','retry_time','retry_delay_second','welcome_message'];

    public function user()
    {
        return $this->belongsTo('\App\User','user_id','id');
    }
}

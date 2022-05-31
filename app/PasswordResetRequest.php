<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    protected $guarded = [];

    function user()
    {
    	return $this->belongsTo('App\User');
    }

    function authority()
    {
    	return $this->belongsTo('App\User','authority_id');
    }

    function notifications()
    {
        return $this->morphMany('App\Notification', 'notificationable');
    }
}

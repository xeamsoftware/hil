<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    public function notificationable()
    {
        return $this->morphTo();
    }

    public function sender()
    {
    	return $this->belongsTo('App\User','sender_id');
    }

    public function receiver()
    {
    	return $this->belongsTo('App\User','receiver_id');
    }
}

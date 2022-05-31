<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveAccumulation extends Model
{
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function leaveType()
    {
    	return $this->belongsTo('App\LeaveType');
    }
}

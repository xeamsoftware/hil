<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveTypeLimit extends Model
{
    protected $guarded = [];

    public function leaveType()
    {
    	return $this->belongsTo('App\LeaveType');
    }
}

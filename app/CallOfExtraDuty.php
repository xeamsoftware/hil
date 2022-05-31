<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallOfExtraDuty extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function extraDutyLeaveApprovals()
    {
        return $this->hasMany(CallOfExtraDutyApproval::class);
    }

    public function notifications()
    {
        return $this->morphMany('App\Notification', 'notificationable');
    }
}

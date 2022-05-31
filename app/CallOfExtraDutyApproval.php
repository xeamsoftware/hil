<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallOfExtraDutyApproval extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function extraDutyLeave()
    {
        return $this->belongsTo(CallOfExtraDuty::class, 'call_of_extra_duty_id');
    }

    public function supervisor()
    {
        return $this->belongsTo('App\User','supervisor_id');
    }
}

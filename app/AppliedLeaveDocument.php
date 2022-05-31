<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppliedLeaveDocument extends Model
{
    protected $guarded = [];

    public function appliedLeave()
    {
        return $this->belongsTo('App\AppliedLeave');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfileApproval extends Model
{
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function approver()
    {
    	return $this->belongsTo('App\User','approver_id');
    }

}

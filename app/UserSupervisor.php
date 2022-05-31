<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSupervisor extends Model
{
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function supervisor()
    {
    	return $this->belongsTo('App\User','supervisor_id');
    }
}

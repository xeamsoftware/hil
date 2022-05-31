<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $guarded = [];

    public function holidays()
    {
    	return $this->hasMany('App\Holiday');
    }
}

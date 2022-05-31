<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserUnit extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function unit()
    {
        return $this->belongsTo('App\Unit');
    }
}

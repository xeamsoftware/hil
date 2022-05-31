<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $guarded = [];
    protected $table = "user_addresses";

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

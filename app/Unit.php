<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Unit extends Model
{
    protected $guarded = [];

    public function userUnits()
    {
        return $this->hasMany('App\UserUnit');
    }

    public function holidays()
    {
        return $this->hasMany('App\Holiday');
    }

    public function unitWiseEmployees($units)
    {
    	$data = DB::table('user_units as uu')
    			->join('users as u','u.id','=','uu.user_id')
    			->whereIn('uu.unit_id',$units)
    			->where('u.id','!=',1)
                ->where('u.status',"1")
                ->select('u.id','u.first_name','u.middle_name','u.last_name','u.employee_code')
                ->groupBy('uu.user_id')
                ->get()->toArray();

        return $data;
    }


}

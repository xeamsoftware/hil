<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CompensatoryLeave extends Model
{
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function compensatoryLeaveApprovals()
    {
        return $this->hasMany('App\CompensatoryLeaveApproval');
    }

    public function extraDutyLeaveApproval()
    {
        return $this->hasMany('App\CompensatoryLeaveApproval');
    }

    public function leaveType()
    {
        return $this->belongsTo('App\LeaveType');
    }

    public function notifications()
    {
        return $this->morphMany('App\Notification', 'notificationable');
    }

    public function listCompensatoryLeaves($userId)
    {
    	$data = DB::table('compensatory_leaves as cl')
    			->where(['cl.user_id'=>$userId])
    			->select('cl.*', DB::raw("SUM(cl.number_of_hours) as number_of_hours"))
    			->groupBy('cl.on_date')
                ->orderBy('cl.created_at','DESC')
    			->get();

    	return $data;
    }
}

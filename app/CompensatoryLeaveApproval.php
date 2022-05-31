<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CompensatoryLeaveApproval extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function leaveType()
    {
    	return $this->belongsTo('App\LeaveType');
    }

    public function compensatoryLeave()
    {
        return $this->belongsTo('App\CompensatoryLeave');
    }

    public function supervisor()
    {
    	return $this->belongsTo('App\User','supervisor_id');
    }

    public function listCompensatoryLeaveApprovals($supervisorId)
    {
       $data = DB::table('compensatory_leave_approvals as cla')
                ->join('users as u','u.id','=','cla.user_id')
                ->join('compensatory_leaves as cl','cl.id','=','cla.compensatory_leave_id')
                ->where(['cla.supervisor_id'=>$supervisorId,'cl.status'=>'1'])
                ->select('u.first_name','u.middle_name','u.last_name','cla.id as compensatory_leave_approval_id','cla.leave_status','cl.*')
                ->distinct('cl.on_date')
                ->distinct('cl.user_id')
                // ->distinct()
                ->orderBy('cl.created_at','DESC')
                ->get();

        if(!$data->isEmpty()){
            foreach ($data as $key => $value) {
                $hours = DB::table('compensatory_leaves')
                        ->where(['status'=>'1','on_date'=>$value->on_date,'user_id'=>$value->user_id])
                       ->sum('number_of_hours');
               $value->number_of_hours = $hours;
            }
        }
        return $data;
    }

    public function checkLeaveApprovalOnAllLevels($compensatoryLeave)
    {
        $allSupervisors = $compensatoryLeave->compensatoryLeaveApprovals()->count();
        $allApprovedSupervisors = $compensatoryLeave->compensatoryLeaveApprovals()->where(['leave_status'=>'1'])->count();

        if($allSupervisors == $allApprovedSupervisors){
            $compensatoryLeave->final_status = '1';
            $compensatoryLeave->save();
        }

        return $compensatoryLeave;

    }
}

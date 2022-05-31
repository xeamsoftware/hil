<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class AppliedLeave extends Model
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

    public function appliedLeaveApprovals()
    {
        return $this->hasMany('App\AppliedLeaveApproval');
    }

    public function appliedLeaveDocuments()
    {
        return $this->hasMany('App\AppliedLeaveDocument');
    }

    public function appliedLeaveSegregations()
    {
        return $this->hasMany('App\AppliedLeaveSegregation');
    }

    public function notifications()
    {
        return $this->morphMany('App\Notification', 'notificationable');
    }

    public function checkPreviousLeaveApprovalPending($userId)
    {
        $data = DB::table('applied_leave_approvals as ala')
            ->join('applied_leaves as al', 'al.id', '=', 'ala.applied_leave_id')
            ->where(['ala.user_id' => $userId, 'ala.leave_status' => '0', 'al.status' => '1'])
            ->select('al.*')
            ->first();

        // $data = AppliedLeave::where(['user_id'=>$userId,'status'=>'1'])
        //                     ->with(['appliedLeaveApprovals'=> function($query){
        //                             $query->where(['leave_status'=>'0']);
        //                     }])->first();

        return $data;
    }

    public function checkLeaveUniqueDates($checkUniqueDates)
    {
        $data = DB::table('applied_leaves as al')
            ->where(['leave_type_id' => $checkUniqueDates['leaveTypeId'], 'al.user_id' => $checkUniqueDates['userId'], 'al.status' => '1'])
            ->where(function ($query) use ($checkUniqueDates) {
                $query->where(['al.from_date' => $checkUniqueDates['fromDate']])
                    ->where(['al.to_date' => $checkUniqueDates['toDate']]);
            })
            ->first();

        // $data = AppliedLeave::where(['user_id' => $checkUniqueDates['userId'],'status'=>'1'])
        //                     ->where(function($query) use($checkUniqueDates){
        //                         $query->where(['from_date'=>$checkUniqueDates['fromDate']])
        //                               ->orWhere(['to_date'=>$checkUniqueDates['toDate']]);
        //                     })
        //                     ->first();

        return $data;
    }

    public function generateLeaveReport($reportData, $leaveStatus)
    {
        $query = DB::table('applied_leaves as al')
            ->join('applied_leave_approvals as ala', 'al.id', '=', 'ala.applied_leave_id')
            ->join('users as u', 'u.id', '=', 'al.user_id')
            ->join('users as su', 'su.id', '=', 'ala.supervisor_id')
            ->join('leave_types as lt', 'lt.id', '=', 'al.leave_type_id')
            ->join('user_units as uu', 'uu.user_id', '=', 'u.id');

            if($leaveStatus  == '1'){
                $query = $query->where(['al.final_status' => '0', 'al.status' => '1',  'uu.unit_id' => $reportData['unitId']])->whereIn('ala.leave_status', ['0','1']);
            }
            if($leaveStatus  == '2'){
                $query = $query->where(['al.final_status' => '1', 'al.status' => '1', 'uu.unit_id' => $reportData['unitId']]);
            }
            if($leaveStatus  == '3'){
                $query = $query->where(['al.final_status' => '0', 'al.status' => '1', 'uu.unit_id' => $reportData['unitId'], 'ala.leave_status' => '2']);
            }elseif($leaveStatus == '' || $leaveStatus == 'all'){
                $query = $query->where(['uu.unit_id' => $reportData['unitId'], 'al.status' => '1']);
            }
            $data  = $query->where('al.from_date', '>=', $reportData['fromDate'])
            ->where('al.to_date', '<=', $reportData['toDate'] . ' 23:59:59')
            ->select("al.*", "ala.leave_status as leave_status", "u.employee_code", "u.first_name", "u.middle_name", "u.last_name", "lt.name as leave_type_name", "supervisor_id", "su.employee_code as supervisor_code")
            ->groupBy('al.id')
            ->orderBy("al.created_at")
            ->get();

        return $data;
    }

} //end of class

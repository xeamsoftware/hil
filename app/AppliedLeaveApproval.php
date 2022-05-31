<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\LeaveApprovalAuthority;
use App\CompensatoryLeave;

class AppliedLeaveApproval extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function appliedLeave()
    {
        return $this->belongsTo('App\AppliedLeave');
    }

    public function supervisor()
    {
        return $this->belongsTo('App\User','supervisor_id');
    }

    public function listAppliedLeaveApprovals($userId,$status)
    {
        $query = DB::table('applied_leave_approvals as ala')
            ->join('applied_leaves as al','al.id','=','ala.applied_leave_id')
            ->join('users as u','u.id','=','ala.user_id')
            ->join('leave_types as lt','al.leave_type_id','=','lt.id');
        if($status == 2){
            $query =  $query->where(['ala.supervisor_id' => $userId,'ala.leave_status'=>$status,'al.status'=>'0']);
            //al.status to 0 as there is no enum with value 2-rejected
        }else{
            $query =  $query->where(['ala.supervisor_id' => $userId,'ala.leave_status'=>$status,'al.status'=>'1']);
        }
        $data = $query->select('ala.*','u.first_name','u.last_name','u.middle_name','al.number_of_days','al.status','lt.name as leave_type_name','al.from_date','al.to_date','al.purpose','al.final_status','al.created_at as applied_leave_created_at','al.weekoffs')
            ->orderBy('ala.applied_leave_id','DESC')
            ->get();

        return $data;
    }

    public function shortLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => $currentAppliedLeaveApproval->priority + 1,
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $leaveAccumulation = $leaveApplier->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$appliedLeave->leave_type_id])
            ->orderBy('id','DESC')
            ->first();

        $finalApproverPriority = 1;  //Supervisor

        $nextApproverAppliedLeave = 0;

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
                $finalApproverPriority += 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            if(($where['priority'] < 5)){  // && ($nextApprover->priority < $finalApproverPriority)
                $where['priority'] += 1;
                $nextApprover = LeaveApprovalAuthority::where($where)->first();
            }else{
                $nextApprover = 0;
            }
        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && ($currentAppliedLeaveApproval->leave_status == '1') && empty($nextApproverPresent) && ($currentAppliedLeaveApproval->priority < $finalApproverPriority)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            if($appliedLeave->final_status == '1'){
                $newAccumulationData =  [
                    'leave_type_id' => $appliedLeave->leave_type_id,
                    'creator_id' => 1,
                    'applied_leave_id' => $appliedLeave->id,
                    'status' => '1',
                    'comment' => 'Leave Rejected After Approval',
                    'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                    'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                    'previous_count' => $leaveAccumulation->total_remaining_count
                ];

                $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count+$appliedLeave->number_of_days;

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            }

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){

            $newAccumulationData =  [
                'leave_type_id' => $appliedLeave->leave_type_id,
                'creator_id' => 1,
                'applied_leave_id' => $appliedLeave->id,
                'status' => '1',
                'comment' => 'Leave Approved',
                'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                'previous_count' => $leaveAccumulation->total_remaining_count
            ];

            $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count - $appliedLeave->number_of_days;

            $leaveAccumulation->status = '0';
            $leaveAccumulation->save();
            $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);
        }
    }

    public function compensatoryLeaveApprovals($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => $currentAppliedLeaveApproval->priority + 1,
            'status' => '1'
        ];

        $leaveAccumulation = $leaveApplier->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$appliedLeave->leave_type_id])
            ->orderBy('id','DESC')
            ->first();

        $originalHours = $appliedLeave->number_of_days / 0.125;

        if($originalHours <= 4){
            $finalApproverPriority = 1;  //Supervisor
        }else{
            $finalApproverPriority = 3;  //HOD
        }

        $nextApproverAppliedLeave = 0;

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){
            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
                $finalApproverPriority += 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            if(($where['priority'] < 5)){  // && ($nextApprover->priority < $finalApproverPriority)
                $where['priority'] += 1;
                $nextApprover = LeaveApprovalAuthority::where($where)->first();
            }else{
                $nextApprover = 0;
            }
        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && ($currentAppliedLeaveApproval->leave_status == '1') && empty($nextApproverPresent) && ($currentAppliedLeaveApproval->priority < $finalApproverPriority)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            if($appliedLeave->final_status == '1'){
                $newAccumulationData =  [
                    'leave_type_id' => $appliedLeave->leave_type_id,
                    'creator_id' => 1,
                    'applied_leave_id' => $appliedLeave->id,
                    'status' => '1',
                    'comment' => 'Leave Rejected After Approval',
                    'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                    'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                    'previous_count' => $leaveAccumulation->total_remaining_count
                ];

                $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count;

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            }

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){

            $compensatoryLeavesAvailable = $leaveApplier->compensatoryLeaves()
                ->where(['status'=>'1','final_status'=>'1','applied_leave_id'=>0])
                ->pluck('id')->toArray();

            $totalParts = $originalHours / 0.5;
            $paidHours = 0;
            $unpaidHours = 0;

            if(!empty($compensatoryLeavesAvailable)){

                if(count($compensatoryLeavesAvailable) <= $totalParts){
                    CompensatoryLeave::whereIn('id',$compensatoryLeavesAvailable)->update(['applied_leave_id'=>$appliedLeave->id]);
                    $paidHours = count($compensatoryLeavesAvailable) * 0.5;
                    $unpaidHours = $originalHours - $paidHours;

                }else{

                    for ($i=1; $i <= $totalParts; $i++) {
                        $key = $compensatoryLeavesAvailable[$i-1];
                        $compLeave = CompensatoryLeave::find($key);
                        $compLeave->update(['applied_leave_id'=>$appliedLeave->id]);
                    }

                    $paidHours = $originalHours;
                    $unpaidHours = 0;

                }

            }else{

                $paidHours = 0;
                $unpaidHours = $originalHours;

            }

            $newAccumulationData =  [
                'leave_type_id' => $appliedLeave->leave_type_id,
                'creator_id' => 1,
                'applied_leave_id' => $appliedLeave->id,
                'status' => '1',
                'comment' => 'Leave Approved',
                'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                'previous_count' => $leaveAccumulation->total_remaining_count
            ];

            $appliedLeave->paid_leaves_count = $paidHours * 0.125;
            $appliedLeave->unpaid_leaves_count = $unpaidHours * 0.125;
            $appliedLeave->save();

            $leaveAccumulation->status = '0';
            $leaveAccumulation->save();

            $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count - ($paidHours * 0.125);

            $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);
        }
    }

    // public function compensatoryLeaveApprovals($data)
    // {
    //     return "eee";
    //     $currentApprover = $data['currentApprover'];
    //     $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
    //     $leaveApplier = $data['leaveApplier'];
    //     $appliedLeave = $data['appliedLeave'];
    //     $where =  [
    //         'user_id' => $leaveApplier->id,
    //         'priority' => $currentAppliedLeaveApproval->priority + 1,
    //         //'leave_type_id' => $appliedLeave->leave_type_id,
    //         'status' => '1'
    //     ];

    //     $leaveAccumulation = $leaveApplier->leaveAccumulations()
    //         ->where(['status'=>'1','leave_type_id'=>$appliedLeave->leave_type_id])
    //         ->orderBy('id','DESC')
    //         ->first();

    //     $originalHours = $appliedLeave->number_of_days / 0.125;

    //     if($originalHours <= 4){
    //         $finalApproverPriority = 1;  //Supervisor
    //     }else{
    //         $finalApproverPriority = 3;  //HOD
    //     }

    //     $nextApproverAppliedLeave = 0;

    //     if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
    //         $nextApprover = LeaveApprovalAuthority::where($where)->first();
    //     }else{
    //         $nextApprover = 0;
    //     }

    //     //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
    //     while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

    //         if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
    //             $nextApproverAppliedLeave = 1;
    //             $finalApproverPriority += 1;
    //         }else{
    //             $nextApproverAppliedLeave = 0;
    //         }

    //         if(($where['priority'] < 5)){  // && ($nextApprover->priority < $finalApproverPriority)
    //             $where['priority'] += 1;
    //             $nextApprover = LeaveApprovalAuthority::where($where)->first();
    //         }else{
    //             $nextApprover = 0;
    //         }
    //     }

    //     $checkApproverData = [];

    //     if(empty($nextApprover)){
    //         $checkApproverData['supervisor_id'] = 0;
    //     }else{
    //         $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
    //     }

    //     $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

    //     if(!empty($nextApprover) && ($currentAppliedLeaveApproval->leave_status == '1') && empty($nextApproverPresent) && ($currentAppliedLeaveApproval->priority < $finalApproverPriority)){
    //         $nextApprovalData = [
    //             'user_id' => $currentAppliedLeaveApproval->user_id,
    //             'supervisor_id' => $nextApprover->supervisor_id,
    //             'priority' => $nextApprover->priority,
    //             'leave_status' => '0',
    //         ];

    //         $notificationData = [
    //             'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
    //             'receiver_id' => $nextApprover->supervisor_id,
    //             'label' => 'Leave Application',
    //             'status' => '1',
    //             'read_status' => '0',
    //             'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
    //         ];

    //         $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
    //         $appliedLeave->notifications()->create($notificationData);

    //     }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

    //         $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

    //     }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

    //         $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

    //     }elseif($currentAppliedLeaveApproval->leave_status == '2'){

    //         if($appliedLeave->final_status == '1'){
    //             $newAccumulationData =  [
    //                 'leave_type_id' => $appliedLeave->leave_type_id,
    //                 'creator_id' => 1,
    //                 'applied_leave_id' => $appliedLeave->id,
    //                 'status' => '1',
    //                 'comment' => 'Leave Rejected After Approval',
    //                 'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
    //                 'total_upper_limit' => $leaveAccumulation->total_upper_limit,
    //                 'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
    //                 'previous_count' => $leaveAccumulation->total_remaining_count
    //             ];

    //             $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count;

    //             $leaveAccumulation->status = '0';
    //             $leaveAccumulation->save();
    //             $leaveApplier->leaveAccumulations()->create($newAccumulationData);

    //         }

    //         $appliedLeave->final_status = '0';
    //         $appliedLeave->paid_leaves_count = '0';
    //         $appliedLeave->unpaid_leaves_count = '0';
    //         $appliedLeave->compensatory_leaves_count = '0';
    //         $appliedLeave->save();

    //     }

    //     //////////////////////////////////////////////////////////////////////////////////////////////////////

    //     if($appliedLeave->final_status == '1'){

    //         $compensatoryLeavesAvailable = $leaveApplier->compensatoryLeaves()
    //             ->where(['status'=>'1','final_status'=>'1','applied_leave_id'=>0])
    //             ->pluck('id')->toArray();

    //         $totalParts = $originalHours / 0.5;
    //         $paidHours = 0;
    //         $unpaidHours = 0;

    //         if(!empty($compensatoryLeavesAvailable)){

    //             if(count($compensatoryLeavesAvailable) <= $totalParts){
    //                 CompensatoryLeave::whereIn('id',$compensatoryLeavesAvailable)->update(['applied_leave_id'=>$appliedLeave->id]);
    //                 $paidHours = count($compensatoryLeavesAvailable) * 0.5;
    //                 $unpaidHours = $originalHours - $paidHours;

    //             }else{

    //                 for ($i=1; $i <= $totalParts; $i++) {
    //                     $key = $compensatoryLeavesAvailable[$i-1];
    //                     $compLeave = CompensatoryLeave::find($key);
    //                     $compLeave->update(['applied_leave_id'=>$appliedLeave->id]);
    //                 }

    //                 $paidHours = $originalHours;
    //                 $unpaidHours = 0;

    //             }

    //         }else{

    //             $paidHours = 0;
    //             $unpaidHours = $originalHours;

    //         }

    //         $newAccumulationData =  [
    //             'leave_type_id' => $appliedLeave->leave_type_id,
    //             'creator_id' => 1,
    //             'applied_leave_id' => $appliedLeave->id,
    //             'status' => '1',
    //             'comment' => 'Leave Approved',
    //             'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
    //             'total_upper_limit' => $leaveAccumulation->total_upper_limit,
    //             'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
    //             'previous_count' => $leaveAccumulation->total_remaining_count
    //         ];

    //         $appliedLeave->paid_leaves_count = $paidHours * 0.125;
    //         $appliedLeave->unpaid_leaves_count = $unpaidHours * 0.125;
    //         $appliedLeave->save();

    //         $leaveAccumulation->status = '0';
    //         $leaveAccumulation->save();

    //         $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count - ($paidHours * 0.125);

    //         $leaveApplier->leaveAccumulations()->create($newAccumulationData);

    //         $notificationData = [
    //             'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
    //             'receiver_id' => $appliedLeave->user_id,
    //             'label' => 'Leave Approved',
    //             'status' => '1',
    //             'read_status' => '0',
    //             'message' => "Your leave has been approved."
    //         ];

    //         $appliedLeave->notifications()->create($notificationData);
    //     }
    // }

    public function extraDutyLeaveApprovals($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => $currentAppliedLeaveApproval->priority + 1,
            'status' => '1'
        ];
        $leaveAccumulation = $leaveApplier->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$appliedLeave->leave_type_id])
            ->orderBy('id','DESC')
            ->first();

        $originalHours = $appliedLeave->number_of_days / 0.125;

        if($originalHours <= 4){
            $finalApproverPriority = 1;  //Supervisor
        }else{
            $finalApproverPriority = 3;  //HOD
        }

        $nextApproverAppliedLeave = 0;

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
                $finalApproverPriority += 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            if(($where['priority'] < 5)){  // && ($nextApprover->priority < $finalApproverPriority)
                $where['priority'] += 1;
                $nextApprover = LeaveApprovalAuthority::where($where)->first();
            }else{
                $nextApprover = 0;
            }
        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && ($currentAppliedLeaveApproval->leave_status == '1') && empty($nextApproverPresent) && ($currentAppliedLeaveApproval->priority < $finalApproverPriority)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            if($appliedLeave->final_status == '1'){
                $newAccumulationData =  [
                    'leave_type_id' => $appliedLeave->leave_type_id,
                    'creator_id' => 1,
                    'applied_leave_id' => $appliedLeave->id,
                    'status' => '1',
                    'comment' => 'Leave Rejected After Approval',
                    'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                    'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                    'previous_count' => $leaveAccumulation->total_remaining_count
                ];

                $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count +  $appliedLeave->number_of_days;

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $leaveApplier->leaveAccumulations()->create($newAccumulationData);
            }else{
                 $leaveAccumulation->total_remaining_count = $leaveAccumulation->total_remaining_count + $appliedLeave->number_of_days;
                $leaveAccumulation->save();
            }

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        if($appliedLeave->final_status == '1'){

            $compensatoryLeavesAvailable = $leaveApplier->compensatoryLeaves()
                ->where(['status'=>'1','final_status'=>'1','applied_leave_id'=>0])
                ->pluck('id')->toArray();

            $totalParts = $originalHours / 0.5;
            $paidHours = 0;
            $unpaidHours = 0;

            if(!empty($compensatoryLeavesAvailable)){

                if(count($compensatoryLeavesAvailable) <= $totalParts){
                    CompensatoryLeave::whereIn('id',$compensatoryLeavesAvailable)->update(['applied_leave_id'=>$appliedLeave->id]);
                    $paidHours = count($compensatoryLeavesAvailable) * 0.5;
                    $unpaidHours = $originalHours - $paidHours;

                }else{

                    for ($i=1; $i <= $totalParts; $i++) {
                        $key = $compensatoryLeavesAvailable[$i-1];
                        $compLeave = CompensatoryLeave::find($key);
                        $compLeave->update(['applied_leave_id'=>$appliedLeave->id]);
                    }

                    $paidHours = $originalHours;
                    $unpaidHours = 0;

                }

            }else{

                $paidHours = 0;
                $unpaidHours = $originalHours;

            }

            $newAccumulationData =  [
                'leave_type_id' => $appliedLeave->leave_type_id,
                'creator_id' => 1,
                'applied_leave_id' => $appliedLeave->id,
                'status' => '1',
                'comment' => 'Leave Approved',
                'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                'previous_count' => $leaveAccumulation->total_remaining_count
            ];

            $appliedLeave->paid_leaves_count = $paidHours * 0.125;
            $appliedLeave->unpaid_leaves_count = $unpaidHours * 0.125;
            $appliedLeave->save();

            $leaveAccumulation->status = '0';
            $leaveAccumulation->save();

            $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count - ($paidHours * 0.125);

            $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);
        }
    }

    public function elNonEnCashableLeaveApprovals($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => $currentAppliedLeaveApproval->priority + 1,
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $leaveAccumulation = $leaveApplier->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$appliedLeave->leave_type_id])
            ->orderBy('id','DESC')
            ->first();

        $elCashableAccumulation = $leaveApplier->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>11])
            ->orderBy('id','DESC')
            ->first();

        $finalApproverPriority = 3;  //HOD

        $nextApproverAppliedLeave = 0;

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }


        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
                $finalApproverPriority += 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            if(($where['priority'] < 5)){  // && ($nextApprover->priority < $finalApproverPriority)
                $where['priority'] += 1;
                $nextApprover = LeaveApprovalAuthority::where($where)->first();
            }else{
                $nextApprover = 0;
            }
        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && ($currentAppliedLeaveApproval->leave_status == '1') && empty($nextApproverPresent) && ($currentAppliedLeaveApproval->priority < $finalApproverPriority)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);
            //make final_status 1 if last approval **

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            if($appliedLeave->final_status == '1'){
                $newAccumulationData =  [
                    'leave_type_id' => $appliedLeave->leave_type_id,
                    'creator_id' => 1,
                    'applied_leave_id' => $appliedLeave->id,
                    'status' => '1',
                    'comment' => 'Leave Rejected After Approval',
                    'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                    'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                    'previous_count' => $leaveAccumulation->total_remaining_count
                ];

                //I dont know why encashable leaves are counted below when we are working on nonencashable remove later if not required Dt 26-05-2021
                if($leaveAccumulation->total_remaining_count + $elCashableAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count <= 300){
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count;
                }else{
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count;
                    $newAccumulationData['comment'] = 'Leave Rejected After Approval and exceeding limit';
                }

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            }

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){//works here**

            $newAccumulationData =  [
                'leave_type_id' => $appliedLeave->leave_type_id,
                'creator_id' => 1,
                'applied_leave_id' => $appliedLeave->id,
                'status' => '1',
                'comment' => 'Leave Approved',
                'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                'previous_count' => $leaveAccumulation->total_remaining_count,
                'total_remaining_count' => $leaveAccumulation->total_remaining_count
            ];

            $currentYear = date("Y");
            $appliedLeaveOldData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$leaveApplier->id,'al.leave_type_id'=>$appliedLeave->leave_type_id])
                ->whereYear('al.updated_at',$currentYear)
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount"))
                ->first();

            if($appliedLeaveOldData->paidLeavesCount == ""){
                //if user has no approved  non enchashable leaves
                $appliedLeaveOldData->paidLeavesCount = 0;
            }
            /*
                Earlier leave pool was max yearly limit, but now we are changing it to total remaining count
            */
            if(($leaveAccumulation->total_remaining_count-$appliedLeaveOldData->paidLeavesCount) >= $appliedLeave->number_of_days){
                //if user has balance leaves and applied leave can be given as paid
                $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            }else{
                //if leaves has some unpaid leaves
                if($leaveAccumulation->total_remaining_count >= $appliedLeaveOldData->paidLeavesCount){
                    $remaining = $leaveAccumulation->total_remaining_count - $appliedLeaveOldData->paidLeavesCount;
                }else{
                    $remaining = 0;
                }

                if($remaining >= $appliedLeave->number_of_days){
                    $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
                }else{
                    $appliedLeave->paid_leaves_count = $remaining;
                    $appliedLeave->unpaid_leaves_count = $appliedLeave->number_of_days - $remaining;
                }

            }


            $appliedLeave->save();

            $leaveAccumulation->status = '0';
            $leaveAccumulation->save();


            $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);
        }
    }

    public function elEnCashableLeaveApprovals($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => $currentAppliedLeaveApproval->priority + 1,
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $leaveAccumulation = $leaveApplier->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$appliedLeave->leave_type_id])
            ->orderBy('id','DESC')
            ->first();

        $elNonCashableAccumulation = $leaveApplier->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>3])
            ->orderBy('id','DESC')
            ->first();


        $finalApproverPriority = 3;  //HOD

        $nextApproverAppliedLeave = 0;

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
                $finalApproverPriority += 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            if(($where['priority'] < 5)){  // && ($nextApprover->priority < $finalApproverPriority)
                $where['priority'] += 1;
                $nextApprover = LeaveApprovalAuthority::where($where)->first();
            }else{
                $nextApprover = 0;
            }
        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && ($currentAppliedLeaveApproval->leave_status == '1') && empty($nextApproverPresent) && ($currentAppliedLeaveApproval->priority < $finalApproverPriority)){

            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            if($appliedLeave->final_status == '1'){
                $newAccumulationData =  [
                    'leave_type_id' => $appliedLeave->leave_type_id,
                    'creator_id' => 1,
                    'applied_leave_id' => $appliedLeave->id,
                    'status' => '1',
                    'comment' => 'Leave Rejected After Approval',
                    'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,  //180
                    'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                    'previous_count' => $leaveAccumulation->total_remaining_count
                ];

                if($leaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count <= 300){
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count;


                }else{
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count;
                    $newAccumulationData['comment'] = 'Leave Rejected After Approval and exceeding limit';
                }

                $newAccumulationData['max_yearly_limit'] = $leaveAccumulation->max_yearly_limit + $appliedLeave->paid_leaves_count;

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $leaveApplier->leaveAccumulations()->create($newAccumulationData);
            }

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){

            $newAccumulationData =  [
                'leave_type_id' => $appliedLeave->leave_type_id,
                'creator_id' => 1,
                'applied_leave_id' => $appliedLeave->id,
                'status' => '1',
                'comment' => 'Leave Approved',
                'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                'total_upper_limit' => $leaveAccumulation->total_upper_limit, //180
                'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                'previous_count' => $leaveAccumulation->total_remaining_count,
                'total_remaining_count' => $leaveAccumulation->total_remaining_count,
            ];

            $currentYear = date("Y");
            $appliedLeaveOldData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$leaveApplier->id,'al.leave_type_id'=>$appliedLeave->leave_type_id])
                ->whereYear('al.updated_at',$currentYear)
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount"))
                ->first();

            if($appliedLeaveOldData->paidLeavesCount == ""){
                $appliedLeaveOldData->paidLeavesCount = 0;
            }


            $remainingAccumulateLeave = 0;


            // if ($leaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count > 285) {
            //     $remainingAccumulateLeave = $leaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count - 285;
            // }
            //echo '('.$leaveAccumulation->max_yearly_limit .'+'. $appliedLeave->number_of_days .'-'.$appliedLeaveOldData->paidLeavesCount.') >='. $appliedLeave->number_of_days;

            // if(($leaveAccumulation->max_yearly_limit + $appliedLeave->number_of_days -$appliedLeaveOldData->paidLeavesCount) >= $appliedLeave->number_of_days){
            //     $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            // }
            // }else{
            //     if($leaveAccumulation->max_yearly_limit + $appliedLeave->number_of_days >= $appliedLeaveOldData->paidLeavesCount){
            //         $remaining = $leaveAccumulation->max_yearly_limit - $appliedLeaveOldData->paidLeavesCount;
            //     }else{
            //         $remaining = 0;
            //     }
            //     // if($remaining >= $appliedLeave->number_of_days || $remaining == 0){
            //     //     $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            //     // }else{
            //     //     $appliedLeave->paid_leaves_count = $remaining;
            //     //     $appliedLeave->unpaid_leaves_count = $appliedLeave->number_of_days - $remaining;
            //     // }

            // }
            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            $appliedLeave->save();
            //dd($appliedLeave);
            $leaveAccumulation->status = '0';
            $leaveAccumulation->save();

//           return $leaveAccumulation->total_remaining_count.'-'. $leaveAccumulation->max_yearly_limit .'-' .$appliedLeave->paid_leaves_count;

            $totalRemainingCount =  ($leaveAccumulation->total_remaining_count + $leaveAccumulation->max_yearly_limit);
//            $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count - $appliedLeave->paid_leaves_count;
//            $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count;

//            $newAccumulationData['max_yearly_limit'] = 0;
//           return $newAccumulationData;

            $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);
        }
    }


    public function casualLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => $currentAppliedLeaveApproval->priority + 1,
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $leaveAccumulation = $leaveApplier->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$appliedLeave->leave_type_id])
            ->orderBy('id','DESC')
            ->first();

        $finalApproverPriority = 3;  //HOD

        $nextApproverAppliedLeave = 0;

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
                $finalApproverPriority += 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            if(($where['priority'] < 5)){  // && ($nextApprover->priority < $finalApproverPriority)
                $where['priority'] += 1;
                $nextApprover = LeaveApprovalAuthority::where($where)->first();
            }else{
                $nextApprover = 0;
            }
        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && ($currentAppliedLeaveApproval->leave_status == '1') && empty($nextApproverPresent) && ($currentAppliedLeaveApproval->priority < $finalApproverPriority)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved
            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            if($appliedLeave->final_status == '1'){
                $newAccumulationData =  [
                    'leave_type_id' => $appliedLeave->leave_type_id,
                    'creator_id' => 1,
                    'applied_leave_id' => $appliedLeave->id,
                    'status' => '1',
                    'comment' => 'Leave Rejected After Approval',
                    'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                    'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                    'previous_count' => $leaveAccumulation->total_remaining_count
                ];

                if($leaveAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count <= $leaveAccumulation->total_upper_limit){
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count;
                }else{
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_upper_limit;
                    $newAccumulationData['comment'] = 'Leave Rejected After Approval and exceeding limit';
                }

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $leaveApplier->leaveAccumulations()->create($newAccumulationData);
            }

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){
            $newAccumulationData =  [
                'leave_type_id' => $appliedLeave->leave_type_id,
                'creator_id' => 1,
                'applied_leave_id' => $appliedLeave->id,
                'status' => '1',
                'comment' => 'Leave Approved',
                'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                'total_upper_limit' => $leaveAccumulation->total_upper_limit, //180
                'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                'previous_count' => $leaveAccumulation->total_remaining_count,
                'total_remaining_count'=>  $leaveAccumulation->total_remaining_count
            ];

            $currentYear = date("Y");
            $appliedLeaveOldData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$leaveApplier->id,'al.leave_type_id'=>$appliedLeave->leave_type_id])
                ->whereYear('al.updated_at',$currentYear)
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount"))
                ->first();

            if($appliedLeaveOldData->paidLeavesCount == ""){
                $appliedLeaveOldData->paidLeavesCount = 0;
            }

            if(($leaveAccumulation->total_remaining_count-$appliedLeaveOldData->paidLeavesCount) >= $appliedLeave->number_of_days){
                $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            }else{
                if($leaveAccumulation->total_remaining_count >= $appliedLeaveOldData->paidLeavesCount){
                    $remaining = $leaveAccumulation->total_remaining_count - $appliedLeaveOldData->paidLeavesCount;
                }else{
                    $remaining = 0;
                }

                if($remaining >= $appliedLeave->number_of_days){
                    $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
                }else{
                    $appliedLeave->paid_leaves_count = $remaining;
                    $appliedLeave->unpaid_leaves_count = $appliedLeave->number_of_days - $remaining;
                }

            }

            $appliedLeave->save();

            $leaveAccumulation->status = '0';
            $leaveAccumulation->save();

            // $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count - $appliedLeave->paid_leaves_count;

            $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);
        }
    }

    public function halfPaySickLeaveApproval($data)
    {

        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => $currentAppliedLeaveApproval->priority + 1,
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $leaveAccumulation = $leaveApplier->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$appliedLeave->leave_type_id])
            ->orderBy('id','DESC')
            ->first();

        if($appliedLeave->number_of_days <= 5){
            $finalApproverPriority = 2;

        }elseif($appliedLeave->number_of_days <= 30){
            $finalApproverPriority = 3;

        }elseif($appliedLeave->number_of_days <= 60){
            $finalApproverPriority = 4;

        }elseif($appliedLeave->number_of_days > 60){
            $finalApproverPriority = 5;

        }

        $nextApproverAppliedLeave = 0;

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }


        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
                $finalApproverPriority += 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            if(($where['priority'] < 5)){  // && ($nextApprover->priority < $finalApproverPriority)
                $where['priority'] += 1;
                $nextApprover = LeaveApprovalAuthority::where($where)->first();
            }else{
                $nextApprover = 0;
            }
        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && ($currentAppliedLeaveApproval->leave_status == '1') && empty($nextApproverPresent) && ($currentAppliedLeaveApproval->priority < $finalApproverPriority)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." "
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            if($appliedLeave->final_status == '1'){
                $newAccumulationData =  [
                    'leave_type_id' => $appliedLeave->leave_type_id,
                    'creator_id' => 1,
                    'applied_leave_id' => $appliedLeave->id,
                    'status' => '1',
                    'comment' => 'Leave Rejected After Approval',
                    'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,  //180
                    'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                    'previous_count' => $leaveAccumulation->total_remaining_count
                ];

                if($leaveAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count <= $leaveAccumulation->total_upper_limit){
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count;
                }else{
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_upper_limit;
                    $newAccumulationData['comment'] = 'Leave Rejected After Approval and exceeding limit';
                }

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            }

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){

            $newAccumulationData =  [
                'leave_type_id' => $appliedLeave->leave_type_id,
                'creator_id' => 1,
                'applied_leave_id' => $appliedLeave->id,
                'status' => '1',
                'comment' => 'Leave Approved',
                'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                'total_upper_limit' => $leaveAccumulation->total_upper_limit, //180
                'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                'previous_count' => $leaveAccumulation->total_remaining_count,
                'total_remaining_count' => $leaveAccumulation->total_remaining_count
            ];

            $currentYear = date("Y");
            $appliedLeaveOldData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$leaveApplier->id,'al.leave_type_id'=>$appliedLeave->leave_type_id])
                ->whereYear('al.updated_at',$currentYear)
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount"))
                ->first();

            if($appliedLeaveOldData->paidLeavesCount == ""){
                $appliedLeaveOldData->paidLeavesCount = 0;
            }
            /*
                Earlier leave pool was max yearly limit, but now we are changing it to total remaining count
            */
            // if(($leaveAccumulation->total_remaining_count-$appliedLeaveOldData->paidLeavesCount) >= $appliedLeave->number_of_days){
            //     $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            // }else{
            //     if($leaveAccumulation->total_remaining_count >= $appliedLeaveOldData->paidLeavesCount){
            //         $remaining = $leaveAccumulation->total_remaining_count - $appliedLeaveOldData->paidLeavesCount;
            //     }else{
            //         $remaining = 0;
            //     }

            //     if($remaining >= $appliedLeave->number_of_days || $remaining == 0){
            //         $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            //     }else{
            //         $appliedLeave->paid_leaves_count = $remaining;
            //         $appliedLeave->unpaid_leaves_count = $appliedLeave->number_of_days - $remaining;
            //     }
            // }
            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;

            $appliedLeave->save();

            $leaveAccumulation->status = '0';
            $leaveAccumulation->save();

            $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count;

            $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);
        }
    }

    public function checkLeaveApprovalOnAllLevels($appliedLeave)
    {
        $allSupervisors = $appliedLeave->appliedLeaveApprovals()->count();
        $allApprovedSupervisors = $appliedLeave->appliedLeaveApprovals()->where(['leave_status'=>'1'])->count();

        if($allSupervisors == $allApprovedSupervisors){
            $appliedLeave->final_status = '1';
            $appliedLeave->save();
        }

        return $appliedLeave;

    }

    public function sterlisationLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => '5',
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $finalApproverPriority = 5; //GM

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            $where['priority'] += 1;
            $nextApprover = LeaveApprovalAuthority::where($where)->first();

        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && empty($nextApproverPresent)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){
            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            $appliedLeave->save();

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);

        }
    }

    public function bloodDonationLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => '5',
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $finalApproverPriority = 5; //GM

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            $where['priority'] += 1;
            $nextApprover = LeaveApprovalAuthority::where($where)->first();

        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && empty($nextApproverPresent)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." "
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){
            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            $appliedLeave->save();

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);

        }
    }

    public function quarantineLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => '5',
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $finalApproverPriority = 5; //GM

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            $where['priority'] += 1;
            $nextApprover = LeaveApprovalAuthority::where($where)->first();

        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && empty($nextApproverPresent)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){
            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            $appliedLeave->save();

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);

        }
    }

    public function maternityLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => '3',
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $finalApproverPriority = 3; //HOD

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            $where['priority'] += 1;
            $nextApprover = LeaveApprovalAuthority::where($where)->first();

        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && empty($nextApproverPresent)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){
            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            $appliedLeave->save();

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);

        }
    }

    public function paternityLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => '3',
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $finalApproverPriority = 3; //HOD

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            $where['priority'] += 1;
            $nextApprover = LeaveApprovalAuthority::where($where)->first();

        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && empty($nextApproverPresent)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){
            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            $appliedLeave->save();

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);

        }
    }

    public function extraOrdinaryLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => '6',
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $finalApproverPriority = 6; //CMD

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            $where['priority'] += 1;
            $nextApprover = LeaveApprovalAuthority::where($where)->first();

        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && empty($nextApproverPresent)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){
            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            $appliedLeave->save();

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);

        }
    }

    public function restrictedHolidayLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => $currentAppliedLeaveApproval->priority + 1,
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

         $leaveAccumulation = $leaveApplier->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$appliedLeave->leave_type_id])
            ->orderBy('id','DESC')
            ->first();

        $finalApproverPriority = 3; //HOD

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
                $finalApproverPriority += 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            $where['priority'] += 1;
//            $nextApprover = LeaveApprovalAuthority::where($where)->first();

            if(($where['priority'] < 5)){
                $where['priority'] += 1;
                $nextApprover = LeaveApprovalAuthority::where($where)->first();
            }else{
                $nextApprover = 0;
            }
        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && empty($nextApproverPresent)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }
        elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            if($appliedLeave->final_status == '1'){
                $newAccumulationData =  [
                    'leave_type_id' => $appliedLeave->leave_type_id,
                    'creator_id' => 1,
                    'applied_leave_id' => $appliedLeave->id,
                    'status' => '1',
                    'comment' => 'Leave Rejected After Approval',
                    'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                    'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                    'previous_count' => $leaveAccumulation->total_remaining_count
                ];

                if($leaveAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count <= $leaveAccumulation->total_upper_limit){
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count + $appliedLeave->paid_leaves_count;
                }else{
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_upper_limit;
                    $newAccumulationData['comment'] = 'Leave Rejected After Approval and exceeding limit';
                }

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            }

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){
            $newAccumulationData =  [
                'leave_type_id' => $appliedLeave->leave_type_id,
                'creator_id' => 1,
                'applied_leave_id' => $appliedLeave->id,
                'status' => '1',
                'comment' => 'Leave Approved',
                'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                'total_upper_limit' => $leaveAccumulation->total_upper_limit, //2
                'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                'previous_count' => $leaveAccumulation->total_remaining_count
            ];

            $currentYear = date("Y");
            $appliedLeaveOldData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$leaveApplier->id,'al.leave_type_id'=>$appliedLeave->leave_type_id])
                ->whereYear('al.updated_at',$currentYear)
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount"))
                ->first();

            if($appliedLeaveOldData->paidLeavesCount == ""){
                $appliedLeaveOldData->paidLeavesCount = 0;
            }

            if(($leaveAccumulation->max_yearly_limit-$appliedLeaveOldData->paidLeavesCount) >= $appliedLeave->number_of_days){
                $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            }else{
                if($leaveAccumulation->max_yearly_limit >= $appliedLeaveOldData->paidLeavesCount){
                    $remaining = $leaveAccumulation->max_yearly_limit - $appliedLeaveOldData->paidLeavesCount;
                }else{
                    $remaining = 0;
                }

//                if($remaining >= $appliedLeave->number_of_days){
//                    $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
//                }else{
//                    $appliedLeave->paid_leaves_count = $remaining;
//                    $appliedLeave->unpaid_leaves_count = $appliedLeave->number_of_days - $remaining;
//                }

            }

            $appliedLeave->save();

            $leaveAccumulation->status = '0';
            $leaveAccumulation->save();

//            $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count - $appliedLeave->paid_leaves_count;
            $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count;

            $leaveApplier->leaveAccumulations()->create($newAccumulationData);

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);
        }
    }

    public function joiningLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => '3',
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $finalApproverPriority = 3; //HOD

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            $where['priority'] += 1;
            $nextApprover = LeaveApprovalAuthority::where($where)->first();

        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && empty($nextApproverPresent)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){
            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            $appliedLeave->save();

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);

        }
    }

    public function specialCasualLeaveApproval($data)
    {
        $currentApprover = $data['currentApprover'];
        $currentAppliedLeaveApproval = $data['currentAppliedLeaveApproval'];
        $leaveApplier = $data['leaveApplier'];
        $appliedLeave = $data['appliedLeave'];
        $where =  [
            'user_id' => $leaveApplier->id,
            'priority' => '3',
            //'leave_type_id' => $appliedLeave->leave_type_id,
            'status' => '1'
        ];

        $finalApproverPriority = 3; //HOD

        if($currentAppliedLeaveApproval->priority < $finalApproverPriority){
            $nextApprover = LeaveApprovalAuthority::where($where)->first();
        }else{
            $nextApprover = 0;
        }

        //If the nextApprover has applied for a leave OR the next reporting manager is same as previous reporting manager
        while(!empty($nextApprover) && (($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id) || ($currentAppliedLeaveApproval->supervisor_id == $nextApprover->supervisor_id))){

            if($currentAppliedLeaveApproval->user_id == $nextApprover->supervisor_id){
                $nextApproverAppliedLeave = 1;
            }else{
                $nextApproverAppliedLeave = 0;
            }

            $where['priority'] += 1;
            $nextApprover = LeaveApprovalAuthority::where($where)->first();

        }

        $checkApproverData = [];

        if(empty($nextApprover)){
            $checkApproverData['supervisor_id'] = 0;
        }else{
            $checkApproverData['supervisor_id'] = $nextApprover->supervisor_id;
        }

        $nextApproverPresent = $appliedLeave->appliedLeaveApprovals()->where($checkApproverData)->first();

        if(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && empty($nextApproverPresent)){
            $nextApprovalData = [
                'user_id' => $currentAppliedLeaveApproval->user_id,
                'supervisor_id' => $nextApprover->supervisor_id,
                'priority' => $nextApprover->priority,
                'leave_status' => '0',
            ];

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $nextApprover->supervisor_id,
                'label' => 'Leave Application',
                'status' => '1',
                'read_status' => '0',
                'message' => $leaveApplier->first_name." ".$leaveApplier->middle_name." ".$leaveApplier->last_name." has applied for a leave."
            ];

            $appliedLeave->appliedLeaveApprovals()->create($nextApprovalData);
            $appliedLeave->notifications()->create($notificationData);

        }elseif(empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1'){  //finally approved

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif(!empty($nextApprover) && $currentAppliedLeaveApproval->leave_status == '1' && !empty($nextApproverPresent)){

            $appliedLeave = $this->checkLeaveApprovalOnAllLevels($appliedLeave);

        }elseif($currentAppliedLeaveApproval->leave_status == '2'){

            $appliedLeave->final_status = '0';
            $appliedLeave->paid_leaves_count = '0';
            $appliedLeave->unpaid_leaves_count = '0';
            $appliedLeave->compensatory_leaves_count = '0';
            $appliedLeave->save();

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        if($appliedLeave->final_status == '1'){
            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
            $appliedLeave->save();

            $notificationData = [
                'sender_id' => $currentAppliedLeaveApproval->supervisor_id,
                'receiver_id' => $appliedLeave->user_id,
                'label' => 'Leave Approved',
                'status' => '1',
                'read_status' => '0',
                'message' => "Your leave has been approved."
            ];

            $appliedLeave->notifications()->create($notificationData);

        }
    }

}//end of class

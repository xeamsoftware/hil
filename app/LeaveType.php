<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class LeaveType extends Model
{
    protected $guarded = [];

    public function leaveAccumulations()
    {
        return $this->hasMany('App\LeaveAccumulation');
    }

    public function compensatoryLeaves()
    {
        return $this->hasMany('App\CompensatoryLeave');
    }

    public function leaveTypeLimits()
    {
        return $this->hasMany('App\LeaveTypeLimit');
    }

    static function leaveTypeWiseLeaveAccumulation($leaveTypeId, $user = NULL)
    {
        $pendingLeaveData = "";
        if(!isset($user)) {
            $user = Auth::user();
        }
        $leaveAccumulation = $user->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$leaveTypeId])
            ->orderBy('id','DESC')
            ->first();

        $currentYear = date("Y");
        $currentMonth = date("m");

        if($leaveTypeId == 4){  //compensatory leave

            $appliedLeaveData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$user->id,'al.leave_type_id'=>$leaveTypeId])
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount,SUM(al.unpaid_leaves_count) as unpaidLeavesCount,SUM(al.compensatory_leaves_count) as compensatoryLeavesCount"))
                ->first();

        }elseif($leaveTypeId == 16){ //extra leave type
            $appliedLeaveData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$user->id,'al.leave_type_id'=>$leaveTypeId])
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount,SUM(al.unpaid_leaves_count) as unpaidLeavesCount"))
                ->first();
        }elseif($leaveTypeId == 14){  //short leave
            $appliedLeaveData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.user_id'=>$user->id,'al.leave_type_id'=>$leaveTypeId])
                ->whereYear('al.updated_at',$currentYear)
                ->whereMonth('al.updated_at',$currentMonth)
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount,SUM(al.unpaid_leaves_count) as unpaidLeavesCount,SUM(al.compensatory_leaves_count) as compensatoryLeavesCount"))
                ->first();
        }elseif($leaveTypeId != 4 && $leaveTypeId != 14){
            $appliedLeaveData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$user->id,'al.leave_type_id'=>$leaveTypeId])
                ->whereYear('al.updated_at',$currentYear)
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount,SUM(al.unpaid_leaves_count) as unpaidLeavesCount,SUM(al.compensatory_leaves_count) as compensatoryLeavesCount"))
                ->first();

        }elseif($leaveTypeId != 16 && $leaveTypeId != 14){
            $appliedLeaveData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$user->id,'al.leave_type_id'=>$leaveTypeId])
                ->whereYear('al.updated_at',$currentYear)
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount,SUM(al.unpaid_leaves_count) as unpaidLeavesCount"))
                ->first();
        }

        $pendingLeaveData=DB::table('applied_leave_approvals as ala')
            ->Join('applied_leaves as al','al.id','=','ala.applied_leave_id')
            ->where(['ala.leave_status' =>'0','ala.user_id'=>$user->id,'al.leave_type_id'=>$leaveTypeId,'al.status'=>'1'])
            ->select('al.number_of_days')
            ->sum('al.number_of_days');

        if($appliedLeaveData->paidLeavesCount == ""){
            $appliedLeaveData->paidLeavesCount = 0;
        }

        if($appliedLeaveData->unpaidLeavesCount == ""){
            $appliedLeaveData->unpaidLeavesCount = 0;
        }

        if(!isset($appliedLeaveData->compensatoryLeavesCount)){
            $appliedLeaveData->compensatoryLeavesCount = 0;
        }
        if(!empty($leaveAccumulation)){
            $result['status'] = true;
            $result['allow'] = true;

            $result['totalRemainingCount'] = $leaveAccumulation->total_remaining_count;
            if($leaveTypeId == 16){
                $pendingLeaveData = $pendingLeaveData;
            }
            $result['processingLeavesCount'] = $pendingLeaveData;
            $result['maxYearlyLimit'] = $leaveAccumulation->max_yearly_limit;
            $result['yearlyLeavesTaken'] = $appliedLeaveData->paidLeavesCount + $appliedLeaveData->unpaidLeavesCount;

            if(($leaveAccumulation->max_yearly_limit != 'NA') && ($leaveTypeId != 1) && ($leaveAccumulation->max_yearly_limit >= $result['yearlyLeavesTaken'])){
                $result['yearlyBalanceLeaves'] = $leaveAccumulation->max_yearly_limit;
            }elseif($leaveTypeId == '4' || $leaveTypeId == '16'){
                $result['yearlyBalanceLeaves'] = 'NA';
            }elseif($leaveTypeId == '14'){
                $result['yearlyBalanceLeaves'] = 'NA';
            }elseif($result['totalRemainingCount'] < '0' && $leaveTypeId == '1') {
                $result['totalRemainingCount'] = 0;
                $result['yearlyBalanceLeaves'] = $result['maxYearlyLimit'];
            }elseif($leaveTypeId == 1){
                $result['yearlyBalanceLeaves'] = $result['maxYearlyLimit'];
                //$result['yearlyBalanceLeaves'] = $result['totalRemainingCount'];
            }
            else{
                $result['yearlyBalanceLeaves'] = $leaveAccumulation->max_yearly_limit;
            }


        }else{
            $result['status'] = false;

            if(in_array($leaveTypeId, [1,2,3,4,11,12,14,16])){
                $result['allow'] = false;
            }else{
                $result['allow'] = true;
            }

        }

        return $result;
    }

    private function checkELType($user, $totalNumberOfDays){
        if($user->employee_type == 'M&S' && $totalNumberOfDays >= 12){
//            return "You can encash only 12 leaves in a year";
            return "Your slot of 12 is filled. You can not applied more leave for now.";
        }elseif($user->employee_type == 'Workman' && $totalNumberOfDays >= 15){
//            return "You can encash only 15 leaves in a year";
            return "Your slot of 15 is filled. You can not applied more leave for now.";
        }
    }
    public function leaveTypeYearlyChecks($user, $leaveTypeId, $numberOfDays, $encashmentStatus=0, $fromDate = NULL, $toDate = NULL)
    {
        $leaveError = 0;
        $currentYear = date("Y");

        $appliedLeaveData = DB::table('applied_leaves as al')
            ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$user->id,'al.leave_type_id'=>$leaveTypeId])
            ->whereYear('al.to_date',$currentYear)
            ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount"))
            ->first();

        $fromDate = date("Y-m-d",strtotime($fromDate));
        $toDate = date("Y-m-d",strtotime($toDate));
        $alreadyAppliedLeave = AppliedLeave::whereDate('from_date', $fromDate)->whereDate('to_date', $toDate)->where(['user_id' => $user->id, 'status' => '1'])->first();
        if(isset($alreadyAppliedLeave)){
            $leaveError = "Leave is already applied for these dates.";
        }

        $currentLeaveAccumulation = $user->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$leaveTypeId])
            ->orderBy('id','DESC')
            ->first();

        if(!empty($currentLeaveAccumulation)){
            $maxYearlyLimit = $currentLeaveAccumulation->max_yearly_limit;
            $totalRemainingCount = $currentLeaveAccumulation->total_remaining_count;
        }else{
            $maxYearlyLimit = 0;
            $totalRemainingCount = 0;
        }

        if($appliedLeaveData->paidLeavesCount == ""){
            $appliedLeaveData->paidLeavesCount = 0;
        }


        if($leaveTypeId == 8 || $leaveTypeId == 9){  //Maternity and Paternity

            if(($appliedLeaveData->paidLeavesCount + $numberOfDays) <= 180){

            }else{
                $remaining = 180 - $appliedLeaveData->paidLeavesCount;

                if($leaveTypeId == 8){
                    $leaveError = "You have only ".$remaining." day(s) of maternity leaves left this year.";
                }else{
                    $leaveError = "You have only ".$remaining." day(s) of paternity leaves left this year.";
                }

            }

        }

        elseif($leaveTypeId == 7){  //Quarantine leaves

            if(($appliedLeaveData->paidLeavesCount+$numberOfDays) <= 15){

            }else{
                $remaining = 15 - $appliedLeaveData->paidLeavesCount;
                $leaveError = "You have only ".$remaining." day(s) of quarantine leaves left this year.";
            }

        }

        elseif($leaveTypeId == 16){  //Call Of Extra Duty leaves

            if(($numberOfDays) <= $totalRemainingCount){

            }else{
                $leaveError = "You have only ".$totalRemainingCount." day(s) of Call Of Extra Duty leaves left this year.";
            }

        }

        elseif($leaveTypeId == 12) {  //Restricted Holiday

            $fromDate = date('Y-m-d', strtotime($fromDate));
            $holiday = Holiday::where('from_date', $fromDate)->first();
            if(!isset($holiday) || $holiday == ''){
                $leaveError = 'No restricted holiday leave found on given date.';
            }

            if ($currentLeaveAccumulation->total_remaining_count < $numberOfDays) {
                if($currentLeaveAccumulation->total_remaining_count == 0){
                    $leaveError = 'You have no restricted holiday leaves left this year.';
                }else{
                    $leaveError = 'You have only ' . $currentLeaveAccumulation->total_remaining_count . ' day(s) of restricted holiday leaves left this year.';
                }
            }
        }

        elseif($leaveTypeId == 5) {  //Sterlisation Leaves

            if($user->userProfile->gender == "Male"){
                $limit = 9;
            }else{
                $limit = 14;
            }

            if(($appliedLeaveData->paidLeavesCount+$numberOfDays) <= $limit){

            }else{
                $remaining = $limit - $appliedLeaveData->paidLeavesCount;
                $leaveError = "You have only ".$remaining." day(s) of sterlisation leaves left this year.";
            }

        }

        elseif($leaveTypeId == 1){  //Casual Leaves

            if(($appliedLeaveData->paidLeavesCount+$numberOfDays) <= $maxYearlyLimit){
            }else{
                $remaining = $maxYearlyLimit - $appliedLeaveData->paidLeavesCount;
                $leaveError = "You have only ".$remaining." day(s) of casual leaves left this year.";
            }

            if(($numberOfDays) <= $totalRemainingCount){
            }else{
                $leaveError = "You have only ".$totalRemainingCount." day(s) of casual leaves left this year.";
            }

        }


        elseif($leaveTypeId == 3 || $leaveTypeId == 11) {
            //Encash = 11 and Non-Encash = 3 Leaves
            if($user->employee_type == 'M&S' || $user->employee_type == 'Workman') {
                $appliedLeaveCount = DB::table('applied_leaves as al')
                    ->where(['al.status' => '1', 'al.user_id' => $user->id])
                    ->whereIn('al.leave_type_id', ['11', '3'])
                    ->whereYear('al.from_date', $currentYear)->count();

//                $totalNumberOfDays = $numberOfDays + $appliedLeaveCount;
                $totalNumberOfDays = $appliedLeaveCount + 1;
                if (isset($appliedLeaveCount) && $totalNumberOfDays > 0) {
                    if($leaveTypeId == 11){

                        if($encashmentStatus==1){
                            //$encashmentStatus==1 is encash where user can encash max 15 leaves per year
                            $leaveError = $this->checkELType($user, $totalNumberOfDays);
                        }
                        elseif($encashmentStatus==0){
                            //if status is 1, then user can take as many leaves available in quota, restricted to 15 times per year, termed as slot, lets calculate slots for the given year
                            $leaveSlotCount = DB::table('applied_leaves')
                                ->where([
                                    'user_id'=>$user->id,
                                    'leave_type_id' => 11,
                                    'encashment_status' => 0,
                                    'final_status' => '1',
                                ])
                                ->whereYear('from_date', $currentYear)
                                ->count();
                            if($leaveSlotCount>=15){
                                $leaveError = "Slot of 15 leaves cannot be exeeded";
                            }
                        }
                    }
                    else{
                        return  $leaveError = $this->checkELType($user, $totalNumberOfDays);
                    }
                }
            }


            $leaveAccumulation = $this->leaveTypeWiseLeaveAccumulation($leaveTypeId, $user);

            $yearlyBalanceLeave = $leaveAccumulation['yearlyBalanceLeaves'];

            if($yearlyBalanceLeave < $numberOfDays){
                return $leaveError = "You have only " . "$yearlyBalanceLeave" . " day(s) of EL-Encashable leaves left as of now.";
            }

            //As per pankaj rawat [tester]

//            if($leaveAccumulation['yearlyBalanceLeaves'] < $numberOfDays) {
//                $nonEncashedLeaveCount = 0;
//                $nonEncashedLeave = LeaveAccumulation::where('user_id', $user->id)->where('leave_type_id', 3)->latest()->first();
//                if ($nonEncashedLeave) {
//                    $nonEncashedLeaveCount = $nonEncashedLeave->total_remaining_count;
//                }
//
//
//                if($user->retirement_date) {
//                    $sixMonthBeforeDate = Carbon::parse(date('Y-m-d'))->subMonth('6')->format('Y-m-d');
//                   if(strtotime($sixMonthBeforeDate) <= strtotime($user->retirement_date)){
//                      $totalLeaveAvailable =  $leaveAccumulation['totalRemainingCount'] + $nonEncashedLeaveCount + $leaveAccumulation['yearlyBalanceLeaves'];
//                       $remaining = ($totalLeaveAvailable * 2) / 3;
//                       if($remaining < $numberOfDays){
//                           return $leaveError = "You have only " . $remaining . " day(s) of EL-Encashable leaves left as of now.";
//                       }
//                   }
//                }else {
//
//                    $remainingAccumulateLeave = 0;
//                    if ($leaveAccumulation['totalRemainingCount'] + $nonEncashedLeaveCount + $leaveAccumulation['yearlyBalanceLeaves'] > 285) {
//                        $remainingAccumulateLeave = $leaveAccumulation['totalRemainingCount'] + $nonEncashedLeaveCount - 285;
//                    } else {
//
//                        return $leaveError = "You have only " . $yearlyBalanceLeave . " day(s) of EL-Encashable leaves left as of now.";
//                    }
//
//                    $remaining = $leaveAccumulation['yearlyBalanceLeaves'] + $remainingAccumulateLeave;
//                    if ($remaining == 0) {
//                        $leaveError = "You have only " . $remaining . " day(s) of EL-Encashable leaves left as of now.";
//                    } elseif ($remaining < $numberOfDays) {
//                        $leaveError = "You have only " . $remaining . " day(s) of EL- Encashable leaves left as of now.";
//                    } else {
//
//                    }
//                }
//            }elseif($leaveAccumulation['totalRemainingCount'] > $numberOfDays){
//
//
//            }
        }

        return $leaveError;
    }

}//end of class

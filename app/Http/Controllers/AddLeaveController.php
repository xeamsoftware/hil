<?php

namespace App\Http\Controllers;

use App\AppliedLeave;
use App\LeaveType;
use App\Unit;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class AddLeaveController extends Controller
{

    public function addLeave()
    {
        $data['leaveTypes'] = LeaveType::where(['status'=>'1'])->get();
        $user = Auth::user();

        $unitIds = $user->userUnits->pluck('unit_id')->toArray();

        $unit = new Unit;
        $data['employees'] = $unit->unitWiseEmployees($unitIds);

        $gender = $user->userProfile->gender;
        return view('leaves.add_leave')->with(['data'=>$data,'user'=>$user,'gender'=>$gender]);
    }

    public function saveLeave(Request $request){
        $request->validate([
            'toDate' => 'bail|required_if:halfLeave,==,0',
            'fromDate' => 'bail|required',
            'purpose' => 'bail|required',
            'leaveTypeId' => 'bail|required',
            'numberOfDays' => 'bail|required',
        ]);
        $userId = $request->user_id;

        if($request->numberOfDays == 0){
            $daysError = "Please select the dates carefully. Number of days should not be zero.";
            return redirect()->back()->with('leaveError',$daysError);
        }

//        DB::beginTransaction();
        $user = User::where(['id' => $userId])->with(['userProfile:id,user_id,gender','userSupervisor.supervisor:id,first_name,middle_name,last_name,personal_mobile_number'])->first();
        if(empty($user->userSupervisor)){
            $supervisorError = "You do not have a supervisor. You cannot apply for leave.";
            return redirect()->back()->with('leaveError',$supervisorError);
        }

        $leaveType = new LeaveType();

        $yearlyChecksError = $leaveType->leaveTypeYearlyChecks($user,$request->leaveTypeId,
            $request->numberOfDays, $request->encashmentStatus);
        //exit;
        if($yearlyChecksError){
            return redirect()->back()->with('leaveError', "You cannot apply for leave, You don't have  exceeding leave. " . $yearlyChecksError);
        }

        $previousAppliedLeave = new AppliedLeave();
        //$checkPreviousLeaveApprovalPending = $previousAppliedLeave->checkPreviousLeaveApprovalPending($user->id);

        // if(!empty($checkPreviousLeaveApprovalPending)){
        //     $pendingError = "The approval status of your previously applied leave is pending with one or more authorities. Please contact the concerned person and clear it first.";
        //     return redirect()->back()->with('leaveError',$pendingError);
        // }


        if(empty($request->fromTime)){
            $fromTime = "";
        }else{
            $fromTime = $request->fromTime;
        }

        if(empty($request->toTime)){
            $toTime = "";
        }else{
            $toTime = $request->toTime;
        }

        $checkUniqueDates = [
            'fromDate' => date("Y-m-d",strtotime($request->fromDate)),
            'userId' => $user->id,
            'leaveTypeId' => $request->leaveTypeId
        ];

        if($request->leaveTypeId == 1 && $request->halfLeave == 1){   //Casual Leaves
            $checkUniqueDates['toDate'] = $checkUniqueDates['fromDate'];

        }
        elseif($request->leaveTypeId == 14){ //Short Leaves
            $checkUniqueDates['toDate'] = $checkUniqueDates['fromDate'];

        }
        else{
            $checkUniqueDates['toDate'] = date("Y-m-d",strtotime($request->toDate));
        }

        // $checkLeaveUniqueDates = $previousAppliedLeave->checkLeaveUniqueDates($checkUniqueDates);

        // if(!empty($checkLeaveUniqueDates)){

        //     $uniqueError = "You have already applied for leave on the given dates.";
        //     return redirect()->back()->with('leaveError',$uniqueError);
        // }

        $leave_type_id = (int)$request->leaveTypeId;
        if(!in_array($leave_type_id, [1,4])){
            $previousLeaveDate = date('Y-m-d',(strtotime('-1 day',strtotime($checkUniqueDates['fromDate']))));
            $clubbedWithCl = AppliedLeave::where(['user_id'=>$user->id,'to_date'=>$previousLeaveDate,'status'=>'1','leave_type_id'=>1])->first();

            if(!empty($clubbedWithCl)){
                $previousLeaveDate = date('d/m/Y',strtotime($previousLeaveDate));
                $clubbedWithClError = "You have already applied for a casual leave on ".$previousLeaveDate." .And casual leave cannot be clubbed with any other leave except compensatory leave.";
                return redirect()->back()->with('leaveError',$clubbedWithClError);
            }
        }

        $appliedLeaveData = [
            'user_id' => $user->id,
            'leave_type_id' => $request->leaveTypeId,
            'from_date' => $checkUniqueDates['fromDate'],
            'to_date' => $checkUniqueDates['toDate'],
            'from_time' => $fromTime,
            'to_time' => $toTime,
            'number_of_days' => $request->numberOfDays,
            'weekoffs' => $request->weekoffs,
            'excluded_dates' => $request->excludedDates,
            'paid_leaves_count' => $request->numberOfDays,
            'unpaid_leaves_count' => '0',
            'compensatory_leaves_count' => '0',
            'purpose' => $request->purpose,
            'status' => '1',
            'final_status' => '1'
        ];

        if(!empty($request->encashmentStatus)){
            $appliedLeaveData['encashment_status'] = $request->encashmentStatus;
        }else{
            $appliedLeaveData['encashment_status'] = false;
        }

        if(!empty($request->firstSecondHalf) && !empty($request->halfLeave) && $request->halfLeave == '1'){
            $appliedLeaveData['leave_half'] = $request->firstSecondHalf;
        }

        if(!empty($request->fullHalfPay) && $request->leaveTypeId == 2){ //HPSL
            $appliedLeaveData['pay_status'] = $request->fullHalfPay;
        }

        if($request->leaveTypeId == 11){
            $appliedLeaveData['to_date'] = "";
        }

        $appliedLeave = AppliedLeave::create($appliedLeaveData);

        $leaveAccumulation = $user->leaveAccumulations()->where(['status'=>'1','leave_type_id'=>$request->leaveTypeId])->orderBy('id','DESC')->first();

        $leaveAccumulation->total_remaining_count = $leaveAccumulation->total_remaining_count - $request->numberOfDays;

        //have removed no enchashable from here below 25-05-2021
        if($request->leaveTypeId == 11){
            $leaveAccumulation->max_yearly_limit = $leaveAccumulation->max_yearly_limit - $request->numberOfDays;
        }
        $leaveAccumulation->save();

        if(!empty($request->newAllDatesArray)){
            $newAllDatesArray = explode(",",$request->newAllDatesArray);
            $monthWiseArray = [];
            $counter = 0;
            $key2 = 0;
            $daysCounter = 0;
            foreach($newAllDatesArray as $key => $value) {

                if($counter == 0){
                    $monthWiseArray[$key2]['fromDate'] = $value;
                    $monthWiseArray[$key2]['noDays'] = ++$daysCounter;

                    $prevMonthYear = date("m-Y",strtotime($value));
                    $prevDate = $value;

                    if(count($newAllDatesArray) == 1){
                        $monthWiseArray[$key2]['toDate'] = $value;
                    }
                }else{
                    $monthYear = date("m-Y",strtotime($value));

                    if($monthYear == $prevMonthYear){
                        $prevMonthYear = date("m-Y",strtotime($value));
                        $prevDate = $value;
                        $monthWiseArray[$key2]['toDate'] = $value;
                        $monthWiseArray[$key2]['noDays'] = ++$daysCounter;

                    }else{
                        $monthWiseArray[$key2]['toDate'] = $prevDate;

                        $key2++;
                        $daysCounter = 0;
                        $monthWiseArray[$key2]['fromDate'] = $value;
                        $monthWiseArray[$key2]['noDays'] = ++$daysCounter;
                        $prevMonthYear = date("m-Y",strtotime($value));
                        $prevDate = $value;
                        if((count($newAllDatesArray)-1) == $counter){
                            $monthWiseArray[$key2]['toDate'] = $value;
                        }
                    }
                }
                $counter++;

            }//end of foreach

            foreach ($monthWiseArray as $key => $value) {
                $segregationData =  [
                    'from_date' => date("Y-m-d",strtotime($value['fromDate'])),
                    'to_date' => date("Y-m-d",strtotime($value['toDate'])),
                    'number_of_days' => $value['noDays']
                ];

                $appliedLeave->appliedLeaveSegregations()->create($segregationData);
            }
        }


        if($request->toDate == '' && $request->leaveTypeId == 11 ){


            $segregationData =  [
                'from_date' => date("Y-m-d",$request->toDate),
                'to_date' => "",
                'number_of_days' => $request->numberOfDays
            ];
            // return $segregationData;
            $appliedLeave->appliedLeaveSegregations()->create($segregationData);
        }


        if($request->leaveTypeId == 4 || $request->leaveTypeId == 16 ){

            $segregationData =  [
                // 'from_date' => date("Y-m-d",$request->fromDate),
                // 'to_date' => date("Y-m-d",$request->toDate),
                'number_of_days' => $request->numberOfDays
            ];
            // return $segregationData;
            $appliedLeave->appliedLeaveSegregations()->create($segregationData);
        }

        $appliedLeaveApprovalData = [
            'user_id' => $user->id,
            'supervisor_id' => Auth::id(),
            'priority' => '1',
            'leave_status' => '1'
        ];
        $appliedLeaveApproval = $appliedLeave->appliedLeaveApprovals()->create($appliedLeaveApprovalData);
        $notificationData = [
            'sender_id' => $user->id,
            'receiver_id' => Auth::id(),
            'label' => 'Leave Application',
            'status' => '1',
            'read_status' => '0',
            'message' => $user->first_name." ".$user->middle_name." ".$user->last_name." has applied for a leave."
        ];

        $appliedLeave->notifications()->create($notificationData);
        if(!empty($request->leaveDocuments)){
            foreach ($request->leaveDocuments as $doc) {
                $document = round(microtime(true)).str_random(5).'.'.$doc->getClientOriginalExtension();
                $doc->move(config('constants.uploadPaths.uploadAppliedLeaveDocument'), $document);
                $appliedLeave->appliedLeaveDocuments()->create(['document_name'=>$document]);
            }
        }

        $supervisor = User::find(Auth::id()); //OR $user->userSupervisor->supervisor()->first();
        $message = $notificationData['message'];
        $sendSms = $supervisor->sendSms($supervisor,$message);

        if($sendSms){
            return redirect()->back()->with('leaveSuccess',$notificationData['message']);
        }
//        DB::commit();
        return redirect()->route('leaves.listAppliedLeaves');
    }
}

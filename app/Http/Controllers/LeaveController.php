<?php

namespace App\Http\Controllers;

use App\LeaveAccumulation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Eloquent;
use Auth;
use Illuminate\Support\Facades\Response;
use View;
use DB;
use App\LeaveType;
use App\Holiday;
use App\AppliedLeave;
use App\User;
use App\Session;
use App\AppliedLeaveApproval;
use App\CompensatoryLeaveApproval;
use App\CompensatoryLeave;
use App\Unit;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function applyLeave()
    {

        $data['leaveTypes'] = LeaveType::where(['status'=>'1'])->get();
        $user = Auth::user();
        $supervisors = [];

        if(!empty($user->userSupervisor)){
            $supervisors[0] = $user->userSupervisor->supervisor()->first();
        }

        if(!empty($user->otherSupervisor)){
            $supervisors[1] = $user->otherSupervisor->supervisor()->first();
        }

        $user->userSupervisor->supervisor()->first();

        $supervisors = collect($supervisors);
        $gender = $user->userProfile->gender;
        return view('leaves.applyLeaveForm')->with(['data'=>$data,'user'=>$user,'gender'=>$gender,'supervisors'=>$supervisors]);

    }

    public function leaveTypeWiseLeaveAccumulation(Request $request)
    {
        $leaveType = LeaveType::find($request->leaveTypeId);

        $result = $leaveType->leaveTypeWiseLeaveAccumulation($request->leaveTypeId);
        return $result;

    }

    public function holidaysBetweenLeaves(Request $request)
    {
        $holiday = new Holiday();
        $holidays = $holiday->holidaysBetweenLeaves($request->all());

        return $holidays;
    }

    public function saveAppliedLeave(Request $request)
    {
        $request->validate([
            'toDate' => 'bail|required_if:halfLeave,==,0',
            'fromDate' => 'bail|required',
            'purpose' => 'bail|required',
            'address' => 'bail|required',
            'leaveTypeId' => 'bail|required',
            'numberOfDays' => 'bail|required',
            'supervisor' => 'bail|required'
        ]);
        $todate = $request->toDate ?? $request->fromDate;
        $datetime1 = strtotime($todate); // convert to timestamps
        $datetime2 = strtotime(date('Y-m-d')); // convert to timestamps
        $days = (int)(($datetime2 - $datetime1)/86400);

        if($days > 7){
            //$daysError = "You cannot apply leave after 7 days of absent. Please contact Admin department";
            //return redirect()->back()->with('leaveError',$daysError);
        }

        if($request->numberOfDays == 0){
            $daysError = "Please select the dates carefully. Number of days should not be zero.";
            return redirect()->back()->with('leaveError',$daysError);
        }

        $user = User::where(['id'=>Auth::id()])->with(['userProfile:id,user_id,gender','userSupervisor.supervisor:id,first_name,middle_name,last_name,personal_mobile_number'])->first();
        if(empty($user->userSupervisor)){
            $supervisorError = "You do not have a supervisor. You cannot apply for leave.";
            return redirect()->back()->with('leaveError',$supervisorError);
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
            $checkUniqueDates['toDate'] = date("Y-m-d",strtotime($todate));
        }

        $leaveType = new LeaveType();



        $yearlyChecksError = $leaveType->leaveTypeYearlyChecks($user, $request->leaveTypeId, $request->numberOfDays, NULL, $request->fromDate, $checkUniqueDates['toDate']);
        if($yearlyChecksError){
//            return redirect()->back()->with('leaveError', "You cannot apply for leave , You Don't have  Exceeding leave .");
            return redirect()->back()->with('leaveError', $yearlyChecksError);

        }

        $previousAppliedLeave = new AppliedLeave();

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



        $leave_type_id = (int)$request->leaveTypeId;
        if(!in_array($leave_type_id, [1,4,12])){
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
            'paid_leaves_count' => '0',
            'unpaid_leaves_count' => '0',
            'compensatory_leaves_count' => '0',
            'purpose' => $request->purpose,
            'address' => $request->address,
            'status' => '1',
            'final_status' => '0'
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

        if($request->leaveTypeId == 11 && $request->encashmentStatus == '1'){
            $appliedLeaveData['to_date'] = "";
        }

        $appliedLeave = AppliedLeave::create($appliedLeaveData);

        if(!in_array($request->leaveTypeId,  ['15', '6', '5', '7', '10'])) {

            $leaveAccumulation = $user->leaveAccumulations()->where(['status' => '1', 'leave_type_id' => $request->leaveTypeId])->orderBy('id', 'DESC')->first();


            $leaveAccumulation->total_remaining_count = $leaveAccumulation->total_remaining_count - $request->numberOfDays;

            //have removed no enchashable from here below 25-05-2021
            if ($request->leaveTypeId == 11) {
                $leaveAccumulation->max_yearly_limit = $leaveAccumulation->max_yearly_limit - $request->numberOfDays;
            }
            $leaveAccumulation->save();
        }

        // return $appliedLeaveData;
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
            'supervisor_id' => $request->supervisor,
            'priority' => '1',
            'leave_status' => '0'
        ];
        $appliedLeaveApproval = $appliedLeave->appliedLeaveApprovals()->create($appliedLeaveApprovalData);
        $notificationData = [
            'sender_id' => $user->id,
            'receiver_id' => $request->supervisor,
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

        $supervisor = User::find($request->supervisor); //OR $user->userSupervisor->supervisor()->first();
        $message = $notificationData['message'];
        $sendSms = $supervisor->sendSms($supervisor,$message);

        if($sendSms){
            return redirect()->back()->with('leaveSuccess',$notificationData['message']);
        }
        return redirect()->route('leaves.listAppliedLeaves');
    }

    public function listAppliedLeaves()
    {
        $user = Auth::user();
        $data = $user->appliedLeavesList($user->id);

        return view('leaves.listAppliedLeaves')->with(['data' => $data]);
    }

    public function cancelAppliedLeave($appliedLeaveId)
    {

        $appliedLeave = AppliedLeave::find($appliedLeaveId);
        $firstApproval = $appliedLeave->appliedLeaveApprovals()->orderBy('priority')->first();

        if($firstApproval->leave_status == 0){
            $appliedLeave->final_status = '0';
            $appliedLeave->status = '0';
            $appliedLeave->save();

            if(!in_array($appliedLeave->leave_type_id,  ['15', '6', '5', '7', '10'])) {
                $leaveAccumulation = $appliedLeave->user->leaveAccumulations()->where(['status' => '1', 'leave_type_id' => $appliedLeave->leave_type_id])->orderBy('id', 'DESC')->first();
                $numberOfDays = $appliedLeave->number_of_days;
                $leaveAccumulation->total_remaining_count = $leaveAccumulation->total_remaining_count + $numberOfDays;
                //removed EL non encashable leaves frombelow check
                if ($appliedLeave->leave_type_id == 11 || $appliedLeave->leave_type_id == 2) {
                    $leaveAccumulation->max_yearly_limit = $leaveAccumulation->max_yearly_limit + $appliedLeave->number_of_days;
                }
                $leaveAccumulation->save();
            }


            return redirect()->back();
        }else{
            return redirect()->back()->with('cannotCancelError','One or more authorities have taken a decision. You cannot cancel your leave now.');
        }

    }

    public function saveAppliedLeaveApproval(Request $request)
    {
//        \Illuminate\Support\Facades\DB::beginTransaction();
        $request->validate([
            'comment' => 'bail|required',
        ]);

        $currentApprover = Auth::user();
        $currentAppliedLeaveApproval = AppliedLeaveApproval::find($request->appliedLeaveApprovalId);
        $leaveApplier = $currentAppliedLeaveApproval->user()->first();
        $currentAppliedLeaveApproval->leave_status = $request->leaveStatus;
        $currentAppliedLeaveApproval->save();


        $appliedLeave = $currentAppliedLeaveApproval->appliedLeave()->first();
        $appliedLeave->status = $request->leaveStatus;
        $appliedLeave->save();

        $appliedLeaveFinalStatusForRejectAfterApproval = $appliedLeave->final_status;
        $appliedLeaveStatusForApproveAfterRejection = $appliedLeave->status;


        $notificationData=[
            'sender_id' => $currentApprover->id,
            'receiver_id' => $leaveApplier->id,
            'label' => 'Leave Comments',
            'status' => '1',
            'read_status' => '0',
            'message' => $request->comment
        ];

        $appliedLeave->notifications()->create($notificationData);

        $data['currentApprover'] = $currentApprover;
        $data['currentAppliedLeaveApproval'] = $currentAppliedLeaveApproval;
        $data['leaveApplier'] = $leaveApplier;
        $data['appliedLeave'] = $appliedLeave;
        $data['status'] = $request->leaveStatus;

        if($appliedLeave->leave_type_id == 1){
            $currentAppliedLeaveApproval->casualLeaveApproval($data);
        }
        elseif($appliedLeave->leave_type_id == 2){
            $currentAppliedLeaveApproval->halfPaySickLeaveApproval($data);
        }
        elseif($appliedLeave->leave_type_id == 3){
            $currentAppliedLeaveApproval->elNonEnCashableLeaveApprovals($data);
        }
        elseif($appliedLeave->leave_type_id == 4){
            $currentAppliedLeaveApproval->compensatoryLeaveApprovals($data);

        }
        elseif($appliedLeave->leave_type_id == 5){
            $currentAppliedLeaveApproval->sterlisationLeaveApproval($data);

        }
        elseif($appliedLeave->leave_type_id == 6){
            $currentAppliedLeaveApproval->bloodDonationLeaveApproval($data);

        }
        elseif($appliedLeave->leave_type_id == 7){
            $currentAppliedLeaveApproval->quarantineLeaveApproval($data);

        }
        elseif($appliedLeave->leave_type_id == 8){
            $currentAppliedLeaveApproval->maternityLeaveApproval($data);

        }
        elseif($appliedLeave->leave_type_id == 9){
            $currentAppliedLeaveApproval->paternityLeaveApproval($data);
        }
        elseif($appliedLeave->leave_type_id == 10){
            $currentAppliedLeaveApproval->extraOrdinaryLeaveApproval($data);
        }
        elseif($appliedLeave->leave_type_id == 11){
            $currentAppliedLeaveApproval->elEnCashableLeaveApprovals($data);
        }
        elseif($appliedLeave->leave_type_id == 12){
             $currentAppliedLeaveApproval->restrictedHolidayLeaveApproval($data);
        }
        elseif($appliedLeave->leave_type_id == 13){
            $currentAppliedLeaveApproval->joiningLeaveApproval($data);
        }
        elseif($appliedLeave->leave_type_id == 14){
            $currentAppliedLeaveApproval->shortLeaveApproval($data);
        }
        elseif($appliedLeave->leave_type_id == 15){
            $currentAppliedLeaveApproval->specialCasualLeaveApproval($data);

        }
        elseif($appliedLeave->leave_type_id == 16){
            $currentAppliedLeaveApproval->extraDutyLeaveApprovals($data);
        }



        if($appliedLeave->leave_type_id != 16) {
            //below checks are common for all leave types
            if ($request->leaveStatus == '2' && $appliedLeaveFinalStatusForRejectAfterApproval == '0') {
                //This code ensure direct reject case, after approval reject  cases are handled in their respective functions above, final status 0 ensure its not approved one i.e direct case
                $leaveAccumulation = $appliedLeave->user->leaveAccumulations()->where(['status' => '1', 'leave_type_id' => $appliedLeave->leave_type_id])->orderBy('id', 'DESC')->first();

                $leaveAccumulation->total_remaining_count = $leaveAccumulation->total_remaining_count + $appliedLeave->number_of_days;

                //have removed EL Non Encashable to deduct from total remainig count 25-05-2021
                if ($appliedLeave->leave_type_id == 11) {
                    $leaveAccumulation->max_yearly_limit = $leaveAccumulation->max_yearly_limit + $appliedLeave->number_of_days;
                }


                $leaveAccumulation->save();

            }
        }


        if ($request->leaveStatus == '1' && $appliedLeaveStatusForApproveAfterRejection == '0') {
            //This code block is for leaves to be approved after rejection, so deduct leaves again from yearly balance
            $leaveAccumulation = $appliedLeave->user->leaveAccumulations()->where(['status' => '1', 'leave_type_id' => $appliedLeave->leave_type_id])->orderBy('id', 'DESC')->first();
            $leaveAccumulation->total_remaining_count = $leaveAccumulation->total_remaining_count - $appliedLeave->number_of_days;
            //have removed EL Non Encashable to deduct from total remainig count 25-05-2021
            if ($appliedLeave->leave_type_id == 11) {
                $leaveAccumulation->max_yearly_limit = abs($leaveAccumulation->max_yearly_limit - $appliedLeave->number_of_days);
                //abs to ensure 0 is the minimum
            }
            $leaveAccumulation->save();
        }

        return redirect()->route('leaves.listAppliedLeaveApprovals');
    }

    public function listAppliedLeaveApprovals($leaveStatus= '')
    {
        $user = Auth::user();
        if(empty($leaveStatus) || $leaveStatus == 'pending'){
            $status = '0';
            $leaveStatus = 'In-progress';
        }elseif ($leaveStatus == 'approved') {
            $status = '1';
            $leaveStatus = 'Approved';
        }elseif ($leaveStatus == 'rejected') {
            $status = '2';
            $leaveStatus = 'Rejected';
        }
        $appliedLeaveApproval = new AppliedLeaveApproval();

        $data = $appliedLeaveApproval->listAppliedLeaveApprovals($user->id,$status);
        return view('leaves.listAppliedLeaveApprovals')->with(['data'=>$data,'selectedStatus'=>$leaveStatus]);
    }

    public function appliedLeaveApprovalMessages(Request $request)
    {
        $appliedLeave = AppliedLeave::find($request->appliedLeaveId);
        $data = $appliedLeave->notifications()->where(['label'=>'Leave Comments'])->get();
        $view = View::make('leaves.listLeaveApprovalMessages',['data' => $data]);
        $contents = $view->render();

        return $contents;
    }

    public function compensatoryLeaveVerificationMessages(Request $request)
    {
        $compensatoryLeave = CompensatoryLeave::find($request->compensatoryLeaveId);
        $data = $compensatoryLeave->notifications()->where(['label'=>'Compensatory Verification Comments'])->get();
        $view = View::make('leaves.listLeaveApprovalMessages',['data' => $data]);
        $contents = $view->render();

        return $contents;
    }

    public function additionalAppliedLeaveDetails(Request $request)
    {
        $appliedLeave = AppliedLeave::find($request->appliedLeaveId);

        if(!empty($appliedLeave->weekoffs)){
            $appliedLeave->weekoffs = explode(',',$appliedLeave->weekoffs);
        }

        $view = View::make('leaves.additionalAppliedLeaveDetails', ['appliedLeave' => $appliedLeave]);
        $contents = $view->render();
        return $contents;
    }

    public function downloadLeaveDocuments($documentName)
    {
        $pathToFile = config('constants.uploadPaths.uploadAppliedLeaveDocument').$documentName;
        return response()->download($pathToFile);
    }

    public function listHolidays($sessionId = null)
    {
        $sessions = Session::where(['status'=>'1'])->get();
        $user = Auth::user();
        if(empty($sessionId)){
            $holidays = Holiday::where(['status'=>'1','unit_id'=>$user->userUnits[0]->unit_id])->orderBy('from_date','DESC')->get();
            $sessionName = "Sessions";
        }else{
            $session = Session::find($sessionId);
            $holidays = $session->holidays()->where(['status'=>'1','unit_id'=>$user->userUnits[0]->unit_id])->orderBy('from_date','DESC')->get();
            $sessionName = $session->name;
        }

        return view('leaves.listHolidays')->with(['holidays'=>$holidays,'sessions'=>$sessions,'sessionName'=>$sessionName]);
    }

    public function listCompensatoryLeaves()
    {
        $compensatoryLeave = new CompensatoryLeave();
        $user = Auth::user();
        $compensatoryLeaves = $compensatoryLeave->listCompensatoryLeaves($user->id);

        return view('leaves.listCompensatoryLeaves')->with(['compensatoryLeaves'=>$compensatoryLeaves]);
    }

    public function compensatoryLeaveAction($action)
    {
        if($action == 'add'){

            $user = Auth::user();

            $supervisors = [];
            if(!empty($user->userSupervisor)){
                $supervisors[0] = $user->userSupervisor->supervisor()->first();
            }

            if(!empty($user->otherSupervisor)){
                $supervisors[1] = $user->otherSupervisor->supervisor()->first();
            }

            $supervisors = collect($supervisors);
            return view('leaves.compensatoryLeaveForm')->with(['supervisors'=>$supervisors]);
        }
    }

    public function saveCompensatoryLeave(Request $request)
    {
        $request->validate([
            'leaveType' => 'bail|required',
            'onDate' => 'bail|required',
            'numberOfHours' => 'bail|required',
            'description' => 'bail|required',
            'inTime' => 'bail|required',
            'outTime' => 'bail|required',
            'supervisor' => 'bail|required'
        ]);

        $user = User::where(['id'=>Auth::id()])->with(['userProfile:id,user_id,gender','userSupervisor.supervisor:id,first_name,middle_name,last_name,personal_mobile_number'])->first();
        if(empty($user->userSupervisor)){
            $supervisorError = "You do not have a supervisor.";
            return redirect()->back()->with('leaveError',$supervisorError);
        }

        $onDate = date("Y-m-d",strtotime($request->onDate));

        $checkAlreadyExists = $user->compensatoryLeaves()->where(['on_date'=>$onDate,'status'=>'1'])->first();
        if(!empty($checkAlreadyExists)){
            return redirect()->back()->with('leaveError','You have already added  leave on given date.');
        }

        $previousApprovalPending = new CompensatoryLeaveApproval();
        $previousLeaveApprovalPending = $previousApprovalPending->where(['user_id'=>$user->id,'leave_status'=>'0'])->first();

        // return $previousLeaveApprovalPending;

        // if(!empty($previousLeaveApprovalPending) && $previousLeaveApprovalPending->compensatoryLeave->status == '1'){
        //     return redirect()->back()->with('leaveError','The approval status of your previously applied leave is pending with one or more authorities. Please contact the concerned person and clear it first.');
        // }

        if(!empty($request->leaveDocument)){
            $doc = $request->leaveDocument;
            $document = round(microtime(true)).str_random(5).'.'.$doc->getClientOriginalExtension();
            $doc->move(config('constants.uploadPaths.uploadAppliedLeaveDocument'), $document);
        }

        $data = [
            'leave_type' => $request->leaveType,
            'on_date' => $onDate,
            'number_of_hours' => $request->numberOfHours,
            'description' => $request->description,
            'selected_supervisor' => $request->supervisor,
            'applied_leave_id' => 0,
            'final_status' => '0',
            'status' => '1',
            'in_time' => $request->inTime,
            'out_time' => $request->outTime,
            'document_name'=> $document ?? NULL
        ];

        $compensatoryLeave = $user->compensatoryLeaves()->create($data);

        $userUnits = $user->userUnits()->pluck('unit_id')->toArray();
        if(count($userUnits) > 1){  //send directly to immediate supervisor
            $compensatoryLeaveApprovalData = [
                'leave_type' => $request->leavetype,
                'user_id' => $user->id,
                'supervisor_id' => $request->supervisor,
                'priority' => '1',
                'leave_status' => '0'
            ];

            $supervisorId =  $request->supervisor;

        }else{  //send to unit attendance verifier first
            $verifier = User::permission('verify-attendance')
                ->where('id','!=',1)
                ->where(['status'=>'1'])
                ->whereHas('userUnits', function(Builder $query)use($userUnits){
                    $query->where('unit_id',$userUnits[0]);
                })
                ->first();

            if(empty($verifier) || (@$verifier->id == $user->id)){
                $supervisorId =  $request->supervisor;
            }else{
                $supervisorId =  $verifier->id;
            }
        }

        $compensatoryLeaveApprovalData = [
            'user_id' => $user->id,
            'supervisor_id' => $supervisorId,
            'priority' => '1',
            'leave_status' => '0'
        ];
        $compensatoryLeaveApproval = $compensatoryLeave->compensatoryLeaveApprovals()->create($compensatoryLeaveApprovalData);

        $notificationData = [
            'sender_id' => $user->id,
            'receiver_id' => $supervisorId,
            'label' => 'Added Compensatory Leave',
            'status' => '1',
            'read_status' => '0',
            'message' => $user->first_name." ".$user->middle_name." ".$user->last_name." has added compensatory leave."
        ];

        $compensatoryLeave->notifications()->create($notificationData);

        return redirect()->route('leaves.listCompensatoryLeaves');
    }


    public function downloadCompensatoryDocument(CompensatoryLeave $compensatoryLeave){
        $file= asset('public/uploads/appliedLeaveDocuments/'.$compensatoryLeave->document_name);
        return Response::download($file);
    }

    public function listCompensatoryLeaveApprovals()
    {
        $user = Auth::user();
        $compensatoryLeaveApproval = new CompensatoryLeaveApproval();
        $compensatoryLeaveApprovals = $compensatoryLeaveApproval->listCompensatoryLeaveApprovals($user->id);
        //  return $compensatoryLeaveApprovals;
        return view('leaves.listCompensatoryLeaveApprovals')->with(['compensatoryLeaveApprovals'=>$compensatoryLeaveApprovals]);
    }

    public function saveCompensatoryLeaveApproval(Request $request)
    {
        $compensatoryLeaveApproval = CompensatoryLeaveApproval::find($request->compensatoryLeaveApprovalId);

        if($request->leaveStatus == 1){  //verified
            $compensatoryLeaveApproval->leave_status = '1';
            $compensatoryLeaveApproval->save();

        }elseif($request->leaveStatus == 2){  //rejected
            $compensatoryLeaveApproval->leave_status = '2';
            $compensatoryLeaveApproval->save();
        }

        $compensatoryLeave = $compensatoryLeaveApproval->compensatoryLeave()->first();
        $notificationData = [
            'sender_id' => $compensatoryLeaveApproval->supervisor_id,
            'receiver_id' => $compensatoryLeaveApproval->user_id,
            'label' => 'Compensatory Verification Comments',
            'status' => '1',
            'read_status' => '0',
            'message' => $request->comment
        ];

        $compensatoryLeave->notifications()->create($notificationData);

        $user = User::where(['id'=>$compensatoryLeave->user_id])
            ->with('userSupervisor')->first();

        //$supervisorId = Auth::id();
        $userUnits = $user->userUnits()->pluck('unit_id')->toArray();

        if(count($userUnits) == 1 && $request->leaveStatus == 1){
            $nextApproval = CompensatoryLeaveApproval::where(['compensatory_leave_id'=>$compensatoryLeave->id,'supervisor_id'=>$compensatoryLeave->selected_supervisor])->first();

            if(empty($nextApproval)){
                $nextApprovalData = [
                    'user_id' => $user->id,
                    'supervisor_id' => $compensatoryLeave->selected_supervisor,
                    'priority' => '2',
                    'leave_status' => '0'
                ];

                $compensatoryLeave->compensatoryLeaveApprovals()->create($nextApprovalData);

                $notificationData = [
                    'sender_id' => $user->id,
                    'receiver_id' => $compensatoryLeave->selected_supervisor,
                    'label' => 'Added Compensatory Leave',
                    'status' => '1',
                    'read_status' => '0',
                    'message' => $user->first_name." ".$user->middle_name." ".$user->last_name." has added compensatory leave."
                ];

                $compensatoryLeave->notifications()->create($notificationData);
            }
        }

        $compensatoryLeave = $compensatoryLeaveApproval->checkLeaveApprovalOnAllLevels($compensatoryLeave);
        if($compensatoryLeave->final_status == '1'){
            $originalHours = $compensatoryLeave->number_of_hours;
            $totalParts = $compensatoryLeave->number_of_hours / 0.5;
            if($totalParts > 1){
                $compensatoryLeave->number_of_hours = 0.5;
                $compensatoryLeave->save();
                $newData =  [
                    'user_id' => $compensatoryLeave->user_id,
                    'on_date' => $compensatoryLeave->on_date,
                    'leave_type' => $compensatoryLeave->leave_type,
                    'number_of_hours' => '0.5',
                    'applied_leave_id' => 0,
                    'selected_supervisor' => $compensatoryLeave->selected_supervisor,
                    'final_status' => '1',
                    'status' => '1'
                ];
                for ($i=1; $i < $totalParts ; $i++) {
                    CompensatoryLeave::create($newData);
                }
            }
            $user = User::find($compensatoryLeave->user_id);

            if($compensatoryLeave['leave_type'] == 'Compensatory Leave'){
                $leaveAccumulation = $user->leaveAccumulations()
                    ->where(['status'=>'1','leave_type_id'=>4])
                    ->orderBy('id','DESC')
                    ->first();

                $newAccumulationData =  [
                    'leave_type_id' => 4,
                    'creator_id' => 1,
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Compensatory Leave Verified',
                    'yearly_credit_number' => 0,
                    'total_upper_limit' => 'NA',
                    'max_yearly_limit' => 'NA'
                ];
            }elseif($compensatoryLeave['leave_type'] == 'Call for Extra Duty Leave'){
                $leaveAccumulation = $user->leaveAccumulations()
                    ->where(['status'=>'1','leave_type_id'=>16])
                    ->orderBy('id','DESC')
                    ->first();
                $newAccumulationData = [
                    'leave_type_id' => 16,
                    'creator_id' => 1,
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Extra Leave Verified',
                    'yearly_credit_number' => 0,
                    'total_upper_limit' => 'NA',
                    'max_yearly_limit' => 'NA'
                ];
            }

            if(empty($leaveAccumulation)){
                $newAccumulationData['total_remaining_count'] = $originalHours * 0.125;  //In days
                $newAccumulationData['previous_count'] = 0;  //In days

            }else{

                $previousRemainingCount = $leaveAccumulation->total_remaining_count;
                $newAccumulationData['total_remaining_count'] = ($originalHours * 0.125) + $previousRemainingCount;
                $newAccumulationData['previous_count'] = $previousRemainingCount;  //In days
                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
            }

            $user->leaveAccumulations()->create($newAccumulationData);
        }
        // return $leaveAccumulation;
        return redirect()->route('leaves.listCompensatoryLeaveApprovals');
    }

    public function saveExtraDutyLeaveApproval(Request $request)
    {
        return $request;

        $extraDutyLeaveApproval = CompensatoryLeaveApproval::find($request->compensatoryLeaveApprovalId);
        if($request->leaveStatus == 1){  //verified
            $extraDutyLeaveApproval->leave_status = '1';
            $extraDutyLeaveApproval->save();


        }elseif($request->leaveStatus == 2){  //rejected
            $extraDutyLeaveApproval->leave_status = '2';
            $extraDutyLeaveApproval->save();
        }

        $extraDutyLeave = $extraDutyLeaveApproval->compensatoryLeave()->first();

        $notificationData = [
            'sender_id' => $extraDutyLeaveApproval->supervisor_id,
            'receiver_id' => $extraDutyLeaveApproval->user_id,
            'label' => 'Compensatory Verification Comments',
            'status' => '1',
            'read_status' => '0',
            'message' => $request->comment
        ];

        $extraDutyLeave->notifications()->create($notificationData);

        $user = User::where(['id'=>$extraDutyLeave->user_id])
            ->with('userSupervisor')->first();

        //$supervisorId = Auth::id();
        $userUnits = $user->userUnits()->pluck('unit_id')->toArray();

        if(count($userUnits) == 1 && $request->leaveStatus == 1){
            $nextApproval = CompensatoryLeaveApproval::where(['compensatory_leave_id'=>$extraDutyLeave->id,'supervisor_id'=>$extraDutyLeave->selected_supervisor])->first();

            if(empty($nextApproval)){
                $nextApprovalData = [
                    'user_id' => $user->id,
                    'supervisor_id' => $extraDutyLeave->selected_supervisor,
                    'priority' => '2',
                    'leave_status' => '0'
                ];

                $extraDutyLeave->compensatoryLeaveApprovals()->create($nextApprovalData);

                $notificationData = [
                    'sender_id' => $user->id,
                    'receiver_id' => $extraDutyLeave->selected_supervisor,
                    'label' => 'Added Extra Duty Leave',
                    'status' => '1',
                    'read_status' => '0',
                    'message' => $user->first_name." ".$user->middle_name." ".$user->last_name." has added compensatory leave."
                ];

                $extraDutyLeave->notifications()->create($notificationData);
            }
        }

        $extraDutyLeave = $extraDutyLeaveApproval->checkLeaveApprovalOnAllLevels($extraDutyLeave);

        if($extraDutyLeave->final_status == '1'){
            $originalHours = $extraDutyLeave->number_of_hours;
            $totalParts = $extraDutyLeave->number_of_hours / 0.5;

            if($totalParts > 1){
                $extraDutyLeave->number_of_hours = 0.5;
                $extraDutyLeave->save();

                $newData =  [
                    'user_id' => $extraDutyLeave->user_id,
                    'on_date' => $extraDutyLeave->on_date,
                    'number_of_hours' => '0.5',
                    'applied_leave_id' => 0,
                    'selected_supervisor' => $extraDutyLeave->selected_supervisor,
                    'final_status' => '1',
                    'status' => '1'
                ];

                for ($i=1; $i < $totalParts ; $i++) {
                    CompensatoryLeave::create($newData);
                }
            }

            $user = User::find($extraDutyLeave->user_id);

            $leaveAccumulation = $user->leaveAccumulations()
                ->where(['status'=>'1','leave_type_id'=>4])
                ->orderBy('id','DESC')
                ->first();

            $newAccumulationData =  [
                'leave_type_id' => 4,
                'creator_id' => 1,
                'applied_leave_id' => 0,
                'status' => '1',
                'comment' => 'Extra Duty Leave Verified',
                'yearly_credit_number' => 0,
                'total_upper_limit' => 'NA',
                'max_yearly_limit' => 'NA'
            ];
            if(empty($leaveAccumulation)){
                $newAccumulationData['total_remaining_count'] = $originalHours * 0.125;  //In days
                $newAccumulationData['previous_count'] = 0;  //In days

            }else{
                $previousRemainingCount = $leaveAccumulation->total_remaining_count;
                $newAccumulationData['total_remaining_count'] = ($originalHours * 0.125) + $previousRemainingCount;
                $newAccumulationData['previous_count'] = $previousRemainingCount;  //In days

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
            }

            $user->leaveAccumulations()->create($newAccumulationData);
        }

        return redirect()->route('leaves.listCompensatoryLeaveApprovals');
    }

// ******************Leave Reports*********************** //

    public function leaveReportForm()
    {
        $user = Auth::user();
        $userUnits = $user->userUnits()->pluck('unit_id')->toArray();
        $units = Unit::whereIn('id',$userUnits)->get();
        return view('leaves.leaveReportForm')->with(['units'=>$units]);
    }

    public function generateLeaveReport(Request $request , $leaveStatus = null)
    {
        $reportData = [
            'fromDate' => date("Y-m-d",strtotime($request->fromDate)),
            'toDate' => date("Y-m-d",strtotime($request->toDate)),
            'unitId' => $request->unitId,
            'leaveStatus' => $request->leaveStatus
        ];

        $leaveStatus = $request->leaveStatus;
        $unit = Unit::find($request->unitId);
        $reportData['unitName'] = $unit->name;
        $appliedLeave = new AppliedLeave();

        $data = $appliedLeave->generateLeaveReport($reportData,$leaveStatus);
        return view('leaves.listLeaveReport')->with([
            'data'=>$data,
            'reportData'=>$reportData,
            'leaveStatus'=>$leaveStatus

        ]);
    }

    public function expireCompensatoryLeavesCron()
    {
        User::where(['status'=>'1','employee_type'=>'M&S'])->whereHas('leaveAccumulations',function($query){
            $query->where(['leave_type_id'=>4]);
        })->chunk(50, function ($users) {
            foreach ($users as $user) {

                $compensatoryLeaves = $user->compensatoryLeaves()->where(['applied_leave_id'=>0,'status'=>'1'])->get();
                $sum = 0;
                $idsString = "";
                $leaveAccumulation = $user->leaveAccumulations()
                    ->where(['status'=>'1','leave_type_id'=>4])
                    ->orderBy('id','DESC')
                    ->first();

                if(!$compensatoryLeaves->isEmpty()){
                    foreach ($compensatoryLeaves as $compensatoryLeave) {
                        $createdAt = new Carbon($compensatoryLeave->created_at);

                        $now = Carbon::now();

                        $difference = $createdAt->diffInMonths($now,false);

                        if($difference > 3){
                            $sum += $compensatoryLeave->number_of_hours; //hours
                            $compensatoryLeave->status = '0';
                            $compensatoryLeave->save();
                            $idsString .= $compensatoryLeave->id.',';
                        }
                    }

                    if($sum > 0){
                        $days = $sum * 0.125;
                        $remaining = $leaveAccumulation->total_remaining_count - $days;
                        $newAccumulationData =  [
                            'leave_type_id' => 4,
                            'creator_id' => 1,
                            'applied_leave_id' => 0,
                            'status' => '1',
                            'comment' => $idsString.' expired by cron',
                            'yearly_credit_number' => 0,
                            'total_upper_limit' => 'NA',
                            'max_yearly_limit' => 'NA',
                            'previous_count' => $leaveAccumulation->total_remaining_count,
                            'total_remaining_count' => $remaining
                        ];


                        $leaveAccumulation->status = '0';
                        $leaveAccumulation->save();
                        $user->leaveAccumulations()->create($newAccumulationData);
                    }
                }
            }
        });

        echo "<p>Cron ran</p>";
    }

    public function creditLeavesCron()
    {
        $firstJanYear = date("Y") . '-01-01';
        $firstJulyYear = date("Y") . '-07-01';
        $currentDate = date("Y-m-d");

//        return

//chunk not work

//        User::where(['status'=>'1'])->has('leaveAccumulations')->chunk(50, function ($users) use($firstJanYear,

//            $firstJulyYear,$currentDate)


        $users = User::where(['status' => '1'])->has('leaveAccumulations')->take(50)->get();

        foreach ($users as $user) {

            ////////////////Restricted Holidays Leaves Jan///////////////////
            $leaveAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 12])
                ->orderBy('id', 'DESC')
                ->first();

            $rhExists = $user->leaveAccumulations()
                ->where(['leave_type_id' => 12, 'comment' => 'Credited by cron'])
                ->whereDate('created_at', $firstJanYear)
                ->first();

            if (empty($rhExists) && ($currentDate == $firstJanYear) && !empty($leaveAccumulation)) {
                $newAccumulationData = [
                    'leave_type_id' => 12,
                    'creator_id' => 1,
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Credited by cron',
                    'yearly_credit_number' => 1,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                    'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                    'previous_count' => $leaveAccumulation->total_remaining_count,
                    'total_remaining_count' => 2
                ];

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $user->leaveAccumulations()->create($newAccumulationData);
            }



            //////////////Casual Leaves Jan/////////////////
            $leaveAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 1])
                ->orderBy('id', 'DESC')
                ->first();

            $clExists = $user->leaveAccumulations()
                ->where(['leave_type_id' => 1, 'comment' => 'Credited by cron'])
                ->whereDate('created_at', $firstJanYear)
                ->first();

            if (empty($clExists) && ($currentDate == $firstJanYear) && !empty($leaveAccumulation)) {

                $newAccumulationData = [
                    'leave_type_id' => 1,
                    'creator_id' => 1,
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Credited by cron',
                    'yearly_credit_number' => 1,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                    'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                    'previous_count' => $leaveAccumulation->total_remaining_count,
                    'total_remaining_count' => 12
                ];

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $user->leaveAccumulations()->create($newAccumulationData);

            }

            /////////////////////HPSL Leaves Jan/////////////////////
            $leaveAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 2])
                ->orderBy('id', 'DESC')
                ->first();

            $hpslExists = $user->leaveAccumulations()
                ->where(['leave_type_id' => 2, 'comment' => 'Credited by cron'])
                ->whereDate('created_at', $firstJanYear)
                ->first();

            if (empty($hpslExists) && ($currentDate == $firstJanYear) && !empty($leaveAccumulation)) {
                $newAccumulationData = [
                    'leave_type_id' => 2,
                    'creator_id' => 1,
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Credited by cron',
                    'yearly_credit_number' => 1,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,  //180
                    'max_yearly_limit' => '10',
                    'previous_count' => $leaveAccumulation->total_remaining_count
                ];

                if (($leaveAccumulation->total_remaining_count + 10) <= $leaveAccumulation->total_upper_limit) {
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count + 10;
                } else {
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_upper_limit;
                }

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $user->leaveAccumulations()->create($newAccumulationData);
            }

            ////////////////////HPSL Leaves July//////////////////

            $hpslExists = $user->leaveAccumulations()
                ->where(['leave_type_id' => 2, 'comment' => 'Credited by cron'])
                ->whereDate('created_at', $firstJulyYear)
                ->first();

            if (empty($hpslExists) && ($currentDate == $firstJulyYear) && !empty($leaveAccumulation)) {
                $newAccumulationData = [
                    'leave_type_id' => 2,
                    'creator_id' => 1,
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Credited by cron',
                    'yearly_credit_number' => 2,
                    'total_upper_limit' => $leaveAccumulation->total_upper_limit,  //180
                    'max_yearly_limit' => '20',
                    'previous_count' => $leaveAccumulation->total_remaining_count
                ];

                if (($leaveAccumulation->total_remaining_count + 10) <= $leaveAccumulation->total_upper_limit) {
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count + 10;
                } else {
                    $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_upper_limit;
                }

                $leaveAccumulation->status = '0';
                $leaveAccumulation->save();
                $user->leaveAccumulations()->create($newAccumulationData);
            }

            /////////////////////////EL-Cashable Leaves Jan//////////////////////////
            $elCashableleaveAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 11])
                ->orderBy('id', 'DESC')
                ->first();

            $elNonCashableAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 3])
                ->orderBy('id', 'DESC')
                ->first();

            $elCashExists = $user->leaveAccumulations()
                ->where(['leave_type_id' => 11, 'comment' => 'Credited by cron'])
                ->whereDate('created_at', $firstJanYear)
                ->first();

            if (empty($elCashExists) && ($currentDate == $firstJanYear) && !empty($elCashableleaveAccumulation) && !empty($elNonCashableAccumulation)) {
                $newAccumulationData = [
                    'leave_type_id' => 11,
                    'creator_id' => 1,
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Credited by cron',
                    'yearly_credit_number' => 1,
                    'total_upper_limit' => $elCashableleaveAccumulation->total_upper_limit,  //NA
                    'max_yearly_limit' => '7.5',
                    'previous_count' => $elCashableleaveAccumulation->total_remaining_count
                ];

                if (($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count + 7.5) <= 300) {
                    $newAccumulationData['total_remaining_count'] = $elCashableleaveAccumulation->total_remaining_count + 7.5;

                    $elCashableleaveAccumulation->status = '0';
                    $elCashableleaveAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);
                } else {

                    if ($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count < 300) {
                        $remaining = abs(($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count) - 300);
                    } else {
                        $remaining = ($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count + 7.5) - 300;

                    }

                    $newAccumulationData['total_remaining_count'] = $elCashableleaveAccumulation->total_remaining_count + $remaining;

                    $elCashableleaveAccumulation->status = '0';
                    $elCashableleaveAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);

                    $newAccumulationData['leave_type_id'] = 3;
                    $newAccumulationData['total_upper_limit'] = $elNonCashableAccumulation->total_upper_limit;
                    $newAccumulationData['max_yearly_limit'] = '7.5';
                    $newAccumulationData['previous_count'] = $elNonCashableAccumulation->total_remaining_count;
                    $newAccumulationData['total_remaining_count'] = $elNonCashableAccumulation->total_remaining_count - $remaining;

                    $elNonCashableAccumulation->status = '0';
                    $elNonCashableAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);
                }


            }

            /////////////////////////EL-Cashable Leaves July//////////////////////////
            $elCashableleaveAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 11])
                ->orderBy('id', 'DESC')
                ->first();

            $elNonCashableAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 3])
                ->orderBy('id', 'DESC')
                ->first();

            $elCashExists = $user->leaveAccumulations()
                ->where(['leave_type_id' => 11, 'comment' => 'Credited by cron'])
                ->whereDate('created_at', $firstJulyYear)
                ->first();

            if (empty($elCashExists) && ($currentDate == $firstJulyYear) && !empty($elCashableleaveAccumulation) && !empty($elNonCashableAccumulation)) {
                $newAccumulationData = [
                    'leave_type_id' => 11,
                    'creator_id' => 1,
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Credited by cron',
                    'yearly_credit_number' => 2,
                    'total_upper_limit' => $elCashableleaveAccumulation->total_upper_limit,  //NA
                    'max_yearly_limit' => '15',
                    'previous_count' => $elCashableleaveAccumulation->total_remaining_count
                ];

                if (($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count + 7.5) <= 300) {
                    $newAccumulationData['total_remaining_count'] = $elCashableleaveAccumulation->total_remaining_count + 7.5;

                    $elCashableleaveAccumulation->status = '0';
                    $elCashableleaveAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);
                } else {
                    if ($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count < 300) {
                        $remaining = abs(($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count) - 300);
                    } else {
                        $remaining = ($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count + 7.5) - 300;

                    }
                    $newAccumulationData['total_remaining_count'] = $elCashableleaveAccumulation->total_remaining_count + $remaining;

                    $elCashableleaveAccumulation->status = '0';
                    $elCashableleaveAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);

                    $newAccumulationData['leave_type_id'] = 3;
                    $newAccumulationData['total_upper_limit'] = $elNonCashableAccumulation->total_upper_limit;
                    $newAccumulationData['max_yearly_limit'] = '15';
                    $newAccumulationData['previous_count'] = $elNonCashableAccumulation->total_remaining_count;
                    $newAccumulationData['total_remaining_count'] = $elNonCashableAccumulation->total_remaining_count - $remaining;

                    $elNonCashableAccumulation->status = '0';
                    $elNonCashableAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);
                }

            }

            /////////////////////////EL-Non Cashable Leaves Jan//////////////////////////
            $elCashableleaveAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 11])
                ->orderBy('id', 'DESC')
                ->first();

            $elNonCashableAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 3])
                ->orderBy('id', 'DESC')
                ->first();

            $elNonCashExists = $user->leaveAccumulations()
                ->where(['leave_type_id' => 3, 'comment' => 'Credited by cron'])
                ->whereDate('created_at', $firstJanYear)
                ->first();

            if (empty($elNonCashExists) && ($currentDate == $firstJanYear) && !empty($elCashableleaveAccumulation) && !empty($elNonCashableAccumulation)) {
                $newAccumulationData = [
                    'leave_type_id' => 3,
                    'creator_id' => 1,
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Credited by cron',
                    'yearly_credit_number' => 1,
                    'total_upper_limit' => $elNonCashableAccumulation->total_upper_limit,  //NA
                    'max_yearly_limit' => '7.5',
                    'previous_count' => $elNonCashableAccumulation->total_remaining_count
                ];

                if (($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count + 7.5) <= 300) {
                    $newAccumulationData['total_remaining_count'] = $elNonCashableAccumulation->total_remaining_count + 7.5;

                    $elNonCashableAccumulation->status = '0';
                    $elNonCashableAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);
                } else {
                    if ($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count < 300) {
                        $remaining = abs(($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count) - 300);
                    } else {
                        $remaining = ($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count + 7.5) - 300;

                    }
                    $newAccumulationData['total_remaining_count'] = $elNonCashableAccumulation->total_remaining_count + $remaining;

                    $elNonCashableAccumulation->status = '0';
                    $elNonCashableAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);

                }
            }

            /////////////////////////EL-Non Cashable Leaves July//////////////////////////

            $elCashableleaveAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 11])
                ->orderBy('id', 'DESC')
                ->first();

            $elNonCashableAccumulation = $user->leaveAccumulations()
                ->where(['status' => '1', 'leave_type_id' => 3])
                ->orderBy('id', 'DESC')
                ->first();

            $elNonCashExists = $user->leaveAccumulations()
                ->where(['leave_type_id' => 3, 'comment' => 'Credited by cron'])
                ->whereDate('created_at', $firstJulyYear)
                ->first();

            if (empty($elNonCashExists) && ($currentDate == $firstJulyYear) && !empty($elCashableleaveAccumulation) && !empty($elNonCashableAccumulation)) {
                $newAccumulationData = [
                    'leave_type_id' => 3,
                    'creator_id' => 1,
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Credited by cron',
                    'yearly_credit_number' => 2,
                    'total_upper_limit' => $elNonCashableAccumulation->total_upper_limit,  //NA
                    'max_yearly_limit' => '15',
                    'previous_count' => $elNonCashableAccumulation->total_remaining_count
                ];

                if (($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count + 7.5) <= 300) {
                    $newAccumulationData['total_remaining_count'] = $elNonCashableAccumulation->total_remaining_count + 7.5;

                    $elNonCashableAccumulation->status = '0';
                    $elNonCashableAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);
                } else {
                    if ($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count < 300) {
                        $remaining = abs(($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count) - 300);
                    } else {
                        $remaining = ($elCashableleaveAccumulation->total_remaining_count + $elNonCashableAccumulation->total_remaining_count + 7.5) - 300;

                    }
                    $newAccumulationData['total_remaining_count'] = $elNonCashableAccumulation->total_remaining_count + $remaining;

                    $elNonCashableAccumulation->status = '0';
                    $elNonCashableAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);
                }
            }

            /////////////////////////Short Leave Monthly//////////////////////////
            $currentDay = date("d");
            // $previousMonth = date("m", strtotime("-1 months"));
            // $previousMonthYear = date("Y", strtotime("-1 months"));

            if ($currentDay == "01") {

                $appliedLeaveApprovals = AppliedLeaveApproval::where(['leave_status' => '0'])
                    ->whereHas('appliedLeave', function ($query) {
                        $query->where(['status' => '1', 'leave_type_id' => 14, 'final_status' => '0']);
                    })->get();

                if (!$appliedLeaveApprovals->isEmpty()) {
                    foreach ($appliedLeaveApprovals as $appliedLeaveApproval) {
                        return "03";
                        $leaveAccumulation = $user->leaveAccumulations()
                            ->where(['status' => '1', 'leave_type_id' => 14])
                            ->orderBy('id', 'DESC')
                            ->first();

                        $appliedLeave = $appliedLeaveApproval->appliedLeave();

                        if ($appliedLeave->number_of_days <= $leaveAccumulation->total_remaining_count) {
                            $appliedLeave->paid_leaves_count = $appliedLeave->number_of_days;
                            $appliedLeave->final_status = '1';
                            $appliedLeave->save();

                            $appliedLeaveApproval->leave_status = '1';
                            $appliedLeaveApproval->save();

                            $toSubtract = $appliedLeave->number_of_days;
                        } else {
                            $remaining = $appliedLeave->number_of_days - $leaveAccumulation->total_remaining_count;
                            $appliedLeave->unpaid_leaves_count = $remaining;
                            $appliedLeave->paid_leaves_count = $leaveAccumulation->total_remaining_count;
                            $appliedLeave->final_status = '1';
                            $appliedLeave->save();

                            $appliedLeaveApproval->leave_status = '1';
                            $appliedLeaveApproval->save();

                            $toSubtract = $leaveAccumulation->total_remaining_count;
                        }

                        $newAccumulationData = [
                            'leave_type_id' => $appliedLeave->leave_type_id,
                            'creator_id' => 1,
                            'applied_leave_id' => $appliedLeave->id,
                            'status' => '1',
                            'comment' => 'Leave Approved by cron',
                            'yearly_credit_number' => $leaveAccumulation->yearly_credit_number,
                            'total_upper_limit' => $leaveAccumulation->total_upper_limit,
                            'max_yearly_limit' => $leaveAccumulation->max_yearly_limit,
                            'previous_count' => $leaveAccumulation->total_remaining_count
                        ];

                        $newAccumulationData['total_remaining_count'] = $leaveAccumulation->total_remaining_count - $toSubtract;

                        $leaveAccumulation->status = '0';
                        $leaveAccumulation->save();
                        $leaveApplier->leaveAccumulations()->create($newAccumulationData);

                        $notificationData = [
                            'sender_id' => 1,
                            'receiver_id' => $appliedLeave->user_id,
                            'label' => 'Leave Approved by cron',
                            'status' => '1',
                            'read_status' => '0',
                            'message' => "Your leave has been approved."
                        ];

                        $appliedLeave->notifications()->create($notificationData);

                    }
                }

                ////////////////////////////////////////////////////////////////////////

                $leaveAccumulation = $user->leaveAccumulations()
                    ->where(['status' => '1', 'leave_type_id' => 14])
                    ->orderBy('id', 'DESC')
                    ->first();

                $shortLeaveExists = $user->leaveAccumulations()
                    ->where(['leave_type_id' => 14, 'comment' => 'Credited by cron'])
                    ->whereDate('created_at', $currentDate)
                    ->first();

                if (empty($shortLeaveExists) && !empty($leaveAccumulation)) {
                    $newAccumulationData = [
                        'leave_type_id' => 14,
                        'creator_id' => 1,
                        'applied_leave_id' => 0,
                        'status' => '1',
                        'comment' => 'Credited by cron',
                        'total_upper_limit' => '0.5',
                        'max_yearly_limit' => 'NA',
                        'previous_count' => '0',
                        'total_remaining_count' => '0.5'
                    ];

                    $newAccumulationData['yearly_credit_number'] = date("n");

                    $leaveAccumulation->status = '0';
                    $leaveAccumulation->save();
                    $user->leaveAccumulations()->create($newAccumulationData);
                }


            }

        }//endforeach

//            });//end chunking

        echo "<p>Cron ran successfully.</p>";

    }

    public function leaveAccumulationsForm()
    {
        return view('leaves.leaveAccumulationsForm');
    }

    public function saveEmployeeAccumulation(Request $request)
    {
        $user = User::where(['employee_code'=>$request->employeeCode])->first();

        if(empty($user)){
            $errorMessage = "User with employee code ".$request->employeeCode." does not exists.";
            return redirect()->back()->with('accumulationError',$errorMessage);
        }else{

            DB::beginTransaction();
            $rhAccumulation = $user->leaveAccumulations()
                ->where(['status'=>'1','leave_type_id'=>12])
                ->orderBy('id','DESC')
                ->first();

            $newAccumulationData =  [
                'leave_type_id' => 12,
                'creator_id' => Auth::id(),
                'applied_leave_id' => 0,
                'status' => '1',
                'comment' => 'Added Manually',
                'yearly_credit_number' => 1,
                'total_upper_limit' => '2',
                'max_yearly_limit' => $request->rhMaxYearlyLimit,
                'total_remaining_count' => $request->rhTotalRemainingCount,
                'previous_count' => '0'
            ];

            if(!empty($rhAccumulation)){
                $newAccumulationData['previous_count'] = $rhAccumulation->total_remaining_count;
                $rhAccumulation->status = '0';
                $rhAccumulation->save();
            }

            $user->leaveAccumulations()->create($newAccumulationData);

            /////////////////////////////////////////////////////////////
            $clAccumulation = $user->leaveAccumulations()
                ->where(['status'=>'1','leave_type_id'=>1])
                ->orderBy('id','DESC')
                ->first();

            $newAccumulationData =  [
                'leave_type_id' => 1,
                'creator_id' => Auth::id(),
                'applied_leave_id' => 0,
                'status' => '1',
                'comment' => 'Added Manually',
                'yearly_credit_number' => 1,
                'total_upper_limit' => '12',
                'max_yearly_limit' => $request->cLMaxYearlyLimit,
                'total_remaining_count' => $request->cLTotalRemainingCount,
                'previous_count' => '0'
            ];

            if(!empty($clAccumulation)){
                $newAccumulationData['previous_count'] = $clAccumulation->total_remaining_count;
                $clAccumulation->status = '0';
                $clAccumulation->save();
            }

            $user->leaveAccumulations()->create($newAccumulationData);

            ////////////////////////////////////////////////////////////

            $hpslAccumulation = $user->leaveAccumulations()
                ->where(['status'=>'1','leave_type_id'=>2])
                ->orderBy('id','DESC')
                ->first();

            $newAccumulationData =  [
                'leave_type_id' => 2,
                'creator_id' => Auth::id(),
                'applied_leave_id' => 0,
                'status' => '1',
                'comment' => 'Added Manually',
                'yearly_credit_number' => 1,
                'total_upper_limit' => '180',
                'max_yearly_limit' => $request->hpslMaxYearlyLimit,
                'total_remaining_count' => $request->hpslTotalRemainingCount,
                'previous_count' => '0'
            ];

            if(!empty($hpslAccumulation)){
                $newAccumulationData['previous_count'] = $hpslAccumulation->total_remaining_count;
                $hpslAccumulation->status = '0';
                $hpslAccumulation->save();
            }

            $user->leaveAccumulations()->create($newAccumulationData);

            ///////////////////////////////////////////////////////////

            $oldElNCAcc = $elNcAccumulation = $user->leaveAccumulations()
                ->where(['status'=>'1','leave_type_id'=>3])
                ->orderBy('id','DESC')
                ->first();

            $newAccumulationData =  [
                'leave_type_id' => 3,
                'creator_id' => Auth::id(),
                'applied_leave_id' => 0,
                'status' => '1',
                'comment' => 'Added Manually',
                'yearly_credit_number' => 1,
                'total_upper_limit' => 'NA',
                'max_yearly_limit' => $request->eLNonCashableMaxYearlyLimit,
                'total_remaining_count' => $request->eLNonCashableTotalRemainingCount,
                'previous_count' => '0'
            ];

            if(!empty($elNcAccumulation)){
                $newAccumulationData['previous_count'] = $elNcAccumulation->total_remaining_count;
                $elNcAccumulation->status = '0';
                $elNcAccumulation->save();
            }

            $user->leaveAccumulations()->create($newAccumulationData);

            /////////////////////////////////////////////////////////////////

            $elCaAccumulation = $user->leaveAccumulations()
                ->where(['status'=>'1','leave_type_id'=>11])
                ->orderBy('id','DESC')
                ->first();

            if(isset($elCaAccumulation)) {
                if ($user->retirement_date != NULL) {
                    // directed by pankaj && gaurav sir
                    $sixMonthBeforeDate = Carbon::parse(date('Y-m-d'))->subMonth('6')->format('Y-m-d');
                    if (strtotime($sixMonthBeforeDate) <= strtotime($user->retirement_date)) {
                        $totalLeaveAvailable = $request->eLNonCashableTotalRemainingCount + $request->eLCashableTotalRemainingCount;
                        $maxYearlyLimit = ($totalLeaveAvailable * 2) / 3;
                        $request->eLCashableMaxYearlyLimit = round($maxYearlyLimit, 2);
                    }
                } else {
                    //added on 28-05-2021
                    $elExceeding285 = 0;
                    if ($oldElNCAcc->total_remaining_count + $elCaAccumulation->total_remaining_count > 285) {
                        //This case has already some leaves added in max yearly count in encashable
                        //consider only the current difference
                        $elExceeding285 = ($oldElNCAcc->eLNonCashableTotalRemainingCount + $elCaAccumulation->eLCashableTotalRemainingCount) - 285;
                    }
                    $sumEncAndNonEncYearlyCount = ($request->eLNonCashableTotalRemainingCount + $request->eLCashableTotalRemainingCount) - 285;

//                    return $sumEncAndNonEncYearlyCount;

                    if ($sumEncAndNonEncYearlyCount > 0) {
                        $request->eLCashableMaxYearlyLimit += ($request->eLCashableMaxYearlyLimit + $sumEncAndNonEncYearlyCount > 30) ? 30 : $sumEncAndNonEncYearlyCount;
                    }
                }
            }

            $newAccumulationData =  [
                'leave_type_id' => 11,
                'creator_id' => Auth::id(),
                'applied_leave_id' => 0,
                'status' => '1',
                'comment' => 'Added Manually',
                'yearly_credit_number' => 1,
                'total_upper_limit' => 'NA',
                'max_yearly_limit' => $request->eLCashableMaxYearlyLimit,
                'total_remaining_count' => $request->eLCashableTotalRemainingCount,
                'previous_count' => '0'
            ];

            if(!empty($elCaAccumulation)){
                $newAccumulationData['previous_count'] = $elCaAccumulation->total_remaining_count;
                $elCaAccumulation->status = '0';
                $elCaAccumulation->save();
            }

            $user->leaveAccumulations()->create($newAccumulationData);

            ///////////////////////////////////////////////////////////////

            $sLAccumulation = $user->leaveAccumulations()
                ->where(['status'=>'1','leave_type_id'=>14])
                ->orderBy('id','DESC')
                ->first();

            $newAccumulationData =  [
                'leave_type_id' => 14,
                'creator_id' => Auth::id(),
                'applied_leave_id' => 0,
                'status' => '1',
                'comment' => 'Added Manually',
                'yearly_credit_number' => date("n"),
                'total_upper_limit' => '0.5',
                'max_yearly_limit' => $request->shortLeaveMaxYearlyLimit,
                'total_remaining_count' => $request->shortLeaveTotalRemainingCount,
                'previous_count' => '0'
            ];

            if(!empty($sLAccumulation)){
                $newAccumulationData['previous_count'] = $sLAccumulation->total_remaining_count;
                $sLAccumulation->status = '0';
                $sLAccumulation->save();
            }

            $user->leaveAccumulations()->create($newAccumulationData);

            DB::commit();

            return redirect()->back();


        }//end else

    }

    public function employeeLeaveAccumulations(Request $request)
    {
        $user = User::where(['employee_code'=>$request->employeeCode])->first();

        $result['status'] = false;

        $result['cLMaxYearlyLimit'] = "";
        $result['cLTotalRemainingCount'] = "";

        $result['rhMaxYearlyLimit'] = "";
        $result['rhTotalRemainingCount'] = "";

        $result['hpslMaxYearlyLimit'] = "";
        $result['hpslTotalRemainingCount'] = "";

        $result['eLNonCashableMaxYearlyLimit'] = "";
        $result['eLNonCashableTotalRemainingCount'] = "";

        $result['eLCashableMaxYearlyLimit'] = "";
        $result['eLCashableTotalRemainingCount'] = "";

        $result['shortLeaveMaxYearlyLimit'] = "NA";
        $result['shortLeaveTotalRemainingCount'] = "";

        $clAccumulation = $user->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>1])
            ->orderBy('id','DESC')
            ->first();

        $rhAccumulation = $user->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>12])
            ->orderBy('id','DESC')
            ->first();

        $hpslAccumulation = $user->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>2])
            ->orderBy('id','DESC')
            ->first();

        $elNcAccumulation = $user->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>3])
            ->orderBy('id','DESC')
            ->first();

        $elCaAccumulation = $user->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>11])
            ->orderBy('id','DESC')
            ->first();

        $sLAccumulation = $user->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>14])
            ->orderBy('id','DESC')
            ->first();

        if(!empty($user)){
            $result['status'] = true;
            $result['employeeName'] = $user->first_name." ".$user->middle_name." ".$user->last_name;

            if(!empty($clAccumulation)){
                $result['cLMaxYearlyLimit'] = $clAccumulation->max_yearly_limit;
                $result['cLTotalRemainingCount'] = $clAccumulation->total_remaining_count;
            }

            if(!empty($rhAccumulation)){
                $result['rhMaxYearlyLimit'] = $rhAccumulation->max_yearly_limit;
                $result['rhTotalRemainingCount'] = $rhAccumulation->total_remaining_count;
            }

            if(!empty($hpslAccumulation)){
                $result['hpslMaxYearlyLimit'] = $hpslAccumulation->max_yearly_limit;
                $result['hpslTotalRemainingCount'] = $hpslAccumulation->total_remaining_count;
            }

            if(!empty($elNcAccumulation)){
                $result['eLNonCashableMaxYearlyLimit'] = $elNcAccumulation->max_yearly_limit;
                $result['eLNonCashableTotalRemainingCount'] = $elNcAccumulation->total_remaining_count;
            }

            if(!empty($elCaAccumulation)){
                $result['eLCashableMaxYearlyLimit'] = $elCaAccumulation->max_yearly_limit;
                $result['eLCashableTotalRemainingCount'] = $elCaAccumulation->total_remaining_count;
            }

            if(!empty($sLAccumulation)){
                $result['shortLeaveMaxYearlyLimit'] = $sLAccumulation->max_yearly_limit;
                $result['shortLeaveTotalRemainingCount'] = $sLAccumulation->total_remaining_count;
            }
        }
        return $result;
    }
}//end of class

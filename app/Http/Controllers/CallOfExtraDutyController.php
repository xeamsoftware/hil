<?php

namespace App\Http\Controllers;

use App\CallOfExtraDuty;
use App\CallOfExtraDutyApproval;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Auth;
use View;

class CallOfExtraDutyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $callOfExtraDutyLeaves = $user->CallOfExtraDutyLeaves;

        return view('leaves.call_of_extra_duty.index', compact('callOfExtraDutyLeaves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $supervisors = [];
        if(!empty($user->userSupervisor)){
            $supervisors[0] = $user->userSupervisor->supervisor()->first();
        }

        if(!empty($user->otherSupervisor)){
            $supervisors[1] = $user->otherSupervisor->supervisor()->first();
        }

        $supervisors = collect($supervisors);
        return view('leaves.call_of_extra_duty.create', compact('supervisors'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
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

        $checkAlreadyExists = $user->CallOfExtraDutyLeaves()->where(['on_date'=>$onDate,'status'=>'1'])->first();
        if(!empty($checkAlreadyExists)){
            return redirect()->back()->with('leaveError','You have already added  leave on given date.');
        }

//        $previousApprovalPending = new CallOfExtraDutyLeaveApprovals();
//        $previousLeaveApprovalPending = $previousApprovalPending->where(['user_id'=>$user->id,'leave_status'=>'0'])->first();

        $data = [
            'on_date' => $onDate,
            'in_time' => $request->inTime,
            'out_time' => $request->outTime,
            'number_of_hours' => $request->numberOfHours,
            'description' => $request->description,
            'selected_supervisor' => $request->supervisor,
            'final_status' => '0',
            'status' => '1',
        ];

        $callOfExtraDutyLeave = $user->CallOfExtraDutyLeaves()->create($data);

        $userUnits = $user->userUnits()->pluck('unit_id')->toArray();
        if(count($userUnits) > 1){  //send directly to immediate supervisor
            $callOfExtraDutyLeaveApprovalData = [
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

        $callOfExtraDutyLeaveApprovalData = [
            'user_id' => $user->id,
            'supervisor_id' => $supervisorId,
            'priority' => '1',
            'leave_status' => '0'
        ];

        $callOfExtraDutyLeaveApproval = $callOfExtraDutyLeave->extraDutyLeaveApprovals()->create($callOfExtraDutyLeaveApprovalData);

        $notificationData = [
            'sender_id' => $user->id,
            'receiver_id' => $supervisorId,
            'label' => 'Added Compensatory Leave',
            'status' => '1',
            'read_status' => '0',
            'message' => $user->first_name." ".$user->middle_name." ".$user->last_name." has added compensatory leave."
        ];

        $callOfExtraDutyLeave->notifications()->create($notificationData);

        return redirect()->route('leaves.listCallOfExtraDutyLeaves');
    }


    public function listApprovals(Request $request){
        $user = Auth::user();
        $callOfExtraDutyApprovals =  $user->CallOfExtraDutyLeaveApprovals;
        return view('leaves.call_of_extra_duty.list_approvals', compact('callOfExtraDutyApprovals'));

    }


    public function saveApproval(Request $request){

        $extraDutyLeaveApproval = CallOfExtraDutyApproval::find($request->callOfExtraDutyApprovalId);

        if($extraDutyLeaveApproval->leave_status == $request->leaveStatus){
            return back()->with('error', 'Status already marked same as the requested status');
        }

        if($request->leaveStatus == 1){  //verified
            $extraDutyLeaveApproval->leave_status = '1';
            $extraDutyLeaveApproval->save();


            $extraDutyLeave = $extraDutyLeaveApproval->extraDutyLeave;
            $extraDutyLeave->final_status = '1';
            $extraDutyLeave->save();
        }
        elseif($request->leaveStatus == 2){  //rejected
            $extraDutyLeaveApproval->leave_status = '2';
            $extraDutyLeaveApproval->save();
        }


        $notificationData = [
            'sender_id' => $extraDutyLeaveApproval->supervisor_id,
            'receiver_id' => $extraDutyLeaveApproval->user_id,
            'label' => 'Compensatory Verification Comments',
            'status' => '1',
            'read_status' => '0',
            'message' => $request->comment
        ];

        $extraDutyLeave->notifications()->create($notificationData);

        if($extraDutyLeave->final_status == '1'){

            $originalHours = $extraDutyLeave->number_of_hours;

            //8 hour is equal to a day
            $day  = $originalHours / 8;

            $user = User::find($extraDutyLeave->user_id);

            $leaveAccumulation = $user->leaveAccumulations()->where(['status'=>'1','leave_type_id'=>16])->orderBy('id','DESC')->first();

            if(isset($leaveAccumulation)){

                $totalRemainingCount = $leaveAccumulation->total_remaining_count + $day;
                $leaveAccumulation->total_remaining_count = $totalRemainingCount;
                $leaveAccumulation->save();
            }else{
                $newAccumulationData =  [
                    'leave_type_id' => 16,
                    'creator_id' => Auth::id(),
                    'applied_leave_id' => 0,
                    'status' => '1',
                    'comment' => 'Extra Duty Leave Verified',
                    'yearly_credit_number' => 0,
                    'previous_count' => 0,
                    'total_remaining_count' => $day,
                    'total_upper_limit' => 'NA',
                    'max_yearly_limit' => 'NA'
                ];
                $user->leaveAccumulations()->create($newAccumulationData);
            }
        }

        return back()->with('success', 'Leave Approved Successfully');
    }


    public function verificationMessages(Request $request)
    {
        $callOfExtraDutyLeave = CallOfExtraDuty::find($request->callOfExtraDutyLeaveId);
        $data = $callOfExtraDutyLeave->notifications()->where(['label'=>'Call Of Extra Duty Verification Comments'])->get();
        $view = View::make('leaves.listLeaveApprovalMessages',['data' => $data]);
        $contents = $view->render();

        return $contents;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\CallOfExtraDuty  $callOfExtraDuty
     * @return \Illuminate\Http\Response
     */
    public function show(CallOfExtraDuty $callOfExtraDuty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CallOfExtraDuty  $callOfExtraDuty
     * @return \Illuminate\Http\Response
     */
    public function edit(CallOfExtraDuty $callOfExtraDuty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CallOfExtraDuty  $callOfExtraDuty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CallOfExtraDuty $callOfExtraDuty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CallOfExtraDuty  $callOfExtraDuty
     * @return \Illuminate\Http\Response
     */
    public function destroy(CallOfExtraDuty $callOfExtraDuty)
    {
        //
    }
}

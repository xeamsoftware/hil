<?php

namespace App\Http\Controllers;

use App\LeaveAccumulation;
use App\Mail\ForgetMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Auth;
use Hash;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Validator;
use Carbon\Carbon;
use Eloquent;
use View;
use DB;
use App\Mail\ForgotPassword;

use App\User;
use App\UserUnit;
use App\UserSupervisor;
use App\OtherSupervisor;
use App\UserQualification;
use App\UserAddress;
use App\PasswordReset;
use App\PasswordResetRequest;
use App\Unit;
use App\Qualification;
use App\Department;
use App\Designation;
use App\Notification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Excel;
use App\Imports\UsersImport;
use App\Imports\LeaveAccumulationsImport;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    function empType(Request $request){
        $user = User::where('id', $request->user_id)->first();

        return Response::json(['emp_type'=>$user->employee_type]);
    }

    /*
        If a person has email send the reset password link to them else
        send the reset password request to concerned person (unit admins)
    */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'employeeCode' => 'bail|required'
        ]);

        $user = User::where(['employee_code' => $request->employeeCode])
            ->with('userUnits')
            ->first();

        if(empty($user)){
            return redirect()->back()->with('errorAttempt',"Employee Code is incorrect!");
        }else{
            if($user->status == '0'){
                return redirect()->back()->with('errorAttempt',"Your account has been disabled. Please contact administrator.");

            }elseif($user->approval_status == '0'){
                return redirect()->back()->with('errorAttempt',"Your account has not been approved yet. Please contact administrator.");

            }else{

                $newForgotToken = str_random(20);

                $forgotData = ['forgot_password_token' => $newForgotToken];
                $user->update($forgotData);

                // if(!empty($user->personal_email == 0)){
                //     $newForgotToken = encrypt($newForgotToken);
                //     $user->url = url('/forgot-password')."/".$newForgotToken;
                //     Mail::to($user->personal_email)->send(new ForgotPassword($user));
                //     return redirect('/')->with('success',"Your forgot password email has been sent successfully.");

                if($user->official_email == ''){
                    return redirect()->back()->with('errorAttempt',"Please contact administrator.");
                }else{
                    $unit = $user->userUnits[0]->unit_id;
                    $authority = User::permission('reset-password')
                        ->where('id','!=',1)
                        ->where(['status'=>'1'])
                        ->whereHas('userUnits',function(Builder $query)use($unit){
                            $query->where(['unit_id'=>$unit]);
                        })
                        ->first();
                    if(!empty($authority)){
                        $resetData = [
                            'authority_id' => $authority->id,
                            'action' => '0'
                        ];
                        $passwordResetRequest = $user->passwordResetRequests()->create($resetData);
                        // $notificationData = [
                        //             'sender_id' => $user->id,
                        //             'receiver_id' => $authority->id,
                        //             'label' => 'Reset Password Request',
                        //             'status' => '1',
                        //             'read_status' => '0',
                        //             'message' => $user->first_name." ".$user->middle_name." ".$user->last_name."(".$user->employee_code.")"." has requested for a password reset. Please change the password and notify him/her."
                        //         ];

                        // $passwordResetRequest->notifications()->create($notificationData);
                        // return $passwordResetRequest;
                        if(isset($authority->official_email)){
                            $recipientMail = $authority->official_email;
                        }elseif($authority->personal_email){
                            $recipientMail = $authority->personal_email;
                        }
//                        return $authority;
                        Mail::to($recipientMail)->send(new ForgetMail($user));
                        return redirect('/')->with('success',"Your reset password request has been sent successfully.");
                    }else{

                        return redirect()->back()->with('errorAttempt',"You do not have an active password reset authority. Please contact administrator.");
                    }
                }
            }
        }
    }//end of function

    /*
        List all the reset password requests received by an authority
    */
    function listPasswordResetRequests()
    {
        $user = Auth::user();
        $data = PasswordResetRequest::where(['authority_id'=>$user->id])
            ->with('user:id,first_name,middle_name,last_name,employee_code')
            ->get();

        return view('employees.listPasswordResetRequests')->with(['data'=>$data,'user'=>$user]);

    }//end of function

    /*
        Reset the password & save it to database for any reset password request
    */
    function resetOriginalPassword($requestId)
    {
        $resetRequest = PasswordResetRequest::find($requestId);

        if(!empty($resetRequest)){
            $newPassword = Hash::make("hil1234");
            $user = $resetRequest->user;
            $user->password = $newPassword;
            $user->save();

            $resetRequest->action = '1';
            $resetRequest->save();
        }

        return redirect()->back();

    }//end of function

    /*
        Show the reset password form when user clicks on the link in the email
    */
    function forgotPasswordForm($encryptedToken)
    {
        $token = decrypt($encryptedToken);
        $user = User::where(['forgot_password_token'=>$token])->first();

        if(!empty($user)){

            $data['token'] = $encryptedToken;
            $data['expire_status'] = "no";
            $data['url'] = "";

        }else{

            $expireToken = "NA";
            $data['token'] = encrypt($expireToken);
            $data['expire_status'] = "yes";
            $data['url'] = url("/forgot-password");

        }

        return view('resetPasswordForm')->with(['data'=>$data]);

    }//end of function

    /*
        Save the user's password & expire the one time link
    */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'new_password'  => 'bail|required|max:20|min:6',
            'confirm_password'  => 'bail|required|max:20|min:6|same:new_password'
        ]);

        $token = decrypt($request->forgot_token);

        $user = User::where(['forgot_password_token'=>$token])->first();

        if(!empty($user)){
            $newPassword = Hash::make($request->new_password);
            $user->password = $newPassword;
            $user->forgot_password_token = "";
            $user->save();

            return redirect('/')->with('success',"Your password has been changed successfully.");
        }else{

            return redirect()->back()->with(['password_error'=>"Your reset password link has expired. Please send the email again."]);
        }
    }//end of function

    //  public function resendOtp(Request $request)
    //  {
    //  	$user = User::where(['personal_mobile_number' => $request->mobileNumber])->first();
    //  	$result = ['error' => false, 'message' => "Please check your mobile. OTP has been sent again."];

    //  	if(empty($user)){
    //  		$result  = ['error'=>true, 'message' => "Mobile Number is incorrect!"];
    //   	return $result;
    //  	}else{
    //  		$passwordReset = PasswordReset::find($request->passwordReset);

    //   	if($passwordReset->otp_count == 3){
    // 	$updatedAt = new Carbon($passwordReset->updated_at);
    //      		$now = Carbon::now();

    //      		$difference = $updatedAt->diffInMinutes($now,false);

    //      		if($difference < 60){
    //      			$result  = ['error'=>true, 'message' => "You have resend otp three times. Please wait for one hour before trying again."];
    //   			return $result;
    //      		}else{
    // 		$passwordReset->otp = rand(99999,1000000);
    // 		$passwordReset->otp_count = 1;

    // 		$passwordReset->save();
    //      		}


    // }else{

    // 	$passwordReset->otp = rand(99999,1000000);
    // 	$passwordReset->otp_count += 1;

    // 	$passwordReset->save();
    // }

    // $message = __('smsMessages.otp').$passwordReset->otp;
    //   	$sendSms = $user->sendSms($user,$message);

    //   	if($sendSms){  //twilio error
    //  			$result  = ['error'=>true, 'message' => $sendSms];
    //  		}

    //  		return $result;
    //  	}
    //  }

    // public function verifyOtp(Request $request)
    // {

    //  if(empty($request->otp)){
    //  	$result  = ['error'=>true, 'message' => "Please enter six digits OTP number."];
    //  	return $result;
    //  }elseif(strlen($request->otp) != 6){
    //  	$result  = ['error'=>true, 'message' => "Please enter six digits OTP number."];
    //  	return $result;
    //  }

    //  $user = User::where(['personal_mobile_number' => $request->mobileNumber])->first();
    //  $result = ['error' => false, 'message' => ""];

    //  if(empty($user)){
    //  	$result  = ['error'=>true, 'message' => "Mobile Number is incorrect!"];
    //  	return $result;
    //  }else{
    //  	$passwordReset = PasswordReset::find($request->passwordReset);

    //  	$updatedAt = new Carbon($passwordReset->updated_at);
    //         $now = Carbon::now();

    //         $difference = $updatedAt->diffInMinutes($now,false);

    //         if($difference > 15){
    //         	$passwordReset->status = '0';
    //  		$passwordReset->save();

    //  		$result  = ['error'=>true, 'message' => "OTP has expired! Please resend the OTP."];
    //  		return $result;

    //         }else{
    //         	if($request->otp == $passwordReset->otp){
    //         		$newPassword = strtoupper(str_random(10));
    //         		$message = __('smsMessages.newPassword').$newPassword;

    //         		$sendSms = $user->sendSms($user,$message);

    //         		if($sendSms){  //twilio error
    //         			$result  = ['error'=>true, 'message' => $sendSms];
    //  				return $result;
    //         		}else{
    //         			$passwordReset->status = '0';
    //  				$passwordReset->save();

    //  				$user->password = Hash::make($newPassword);
    //  				$user->save();

    //  				$result  = ['error'=>false, 'message' => "Your new password has been send to the registered mobile number."];
    //  				return $result;
    //         		}

    //  		}else{
    //  			$result  = ['error'=>true, 'message' => "OTP does not match!"];
    //  			return $result;
    //  		}
    //         }

    //  }
    // }

    /*
        Authenticate a user with employee code & password, then redirect them to dashboard
    */
    public function login(Request $request)
    {
        $request->validate([
            'employeeCode' => 'bail|required',
            'password' => 'bail|required|min:6|max:20'
        ]);

        if(Auth::attempt(['employee_code'=>$request->employeeCode, 'password'=>$request->password])){
            $user = Auth::user();

            if($user->status == '0'){
                Auth::logout();
                return redirect('/')->with('errorAttempt',"Your account has been disabled. Please contact administrator.");
            }else{
                return redirect()->route('employees.dashboard');
            }
        }else{
            return redirect('/')->with('errorAttempt',"Employee code or password is incorrect!");
        }
    }

    /*
        Display the dashboard page with necessary information
    */
    public function dashboard()
    {

        $users = User::whereHas('userUnits',function(Builder $query){
            $query->where(['unit_id'=>10]);
        })
            ->with(['userSupervisor', 'userSupervisor.supervisor', 'leaveApprovalAuthorities.supervisor'])
            ->get();

        $data = [];
        $str= '<style>table tr td { width: 200px;}</style><table border="1">
                <tr>
                    <td>Employee Code</td>
                    <td>Employee Name</td>
                    <td>SO1 Code</td>
                    <td>SO1 Name</td>
                    <td>SO2 Code</td>
                    <td>SO2 Name</td>
                    <td>SO3 Code</td>
                    <td>SO3 Name</td>
                </tr>
            ';
        if($users->count()){
            foreach ($users as $user) {
                $str.= '<tr>
                        <td>'.$user->employee_code.'</td>
                        <td>'.$user->first_name . ' ' . $user->last_name.'</td>';
                if(isset($user->userSupervisor)){
                    $str.= '<td>'.$user->userSupervisor->supervisor->employee_code.'</td>
                                <td>'.$user->userSupervisor->supervisor->first_name . ' ' . $user->userSupervisor->supervisor->last_name.'</td>';
                }
                else{
                    $str.= '<td>--</td><td>--</td>';
                }
                $thirdTd = 0;
                if($user->leaveApprovalAuthorities->count()){
                    foreach ($user->leaveApprovalAuthorities as $la) {
                        if($la->priority == '2'){
                            $thirdTd++;
                            $str.= '<td>'.$la->supervisor->employee_code.'</td>
                                <td>'.$la->supervisor->first_name . ' ' . $la->supervisor->last_name.'</td>';
                        }
                        elseif($la->priority == 1){

                        }
                        elseif($la->priority == 3){
                            $thirdTd++;
                            $str.= '<td>'.$la->supervisor->employee_code.'</td>
                                <td>'.$la->supervisor->first_name . ' ' . $la->supervisor->last_name.'</td>';
                        }
                        else{
                            $str.= '<td>--</td><td>--</td>';
                        }
                    }
                    if($thirdTd==1)
                        $str.= '<td>--</td><td>--</td>';
                }
                else
                    $str.= '<td>--</td><td>--</td><td>--</td><td>--</td>';

                $str.= '</tr>';
            }
        }
        $str.= '</table>';
        //echo $str;exit;

        $user = Auth::user();
        $data = [];
        $leavesArray = [1,2,3,4,11,12,14];  //leave-type ids

        foreach ($leavesArray as $key => $value) {

            $data[$value] = $this->leaveTypeWiseAccumulation($user,$value);
        }

        return view('admins.dashboard')->with(['data'=>$data]);
    }

    /*
        Ajax request to show the leave details of other employees in a modal
    */
    public function othersDashboardDetails(Request $request)
    {
        $user = User::find($request->userId);

        $data = [];
        $leavesArray = [1,2,3,4,11,12,14];   //leave-type ids

        foreach ($leavesArray as $key => $value) {
            $data[$value] = $this->leaveTypeWiseAccumulation($user,$value);
        }

        $view = View::make('leaves.othersDashboardDetails',['data' => $data]);
        $contents = $view->render();

        $result['contents'] = $contents;
        $result['title'] = $user->first_name." ".$user->middle_name." ".$user->last_name." ($user->employee_code)";

        return $result;
    }

    /*
        Get the leave accumulation details of a leave-type of an user
    */
    public function leaveTypeWiseAccumulation($user,$leaveTypeId)
    {
        $leaveAccumulation = $user->leaveAccumulations()
            ->where(['status'=>'1','leave_type_id'=>$leaveTypeId])
            ->orderBy('id','DESC')
            ->first();

        $currentYear = date("Y");
        $currentMonth = date("m");

        if($leaveTypeId != 4 && $leaveTypeId != 14){

            $appliedLeaveData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$user->id,'al.leave_type_id'=>$leaveTypeId])
                ->whereYear('al.updated_at',$currentYear)
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount,SUM(al.unpaid_leaves_count) as unpaidLeavesCount,SUM(al.compensatory_leaves_count) as compensatoryLeavesCount"))
                ->first();

        }elseif($leaveTypeId == 4){  //compensatory leave

            $appliedLeaveData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.final_status'=>'1','al.user_id'=>$user->id,'al.leave_type_id'=>$leaveTypeId])
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount,SUM(al.unpaid_leaves_count) as unpaidLeavesCount,SUM(al.compensatory_leaves_count) as compensatoryLeavesCount"))
                ->first();

        }elseif($leaveTypeId == 14){  //short leave
            $appliedLeaveData = DB::table('applied_leaves as al')
                ->where(['al.status'=>'1','al.user_id'=>$user->id,'al.leave_type_id'=>$leaveTypeId])
                ->whereYear('al.updated_at',$currentYear)
                ->whereMonth('al.updated_at',$currentMonth)
                ->select(DB::raw("SUM(al.paid_leaves_count) as paidLeavesCount,SUM(al.unpaid_leaves_count) as unpaidLeavesCount,SUM(al.compensatory_leaves_count) as compensatoryLeavesCount"))
                ->first();
        }

        if($appliedLeaveData->paidLeavesCount == ""){
            $appliedLeaveData->paidLeavesCount = 0;
        }

        if($appliedLeaveData->unpaidLeavesCount == ""){
            $appliedLeaveData->unpaidLeavesCount = 0;
        }

        if(!empty($leaveAccumulation)){
            $result['totalRemainingCount'] = $leaveAccumulation->total_remaining_count;
            $result['maxYearlyLimit'] = $leaveAccumulation->max_yearly_limit;
            $result['processingLeavesCount'] = $leaveAccumulation->processing_remaining_count;
            $result['yearlyLeavesTaken'] = $appliedLeaveData->paidLeavesCount + $appliedLeaveData->unpaidLeavesCount;

            if(($leaveAccumulation->max_yearly_limit != 'NA') && ($leaveAccumulation->max_yearly_limit >= $result['yearlyLeavesTaken'])){
                $result['yearlyBalanceLeaves'] = $leaveAccumulation->max_yearly_limit - $result['yearlyLeavesTaken'];

            }elseif($leaveTypeId == 4){
                $result['yearlyBalanceLeaves'] = 'NA';
            }elseif($leaveTypeId == 14){
                $result['yearlyBalanceLeaves'] = 'NA';
            }else{
                $result['yearlyBalanceLeaves'] = 0;
            }
        }else{
            $result['yearlyBalanceLeaves'] = 'Not Added';
        }
        $data['leaveAccumulation'] = $leaveAccumulation;
        $data['yearlyBalanceLeaves'] = $result['yearlyBalanceLeaves'];
        return $data;
    }

    /*
        End the session of a user & redirect them to landing page
    */
    public function logout()
    {
        session(['lastInsertedEmployee' => 0]);
        Auth::logout();

        return redirect('/');
    }

    /*
        Ajax request to check the uniqueness of certain parameters while creating employees
    */
    public function checkUnique(Request $request)
    {
        //0 = blank, 1 = yes, 2 = no
        $result = [
            'employeeCode' => 1,
            'personalEmail' => 1,
            'personalMobileNumber' => 1
        ];

        if(empty($request->employeeCode)){
            $result['employeeCode'] = 0;
        }else{
            $user = User::where('employee_code',$request->employeeCode)->first();

            if(!empty($user)){
                $result['employeeCode'] = 2;
            }
        }

        if(empty($request->personalEmail)){
            $result['personalEmail'] = 0;
        }else{
            $user = User::where('personal_email',$request->personalEmail)->first();

            if(!empty($user)){
                $result['personalEmail'] = 2;
            }
        }

        if(empty($request->personalMobileNumber)){
            $result['personalMobileNumber'] = 0;
        }else{
            $user = User::where('personal_mobile_number',$request->personalMobileNumber)->first();

            if(!empty($user)){
                $result['personalMobileNumber'] = 2;
            }
        }

        return $result;
    }

    /*
        Ajax request to get units wise employees details
    */
    public function unitWiseEmployees(Request $request)
    {
        $unit = new Unit();
        $data = $unit->unitWiseEmployees($request->unitIds);

        return $data;
    }

    /*
        Approve a user's profile after it has been created & details have been verified
    */
    public function approveUser($userId)
    {
        $approverId = Auth::id();
        $user = User::find($userId);
        $user->approval_status='1';
        $user->save();
        $user->userProfileApproval()->updateOrCreate(['user_id'=>$user->id],['approver_id'=>$approverId]);

        return redirect("employees/edit/$userId");
    }

    /*
        Get the details of an employee & show them on the edit employee form
    */
    public function edit($userId, $tabName = null)
    {
        $data = array();
        if(empty($tabName)){
            $data['tabName'] = "basicDetailsTab";
        }else{
            $data['tabName'] = $tabName;
        }

        $data['user'] = User::where(['id'=>$userId])->with(['userProfile','userAddress','userSupervisor','otherSupervisor','userQualification','userProfileApproval.approver:id,first_name,middle_name,last_name'])->first();
        $data['userUnits'] = $data['user']->userUnits()->pluck('unit_id')->toArray();
        $userRoles = $data['user']->getRoleNames();
        $data['userRole'] = $userRoles[0];
        $data['userPermissions'] = $data['user']->getAllPermissions()->pluck('name')->toArray();

        $leaveApprovalAuthorities = $data['user']->leaveApprovalAuthorities()->where(['status'=>'1'])->get();

        $data['userDyHod'] = "";
        $data['userHod'] = "";
        $data['userDgm'] = "";
        $data['userGm'] = "";
        $data['userCmd'] = "";

        if(!$leaveApprovalAuthorities->isEmpty()){
            foreach ($leaveApprovalAuthorities as $key => $value) {
                if($value->priority == '2'){
                    $data['userDyHod'] = $value->supervisor_id;

                }elseif($value->priority == '3') {
                    $data['userHod'] = $value->supervisor_id;

                }elseif($value->priority == '4') {
                    $data['userDgm'] = $value->supervisor_id;

                }elseif($value->priority == '5') {
                    $data['userGm'] = $value->supervisor_id;

                }elseif($value->priority == '6') {
                    $data['userCmd'] = $value->supervisor_id;
                }
            }
        }

        if($data['user']->id == 1){
            $data['units'] = Unit::where(['status' => '1'])->get();
        }else{
            $data['units'] = Unit::whereIn('id',$data['userUnits'])->where(['status' => '1'])->get();
        }

        $data['dgms'] = User::role('DGM')->where('id','!=',1)->where(['status'=>'1'])->get();
        $data['gms'] = User::role('GM')->where('id','!=',1)->where(['status'=>'1'])->get();
        $data['cmds'] = User::role('CMD')->where('id','!=',1)->where(['status'=>'1'])->get();
        $data['roles'] = Role::get();
        $data['designations'] = Designation::where(['status'=>'1'])->get();
        $data['departments'] = Department::where(['status'=>'1'])->get();
        $data['qualifications'] = Qualification::where(['status' => '1'])->get();
        $data['permissions'] = Permission::pluck('id','name')->toArray();

        $data['allEmployees'] = User::where(['status'=>'1','approval_status'=>'1'])
            ->where('id','!=',1)
            ->where('employee_code','not like','ADMIN%')
            ->select('id','first_name','middle_name','last_name','employee_code')
            ->get();

        return view('employees.edit')->with(['data'=>$data]);
    }

    /*
        Get the necessary details & show them on the create employee form
    */
    public function create($tabName = null)
    {
        $data = array();
        if(empty($tabName)){
            $data['tabName'] = "basicDetailsTab";
        }else{
            $data['tabName'] = $tabName;
        }

        $user = Auth::user();
        $data['user'] = $user;
        $data['userUnits'] = $data['user']->userUnits()->pluck('unit_id')->toArray();

        if($user->id == 1){
            $data['units'] = Unit::where(['status' => '1'])->get();
        }else{
            $data['units'] = Unit::whereIn('id',$data['userUnits'])->where(['status' => '1'])->get();
        }


        $data['dgms'] = User::role('DGM')->where('id','!=',1)->where(['status'=>'1'])->get();
        $data['gms'] = User::role('GM')->where('id','!=',1)->where(['status'=>'1'])->get();
        $data['cmds'] = User::role('CMD')->where('id','!=',1)->where(['status'=>'1'])->get();
        $data['roles'] = Role::get();
        $data['designations'] = Designation::where(['status'=>'1'])->get();
        $data['departments'] = Department::where(['status'=>'1'])->get();
        $data['qualifications'] = Qualification::where(['status' => '1'])->get();
        $data['permissions'] = Permission::pluck('id','name')->toArray();

        $data['allEmployees'] = User::where(['status'=>'1','approval_status'=>'1'])
            ->where('id','!=',1)
            ->where('employee_code','not like','ADMIN%')
            ->select('id','first_name','middle_name','last_name','employee_code')
            ->get();

        return view('employees.create')->with(['data'=>$data]);
    }

    /*
        Ajax request to check which user in a given unit has permission to verify-attendance & reset password
    */
    public function checkPermission(Request $request)
    {
        $unit = $request->unit;
        $reset_password = $request->reset_password;
        $verify_attendance = $request->verify_attendance;

        $result['verify_attendance'] = 1;
        $result['reset_password'] = 1;

        if($verify_attendance != -1){
            $user = User::permission('verify-attendance')
                ->where('id','!=',1)
                ->where(['status'=>'1'])
                ->whereHas('userUnits',function(Builder $query)use($unit){
                    $query->where(['unit_id'=>$unit]);
                })
                ->first();

            if(!empty($user)){
                $result['verify_attendance'] = 0;
                $result['verify_employee_code'] = $user->employee_code;
            }
        }

        if($reset_password != -1){
            $user = User::permission('reset-password')
                ->where('id','!=',1)
                ->where(['status'=>'1'])
                ->whereHas('userUnits',function(Builder $query)use($unit){
                    $query->where(['unit_id'=>$unit]);
                })
                ->first();

            if(!empty($user)){
                $result['reset_password'] = 0;
                $result['reset_employee_code'] = $user->employee_code;
            }
        }

        return $result;
    }

    /*
        Save the information from basic details tab of create employee form
    */
    public function saveBasicDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'personalMobileNumber' => 'bail|required|unique:users,personal_mobile_number',
            'employeeCode' => 'bail|required|unique:users,employee_code',
            //'dateOfBirth' => 'bail|required',
            'dateOfJoining' => 'bail|required',
            'firstName' => 'bail|required',
            //'lastName' => 'bail|required',
            'unitIds' => 'bail|required',
            'designationId' => 'bail|required',
            'permissionIds' => 'bail|required',
            'maritalStatus' => 'bail|required',
            //'emergencyContactName' => 'bail|required',
            //'emergencyContactNumber' => 'bail|required',
            'gender' => 'bail|required',
            //'spouseName' => 'required_if:maritalStatus,==,Married'
        ]);

        if($validator->fails()) {
            return redirect("employees/create")
                ->withErrors($validator,'basic')
                ->withInput();
        }

        $password = 'hil1234';
        $userData = [
            'employee_code' => $request->employeeCode,
            'employee_type' => $request->employeeType,
            'password' => Hash::make($password),
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'middle_name' => $request->middleName,
            'official_email' => $request->officialEmail,
            'personal_email' => $request->personalEmail,
            'official_mobile_number' => $request->officialMobileNumber,
            'personal_mobile_number' => $request->personalMobileNumber,
            'status' => '1',
            'approval_status' => '0'
        ];

        $user = User::firstOrCreate($userData);

        $user->assignRole($request->roleId);
        $user->syncPermissions($request->permissionIds);

        $userProfileData =  [
            'creator_id' => Auth::id(),
            'father_name' => $request->fatherName,
            'mother_name' => $request->motherName,
            'joining_date' => date("Y-m-d",strtotime($request->dateOfJoining)),
            'gender' => $request->gender,
            'marital_status' => $request->maritalStatus,
            'spouse_name' => $request->spouseName,
            'emergency_contact_number' => $request->emergencyContactNumber,
            'emergency_contact_name' => $request->emergencyContactName,
            'relationship' => $request->relationship,
            'pan_number' => $request->panNumber,
            'adhaar_number' => $request->adhaarNumber,
            'blood_group' => $request->bloodGroup,
            'department_id' => $request->departmentId,
            'designation_id' => $request->designationId,
            'vehicle_number' => $request->vehicleNumber
        ];

        if(!empty($request->dateOfBirth)){
            $userProfileData['birth_date'] = date("Y-m-d",strtotime($request->dateOfBirth));
        }

        $user->userProfile()->firstOrCreate($userProfileData);

        foreach ($request->unitIds as $key => $value) {
            $user->userUnits()->firstOrCreate(['unit_id'=>$value]);
        }

        if(!empty($request->qualificationId)){
            $user->userQualification()->firstOrCreate(['qualification_id'=>$request->qualificationId]);
        }

        if(!empty($request->supervisor)){
            $user->userSupervisor()->firstOrCreate(['supervisor_id'=>$request->supervisor,'status'=>'1']);
            $supervisor = User::find($request->supervisor);

            if(!$supervisor->hasPermissionTo('approve-leave')){
                $supervisor->givePermissionTo(['approve-leave']);
            }
        }

        if(!empty($request->otherSupervisor)){
            $user->otherSupervisor()->firstOrCreate(['supervisor_id'=>$request->otherSupervisor,'status'=>'1']);

            $otherSupervisor = User::find($request->otherSupervisor);

            if(!$otherSupervisor->hasPermissionTo('approve-leave')){
                $otherSupervisor->givePermissionTo(['approve-leave']);
            }
        }

        if(!empty($request->deputyHodId)){
            $user->leaveApprovalAuthorities()->firstOrCreate(['priority'=>'2','supervisor_id'=>$request->deputyHodId,'status'=>'1']);

            $deputyHod = User::find($request->deputyHodId);

            if(!$deputyHod->hasPermissionTo('approve-leave')){
                $deputyHod->givePermissionTo(['approve-leave']);
            }
        }

        if(!empty($request->hodId)){
            $user->leaveApprovalAuthorities()->firstOrCreate(['priority'=>'3','supervisor_id'=>$request->hodId,'status'=>'1']);

            $hod = User::find($request->hodId);

            if(!$hod->hasPermissionTo('approve-leave')){
                $hod->givePermissionTo(['approve-leave']);
            }
        }

        if(!empty($request->dgmId)){
            $user->leaveApprovalAuthorities()->firstOrCreate(['priority'=>'4','supervisor_id'=>$request->dgmId,'status'=>'1']);
        }

        if(!empty($request->gmId)){
            $user->leaveApprovalAuthorities()->firstOrCreate(['priority'=>'5','supervisor_id'=>$request->gmId,'status'=>'1']);
        }

        if(!empty($request->cmdId)){
            $user->leaveApprovalAuthorities()->firstOrCreate(['priority'=>'6','supervisor_id'=>$request->cmdId,'status'=>'1']);
        }

        session(['lastInsertedEmployee' => $user->id]);

        return redirect("employees/create/addressDetailsTab")->with('basicSuccess',"Basic details saved successfully.");
    }

    /*
        Update the information from basic details tab of edit employee form
    */
    public function editBasicDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'dateOfBirth' => 'bail|required',
            'dateOfJoining' => 'bail|required',
            'firstName' => 'bail|required',
            //'lastName' => 'bail|required',
            'designationId' => 'bail|required',
            'permissionIds' => 'bail|required',
            'maritalStatus' => 'bail|required',
            //'emergencyContactName' => 'bail|required',
            //'emergencyContactNumber' => 'bail|required',
            'gender' => 'bail|required',
            //'spouseName' => 'required_if:maritalStatus,==,Married'
        ]);

        $user = User::find($request->userId);

        if($validator->fails()) {
            return redirect("employees/edit/$user->id")
                ->withErrors($validator,'basic')
                ->withInput();
        }

        if($user->id != 1 && $user->id == Auth::id()){
            return redirect()->back()->with('basicError','You cannot edit your own profile.');
        }

        $userData = [

            'employee_type' => $request->employeeType,
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'middle_name' => $request->middleName,
            'official_email' => $request->officialEmail,
            'official_mobile_number' => $request->officialMobileNumber

        ];

        $user->update($userData);

        $user->assignRole($request->roleId);
        $user->syncPermissions($request->permissionIds);

        $userProfileData =  [
            'father_name' => $request->fatherName,
            'mother_name' => $request->motherName,
            'joining_date' => date("Y-m-d",strtotime($request->dateOfJoining)),
            'gender' => $request->gender,
            'marital_status' => $request->maritalStatus,
            'spouse_name' => $request->spouseName,
            'emergency_contact_number' => $request->emergencyContactNumber,
            'emergency_contact_name' => $request->emergencyContactName,
            'relationship' => $request->relationship,
            'pan_number' => $request->panNumber,
            'adhaar_number' => $request->adhaarNumber,
            'blood_group' => $request->bloodGroup,
            'department_id' => $request->departmentId,
            'designation_id' => $request->designationId,
            'vehicle_number' => $request->vehicleNumber
        ];

        if(!empty($request->dateOfBirth)){
            $userProfileData['birth_date'] = date("Y-m-d",strtotime($request->dateOfBirth));
        }

        $user->userProfile()->update($userProfileData);

        if(!empty($request->qualificationId)){
            UserQualification::updateOrCreate(['user_id'=>$user->id],['qualification_id'=>$request->qualificationId]);
        }

        if(!empty($request->supervisor)){
            UserSupervisor::updateOrCreate(['user_id'=>$user->id],['supervisor_id'=>$request->supervisor,'status'=>'1']);

            $supervisor = User::find($request->supervisor);

            if(!$supervisor->hasPermissionTo('approve-leave')){
                $supervisor->givePermissionTo(['approve-leave']);
            }
        }

        if(!empty($request->otherSupervisor)){
            OtherSupervisor::updateOrCreate(['user_id'=>$user->id],['supervisor_id'=>$request->otherSupervisor,'status'=>'1']);

            $otherSupervisor = User::find($request->otherSupervisor);

            if(!$otherSupervisor->hasPermissionTo('approve-leave')){
                $otherSupervisor->givePermissionTo(['approve-leave']);
            }
        }

        if(!empty($request->deputyHodId)){
            $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'2','status'=>'1'])->first();

            if(empty($authority->priority)){
                $user->leaveApprovalAuthorities()->create(['supervisor_id'=>$request->deputyHodId,'priority'=>'2','status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$request->deputyHodId,'priority'=>'2','status'=>'1']);
            }

            $deputyHod = User::find($request->deputyHodId);

            if(!$deputyHod->hasPermissionTo('approve-leave')){
                $deputyHod->givePermissionTo(['approve-leave']);
            }
        }

        if(!empty($request->hodId)){
            $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'3','status'=>'1'])->first();

            if(empty($authority->priority)){
                $user->leaveApprovalAuthorities()->create(['supervisor_id'=>$request->hodId,'priority'=>'3','status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$request->hodId,'priority'=>'3','status'=>'1']);
            }

            $hod = User::find($request->hodId);

            if(!$hod->hasPermissionTo('approve-leave')){
                $hod->givePermissionTo(['approve-leave']);
            }
        }

        if(!empty($request->dgmId)){
            $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'4','status'=>'1'])->first();

            if(empty($authority->priority)){
                $user->leaveApprovalAuthorities()->create(['supervisor_id'=>$request->dgmId,'priority'=>'4','status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$request->dgmId,'priority'=>'4','status'=>'1']);
            }
        }

        if(!empty($request->gmId)){
            $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'5','status'=>'1'])->first();

            if(empty($authority->priority)){
                $user->leaveApprovalAuthorities()->create(['supervisor_id'=>$request->gmId,'priority'=>'5','status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$request->gmId,'priority'=>'5','status'=>'1']);
            }
        }

        if(!empty($request->cmdId)){
            $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'6','status'=>'1'])->first();

            if(empty($authority->priority)){
                $user->leaveApprovalAuthorities()->create(['supervisor_id'=>$request->cmdId,'priority'=>'6','status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$request->cmdId,'priority'=>'6','status'=>'1']);
            }
        }

        return redirect("employees/edit/$user->id/addressDetailsTab")->with('basicSuccess',"Basic details updated successfully.");
    }

    /*
        Save the information from address details tab of create employee form
    */
    public function saveAddressDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currentAddressOne' => 'bail|required',
            'currentAddressTwo' => 'bail|required',
            'currentAddressCity' => 'bail|required',
            'currentAddressPin' => 'bail|required',
            'permanentAddressOne' => 'bail|required',
            'permanentAddressTwo' => 'bail|required',
            'permanentAddressCity' => 'bail|required',
            'permanentAddressPin' => 'bail|required'
        ]);

        if($validator->fails()) {
            return redirect("employees/create/addressDetailsTab")
                ->withErrors($validator,'address')
                ->withInput();
        }

        $lastInsertedEmployee = session('lastInsertedEmployee');

        if($lastInsertedEmployee != 0){
            $user = UserAddress::where('user_id',$lastInsertedEmployee)->first();

            if(!empty($user)){
                return redirect('employees/create/addressDetailsTab')->with('addressError',"Details of this employee have already been saved. Please create a new employee.");
            }else{

                $addressData =  [
                    'user_id' => $lastInsertedEmployee,
                    'current_address1' => $request->currentAddressOne,
                    'current_address2' => $request->currentAddressTwo,
                    'permanent_address1' => $request->permanentAddressOne,
                    'permanent_address2' => $request->permanentAddressTwo,
                    'current_address_city' => $request->currentAddressCity,
                    'current_address_pin' => $request->currentAddressPin,
                    'permanent_address_city' => $request->permanentAddressCity,
                    'permanent_address_pin' => $request->permanentAddressPin
                ];

                UserAddress::create($addressData);

                return redirect('employees/create/addressDetailsTab')->with('addressSuccess',"Address details saved successfully.");
            }
        }
    }

    /*
        Update the information from address details tab of edit employee form
    */
    public function editAddressDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currentAddressOne' => 'bail|required',
            'currentAddressTwo' => 'bail|required',
            'currentAddressCity' => 'bail|required',
            'currentAddressPin' => 'bail|required',
            'permanentAddressOne' => 'bail|required',
            'permanentAddressTwo' => 'bail|required',
            'permanentAddressCity' => 'bail|required',
            'permanentAddressPin' => 'bail|required'
        ]);

        $user = User::find($request->userId);

        if($validator->fails()) {
            return redirect("employees/edit/$user->id/addressDetailsTab")
                ->withErrors($validator,'address')
                ->withInput();
        }

        if($user->id != 1 && $user->id == Auth::id()){
            return redirect()->back()->with('basicError','You cannot edit your own profile.');
        }

        $addressData =  [
            'current_address1' => $request->currentAddressOne,
            'current_address2' => $request->currentAddressTwo,
            'permanent_address1' => $request->permanentAddressOne,
            'permanent_address2' => $request->permanentAddressTwo,
            'current_address_city' => $request->currentAddressCity,
            'current_address_pin' => $request->currentAddressPin,
            'permanent_address_city' => $request->permanentAddressCity,
            'permanent_address_pin' => $request->permanentAddressPin
        ];

        UserAddress::updateOrCreate(['user_id'=>$user->id],$addressData);

        return redirect("employees/edit/$user->id/addressDetailsTab")->with('addressSuccess',"Address details updated successfully.");
    }

    /*
        Save the information when an employee is transferred from one unit to another
    */
    public function saveTransferDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oldUnitId' => 'bail|required',
            'transferUnitName' => 'bail|required',
            'newSupervisorName' => 'bail|required',
            //'newDeputyHodId' => 'bail|required',
            //'newHodId' => 'bail|required',
            //'newDgmId' => 'bail|required',
            //'newGmId' => 'bail|required',
            //'newCmdId' => 'bail|required'
        ]);

        if($validator->fails()) {
            return redirect("employees/employeeTransfer")
                ->withErrors($validator,'transfer')
                ->withInput();
        }

        $user = User::where(['id'=>$request->userId])
            ->with(['userSupervisor','otherSupervisor'])
            ->with(['userProfile.department:id,name'])
            ->with(['userProfile.designation:id,name'])
            ->first();

        $userRoles = $user->getRoleNames();
        $oldUserRole = Role::where(['name'=>$userRoles[0]])->first();
        $newRole = Role::find($request->transferRoleName);

        $leaveApprovalAuthorities = $user->leaveApprovalAuthorities()->where(['status'=>'1'])->get();

        $data = [
            'old_role_id' => $oldUserRole->id,
            'new_role_id' => $newRole->id,
            'old_unit_id' => $request->oldUnitId,
            'new_unit_id' => $request->transferUnitName,
            'new_supervisor_id' => $request->newSupervisorName,
            'new_other_supervisor_id' => $request->newOtherSupervisorName,
            'new_deputyhod_id' => $request->newDeputyHodId,
            'new_hod_id' => $request->newHodId,
            'new_dgm_id' => $request->newDgmId,
            'new_gm_id' => $request->newGmId,
            'new_cmd_id' => $request->newCmdId,
            'old_department_id' => $user->userProfile->department->id,
            'new_department_id' => $request->transferDepartmentName,
            'old_designation_id' => $user->userProfile->designation->id,
            'new_designation_id' => $request->transferDesignationName
        ];

        if(!empty($user->userSupervisor->supervisor_id)){
            $data['old_supervisor_id'] = $user->userSupervisor->supervisor_id;
        }

        if(!empty($user->otherSupervisor)){
            $data['old_other_supervisor_id'] = $user->otherSupervisor->supervisor_id;
        }

        if(!$leaveApprovalAuthorities->isEmpty()){
            foreach ($leaveApprovalAuthorities as $key => $value) {
                if($value->priority == '2'){
                    $data['old_deputyhod_id'] = $value->supervisor_id;

                }elseif($value->priority == '3') {
                    $data['old_hod_id'] = $value->supervisor_id;

                }elseif($value->priority == '4') {
                    $data['old_dgm_id'] = $value->supervisor_id;

                }elseif($value->priority == '5') {
                    $data['old_gm_id'] = $value->supervisor_id;

                }elseif($value->priority == '6') {
                    $data['old_cmd_id'] = $value->supervisor_id;
                }
            }
        }

        $userTransfer = $user->userTransfer()->create($data);

        $userUnit = $user->userUnits()->where(['unit_id'=>$data['old_unit_id']])->first();
        $userUnit->update(['unit_id'=>$data['new_unit_id']]);

        $user->assignRole($newRole->name);

        if(!empty($data['new_supervisor_id'])){
            $authority = $user->userSupervisor()->first();

            if(empty($data['old_supervisor_id'])){
                $user->userSupervisor()->create(['supervisor_id'=>$data['new_supervisor_id'],'status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$data['new_supervisor_id'],'status'=>'1']);
            }

            $supervisor = User::find($data['new_supervisor_id']);

            if(!$supervisor->hasPermissionTo('approve-leave')){
                $supervisor->givePermissionTo(['approve-leave']);
            }
        }

        if(!empty($data['new_other_supervisor_id'])){
            $authority = $user->otherSupervisor;

            if(empty($data['old_other_supervisor_id'])){
                $user->otherSupervisor()->create(['supervisor_id'=>$data['new_other_supervisor_id'],'status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$data['new_other_supervisor_id'],'status'=>'1']);
            }

            $supervisor = User::find($data['new_other_supervisor_id']);

            if(!$supervisor->hasPermissionTo('approve-leave')){
                $supervisor->givePermissionTo(['approve-leave']);
            }
        }else{
            if(!empty($data['old_other_supervisor_id'])){
                $user->otherSupervisor()->delete();
            }
        }

        if(!empty($data['new_deputyhod_id'])){
            $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'2','status'=>'1'])->first();

            if(empty($data['old_deputyhod_id'])){
                $user->leaveApprovalAuthorities()->create(['priority'=>'2','supervisor_id'=>$data['new_deputyhod_id'],'status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$data['new_deputyhod_id'],'status'=>'1']);
            }

            $deputyHod = User::find($data['new_deputyhod_id']);

            if(!$deputyHod->hasPermissionTo('approve-leave')){
                $deputyHod->givePermissionTo(['approve-leave']);
            }
        }else{
            if(!empty($data['old_deputyhod_id'])){
                $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'2','status'=>'1'])->first();
                $authority->delete();
            }
        }

        if(!empty($data['new_hod_id'])){
            $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'3','status'=>'1'])->first();

            if(empty($data['old_hod_id'])){
                $user->leaveApprovalAuthorities()->create(['priority'=>'3','supervisor_id'=>$data['new_hod_id'],'status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$data['new_hod_id'],'status'=>'1']);
            }

            $hod = User::find($data['new_hod_id']);

            if(!$hod->hasPermissionTo('approve-leave')){
                $hod->givePermissionTo(['approve-leave']);
            }
        }else{
            if(!empty($data['old_hod_id'])){
                $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'3','status'=>'1'])->first();
                $authority->delete();
            }
        }

        if(!empty($data['new_dgm_id'])){
            $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'4','status'=>'1'])->first();

            if(empty($data['old_dgm_id'])){
                $user->leaveApprovalAuthorities()->create(['priority'=>'4','supervisor_id'=>$data['new_dgm_id'],'status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$data['new_dgm_id'],'status'=>'1']);
            }
        }else{
            if(!empty($data['old_dgm_id'])){
                $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'4','status'=>'1'])->first();
                $authority->delete();
            }
        }

        if(!empty($data['new_gm_id'])){
            $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'5','status'=>'1'])->first();

            if(empty($data['old_gm_id'])){
                $user->leaveApprovalAuthorities()->create(['priority'=>'5','supervisor_id'=>$data['new_gm_id'],'status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$data['new_gm_id'],'status'=>'1']);
            }
        }else{
            if(!empty($data['old_gm_id'])){
                $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'5','status'=>'1'])->first();
                $authority->delete();
            }
        }

        if(!empty($data['new_cmd_id'])){
            $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'6','status'=>'1'])->first();

            if(empty($data['old_cmd_id'])){
                $user->leaveApprovalAuthorities()->create(['priority'=>'6','supervisor_id'=>$data['new_cmd_id'],'status'=>'1']);
            }else{
                $authority->update(['supervisor_id'=>$data['new_cmd_id'],'status'=>'1']);
            }
        }else{
            if(!empty($data['old_cmd_id'])){
                $authority = $user->leaveApprovalAuthorities()->where(['priority'=>'6','status'=>'1'])->first();
                $authority->delete();
            }
        }

        return redirect()->back()->with('transferSuccess','Transfer details saved successfully.');
    }

    /*
        Get all notifications to show on the all messages list page
    */
    public function allMessages()
    {
        $userId = Auth::id();
        $notifications = Notification::where(['status'=>'1','receiver_id'=>$userId])
            ->with(['sender:id,first_name,middle_name,last_name'])
            ->orderBy('created_at','DESC')
            ->paginate(10);

        return view('employees.allMessages')->with(['notifications'=>$notifications]);
    }

    /*
        Soft delete the selected notifications & also mark them as read
    */
    public function deleteMessages(Request $request)
    {
        $selectedIds = $request->selectedIds;

        Notification::whereIn('id',$selectedIds)->update(['status'=>'0','read_status'=>'1']);

        $result['status'] = true;

        return $result;
    }

    /*
        Ajax request to mark some notifications as read
    */
    public function unreadMessages(Request $request)
    {
        $notificationIds = $request->notificationIds;

        Notification::whereIn('id',$notificationIds)->update(['read_status'=>'1']);

        $result['status'] = true;

        return $result;
    }

    /*
        Get my profile information from database & show it on my profile page
    */
    public function myProfile()
    {
        $user = User::where(['id'=>Auth::id()])->with(['userProfile','userQualification','userAddress','userSupervisor.supervisor:id,first_name,middle_name,last_name,employee_code','otherSupervisor.supervisor:id,first_name,middle_name,last_name,employee_code'])->first();

        if(empty($user->profile_pic)){
            $user->profile_pic = config('constants.static.profilePic');
        }else{
            $user->profile_pic = config('constants.uploadPaths.profilePic').$user->profile_pic;
        }

        $leaveApprovalAuthorities = $user->leaveApprovalAuthorities()->where(['status' => '1'])->orderBy('priority','ASC')->with(['supervisor:id,first_name,middle_name,last_name,employee_code'])->get();

        $roles = $user->getRoleNames()->toArray();

        $units = $user->userUnits()->with(['unit:id,name'])->get();

        $permissions = $user->permissions->pluck('name');

        return view('employees.myProfile')->with(['permissions'=>$permissions,'units'=>$units,'user'=>$user,'role'=>$roles[0],'leaveApprovalAuthorities'=>$leaveApprovalAuthorities]);
    }

    /*
        Get other user's profile information from database & show it on user profile page
    */
    public function userProfile($userId)
    {
        $user = User::where(['id'=>$userId])->with(['userProfile','userQualification','userAddress','userSupervisor.supervisor:id,first_name,middle_name,last_name,employee_code','otherSupervisor.supervisor:id,first_name,middle_name,last_name,employee_code'])->first();

        if(empty($user->profile_pic)){
            $user->profile_pic = config('constants.static.profilePic');
        }else{
            $user->profile_pic = config('constants.uploadPaths.profilePic').$user->profile_pic;
        }

        $leaveApprovalAuthorities = $user->leaveApprovalAuthorities()->where(['status' => '1'])->orderBy('priority','ASC')->with(['supervisor:id,first_name,middle_name,last_name,employee_code'])->get();

        $roles = $user->getRoleNames()->toArray();

        $units = $user->userUnits()->with(['unit:id,name'])->get();

        $permissions = $user->permissions->pluck('name');

        return view('employees.userProfile')->with(['permissions'=>$permissions,'units'=>$units,'user'=>$user,'role'=>$roles[0],'leaveApprovalAuthorities'=>$leaveApprovalAuthorities]);
    }

    /*
        Get necessary details to show on the employee transfer form page
    */
    public function employeeTransfer()
    {
        $excluded = User::role(['CMD'])->pluck('id')->toArray();

        $users = User::where('id','!=',1)
            ->where('employee_code','not like','ADMIN%')
            ->where(['status'=>'1'])  //also include approval status
            ->whereNotIn('id',$excluded)
            ->select('id','first_name','middle_name','last_name','employee_code')
            ->get();

        $data['units'] = Unit::where(['status' => '1'])->get();
        $data['dgms'] = User::role('DGM')->where('id','!=',1)->where(['status'=>'1'])->get();
        $data['gms'] = User::role('GM')->where('id','!=',1)->where(['status'=>'1'])->get();
        $data['cmds'] = User::role('CMD')->where('id','!=',1)->where(['status'=>'1'])->get();
        $data['roles'] = Role::get();
        $data['designations'] = Designation::where(['status'=>'1'])->get();
        $data['departments'] = Department::where(['status'=>'1'])->get();
        return view('employees.transferForm')->with(['users'=>$users,'data'=>$data]);
    }

    /*
        Show the list of employees from respective units with role based exceptions
    */
    public function list(Request $request)
    {
        $user = Auth::user();

        if(isset($request->unit_id) && $request->unit_id != 'all'){
            $userUnits = [$request->unit_id];
        }

        if($user->hasRole('Main Administrator')){

            $query = User::with('userProfile.creator:id,first_name,middle_name,last_name')
                ->with('userUnits.unit:id,name');

            if(isset($userUnits)){
                $query = $query->wherehas('userUnits',function($query) use($userUnits){
                    $query->whereIn('unit_id',$userUnits);
                });
            }
            $allUsers = $query->get();

        }
        elseif($user->hasAnyRole(['CMD','GM','DGM'])){


            $query = User::where('id','!=',1)->with('userProfile.creator:id,first_name,middle_name,last_name')
                ->with('userUnits.unit:id,name');

            if(isset($userUnits)){
                $query = $query->wherehas('userUnits',function($query) use($userUnits){
                    $query->whereIn('unit_id',$userUnits);
                });
            }
            $allUsers = $query->get();

        }
        else{

            // $userUnit = $user->userUnits()->first();

            // $allUsers = User::wherehas('userUnits',function($query) use($userUnit){
            //                 $query->where('unit_id','=',$userUnit->unit_id)
            //                       ->where('user_id','!=',1);
            //             })->with('userProfile.creator:id,first_name,middle_name,last_name')
            //             ->with('userUnits.unit:id,name')
            //             ->get();
            $userUnits = $user->userUnits()->distinct('unit_id')->pluck('unit_id')->toArray();

            $allUsers = User::wherehas('userUnits',function($query) use($userUnits){
                $query->whereIn('unit_id',$userUnits)
                    ->where('user_id','!=',1);
            })->with('userProfile.creator:id,first_name,middle_name,last_name')->with('userUnits.unit:id,name')->get();

        }

        $units = Unit::where('status', '1')->get();
        return view('employees.list', compact('units'))->with(['allUsers'=>$allUsers]);
    }


    /*
        Show the list of employees from respective units with role based exceptions
    */
    public function retirementList()
    {
        $user = Auth::user();

        if($user->hasRole('Main Administrator')){

            $allUsers = User::with('userProfile.creator:id,first_name,middle_name,last_name')
                ->with('userUnits.unit:id,name')
                ->get();

        }elseif($user->hasAnyRole(['CMD','GM','DGM'])){

            $allUsers = User::where('id','!=',1)->with('userProfile.creator:id,first_name,middle_name,last_name')
                ->with('userUnits.unit:id,name')
                ->get();

        }else{

            // $userUnit = $user->userUnits()->first();

            // $allUsers = User::wherehas('userUnits',function($query) use($userUnit){
            //                 $query->where('unit_id','=',$userUnit->unit_id)
            //                       ->where('user_id','!=',1);
            //             })->with('userProfile.creator:id,first_name,middle_name,last_name')
            //             ->with('userUnits.unit:id,name')
            //             ->get();
            $userUnits = $user->userUnits()->distinct('unit_id')->pluck('unit_id')->toArray();

            $allUsers = User::wherehas('userUnits',function($query) use($userUnits){
                $query->whereIn('unit_id',$userUnits)
                    ->where('user_id','!=',1)
                    ->where('retirement_date','!=',NULL);
            })->with('userProfile.creator:id,first_name,middle_name,last_name')
                ->with('userUnits.unit:id,name')
                ->get();

        }
        $user = Auth::user();

        $unitIds = $user->userUnits->pluck('unit_id')->toArray();

        $unit = new Unit;
        $employees = $unit->unitWiseEmployees($unitIds);

        return view('employees.retirement_list', compact('employees'))->with(['allUsers'=>$allUsers]);
    }

    function addRetirement(Request $request){
        $user = User::find($request->user_id);
        if($user->retirement_date == NULL) {
            $user->retirement_date = $request->retirement_date;
            $user->save();


            // directed by pankaj && gaurav sir
            $sixMonthBeforeDate = Carbon::parse(date('Y-m-d'))->subMonth('6')->format('Y-m-d');
            if(strtotime($sixMonthBeforeDate) <= strtotime($user->retirement_date)){
                $nonEncashedLeaveCount = 0;
                $nonEncashedLeave = LeaveAccumulation::where('user_id', $user->id)->where('leave_type_id', 3)->latest()->first();
                if ($nonEncashedLeave) {
                    $nonEncashedLeaveCount = $nonEncashedLeave->total_remaining_count;
                }

                $encashableLeaveAccumulation =  $user->leaveAccumulations()->where(['status'=>'1','leave_type_id'=>'11'])->orderBy('id','DESC')->first();

                $totalLeaveAvailable =  $encashableLeaveAccumulation->total_remaining_count + $nonEncashedLeaveCount;
                $maxYearlyLimit = ($totalLeaveAvailable * 2) / 3;
                $maxYearlyLimit = round($maxYearlyLimit, 2);
            }

            LeaveAccumulation::create([
                'user_id' => $user->id,
                'creator_id' => Auth::id(),
                'leave_type_id' => '11',
                'comment' => 'Add Retirement',
                'max_yearly_limit' => $maxYearlyLimit,
                'previous_count' => $encashableLeaveAccumulation->total_remaining_count,
                'total_remaining_count' => $encashableLeaveAccumulation->total_remaining_count,
            ]);

            return back()->with('success', 'Retirement date successfully added');
        }else{
            return back()->with('error', 'Retirement date already added');
        }
    }

    /*
        Activate/Deactivate a selected employee
    */
    public function changeUserStatus($action, $userId)
    {
        $user = User::find($userId);

        if($action == "activate"){
            $user->status = '1';
        }elseif($action == "deactivate"){
            $user->status = '0';
        }

        $user->save();

        return redirect()->back();
    }

    /*
        Show the change password form
    */
    public function changePassword()
    {
        return view('employees.changePasswordForm');
    }

    /*
        Reset the password as entered by user
    */
    public function saveChangePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'bail|required|min:6|max:20',
            'newPassword' => 'bail|required|min:6|max:20',
            'confirmPassword' => 'bail|required|min:6|max:20|same:newPassword'
        ]);

        $user = Auth::user();

        if(Hash::check("$request->oldPassword", $user->password)){
            $user->password = Hash::make($request->confirmPassword);
            $user->save();

            Auth::logout();
            return redirect('/')->with('success','Password Changed Successfully. Please login again.');
        }else{
            return redirect()->back()->with('errorAttempt','Please enter the correct old password.');
        }
    }

    /*
        Upload your profile picture
    */
    public function changeProfilePicture(Request $request)
    {
        if ($request->hasFile('profilePic')) {
            $profilePic = time().'.'.$request->file('profilePic')->getClientOriginalExtension();
            $request->file('profilePic')->move(config('constants.uploadPaths.uploadPic'), $profilePic);

            $user = Auth::user();
            $user->profile_pic = $profilePic;
            $user->save();
        }

        return redirect("/employees/myProfile");
    }

    /*
        Ajax request to get user unit & designation
    */
    public function userUnitAndDesignation(Request $request)
    {
        $user = User::where(['id'=>$request->userId])
            ->with(['userUnits.unit:id,name'])
            ->with(['userProfile.department:id,name'])
            ->with(['userProfile.designation:id,name'])
            ->first();
        $roles = $user->getRoleNames();

        $result['unitName'] = $user->userUnits[0]->unit->name;
        $result['unitId'] = $user->userUnits[0]->unit->id;
        $result['role'] = $roles[0];
        $result['department'] = $user->userProfile->department->name;
        $result['designation'] = $user->userProfile->designation->name;

        return $result;
    }

    /*
        Show import leave accumulations form
    */
    public function importLeaveAccumulations()
    {
        return view('employees.importLeaveAccumulationsForm');
    }

    /*
        Show import users form
    */
    public function importUsers()
    {
        return view('employees.importUsersForm');
    }

    /*
        Upload leave accumulation excel file to import leaves
    */
    public function importAccumulationFile(Request $request)
    {
        ini_set('max_execution_time', 800); //300 seconds = 5 minutes

        $request->validate([
            'accumulationFile' => 'required'
        ]);

        if($request->hasFile('accumulationFile')){

            $import = new LeaveAccumulationsImport();
            $import->onlySheets('Sheet1');

            try {

                $data = Excel::import($import, request()->file('accumulationFile'));
                return redirect()->route("employees.dashboard");

            }catch(\Exception $e) {
                $message = $e->getMessage();
                return redirect()->back()->with('accumulationFileError',$message);

            }

        }
    }

    /*
        Upload users excel file to import users
    */
    public function importUserFile(Request $request)
    {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes

        $request->validate([
            'userFile' => 'required'
        ]);

        if($request->hasFile('userFile')){

            $import = new UsersImport();
            $import->onlySheets('Sheet1');

            try {

                $data = Excel::import($import, request()->file('userFile'));
                return redirect()->route("employees.list");

            }catch(\Exception $e) {

                $message = $e->getMessage();
                return redirect()->back()->with('userFileError',$message);

            }

        }

    }

}//end class

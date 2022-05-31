<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Eloquent;
use Spatie\Permission\Traits\HasRoles;
use Twilio\Rest\Client;
use Validator;
use DB;

class User extends Authenticatable
{
    use Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function userProfile()
    {
        return $this->hasOne('App\UserProfile');
    }

    public function userProfileApproval()
    {
        return $this->hasOne('App\UserProfileApproval');
    }

    public function userAddress()
    {
        return $this->hasOne('App\UserAddress');
    }

    public function userTransfer()
    {
        return $this->hasMany('App\UserTransfer');
    }

    public function userSupervisor()
    {
        return $this->hasOne('App\UserSupervisor');
    }

    public function otherSupervisor()
    {
        return $this->hasOne('App\OtherSupervisor');
    }

    public function userUnits()
    {
        return $this->hasMany('App\UserUnit');
    }

    public function userQualification()
    {
        return $this->hasOne('App\UserQualification');
    }

    public function appliedLeaves()
    {
        return $this->hasMany('App\AppliedLeave');
    }

    public function leaveApprovalAuthorities()
    {
        return $this->hasMany('App\LeaveApprovalAuthority');
    }

    public function appliedLeaveSegregations()
    {
        return $this->hasManyThrough('App\appliedLeaveSegregation', 'App\AppliedLeave');
    }

    public function appliedLeaveApprovals()
    {
        return $this->hasMany('App\AppliedLeaveApproval');
    }

    public function compensatoryLeaves()
    {
        return $this->hasMany('App\CompensatoryLeave');
    }

    public function compensatoryLeaveApprovals()
    {
        return $this->hasMany('App\CompensatoryLeaveApproval');
    }

    public function CallOfExtraDutyLeaves()
    {
        return $this->hasMany(CallOfExtraDuty::class);
    }

    public function CallOfExtraDutyLeaveApprovals()
    {
        return $this->hasMany(CallOfExtraDutyApproval::class, 'supervisor_id');
    }

    public function leaveAccumulations()
    {
        return $this->hasMany('App\LeaveAccumulation');
    }

    public function passwordResetRequests()
    {
        return $this->hasMany('App\PasswordResetRequest');
    }

    ///////////////////////////////////////

    static function sendSms($authority,$message)
    {
//        return 0;
        // try{

        //     $sid    = env('TWILIO_SID');
        //     $token  = env('TWILIO_TOKEN');
        //     $client = new Client( $sid, $token );

        //     $to = '+91'.$user->personal_mobile_number;

        //     $client->messages->create(
        //        $to,
        //        [
        //            'from' => env('TWILIO_FROM'),
        //            'body' => $message,
        //        ]
        //     );

        //     return 0;
        // }catch(\Exception $e){
        //     $message = $e->getMessage();

        //     return $message;
        // }

         try{

             $message = $message['message'];
             $mobile = $authority->personal_mobile_number;

             $ch = curl_init();
             $url = 'http://www.alots.in/sms-panel/api/http/index.php?username=XeamSMSportal&apikey=67191-3D5D9&apirequest=Text&sender=XEAMHR&mobile='.$mobile.'&message='.urlencode($message).'&route=TRANS&format=JSON';
             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             $server_output = curl_exec($ch);
             curl_close ($ch);

            return 0;

         }catch(\Exception $e){
             $message = $e->getMessage();
             return $message;
         }

    }

    public function appliedLeavesList($userId)
    {
        $data = DB::table('applied_leaves as al')
                ->join('leave_types as lt','al.leave_type_id','=','lt.id')
                // ->join('leave_accumulations as la','la.applied_leave_id','=','al.id')
                ->where(['al.user_id' => $userId,'al.status'=>'1'])
                ->select('al.*','lt.name as leave_type_name')
                ->orderBy('al.id','desc')
                ->groupBy('al.id')
                ->get();

        if(!$data->isEmpty()){
            foreach ($data as $key => $value) {
                $priorityWiseStatus = DB::table('applied_leave_approvals as ala')
                                      ->where(['ala.applied_leave_id' => $value->id])
                                      ->select('ala.priority','ala.leave_status')
                                      ->orderBy('ala.priority')
                                      ->get();

                $canCancelLeave = 0;

                if(count($priorityWiseStatus) == 1 && $priorityWiseStatus[0]->leave_status == 0){
                    $canCancelLeave = 1;
                }

                $value->priorityWiseStatus = $priorityWiseStatus;
                $value->canCancelLeave = $canCancelLeave;

            }

        }
        // dd($data);
        return $data;
    }

}//end class

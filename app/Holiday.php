<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Holiday;
use Auth;

class Holiday extends Model
{
    protected $guarded = [];

    public function session()
    {
        return $this->belongsTo('App\Session');
    }

    public function unit()
    {
        return $this->belongsTo('App\Unit');
    }

    public function holidaysBetweenLeaves($requestArray)
    {
        $holidays = [];
        $user = Auth::user();

        if(!empty($requestArray['allDatesArray'])){
            foreach ($requestArray['allDatesArray'] as $key => $value) {
                $date = date("l",strtotime($value));
                $flag = 0;

                if($requestArray['employeeType'] != 'Workman' && ($date != 'Sunday' || $date != 'Saturday')){
                    $flag = 1;

                }elseif($requestArray['employeeType'] == 'Workman'){
                    if(!in_array($value, $requestArray['offDatesArray'])){
                        $flag = 1;
                    }
                }

                if($flag == 1){
                    $check = Holiday::where(['status'=>'1','unit_id'=>$user->userUnits[0]->unit_id])
                        ->where('from_date','<=',$value)
                        ->where('to_date','>=',$value)
                        ->where('holiday_type', 'GH')
                        ->first();

                    if(!empty($check)){
                        $holidays[] = $value;
                    }
                }

            }
        }

        return $holidays;
    }


}//end of class

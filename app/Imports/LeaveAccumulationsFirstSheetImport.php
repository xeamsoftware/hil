<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Auth;
use App\CompensatoryLeave;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeaveAccumulationsFirstSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

        	if(!empty($row['employee_code'])){

        		$checkCode = ['employee_code'=>$row['employee_code']];

        		$user = User::where($checkCode)->first();

        		if(empty($user)){
        			$errorMessage = $row['employee_code']." employee code does not exists in the database. Please create the excel file carefully.";
        			throw new \Exception($errorMessage);

        		}else{

                    ////////////////////////Casual Leave//////////////////////////
                    $prevAccumulation = $user->leaveAccumulations()
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
                                                'max_yearly_limit' => '12',  
                                                'total_remaining_count' => $row['casual_leave'],
                                                'previous_count' => '0' 
                                            ];

                    $newAccumulationData['total_upper_limit'] = '12';                        
                    
                    if($row['casual_leave'] != ""){
                        if(!empty($prevAccumulation)){
                            $newAccumulationData['previous_count'] = $prevAccumulation->total_remaining_count;
                            $prevAccumulation->status = '0';
                            $prevAccumulation->save();
                        }  
    
                        $user->leaveAccumulations()->create($newAccumulationData);
                    }

                    ////////////////////////HPSL Leave//////////////////////////
                    $prevAccumulation = $user->leaveAccumulations()
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
                                                'max_yearly_limit' => '20',  
                                                'total_remaining_count' => $row['hpsl'],
                                                'previous_count' => '0' 
                                            ];

                    $newAccumulationData['total_upper_limit'] = '180';    
                    
                    if($row['hpsl'] != ""){
                        if(!empty($prevAccumulation)){
                            $newAccumulationData['previous_count'] = $prevAccumulation->total_remaining_count;
                            $prevAccumulation->status = '0';
                            $prevAccumulation->save();
                        }
    
                        $user->leaveAccumulations()->create($newAccumulationData);
                    }

                    ////////////////////////EL NonEncash Leave//////////////////////////
                    $prevAccumulation = $user->leaveAccumulations()
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
                                                'max_yearly_limit' => '15',  
                                                'total_remaining_count' => $row['el_non_encashment'],
                                                'previous_count' => '0' 
                                            ];

                    $newAccumulationData['total_upper_limit'] = 'NA';     
                    
                    if($row['el_non_encashment'] != ""){
                        if(!empty($prevAccumulation)){
                            $newAccumulationData['previous_count'] = $prevAccumulation->total_remaining_count;
                            $prevAccumulation->status = '0';
                            $prevAccumulation->save();
                        }
    
                        $user->leaveAccumulations()->create($newAccumulationData);
                    }

                    ////////////////////////EL Encash Leave//////////////////////////
                    $prevAccumulation = $user->leaveAccumulations()
                                      ->where(['status'=>'1','leave_type_id'=>11])
                                      ->orderBy('id','DESC')
                                      ->first();

                    $newAccumulationData =  [
                                                'leave_type_id' => 11,
                                                'creator_id' => Auth::id(),
                                                'applied_leave_id' => 0,
                                                'status' => '1',
                                                'comment' => 'Added Manually',
                                                'yearly_credit_number' => 1,
                                                'max_yearly_limit' => '15',  
                                                'total_remaining_count' => $row['el_encashment'],
                                                'previous_count' => '0' 
                                            ];

                    $newAccumulationData['total_upper_limit'] = 'NA';    
                    
                    if($row['el_encashment'] != ""){
                        if(!empty($prevAccumulation)){
                            $newAccumulationData['previous_count'] = $prevAccumulation->total_remaining_count;
                            $prevAccumulation->status = '0';
                            $prevAccumulation->save();
                        }
    
                        $user->leaveAccumulations()->create($newAccumulationData);
                    }

                    ////////////////////////RH Leave//////////////////////////
                    $prevAccumulation = $user->leaveAccumulations()
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
                                                'max_yearly_limit' => '2',  
                                                'total_remaining_count' => $row['restricted_leave'],
                                                'previous_count' => '0' 
                                            ];

                    $newAccumulationData['total_upper_limit'] = '2'; 
                    
                    if($row['restricted_leave'] != ""){
                        if(!empty($prevAccumulation)){
                            $newAccumulationData['previous_count'] = $prevAccumulation->total_remaining_count;
                            $prevAccumulation->status = '0';
                            $prevAccumulation->save();
                        }
    
                        $user->leaveAccumulations()->create($newAccumulationData);
                    }

                    ////////////////////////Short Leave//////////////////////////
                    $prevAccumulation = $user->leaveAccumulations()
                                      ->where(['status'=>'1','leave_type_id'=>14])
                                      ->orderBy('id','DESC')
                                      ->first();

                    $newAccumulationData =  [
                                                'leave_type_id' => 14,
                                                'creator_id' => Auth::id(),
                                                'applied_leave_id' => 0,
                                                'status' => '1',
                                                'comment' => 'Added Manually',
                                                'yearly_credit_number' => 1,
                                                'max_yearly_limit' => 'NA',  
                                                'total_remaining_count' => $row['short_leave'],
                                                'previous_count' => '0' 
                                            ];

                    $newAccumulationData['total_upper_limit'] = '0.5';
                    $newAccumulationData['yearly_credit_number'] = date("n");     
                    
                    if($row['short_leave'] != ""){
                        if(!empty($prevAccumulation)){
                            $newAccumulationData['previous_count'] = $prevAccumulation->total_remaining_count;
                            $prevAccumulation->status = '0';
                            $prevAccumulation->save();
                        }
    
                        $user->leaveAccumulations()->create($newAccumulationData);
                    }

                    //////////////////Compensatory Leave///////////////////

                    if($row['comp_off'] != "" && !empty($user->userSupervisor->supervisor_id)){
                        $data = [
                                    'on_date' => date("Y-m-d"),
                                    'number_of_hours' => $row['comp_off'],
                                    'description' => 'Added Manually',
                                    'applied_leave_id' => 0,
                                    'selected_supervisor' => $user->userSupervisor->supervisor_id,
                                    'final_status' => '0',
                                    'status' => '1',
                                    'in_time' => '09:30 AM',
                                    'out_time' => '06:30 PM'
                                ];

                        $compensatoryLeave = $user->compensatoryLeaves()->create($data);

                        $compensatoryLeaveApprovalData = [
                                                'user_id' => $user->id,
                                                'supervisor_id' => $user->userSupervisor->supervisor_id,
                                                'priority' => '1',
                                                'leave_status' => '1' 
                                             ];

                        $compensatoryLeaveApproval = $compensatoryLeave->compensatoryLeaveApprovals()->create($compensatoryLeaveApprovalData);  
                        
                        $compensatoryLeave->final_status = '1';
                        $compensatoryLeave->save();  

                        if($compensatoryLeave->final_status == '1'){
                            $originalHours = $compensatoryLeave->number_of_hours;
                            $totalParts = $compensatoryLeave->number_of_hours / 0.5;

                            if($totalParts > 1){
                                $compensatoryLeave->number_of_hours = 0.5;
                                $compensatoryLeave->save();

                                $newData =  [
                                                'user_id' => $compensatoryLeave->user_id,
                                                'on_date' => $compensatoryLeave->on_date,
                                                'number_of_hours' => '0.5',
                                                'applied_leave_id' => 0,
                                                'final_status' => '1',
                                                'status' => '1'   
                                            ];

                                for ($i=1; $i < $totalParts ; $i++) { 
                                    CompensatoryLeave::create($newData);
                                }
                            }  

                            //$user = User::find($compensatoryLeave->user_id);
                            
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
                        }//end if final status 1                 
                    }//end if comp off          
        			
        		}

        	}else{
        		throw new \Exception("Please check the excel file. Please fill the required fields.");
        	}
        	
        }//endforeach
    }
}

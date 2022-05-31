<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\User;
use App\Department;
use App\Designation;
use App\UserProfile;
use App\UserSupervisor;
use App\UserProfileApproval;
use App\LeaveApprovalAuthority;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Auth;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersFirstSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $employees_array = [];
        //print_r($rows);die;
        foreach ($rows as $row) {
            
        	if(!empty($row['unit']) && !empty($row['employee_code']) && !empty($row['employee_type']) && !empty($row['first_name']) && !empty($row['joining_date']) && !empty($row['gender']) && !empty($row['marital_status']) && !empty($row['department']) && !empty($row['designation']) && !empty($row['permissions'])){

        		$userData = [
		                        'employee_code'=>$row['employee_code'],
		                        'employee_type' => $row['employee_type'],
		                        'password' => bcrypt('hil1234'),
		                        'first_name' => $row['first_name'],
		                        'middle_name' => $row['middle_name'],
		                        'last_name' => $row['last_name'],
		                        'official_email' => '',
		                        'personal_email' => $row['personal_email'],
		                        'official_mobile_number' => '',
		                        'personal_mobile_number' => $row['personal_mobile_number'],
		                        'status' => '1',
		                        'approval_status' => '1'
        					];

                //print_r($row);die;            

        		if(empty($row['middle_name'])){
        			$userData['middle_name'] = "";
        		}

                if(empty($row['last_name'])){
                    $userData['last_name'] = "";
                }

        		$checkCode = ['employee_code'=>$row['employee_code']];

        		$user = User::where($checkCode)->first();

        		if(!empty($user)){
        			$errorMessage = $row['employee_code']." employee code already exists in the database. Please create the excel file carefully.";
        			throw new \Exception($errorMessage);
        		}else{
        			$user = User::create($userData);

                    UserProfileApproval::create(['user_id'=>$user->id,'approver_id'=>1]);

                    $code = $row['employee_code'];
                    $employees_array[$code] = $user->id;

        		}

                //dd($user);die;

        		if(!empty($user)){

                    $department = Department::where(['name'=>$row['department']])->first();
                    $designation = Designation::where(['name'=>$row['designation']])->first();

                    // print_r(++$counter);
                    // echo "<br>";
                    // print_r($designation->name);
                    // continue;

                    if(empty($designation)){
                        $designation_data = [
                                                'name' => $row['designation'],
                                                'status' => '1'
                                            ];

                        $designation = Designation::create($designation_data);                    
                    }

                    $joining_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['joining_date']);
                    $new_joining_date = $joining_date->format('Y-m-d'); // convert creation date format

                    $profileData =  [
			                            'creator_id' => Auth::id(),
			                            'father_name' => '',    
			                            'mother_name' => '',    
			                            'joining_date' => $new_joining_date,    
			                            'gender' => $row['gender'],
			                            'marital_status' => $row['marital_status'],
			                            'emergency_contact_name' => '',
			                            'emergency_contact_number' => '',
                                        'spouse_name' => '',
			                            'relationship' => '',
			                            'pan_number' => '',
			                            'adhaar_number' => '',
			                            'blood_group' => '',
			                            'vehicle_number' => '',
                                        'department_id' => $department->id,  
                                        'designation_id' => $designation->id  
			                        ];  


	        		              
        			$user->userProfile()->create($profileData);

                    // if(!empty($row['qualification'])){
                    //     $user->userQualification()->create(['qualification_id'=>$row['qualification']]);
                    // }

        			$unitsString = $row['unit'];
        			$units = explode(",",$unitsString);

        			foreach ($units as $unit) {
        				$user->userUnits()->create(['unit_id'=>$unit]);
        			}

        			$role = Role::find($row['role']);
                    $roles = [];
                    array_push($roles, $role->name);
        			$user->assignRole($roles);
                    
        			$permissionsString = $row['permissions'];
        			$permissions = explode(",",$permissionsString);
        			$user->syncPermissions($permissions);

        		}

        	}else{
                
        		throw new \Exception("Please check the excel file. Please fill the required fields.");
        	}
        	
        }//endforeach

        foreach ($rows as $row) {
            if(!empty($row['so1'])){
                $supervisor = User::where(['employee_code'=>$row['so1']])->first();
        
                if(!empty($supervisor) && !empty($row['employee_code'])){
                    $code = $row['employee_code'];

                    $supervisor_data =  [
                                            'user_id' => $employees_array[$code],
                                            'supervisor_id' => $supervisor->id,
                                            'status' => '1'
                                        ];

                    UserSupervisor::create($supervisor_data);

                    if(!$supervisor->hasPermissionTo('approve-leave')){
                        $supervisor->givePermissionTo(['approve-leave']);
                    }
                }
            }

            if(!empty($row['so2'])){
                $supervisor = User::where(['employee_code'=>$row['so2']])->first();

                if(!empty($supervisor) && !empty($row['employee_code'])){
                    $code = $row['employee_code'];

                    $supervisor_data =  [
                                            'user_id' => $employees_array[$code],
                                            'supervisor_id' => $supervisor->id,
                                            'status' => '1',
                                            'priority' => '2'
                                        ];

                    LeaveApprovalAuthority::create($supervisor_data);

                    if(!$supervisor->hasPermissionTo('approve-leave')){
                        $supervisor->givePermissionTo(['approve-leave']);
                    }
                }
            }

            if(!empty($row['so3'])){
                $supervisor = User::where(['employee_code'=>$row['so3']])->first();

                if(!empty($supervisor) && !empty($row['employee_code'])){
                    $code = $row['employee_code'];

                    $supervisor_data =  [
                                            'user_id' => $employees_array[$code],
                                            'supervisor_id' => $supervisor->id,
                                            'status' => '1',
                                            'priority' => '3'
                                        ];

                    LeaveApprovalAuthority::create($supervisor_data);

                    if(!$supervisor->hasPermissionTo('approve-leave')){
                        $supervisor->givePermissionTo(['approve-leave']);
                    }
                }
            }
            
        }//endforeach
    }
}

<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UnitsAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
        				'employee_code' => 'ADMIN-RASAYANI',
                        'employee_type' => 'M&S',
        				'password' => bcrypt('admin@123'),
        				'first_name' => 'Admin',
        				'middle_name' => '',
        				'last_name' => 'Rasayani',
        				'official_email' => '',
        				'personal_email' => 'adminrasayani@hil.com',
        				'official_mobile_number' => '',
        				'personal_mobile_number' => '',
        				'status' => '1',
                        'approval_status' => '1'	
        			];

        $user = App\User::firstOrCreate($userData);			

        $profileData =  [
        					'creator_id' => 1,
                            'father_name' => '',	
        					'mother_name' => '',	
        					'joining_date' => date("Y-m-d"),	
        					'birth_date' => date("Y-m-d"),
        					'gender' => 'Male',
        					'marital_status' => 'Unmarried',
        					'spouse_name' => '',
        					'emergency_contact_name' => '',
        					'emergency_contact_number' => '',
        					'relationship' => '',
        					'pan_number' => '',
        					'adhaar_number' => '',
        					'blood_group' => 'O+',
        					'vehicle_number' => '',
                            'department_id' => 1,   
                            'designation_id' => 1  
        				];	

        $userProfile = $user->userProfile()->firstOrCreate($profileData);	

        $permissions = Permission::whereNotIn('id',[4,5,6,7,8,9,10])->pluck('name')->toArray();

        $user->syncPermissions($permissions);	

        $user->assignRole(['Supervisor']);	

        $user->userUnits()->create(['unit_id'=>1]);
        $user->userQualification()->create(['qualification_id'=>1]);
        App\UserProfileApproval::create(['user_id'=>$user->id,'approver_id'=>1]);
        
        ///////////////////////////////////////////////////////////////////////////

        $userData = [
        				'employee_code' => 'ADMIN-UDHYOGA',
                        'employee_type' => 'M&S',
        				'password' => bcrypt('admin@123'),
        				'first_name' => 'Admin',
        				'middle_name' => '',
        				'last_name' => 'Udhyoga',
        				'official_email' => '',
        				'personal_email' => 'adminudhyoga@hil.com',
        				'official_mobile_number' => '',
        				'personal_mobile_number' => '',
        				'status' => '1',
                        'approval_status' => '1'	
        			];

        $user = App\User::firstOrCreate($userData);			

        $profileData =  [
        					'creator_id' => 1,
                            'father_name' => '',	
        					'mother_name' => '',	
        					'joining_date' => date("Y-m-d"),	
        					'birth_date' => date("Y-m-d"),
        					'gender' => 'Male',
        					'marital_status' => 'Unmarried',
        					'spouse_name' => '',
        					'emergency_contact_name' => '',
        					'emergency_contact_number' => '',
        					'relationship' => '',
        					'pan_number' => '',
        					'adhaar_number' => '',
        					'blood_group' => 'O+',
        					'vehicle_number' => '',
                            'department_id' => 1,   
                            'designation_id' => 1  
        				];	

        $userProfile = $user->userProfile()->firstOrCreate($profileData);	

        $permissions = Permission::whereNotIn('id',[4,5,6,7,8,9,10])->pluck('name')->toArray();

        $user->syncPermissions($permissions);	

        $user->assignRole(['Supervisor']);	

        $user->userUnits()->create(['unit_id'=>2]);
        $user->userQualification()->create(['qualification_id'=>1]);
        App\UserProfileApproval::create(['user_id'=>$user->id,'approver_id'=>1]);

        ///////////////////////////////////////////////////////////////////////////

        $userData = [
        				'employee_code' => 'ADMIN-BHATINDA',
                        'employee_type' => 'M&S',
        				'password' => bcrypt('admin@123'),
        				'first_name' => 'Admin',
        				'middle_name' => '',
        				'last_name' => 'Bhatinda',
        				'official_email' => '',
        				'personal_email' => 'adminbhatinda@hil.com',
        				'official_mobile_number' => '',
        				'personal_mobile_number' => '',
        				'status' => '1',
                        'approval_status' => '1'	
        			];

        $user = App\User::firstOrCreate($userData);			

        $profileData =  [
        					'creator_id' => 1,
                            'father_name' => '',	
        					'mother_name' => '',	
        					'joining_date' => date("Y-m-d"),	
        					'birth_date' => date("Y-m-d"),
        					'gender' => 'Male',
        					'marital_status' => 'Unmarried',
        					'spouse_name' => '',
        					'emergency_contact_name' => '',
        					'emergency_contact_number' => '',
        					'relationship' => '',
        					'pan_number' => '',
        					'adhaar_number' => '',
        					'blood_group' => 'O+',
        					'vehicle_number' => '',
                            'department_id' => 1,   
                            'designation_id' => 1  
        				];	

        $userProfile = $user->userProfile()->firstOrCreate($profileData);	

        $permissions = Permission::whereNotIn('id',[4,5,6,7,8,9,10])->pluck('name')->toArray();

        $user->syncPermissions($permissions);	

        $user->assignRole(['Supervisor']);	

        $user->userUnits()->create(['unit_id'=>3]);
        $user->userQualification()->create(['qualification_id'=>1]);
        App\UserProfileApproval::create(['user_id'=>$user->id,'approver_id'=>1]);

        //////////////////////////////////////////////////////////////////////////////

        $userData = [
        				'employee_code' => 'ADMIN-KOLKATA',
                        'employee_type' => 'M&S',
        				'password' => bcrypt('admin@123'),
        				'first_name' => 'Admin',
        				'middle_name' => '',
        				'last_name' => 'Kolkata',
        				'official_email' => '',
        				'personal_email' => 'adminkolkata@hil.com',
        				'official_mobile_number' => '',
        				'personal_mobile_number' => '',
        				'status' => '1',
                        'approval_status' => '1'	
        			];

        $user = App\User::firstOrCreate($userData);			

        $profileData =  [
        					'creator_id' => 1,
                            'father_name' => '',	
        					'mother_name' => '',	
        					'joining_date' => date("Y-m-d"),	
        					'birth_date' => date("Y-m-d"),
        					'gender' => 'Male',
        					'marital_status' => 'Unmarried',
        					'spouse_name' => '',
        					'emergency_contact_name' => '',
        					'emergency_contact_number' => '',
        					'relationship' => '',
        					'pan_number' => '',
        					'adhaar_number' => '',
        					'blood_group' => 'O+',
        					'vehicle_number' => '',
                            'department_id' => 1,   
                            'designation_id' => 1  
        				];	

        $userProfile = $user->userProfile()->firstOrCreate($profileData);	
        $permissions = Permission::whereNotIn('id',[4,5,6,7,8,9,10])->pluck('name')->toArray();

        $user->syncPermissions($permissions);	

        $user->assignRole(['Supervisor']);	

        $user->userUnits()->create(['unit_id'=>4]);
        $user->userQualification()->create(['qualification_id'=>1]);
        App\UserProfileApproval::create(['user_id'=>$user->id,'approver_id'=>1]);
        
    }//end of function

}//end of class 

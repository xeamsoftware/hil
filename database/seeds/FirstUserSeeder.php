<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FirstUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
        				'employee_code' => '000',
                        'employee_type' => 'M&S',
        				'password' => bcrypt('admin@321'),
        				'first_name' => 'Super',
        				'middle_name' => '',
        				'last_name' => 'Admin',
        				'official_email' => '',
        				'personal_email' => 'admin@hil.com',
        				'official_mobile_number' => '',
        				'personal_mobile_number' => '8699979759',
        				'status' => '1',
                        'approval_status' => '1'	
        			];

        $user = App\User::firstOrCreate($userData);			

        $profileData =  [
        					'creator_id' => $user->id,
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

        $permissions = Permission::pluck('name')->toArray();

        $user->syncPermissions($permissions);	

        $user->assignRole(['Main Administrator']);	

        $user->userUnits()->create(['unit_id'=>1]);
        $user->userUnits()->create(['unit_id'=>2]);
        $user->userUnits()->create(['unit_id'=>3]);
        $user->userUnits()->create(['unit_id'=>4]);
        $user->userQualification()->create(['qualification_id'=>1]);
        
        ///////////////////////////////////////////////////////////////////////////
        App\UserProfileApproval::create(['user_id'=>1,'approver_id'=>1]);

    }
}

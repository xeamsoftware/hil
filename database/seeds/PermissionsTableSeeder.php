<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions =  [
				           'create-user',
				           'edit-user',
                   'approve-user',
				           'approve-leave',
				           'manage-masterTable',
				           'apply-leave',
				           'generate-leaveReport',
                   'import-user',
                   'import-leave',
                   'verify-attendance',
                   'reset-password'
				        ];

		foreach ($permissions as $key => $value) {
        	Permission::create(['name'=>$value]);
        }		        
    }
}

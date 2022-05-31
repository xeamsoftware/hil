<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
        			'Main Administrator',
        			'HR',
        			'DGM',
        			'GM',
        			'HOD',
        			'Dy.HOD',
        			'CMD',
        			'Supervisor',
                    'Employee'
        		 ];

        foreach ($roles as $key => $value) {
		 	Role::create(['name' => $value]);
		}		 
    }
}

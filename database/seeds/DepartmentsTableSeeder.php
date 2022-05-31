<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        			['name'=>'PRODUCTION','status'=>'1'],
        			['name'=>'ENGINEERING','status'=>'1'],
                    ['name'=>'HR','status'=>'1'],
                    ['name'=>'GM CELL','status'=>'1'],
                    ['name'=>'COMM.','status'=>'1'],
                    ['name'=>'DGM CELL','status'=>'1']
        		];

        foreach ($data as $key => $value) {
			App\Department::create($value);
		}
    }
}

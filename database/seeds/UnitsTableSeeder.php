<?php

use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        			['name'=>'Rasayani Unit','status'=>'1'],
        			['name'=>'Udhyoga Mandal Unit','status'=>'1'],
        			['name'=>'Bhatinda Unit','status'=>'1'],
        			['name'=>'RSO Kolkata Unit','status'=>'1']
        		];

        
		foreach ($data as $key => $value) {
			App\Unit::create($value);
		}
			
    }
}

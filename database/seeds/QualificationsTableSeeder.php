<?php

use Illuminate\Database\Seeder;

class QualificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        			['name'=>'High School','hindi_name'=>'माध्यामिक शिक्षा','status'=>'1'],
        			['name'=>'Intermediate','hindi_name'=>'इंटर पास (व्यति)','status'=>'1'],
        			['name'=>'Diploma','hindi_name'=>'डिप्लोमा','status'=>'1'],
        			['name'=>'Graduation','hindi_name'=>'स्नातक','status'=>'1'],
        			['name'=>'Post Graduation','hindi_name'=>'स्नातकोत्तर','status'=>'1'],
                    ['name'=>'None','hindi_name'=>'कुछ भी नहीं','status'=>'1']
        		];

        foreach ($data as $key => $value) {
			App\Qualification::create($value);
		}		
    }
}

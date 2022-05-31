<?php

use Illuminate\Database\Seeder;

class LeaveTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        			['name'=>'Casual Leave','hindi_name'=>'सामान्य छुट्टी','status'=>'1'],
        			['name'=>'Half Pay Sick Leave (HPSL)','hindi_name'=>'बीमारी की छुट्टी (आधा वेतन) एच पी एस एल','status'=>'1'],
                    ['name'=>'EL - Non Encashable','hindi_name'=>'ई.एल. - गैर नकद योग्य','status'=>'1'],
                    ['name'=>'Compensatory Off','hindi_name'=>'मुआवज़ा छुट्टी','status'=>'1'],
                    ['name'=>'Sterlisation Leave','hindi_name'=>'नसबंदी छुट्टी','status'=>'1'],
                    ['name'=>'Blood Donation','hindi_name'=>'रक्त दान','status'=>'1'],
                    ['name'=>'Quarantine Leave','hindi_name'=>'संगरोध छुट्टी','status'=>'1'],
                    ['name'=>'Maternity Leave','hindi_name'=>'मातृत्व छुट्टी','status'=>'1'],
                    ['name'=>'Paternity Leave','hindi_name'=>'पितृत्व छुट्टी','status'=>'1'],
                    ['name'=>'Extra Ordinary Leave (EOL)','hindi_name'=>'असाधारण छुट्टी','status'=>'1'],
                    ['name'=>'EL - Encashable','hindi_name'=>'ई.एल. - नकद योग्य्','status'=>'1'],
                    ['name'=>'Restricted Holiday (RH)','hindi_name'=>'आर.एच. -प्रतिबंधित अवकाश','status'=>'1'],
                    ['name'=>'Transfer / Joining Leave','hindi_name'=>'स्थानांतरण','status'=>'1'],
                    ['name'=>'Short Leave','hindi_name'=>'अल्पावधि छुट्टी','status'=>'1'],
                    ['name'=>'Special Casual Leave','hindi_name'=>'विशेष आकस्मिक अवकाश','status'=>'1'],
        		];

        foreach ($data as $key => $value) {
			App\LeaveType::create($value);
		}		
    }
}

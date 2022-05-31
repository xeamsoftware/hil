<?php

use Illuminate\Database\Seeder;

class LeaveAccumulationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        			['user_id'=>5,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>5,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>5,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>5,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>5,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>5,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>6,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>6,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>6,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>6,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>6,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>6,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>7,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>7,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>7,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>7,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>7,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>7,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>8,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>8,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>8,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>8,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>8,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>8,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>8,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>15,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>15,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>15,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>15,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>15,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>15,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>16,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>16,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>16,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>16,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>16,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>16,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>17,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>17,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>17,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>17,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>17,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>17,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>18,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>18,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>18,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>18,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>18,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>18,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>22,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>22,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>22,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>22,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>22,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>22,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>23,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>23,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>23,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>23,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>23,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>23,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>24,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>24,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>24,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>24,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>24,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>24,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>25,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>25,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>25,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>25,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>25,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>25,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>29,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>29,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>29,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>29,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>29,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>29,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>30,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>30,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>30,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>30,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>30,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>30,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>31,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>31,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>31,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>31,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>31,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>31,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"],

                    ['user_id'=>32,'creator_id'=>1,'leave_type_id'=>1,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '12','total_upper_limit' => '12','max_yearly_limit' => '12','comment'=>"Added Manually"],
                    ['user_id'=>32,'creator_id'=>1,'leave_type_id'=>2,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => '180','max_yearly_limit' => '20','comment'=>"Added Manually"],
                    ['user_id'=>32,'creator_id'=>1,'leave_type_id'=>3,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>32,'creator_id'=>1,'leave_type_id'=>11,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '145','total_upper_limit' => 'NA','max_yearly_limit' => '15','comment'=>"Added Manually"],
                    ['user_id'=>32,'creator_id'=>1,'leave_type_id'=>14,'status'=>'1','yearly_credit_number'=>date("n"),'previous_count'=>'0','total_remaining_count' => '0.5','total_upper_limit' => '0.5','max_yearly_limit' => 'NA','comment'=>"Added Manually"],
                    ['user_id'=>32,'creator_id'=>1,'leave_type_id'=>12,'status'=>'1','yearly_credit_number'=>1,'previous_count'=>'0','total_remaining_count' => '2','total_upper_limit' => '2','max_yearly_limit' => '2','comment'=>"Added Manually"]
        		];

        foreach ($data as $key => $value) {
			App\LeaveAccumulation::create($value);
		}
    }
}

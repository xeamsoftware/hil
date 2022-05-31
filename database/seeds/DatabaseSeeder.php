<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(QualificationsTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(DesignationsTableSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(LeaveTypesTableSeeder::class);
        $this->call(FirstUserSeeder::class);
        $this->call(UnitsAdminSeeder::class);
        //$this->call(LeaveApprovalAuthoritiesTableSeeder::class);
        //$this->call(LeaveAccumulationsTableSeeder::class);
    }
}

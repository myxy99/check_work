<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\users::create([
            'user_name' => 'Admin112233',
            'department_name' => '超级管理管理员',
            'passwd' => bcrypt('Admin112233'),
            'is_admin'=>1,
        ]);
    }
}

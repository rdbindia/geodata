<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([

            ['id'=>'1', 'user'=>'User1', 'email'=>'abc@abc.com', 'email_verified_at' =>now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'created_at' => now(), 'updated_at' => now()],

            ['id'=>'2', 'user'=>'User2', 'email'=>'xyz@xyz.com', 'email_verified_at' =>now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'created_at' => now(), 'updated_at' => now() ],

        ]);
    }
}

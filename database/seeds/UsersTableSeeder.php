<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'firstName' => 'Direct',
            'lastName' => 'Connect',
            'email' => 'directconnect@gmail.com',
            'password' => bcrypt('direct786'),
            'created_at'  =>now(),
            'updated_at' => now()
        ]);
    }
}

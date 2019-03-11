<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'admin',  
            'created_at' => now(),
            'updated_at' => now()              
        ]);
        DB::table('roles')->insert([
            'name' => 'customer',  
            'created_at' => now(),
            'updated_at' => now()              
        ]);
    }
}

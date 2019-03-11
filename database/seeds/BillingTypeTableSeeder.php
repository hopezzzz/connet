<?php

use Illuminate\Database\Seeder;

class BillingTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data  = array(  array(
                          'planType' => 'normal',
                          'status' => '0',
                          'is_deleted' => '0',
                          'created_at'  =>now(),
                          'updated_at' => now()
                          ),
                          array(
                          'planType' => 'Pay per minute',
                          'status' => '1',
                          'is_deleted' => '0',
                          'created_at'  =>now(),
                          'updated_at' => now()
                                ),
                          array(
                          'planType' => 'Trial Plan',
                          'status' => '1',
                          'is_deleted' => '0',
                          'created_at'  =>now(),
                          'updated_at' => now()
                          ),
                  );
    DB::table('billing_type')->insert($data);
    }
}

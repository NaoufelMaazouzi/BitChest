<?php

use Illuminate\Database\Seeder;

class CryptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cryptos')->insert([
            [
                'name' => 'Bitcoin',
                'price' => floatval('44083,82') 
            ]
        ]);
    }
}

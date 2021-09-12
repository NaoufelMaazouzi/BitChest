<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PuschaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('purchases')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'crypto' => 'Bitcoin',
                'price' => floatval('44083,82'),
                'amount' => floatval(1),
                'totalValue' => floatval('44083,82'),
                'imageUrl' => 'https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579',
                'date' => Carbon::now()
            ]
        ]);
    }
}

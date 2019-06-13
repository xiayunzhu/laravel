<?php

use Illuminate\Database\Seeder;

class BuyerAddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=BuyerAddressesTableSeeder
     * @return void
     */
    public function run()
    {
        //
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;
        }

        $count = 0;
        $buyers = \App\Models\Buyer::orderBy('id', 'asc')->limit(10)->get();
        if ($buyers) {
            foreach ($buyers as $buyer) {
                factory(\App\Models\BuyerAddress::class, 1)->create(['buyer_id' => $buyer->id, 'shop_id' => $buyer->shop_id]);
                $count++;
            }
        }
        echo 'count:' . $count . ' run script ' . __CLASS__ . ' end !' . PHP_EOL;
    }
}

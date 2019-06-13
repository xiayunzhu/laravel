<?php

use Illuminate\Database\Seeder;

class BuyersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=BuyersTableSeeder
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
        $shops = \App\Models\Shop::all();
        if ($shops) {
            foreach ($shops as $shop) {
                $res = factory(\App\Models\Buyer::class, 1)->create(['shop_id' => $shop->id]);
                $count++;
            }
        }
        echo 'run script ' . __CLASS__ . ' end !' . PHP_EOL . ',count:' . $count.PHP_EOL;
    }
}

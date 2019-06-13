<?php

use Illuminate\Database\Seeder;

class WxappsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=WxappsTableSeeder
     * @return void
     */
    public function run()
    {
        //
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;
        }
        $shops = \App\Models\Shop::all();
        foreach ($shops as $index => $shop) {
            $row = ['app_name' => $shop->shop_nick . '的商城', 'shop_id' => $shop->id];

            if ($index == 0) {
                $row['app_name'] = 'JJG的商城';
                $row['app_id'] = 'wx704aa63adc4ef822';
                $row['app_secret'] = '3a09b2678adc5c3ebd96d984426ef715';
            }

            if ($index == 1) {
                $row['app_name'] = 'ZJY的商城';
                $row['app_id'] = 'wx7ba43f874a4a6516';
                $row['app_secret'] = 'ba124dfd199456edb6c8bc3a461b5ed5';
                $row['mchid'] = '1530321461';
                $row['apikey'] = 'wrKvqKJJsHkQTvKtNujDYRFmHmwwEeKC';
            }

            factory(\App\Models\Wxapp::class, 1)->create($row);
        }

        echo 'run script ' . __CLASS__ . ' end !' . PHP_EOL;
    }
}

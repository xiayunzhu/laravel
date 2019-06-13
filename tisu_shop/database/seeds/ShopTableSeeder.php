<?php

use Illuminate\Database\Seeder;

class ShopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=ShopTableSeeder
     * @return void
     */
    public function run()
    {
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;

        }


        $sellers = \App\Models\User::where('user_type', \App\Models\User::USER_TYPE_SELLER)->get();
        foreach ($sellers as $seller) {
            $shop_code = 10000 + $seller->id;
            $exist = \App\Models\Shop::where('shop_code', $shop_code)->count();
            if (!$exist)
                factory(\App\Models\Shop::class, 1)->create(['shop_code' => 10000 + $seller->id, 'shop_name' => $seller->name . '的店铺', 'shop_nick' => $seller->name . '的小店', 'user_id' => $seller->id]);
        }

        echo 'run script ' . __CLASS__ . ' end !' . PHP_EOL;

    }
}

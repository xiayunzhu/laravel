<?php

use Illuminate\Database\Seeder;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=FavoritesTableSeeder
     * @return void
     */
    public function run()
    {
        //
         $i = 0;
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;
        }

        $goods = \App\Models\Goods::orderBy('id', 'asc')->limit(5)->get();
        $shop = \App\Models\Shop::orderBy('id', 'asc')->limit(5)->get();
        $buyer = \App\Models\User::orderBy('id', 'asc')->limit(5)->get();
        if ($goods && $shop && $buyer) {
            foreach ($goods as $good) {
                foreach ($shop as $sh) {
                    foreach ($buyer as $by) {
                        \App\Models\Favorites::firstOrCreate(['buyer_id' => $by->id, 'goods_id' => $good->id, 'shop_id' => $sh->id]);
                        $i++;
                    }
                }
            }
        }

        echo 'count:' . $i;
    }

}

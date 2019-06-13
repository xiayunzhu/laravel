<?php

use Illuminate\Database\Seeder;

class PromosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=PromosTableSeeder
     */
    public function run()
    {
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;
        }

        $count = 0;
        $shops = \App\Models\Shop::all();
        if ($shops) {
            foreach ($shops as $shop) {
                factory(\App\Models\Promo::class, 1)->create(['shop_id' => $shop->id]);
                factory(\App\Models\Promo::class, 1)->create(['shop_id' => $shop->id]);
                $model = factory(\App\Models\Promo::class, 1)->create(['shop_id' => $shop->id]);

                if ($model[0]['range'] != \App\Models\Promo::GOOD_RANGE_ALL_CAN) {
                    $goods = \App\Models\Goods::where('shop_id', $shop->id)->get();
                    foreach ($goods as $good) {
                        $promoId = isset($model[0]['id']) ? $model[0]['id'] : null;
                        if ($promoId) {
                            \App\Models\PromoItem::create(['shop_id' => $shop->id, 'promo_id' => $promoId, 'goods_id' => $good->id, 'status' => array_rand(\App\Models\PromoItem::$statusMap)]);
                        }
                    }
                }

                $count++;
            }
        }
        echo __FUNCTION__ . ',count:' . $count . ',' . PHP_EOL;
    }
}

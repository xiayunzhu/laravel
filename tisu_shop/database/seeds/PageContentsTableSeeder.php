<?php

use Illuminate\Database\Seeder;

class PageContentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=PageContentsTableSeeder
     * @return void
     */
    public $pageContents = [

    ];

    /**
     * Run the database seeds.
     *
     * @return void
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
                $pageContentTypes = \App\Models\PageContent::$pageContentTypeMap;
                foreach ($pageContentTypes as $type => $value) {
                    $model = factory(\App\Models\PageContent::class, 1)->create(['type' => $type, 'shop_id' => $shop->id, 'image_url' => "images/pics/201903/30/dcRTQ6fYpwTPTNEoVv9WLpZmiAXulU3hmPzQcEXV.png"]);

                    $pcId = isset($model[0]['id']) ? $model[0]['id'] : null;
                    if ($pcId) {
                        $this->createPageContentsGoods($pcId, $shop);
                        $this->createPageItems($pcId);
                    }

                    $count++;
                }
            }
        }

    }

    /**
     * @param $pcId
     * @param $shop
     */
    public function createPageContentsGoods($pcId, $shop)
    {
        $goodsCount = rand(1, 10);

        for ($i = 0; $i < $goodsCount; $i++) {
            $goods = \App\Models\Goods::where('shop_id', $shop->id)
                ->inRandomOrder()
                ->first();


            if ($goods) {
                if ($goods_id = $goods->id)
                    \App\Models\PageContentsGood::create(['page_contents_id' => $pcId, 'goods_id' => $goods_id]);
            } else {
                echo __FUNCTION__ . ',shop:' . $shop->id . ', goods is null ' . PHP_EOL;
            }


        }
    }

    /**
     * @param $pageContentId
     */
    public function createPageItems($pageContentId)
    {
        $img_urls = [
            "http://www.pblab.com/images/star/wh1.jpg",
            "http://www.pblab.com/images/star/wh8.jpg",
            "http://www.pblab.com/images/star/wh2.jpg",
            "http://www.pblab.com/images/star/wh5.jpg",
            "http://www.pblab.com/images/star/wh3.jpg",
            "http://www.pblab.com/images/star/wh4.jpg",
            "http://www.pblab.com/images/star/wh6.jpg",
            "http://www.pblab.com/images/star/wh7.jpg",
        ];
        foreach ($img_urls as $key => $img_url) {
            \App\Models\PageContentsItem::create(['image_url' => $img_url, 'sort' => $key, 'page_contents_id' => $pageContentId]);
        }
    }
}

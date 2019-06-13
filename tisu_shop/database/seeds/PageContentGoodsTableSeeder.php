<?php

use Illuminate\Database\Seeder;

class PageContentGoodsTableSeeder extends Seeder
{
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
        $pageContents = \App\Models\PageContent::all();

        if ($pageContents) {
            foreach ($pageContents as $pageContent) {
                $goods = \App\Models\Goods::where('shop_id', $pageContent->shop_id)->limit(5)->get();
                foreach ($goods as $good){
                    \App\Models\PageContentsGood::create(['goods_id' => $good->id, 'page_contents_id' => $pageContent->id]);
                }
                $count++;
            }
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class PageContentItemsTableSeeder extends Seeder
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

        if ($pageContents) {
            foreach ($pageContents as $pageContent) {
                foreach ($img_urls as $key => $img_url){
                    \App\Models\PageContentsItem::create(['image_url' => $img_url,'sort' => $key, 'page_contents_id' => $pageContent->id]);
                }
                $count++;
            }
        }
    }
}

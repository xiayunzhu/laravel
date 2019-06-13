<?php

use App\Handlers\GoodsHandler;
use Illuminate\Database\Seeder;

class GoodsTableSeeder extends Seeder
{
    /**
     * @var GoodsHandler
     */
    private $goodsHandler;

    public function __construct(GoodsHandler $goodsHandler)
    {
        $this->goodsHandler = $goodsHandler;
    }

    /**
     * Run the database seeds.
     * php artisan db:seed --class=GoodsTableSeeder
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
        $org_goods = \App\Models\OrgGood::all();

        if ($shops && $org_goods) {
            foreach ($shops as $shop) {
                foreach ($org_goods as $org_good) {
                    try {

                        $org_good->load('specs');
                        if ($org_good->specs) {
                            foreach ($org_good->specs as $spec) {
                                $this->goodsHandler->copyFormOrg($shop->id, $spec->id, \App\Models\OrgGood::PUBLISH_STATUS_UPPER);
                            }
                        }

                        echo PHP_EOL . print_r(true) . PHP_EOL;
                        $count++;
                    } catch (\Exception $exception) {

                        echo date('YmdHis') . ',error:' . $exception->getMessage() . PHP_EOL;
                    }

                }
            }
        }

        echo 'run script ' . __CLASS__ . ' end !' . PHP_EOL . ',count:' . $count . PHP_EOL;


    }
}

<?php

use Illuminate\Database\Seeder;

class StocksTableSeeder extends Seeder
{

    private $stockHandler;

    public function __construct(\App\Handlers\StockHandler $stockHandler)
    {
        $this->stockHandler = $stockHandler;
    }

    /**
     * Run the database seeds.
     * php artisan db:seed --class=StocksTableSeeder
     * @return void
     */
    public function run()
    {
        //
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;
        }
        $filePath = storage_path('logs/report-' . date('Y-m-d') . '.log');//realpath();

        file_put_contents($filePath, __CLASS__ . PHP_EOL, FILE_APPEND);
        $start_time = microtime(true);
        $count = 0;
        $stock = ["available" => "可用库存", "item_code" => "商品编码", "modified" => "库存修改时间", "oln_item_id" => "", "oln_sku_id" => "", "quantity" => "实际库存", "sku_code" => "系统规格编码", "storage_code" => "001", "storage_name" => "仓库01"];
        # 查询商品
        \App\Models\Product::orderBy('id', 'asc')->chunk(100, function ($products) use ($stock, &$count) {
            foreach ($products as $product) {
                $stock['available'] = rand(1, 200);
                $stock['quantity'] = $stock['available'] + rand(1, 100);
                $stock['item_code'] = $product->item_code;
                $stock['sku_code'] = $product->spec_code;
                $stock['modified'] = \Carbon\Carbon::now();

                $model = $this->stockHandler->store($stock);
                $count++;
                echo $model->id . PHP_EOL;
            }
        });

        $end_time = microtime(true);

        $usedSec = ($end_time - $start_time);
        $report = config('database.default') . ' sec:' . $usedSec . ', count:' . $count . ',rate:' . ($count / $usedSec);
        var_dump($report);

        file_put_contents($filePath, $report . PHP_EOL, FILE_APPEND);
    }
}

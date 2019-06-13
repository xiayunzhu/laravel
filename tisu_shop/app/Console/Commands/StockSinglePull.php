<?php

namespace App\Console\Commands;

use App\Lib\Wln\WLnStockSinglePull;
use Illuminate\Console\Command;

class StockSinglePull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:single-pull {item_id} {sku_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '单商品库存拉取,参数一:商品编号 参数二: 规格编码(非必填)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $item_id = $this->argument('item_id');
        $sku_id = $this->argument('sku_id') ?: '';

        $client = new WLnStockSinglePull();
        $client->setItemId($item_id);
        $client->setSkuId($sku_id);
        $result = $client->handle();

        $this->info(print_r($result,true));
    }
}

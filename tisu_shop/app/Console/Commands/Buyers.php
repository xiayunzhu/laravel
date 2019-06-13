<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/22
 * Time: 18:15
 */

namespace App\Console\Commands;


use App\Handlers\BuyerHandler;
use App\Models\CustomerData;
use App\Models\Shop;
use Illuminate\Console\Command;
use App\Models\Buyer;

class Buyers extends Command
{
    /**
     * The name and signature of the console command.
     * php artisan buyer:count
     * @var string
     */
    protected $signature = 'buyer:count';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计当天的客流量';

    private $buyerHandler;

    /**
     * Buyer constructor.
     * @param BuyerHandler $buyerHandler
     */
    public function __construct(BuyerHandler $buyerHandler)
    {
        $this->buyerHandler = $buyerHandler;
        parent::__construct();
    }

    public function handle()
    {

        $beginToday = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        $endToday = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1);
        $shopIDs = Shop::get(['id'])->toArray();
        $buyers = Buyer::whereBetween('created_at', [$beginToday, $endToday])->get(['shop_id'])->toArray();
        $param=[];
        foreach ($shopIDs as $val) {
            $count = 0;
            $all_count = Buyer::where('shop_id', $val['id'])->count();
            foreach ($buyers as $buyer) {
                if ($val['id'] == $buyer['shop_id']) {
                    $count++;
                }
            }

            $param[] = ['shop_id' => $val['id'],
                'customer_new' => $count,
                'customer_total' => $all_count,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
                'time' => strtotime('-5hours'),
            ];

        }
        if (count($param)>0) {
            $this->buyerHandler->create($param);
        }
    }
}
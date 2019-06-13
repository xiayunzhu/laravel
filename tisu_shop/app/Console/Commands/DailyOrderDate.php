<?php

namespace App\Console\Commands;

use App\Handlers\OrderHandler;
use App\Models\Order;
use App\Models\Shop;
use Illuminate\Console\Command;

class DailyOrderDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DailyOrderData:record {begin?}{end?}';


    protected $orderHandler;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计各店铺订单数据[默认统计前一天的数据]:参数1:开始时间（非必填,格式:2019-04-24） 参数2:结束时间（非必填）';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OrderHandler $orderHandler)
    {
        $this->orderHandler = $orderHandler;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dailyOrderData();

    }

    public function dailyOrderData()
    {
        $shops = Shop::select('id')->get();
        ##格式  2019-04-25
        $begin_time = $this->argument('begin') ? strtotime($this->argument('begin')) : strtotime(date("Y-m-d", strtotime('-1 day')));
        $end_time = $this->argument('end') ? strtotime($this->argument('end')) : $begin_time + 86400;
        ## 统计每日的订单数据
        foreach ($shops as $shop) {
            $query = Order::query();
            $query->where('shop_id', $shop['id']);
            $query->whereBetween('create_time', [$begin_time, $end_time]);
            $query->chunk(100, function ($orders) {
                ##数据处理
                $orderData = $this->orderHandler->dailyOrderData($orders);
                $this->info($orderData);
            });
            if (!$query->first())
                $this->info("店铺{$shop['id']}暂无数据");
        }
    }
}

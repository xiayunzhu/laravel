<?php

namespace App\Console\Commands;

use App\Lib\Wln\WLnTrade;
use Illuminate\Console\Command;

class OrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:status {order_nos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '根据订单编号 order_no 状态查询,多个订单以半角逗号相隔，如”123,456”，最多支持 200 个订单号';

    private $wLnTrade;

    /**
     * Create a new command instance.
     *
     * @param WLnTrade $wLnTrade
     */
    public function __construct(WLnTrade $wLnTrade)
    {
        $this->wLnTrade = $wLnTrade;
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
        $order_nos = $this->argument('order_nos');
        $this->wLnTrade->setTradeIds($order_nos);
        $res = $this->wLnTrade->status();
        $this->info(print_r($res, true));
    }
}

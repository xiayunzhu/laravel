<?php

namespace App\Console\Commands;

use App\Events\OrderSendEvent;
use App\Models\Order;
use Illuminate\Console\Command;

class OrderPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:push {order_no?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单推送到ERP';

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
        $order_no = $this->argument('order_no') ?: null;
        if ($order_no) {
            $order = Order::where('order_no', $order_no)->first();

            //触发事件
            event(new OrderSendEvent($order));
        }
    }
}

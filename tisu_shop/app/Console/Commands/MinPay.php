<?php

namespace App\Console\Commands;

use Curl\Curl;
use Illuminate\Console\Command;

class MinPay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'min:pay {order_no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '小程序支付测试脚本';

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
     * @throws \ErrorException
     */
    public function handle()
    {
        $order_no = $this->argument('order_no');

        $curl = new Curl();
        $url = config('app.url');

        $res = $curl->post($url . '/api/wx/pay/unifiedOrder', ['order_no' => $order_no]);
        $res->response;

        $data = json_decode($res->response, true);

        $this->info(print_r($data, true));

    }
}

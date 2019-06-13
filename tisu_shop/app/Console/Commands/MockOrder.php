<?php

namespace App\Console\Commands;

use Curl\Curl;
use Illuminate\Console\Command;

class MockOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mock:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '被门挤过的核桃还能补脑吗';

    protected $url = 'http://local.tss.com/api/wx/orders/store';
    protected $curl_type = 'POST';
    protected $postData = [];


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
        $result = $this->requestDemo();
    }

    /**
     * 订单创建请求参数
     */
    private function orderFrakeData()
    {
        $data = [
            "shop_name" => "小季",
            "shop_nick" => "小季的商城",
            "source" => "WeChat_applet",
            "total_fee" => "1",
            "discount_fee" => "1",
            "express_price" => "1",
            "buyer_msg" => "测试订单",
            "seller_msg" => "随意买买",
            "buyer" => "季建贵",
            "buyer_id" => "2",
            "shop_id" => "2",
            "receiver" => "王贵",
            "mobile" => "15869021868",
            "phone" => "64483498",
            "province" => "浙江省",
            "city" => "杭州市",
            "district" => "江干区",
            "detail" => "东谷创业",
            "zip_code" => "325804",
            "create_time" => 12121212,
            "order_items" => [
                [
                    "goods_spec_id" => "2",
                    "num" => 5
                ],
                [
                    "goods_spec_id" => "2",
                    "num" => 1
                ]

            ]
        ];

        return $data;
    }

    /**
     * 执行curl
     * @return bool|mixed
     */
    public function requestDemo()
    {
        $this->postData = $this->orderFrakeData();

        for ($i = 0; $i < 1000; $i++) {
            $res = $this->curlPost();
        }
        \Log::info(__CLASS__ . ':' . print_r($res, true));
        return $res;
    }

    public function curlPost()
    {
        $curl = new Curl();

        $res = $curl->post($this->url, $this->postData);
        $json_data = $res->response;

        if (!$json_data) {
            return false;
        }

        return is_json($json_data) ? json_decode($json_data, true) : $json_data;
    }

}

<?php

namespace App\Console\Commands;

use Curl\Curl;
use Illuminate\Console\Command;

class WlnB2cApiDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wln:b2c-api-demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '万里牛B2C接口测试';

    private $choiceMethods = [
        '0' => 'none',
        '1' => 'push.category.demo',
        '2' => 'push.good.demo',
        '3' => 'pull.good.stock.single.demo',
        '4' => 'pull.good.stock.demo',
        '5' => 'push.trade.demo',
        '6' => 'pull.trade.status.demo',
    ];

    private $apiRoute = [
        'none' => 'none',
        'push.category.demo' => 'v1/categories/open',
        'push.good.demo' => 'v1/items/open',
        'pull.good.stock.single.demo' => 'v1/inventories/erp/single',
        'pull.good.stock.demo' => 'v1/inventories/erp',
        'push.trade.demo' => 'v1/trades/open',
        'pull.trade.status.demo' => 'v1/trades/erp/status',
    ];

    protected $url = 'http://114.67.231.99/open/api/'; ### 测试地址

//    protected $url = 'http://open.hupun.com/api/'; ### 正式地址

    protected $app_key = '19ZY0226TEST';
    protected $app_secret = '01A3F37CF67F3EFDA61127980B31C2B8';
    protected $app_format = 'json';
    protected $hash_type = 'md5';

    protected $shopNick = 'zhiyue';

    protected $curl_type = 'POST';

    protected $postData = [];

    protected $params = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->postData = [
            'app_key' => $this->app_key,
            'sign' => '',
            'format' => $this->app_format,
            'timestamp' => (int)floor(microtime(true) * 1000),
        ];
    }

    /**
     * 签名规则
     * 对所有API请求参数（包括公共参数和请求参数，但除去sign参数），根据参数名称的ASCII码表的顺序排序。如：foo=1, bar=2, foo_bar=3, foobar=4排序后的顺序是bar=2, foo=1, foo_bar=3, foobar=4。
     * 将排序好的参数名和参数值拼装在一起，根据上面的示例得到的结果为：bar2foo1foo_bar3foobar4。
     * 把拼装好的字符串采用utf-8编码，使用签名算法对编码后的字节流进行摘要。如果使用MD5算法，则需要在拼装的字符串前后加上app的secret后，再进行摘要，如：md5(secret+bar2foo1foo_bar3foobar4+secret)
     * 将摘要得到的字节结果使用大写表示
     * @param  array $params 一维数组，若存在需要二维 ，使用 json_encode() 对数据进行JSON编码
     * @param  string $app_secret app_id对应的密码
     * @return string
     */
    function generateMd5Sign($params = array(), $app_secret = '')
    {
        if (isset($params['sign']))
            unset($params['sign']);

        ksort($params);

        $tmps = array();
        foreach ($params as $k => $v) {
            $tmps[] = $k . $v;
        }

        $string = $app_secret . implode('', $tmps) . $app_secret;
        return strtoupper(md5($string));
    }

    /**
     * 命令开始
     * @throws \ErrorException
     */
    public function handle()
    {
        $method = $this->choice('请选择测试的api,默认[0]', $this->choiceMethods, 0);
        $this->info('您选择的请求接口：' . $method);
        if ($method) {
            $this->buildFakeDatas($method);
            $result = $this->requestDemo();
            \Log::info(__CLASS__ . ':' . print_r($result, true));
            $this->info(var_export($result, true));
        }
    }

    /**
     * 执行curl
     * @return bool|mixed
     * @throws \ErrorException
     */
    public function requestDemo()
    {
        if ($this->hash_type == 'md5')
            $this->postData['sign'] = $this->generateMd5Sign($this->postData, $this->app_secret);
        if (strtoupper($this->curl_type) == 'POST') {
            $res = $this->curlPost();
        } else {
            $res = $this->curlGet();
        }
        $json_data = $res->response;
        if (!$json_data)
            return false;
        return json_decode($json_data, true);
    }

    /**
     * Post 请求
     * @return Curl
     * @throws \ErrorException
     */
    public function curlPost()
    {
        $curl = new Curl();
        $this->info(__FUNCTION__ . $this->url);
        $res = $curl->post($this->url, $this->postData);
        return $res;
    }

    /**
     * Get 请求
     * @return Curl
     * @throws \ErrorException
     */
    public function curlGet()
    {
        $curl = new Curl();
        $this->info(__FUNCTION__ . $this->url);
        $this->info($this->curl_type);
        $res = $curl->get($this->url, $this->postData);
        return $res;
    }

    /**
     * 伪造数据
     * @param $app_name
     */
    private function buildFakeDatas($app_name)
    {
        if ($app_name)
            $this->url .= $this->apiRoute[$app_name];
        $this->curl_type = 'POST';
        switch ($app_name) {
            case 'push.category.demo':
                $this->postData['categories'] = json_encode($this->buildFakeCategory());
                break;
            case 'push.good.demo':
                // 成功时会返回 'response' => '["4245"]' 数据
                $this->postData['items'] = json_encode($this->buildFakeGood());
                break;
            case 'pull.good.stock.single.demo':
                $this->curl_type = 'GET';
                $this->postData['shop_type'] = 100; // 店铺类型，B2C 平台：100
                $this->postData['shop_nick'] = $this->shopNick; // 店铺昵称，必须和商品推送中的店铺昵称相同
                $this->postData['item_id'] = '3403'; // 商品编号，对应商品推送中的 itemID，如 TEST0002：4245
                $this->postData['sku_id'] = '1939631'; // 非必填，如果商品含规格，则必填，对应商品推送的中 skuID
//                $this->postData['storage_code']    = '1939631'; // 非必填，ERP 中的仓库编码，默认返回所有仓库的库存
                break;
            case 'pull.good.stock.demo':
                $this->curl_type = 'GET';
                $this->postData['page'] = 1; // 当前页，默认为 1
                $this->postData['limit'] = 100; // 每页条数，默认：80，最大值：200
                $this->postData['start'] = date('Y-m-d H:i:s', strtotime('-10 day')); // 修改库存的开始时间，格式：yyyy-MM-dd HH:mm:ss
                $this->postData['end'] = date('Y-m-d H:i:s', strtotime('tomorrow')); // 修改库存的结束时间，格式：yyyy-MM-dd HH:mm:ss
//                $this->postData['storage_code']    = '1939631'; // 非必填，ERP 中的仓库编码，默认返回所有仓库的库存
                break;
            case 'push.trade.demo':
                $this->postData['trades'] = json_encode($this->buildFakeTrade());
//                $this->info(var_export($this->buildFakeTrade(), true));
//                $this->postData['trades'] = json_encode([]);
                break;
            case 'pull.trade.status.demo':
                $this->curl_type = 'GET';
                $this->postData['shop_type'] = 100; // 店铺类型，B2C 平台：100
                $this->postData['shop_nick'] = $this->shopNick; // 店铺类型，B2C 平台：100
                $this->postData['trade_ids'] = '56f5f3285ec77a5d97194a598a5afb8c,50b853cad0e061bd10c6fed10dc7a535'; // 订单号，多个订单以半角逗号相隔，如”123,456”，最多支持 200 个订单号
                break;
        }
    }

    /**
     * 伪造类目数据
     * @return array
     */
    private function buildFakeCategory()
    {
        $randPid = mt_rand(1, 999);
        $randCid = mt_rand($randPid++, 9999);
        $randPname = chr(mt_rand(65, 90));
        $randCname = $randPname . chr(mt_rand(65, 90));
        $data = [
            [
                'shopNick' => $this->shopNick, // ERP 系统中的店铺昵称
                'categoryID' => "{$randPid}", // B2C 系统商品类目编号
                'name' => $randPname, // 类目名称
                'parentID' => '1', // 非必填，子类目需必填
                'status' => 1, // 状态，1：使用中；0：已删除
                'sortOrder' => 0, // 类目排序索引
            ],
        ];
        return $data;
    }

    /**
     * 伪造商品数据
     * @return array
     */
    private function buildFakeGood()
    {
        $randPid = mt_rand(1001, 9999);
        $randCid = $randPid . mt_rand(1, 999);
        $data = [
            [
                'itemID' => "{$randPid}", // 线上系统商品编号，单规格商品 唯一标识
                'categoryID' => '001', // 非必填，商品类目编号（属于哪个类目）
                'shopNick' => $this->shopNick, // ERP 中的店铺昵称
                'title' => '测试商品04', // 商品标题
                'itemCode' => 'test0004', // 非必填，商家编码，商家自己输入的编码
                'price' => 1.23, // 单价
                'quantity' => 1, // 库存
                'itemURL' => 'https:www.baidu.com', // 商品地址
                'imageURL' => 'https:www.baidu.com', // 图片地址
                'status' => 2, // 状态，0：已删除，1：在售，2：待售，仓库中
                'createTime' => 1551337423123, // 创建时间，毫秒级时间戳，如 1421585369113
                'modifyTime' => 1551337430123, // 最新修改时间，毫秒级时间戳，如 1421585369113
                'properties' => '', // 非必填，商品属性，key1:value;key2:value;... 以key:value 的键值形式拼接，必须使用半角符。如生产商:万里牛;原料:实木
                'brand' => '', // 非必填，品牌，如 nike
                'weight' => 1.023, // 非必填，重量
                'barcode' => '', // 非必填，条码
                // 规格集，详见 Sku
                'skus' => [
                    [
                        'skuID' => "{$randCid}", // B2C 系统规格编号，多规格商品 唯一标识
                        'itemID' => "{$randPid}", // B2C 系统商品编号
                        'quantity' => 1, // 库存
                        'price' => 1.23, // 单价
                        'skuCode' => 'test0004-1', // 非必填，商家编码，商家自己输入的编码
                        'createTime' => 1551337423123, // 创建时间，毫秒级时间戳，如 1421585369113
                        'modifiyTime' => 1551337430123, // 最新修改时间，毫秒级时间戳，如1421585369113
                        'imageURL' => 'https:www.baidu.com', // 规格图片地址
                        'status' => 2, // 状态，0：已删除，1：在售，2：待售，仓库中
                        'weight' => 1.023, // 非必填，重量
                        'barcode' => '', // 非必填，条码
                        'attributes' => '颜色:红色', // 规格：key1:value;key2:value;...以 key:value 的键值形式拼接，必须使用半角符，如颜色:红色;尺码:M
                    ]
                ],
            ],
        ];
        return $data;
    }

    /**
     * 伪造订单数据
     * @return array
     */
    private function buildFakeTrade()
    {
        $tradeId = md5(uniqid());
        $orderId = md5(uniqid());
        $data = [
            [
                'tradeID' => $tradeId, // 第三方交易号
                'shopNick' => $this->shopNick, // 对应到 ERP 中的店铺昵称
                'status' => 1, // 交易状态:0：未创建交易；1：等待付款；2：等待发货；3：已完成；4：已关闭；5：等待确认；6：已签收
                'createTime' => 1551337423123, // 交易创建时间，毫秒级时间戳，如 1421585369113
//                'payTime'          => 1551337430123, // 非必填，付款后必填，交易付款时间，毫秒级时间戳，如 1421585369113，付款后才会有值，其他状态不传
//                'endTime'          => 0, // 非必填，结束后必填，交易结束时间，毫秒级时间戳，如 1421585369113，结束后才会有值，其他状态不传
                'modifyTime' => 1551337430123, // 交易修改时间，毫秒级时间戳，如 1421585369113，订单每次修改更新该值
//                'shippingTime'     => 0, // 非必填，发货后必填，交易发货时间，毫秒级时间戳，如 1421585369113，发货后才会有值，其他状态不传
//                'storeID'          => '', // 非必填，仓库编码，与系统基础信息仓库相对应
//                'sellerMemo'       => '', // 非必填，卖家备注
                'shippingType' => 0, // 发货类型:0：快递；1：EMS；2：平邮；9：卖家承担运费（包邮）；11：虚拟物品；121：自提；122：商家自送（门店配送）
                'totalFee' => 100, // 商品总金额，不含邮费
                'postFee' => 10, // 邮费
                'payment' => 110, // 非必填，买家最后实际支付金额
                'discountFee' => 0, // 非必填，总的优惠金额
                'buyer' => 'test_buyer_01', // 买家
//                'buyerMessage'     => '', // 非必填，买家备注
//                'buyerEmail'       => '', // 非必填，买家邮箱
                'receiverName' => '测试买家01', // 收件人
                'receiverProvince' => '浙江省', // 收件地址：省
                'receiverCity' => '杭州市', // 收件地址：市
                'receiverArea' => '江干区', // 收件地址：区
                'receiverAddress' => '地铁东城广场', // 收件详细地址
//                'receiverZip'      => '', // 非必填，收件地址：邮编
                'receiverMobile' => '15267847898', // 收件人手机
//                'receiverPhone'    => '', // 非必填，收件人座机
//                'identityNum'      => '', // 非必填，身份证号码
                'hasRefund' => 0, // 退款退货标记，1：退款；0：未退款
//                'invoice'          => '', // 非必填，发票抬头
                // 明细数组
                'orders' => [
                    [
                        'tradeID' => $tradeId, // 交易号
                        'orderID' => $orderId, // 子交易号
                        'itemID' => '4245', // 商品编号
                        'itemTitle' => '测试商品02', // 商品标题，如万里牛
//                        'itemCode'  => 'TEST0002', // 非必填，商品标题，如万里牛
//                        'skuID'     => '', // 非必填，多规格必填，规格编号
//                        'skuTitle'  => 'M', // 非必填，规格值，如红色,M
                        'skuCode' => 'TEST0002', // 非必填，规格商家编码
                        'status' => 1, // 明细状态: 0：未创建订单；1：等待付款；2：等待发货；3：已完成；4：已关闭；5：等待确认；
//                        'hasRefund' => 0, // 非必填，是否为退款/退货明细，0：无退款；1：有退款
                        'price' => 150, // 商品单价
                        'size' => 1, // 数量
//                        'snapshot'  => '', // 非必填，商品链接或者快照链接
                        'imageUrl' => 'http:www.baidu.com', // 商品图片地址
                        'payment' => 100, // 明细实付
                    ]
                ],
            ]
        ];

        return $data;
    }
}

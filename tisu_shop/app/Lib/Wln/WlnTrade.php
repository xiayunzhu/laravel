<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/16
 * Time: 14:20
 */

namespace App\Lib\Wln;


class WLnTrade extends WlnB2cClient
{
    /**
     * 推送的订单集合
     * @var array
     */
    private $trades = [];

    /**
     * 查询的订单ID
     * 订单号，多个订单以半角逗号相隔，如”123,456”，最多支持 200 个订单号
     * @var string
     */
    private $trade_ids = '';

    /**
     * 订单状态查询
     *
     * @return mixed
     */
    public function status()
    {
        $this->setMethod('v1/trades/erp/status');
        ## 必要参数
        $this->appendParams([
            'shop_type' => 100,// 店铺类型，B2C 平台：100
            'shop_nick' => config('bs.erp.shop_nick'),// 店铺昵称，必须和商品推送中的店铺昵称相同
            'trade_ids' => $this->trade_ids,//订单ID
        ]);
        //设置 GET 请求
        $this->setRequestMethod('GET');

        return $this->load();
    }

    /**
     * 订单推送
     * @return mixed
     */
    public function push()
    {

        $this->setMethod('v1/trades/open');//订单推送
        ## 必要参数
        $this->appendParams(['trades' => json_encode($this->trades)]);

        return $this->load();
    }

    /**
     * @return array
     */
    public function getTrades(): array
    {
        return $this->trades;
    }

    /**
     * @param array $trades
     */
    public function setTrades(array $trades)
    {
        $this->trades = $trades;
    }

    /**
     * @return string
     */
    public function getTradeIds(): string
    {
        return $this->trade_ids;
    }

    /**
     * @param string $trade_ids
     */
    public function setTradeIds(string $trade_ids)
    {
        $this->trade_ids = $trade_ids;
    }

}
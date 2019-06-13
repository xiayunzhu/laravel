<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/16
 * Time: 13:21
 */

namespace App\Lib\Wln;


class WLnStockSinglePull extends WlnB2cClient
{
    /**
     * 必填 商品编号，对应商品推送中的 itemID，如 TEST0002：4245
     * @var string
     */
    private $item_id;

    /**
     * 非必填，如果商品含规格，则必填，对应商品推送的中 skuID
     * @var string
     */
    private $sku_id;

    /**
     * 非必填，ERP 中的仓库编码，默认返回所有仓库的库存
     * @var string
     */
    private $storage_code;

    public function handle()
    {
        $this->setMethod('v1/inventories/erp/single');//商品拉取接口
        $this->setRequestMethod('GET');
        $this->appendParams(
            [
                'shop_type' => 100,// 店铺类型，B2C 平台：100
                'shop_nick' => config('bs.erp.shop_nick'),// 店铺昵称，必须和商品推送中的店铺昵称相同
                'item_id' => $this->item_id,
            ]
        );

        //非必填项
        if (!empty($this->sku_id)) {
            $this->appendParams(['sku_id' => $this->sku_id]);
        }
        //非必填项
        if (!empty($this->storage_code)) {
            $this->appendParams(['storage_code' => $this->storage_code]);
        }

        return $this->load();

    }

    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->item_id;
    }

    /**
     * @param string $item_id
     */
    public function setItemId(string $item_id)
    {
        $this->item_id = $item_id;
    }

    /**
     * @return string
     */
    public function getSkuId(): string
    {
        return $this->sku_id;
    }

    /**
     * @param string $sku_id
     */
    public function setSkuId(string $sku_id)
    {
        $this->sku_id = $sku_id;
    }

    /**
     * @return string
     */
    public function getStorageCode(): string
    {
        return $this->storage_code;
    }

    /**
     * @param string $storage_code
     */
    public function setStorageCode(string $storage_code)
    {
        $this->storage_code = $storage_code;
    }


}
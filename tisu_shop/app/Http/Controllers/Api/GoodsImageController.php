<?php

namespace App\Http\Controllers\Api;

use App\Handlers\GoodsImageHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GoodsImage\DeleteRequest;
use App\Http\Requests\Api\GoodsImage\PicMoveRequest;
use App\Http\Requests\Api\GoodsImage\UpdateRequest;
use App\Lib\Response\Result;
use App\Models\GoodsImage;

/**
 * @group 商品图片管理(goods image management)
 * author:zhaona
 * review_at:2019-05-14
 *
 * Class GoodsImageController
 * @package App\Http\Controllers\Api
 */
class GoodsImageController extends Controller
{

    private $goodsImageHandler;

    public function __construct(GoodsImageHandler $goodsImageHandler)
    {
        $this->goodsImageHandler = $goodsImageHandler;
    }


    /**
     * 商品图片激活隐藏(api.goods_image.is_show)
     * @queryParam id required 图片ID Example: 1
     * @queryParam is_show required 显示（display）或隐藏（hidden） Example: hidden
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param UpdateRequest $request
     * @param Result $result
     * @return array
     */
    public function is_show(UpdateRequest $request, Result $result)
    {
        try {
            $data = $this->goodsImageHandler->is_show($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 商品图片删除(api.goods_image.delete)
     * @queryParam id required 图片ID Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param DeleteRequest $request
     * @param Result $result
     * @return array
     */
    public function delete(DeleteRequest $request, Result $result)
    {
        $id = $request->get('id');
        try {
            $goodsGroup = GoodsImage::find($id);
            if ($goodsGroup) {
                $goodsGroup->delete();
                $result->setMessage('删除成功');
                $result->succeed(true);
            } else {
                $result->failed('已删除');
            }

        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 商品图片排序上移下移(api.goods_image.sort)
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param PicMoveRequest $request
     * @param Result $result
     * @return array
     */
    public function picMove(PicMoveRequest $request, Result $result)
    {
        try {
            $this->goodsImageHandler->picMove($request);
            $result->succeed(true);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

}

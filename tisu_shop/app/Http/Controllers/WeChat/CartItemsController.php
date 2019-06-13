<?php

namespace App\Http\Controllers\WeChat;

use App\Exceptions\CartItemsException;
use App\Handlers\CartItemHandler;
use Ml\Response\Result;
use App\Models\User;
use App\Models\GoodsSpec;
use App\Http\Requests\WeChat\CartItem\StoreRequest;
use App\Http\Requests\WeChat\CartItem\UpdateRequest;
use App\Http\Requests\WeChat\CartItem\ListRequest;
use App\Http\Requests\WeChat\CartItem\DeleteBatRequest;
use App\Http\Controllers\Controller;


class CartItemsController extends Controller
{

    private $cartItemHandler;

    public function __construct(CartItemHandler $cartItemHandler)
    {
        $this->cartItemHandler = $cartItemHandler;
    }

    /**
     * 购物车列表
     *
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request, Result $result)
    {
        $data = $this->cartItemHandler->page($request);
        $data->load(['good_spec','good_spec.good']);
        if ($data) {
            $data = $data->toArray();
            $data = fmt_array($data, ['image_url' => 'image_link']);
        }
        $result->succeed($data);

        return $result->toArray();
    }
    /**
     * 添加购物车
     *
     * @param StoreRequest $request
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function store(StoreRequest $request, Result $result)
    {
        try {

            if (!User::find($request->get('user_id')))
                throw new CartItemsException('该用户不存在');

            if(!GoodsSpec::find($request->get('goods_spec_id')))
                throw new CartItemsException('该商品不存在');

            $data = $this->cartItemHandler->store($request);
            $result->succeed($data);
        } catch (CartItemsException $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }

    /**
     * 修改购物车商品数量
     *
     * @param UpdateRequest $request
     * @param int $id
     * @param Result $result
     * @return array
     */
    public function update(UpdateRequest $request, int $id, Result $result)
    {
        try {
            $data = $this->cartItemHandler->update($request, $id);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }



    /**
     * 删除
     *
     * @param int $id
     * @param Result $result
     * @return array
     */
    public function delete(int $id, Result $result)
    {
        try {
            $data = $this->cartItemHandler->delete($id);
            $result->setMessage('删除成功');
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }


    /**
     * 批量删除
     * @param DeleteBatRequest $request
     * @param Result $result
     * @return array
     */
    public function destroyBat(DeleteBatRequest $request, Result $result)
    {
        try {
            $ids = $request->get('ids');
            if ($ids && is_array($ids)) {
                $dels = $this->cartItemHandler->destroyBat($ids);
                if ($dels > 0) {
                    $result->succeed($ids);
                } else {
                    $result->failed('删除失败');
                }
            } else {
                $result->failed('参数错误');
            }
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }


}

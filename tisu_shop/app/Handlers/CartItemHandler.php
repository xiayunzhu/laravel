<?php

namespace App\Handlers;


use App\Exceptions\CartItemsException;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;

class CartItemHandler
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = CartItem::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['user_id'])) {
                    if (!empty($value)) {
                        $query->where($field, $value);
                    }
                }
            }
        }

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        $query->select(['id', 'goods_spec_id', 'num']);
        $data = $query->paginate($per_page);
        return $data;
    }


    /**
     * @param Request $request
     * @return bool
     */
    public function store(Request $request)
    {
        $isCart = $this->checkCart($request->get('user_id'), $request->get('goods_spec_id'));

        if ($isCart) {
            $isCart->addNum($request->get('num'));
            return $isCart;
        }

        $row = $request->only(CartItem::$fields);
        return CartItem::create($row);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     * @throws CartItemsException
     */
    public function update(Request $request, int $id)
    {
        $cartItem = CartItem::find($id);
        if ($cartItem) {
            $cartItem->num = $request->get('num');
            $cartItem->save();
        } else {
            throw new CartItemsException('该条购物车记录不存在');
        }

        return $cartItem;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws CartItemsException
     */
    public function delete(int $id)
    {
        $cartItem = CartItem::find($id);
        if ($cartItem) {
            $user_id = \request('user_id');
            if ($cartItem->user_id != $user_id) {
                throw new CartItemsException('操作失败');
            }
            $cartItem->delete();
        } else {
            throw new CartItemsException('已删除');
        }

        return $cartItem;
    }

    /**
     * @param $ids
     * @return mixed
     * @throws CartItemsException
     */
    public function destroyBat($ids)
    {
        $user_id = \request('user_id');
        $dels = CartItem::whereIn('id', $ids)->where('user_id', $user_id)->delete();
        if (!$dels) {
            throw new CartItemsException('删除失败');
        }

        return $dels;
    }

    /**
     * @param $user_id
     * @param $goods_spec_id
     * @return bool
     */
    public function checkCart($user_id, $goods_spec_id)
    {
        $count = CartItem::where(
            [
                ['user_id', '=', $user_id],
                ['goods_spec_id', '=', $goods_spec_id]
            ]
        )->count();

        if ($count > 0) {
            return CartItem::where(
                [
                    ['user_id', '=', $user_id],
                    ['goods_spec_id', '=', $goods_spec_id]
                ]
            )->first();
        }
        return false;
    }

    /**
     * 清空购物车(下单SKU)
     * @param $user_id
     * @param $goods_spec_ids
     * @return boolean
     * @throws CartItemsException
     */
    public function flushCartItems($user_id, $goods_spec_ids)
    {

        if (!User::find($user_id))
            throw new CartItemsException('该用户不存在[' . $user_id . ']');
        if (!is_array($goods_spec_ids))
            throw new CartItemsException('goods_spec_ids参数必须是数组类型');

        $res = CartItem::where('user_id', $user_id)->whereIn('goods_spec_id', $goods_spec_ids)->delete();
        if ($res)
            return true;
        return false;
    }

}
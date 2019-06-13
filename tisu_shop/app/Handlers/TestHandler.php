<?php
/**
 * Created by PhpStorm.
 * User: jjg
 * Date: 2019-04-22
 * Time: 11:13
 */

namespace App\Handlers;


use App\Exceptions\OrderException;
use App\Models\Buyer;
use App\Models\GoodsSpec;
use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class TestHandler
{
    /**
     * @var OrderHandler
     */
    private $orderHandler;

    public function __construct(OrderHandler $orderHandler)
    {
        $this->orderHandler = $orderHandler;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws OrderException
     */
    public function testCreate(Request $request)
    {
        // 模拟数据
        ## 设置店铺信息
        $shop_id = 2;
        $shop = Shop::find($shop_id);
        if (!$shop) {
            throw new OrderException('店铺不存在[' . $shop_id . ']');
        }
        $request->offsetSet('shop_nick', $shop->shop_nick);
        $request->offsetSet('shop_name', $shop->shop_name);
        ## 设置买家信息
        $buyer = Buyer::where('shop_id', '=', $shop_id)->first();
        if (!$buyer) {
            throw new OrderException('没有买家');
        }
        $request->offsetSet('buyer', $buyer->nick_name);
        $user = User::where('open_id', $buyer->open_id)->first();
        if (!$user) {
            throw new OrderException('没有用户');
        }
        $request->offsetSet('user_id', $user->id);

        ## 订单数据
        $request->offsetSet('express_price', 10);
        $request->offsetSet('express_company', 'SF');
        $request->offsetSet('buyer_msg', '测试' . date('YmdHis'));
        $request->offsetSet('seller_msg', '测试' . date('YmdHis'));
        $request->offsetSet('receiver', 'JJG');
        $request->offsetSet('mobile', '15869021868');
        $request->offsetSet('province', '浙江省');
        $request->offsetSet('city', '杭州市');
        $request->offsetSet('district', '江干区');
        $request->offsetSet('detail', '东谷');
        $request->offsetSet('total_fee', 0);
        $request->offsetSet('paid_fee', 0);
        $request->offsetSet('shop_id', $shop_id);
        $request->offsetSet('buyer_id', $buyer->id);

        $order_items = [];
        $goods_specs = GoodsSpec::where('shop_id', $shop_id)->limit(3)->get();
        foreach ($goods_specs as $spec) {
            $order_items[] = [
                'goods_spec_id' => $spec->id,
                'num' => rand(1, 10)
            ];
        }

        $request->offsetSet('order_items', $order_items);

        $request->offsetSet('source', Order::SOURCE_WE_CHAT_APPLET);


        return $this->orderHandler->createOrder($request);

    }
}
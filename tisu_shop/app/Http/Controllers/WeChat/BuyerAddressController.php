<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/3/19
 * Time: 17:20
 */

namespace App\Http\Controllers\WeChat;


use App\Handlers\BuyerAddressHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BuyerAddressRequest;
use App\Http\Requests\WeChat\BuyerAddress\IsdefaultRequest;
use App\Http\Requests\WeChat\BuyerAddress\StoreRequest;
use App\Lib\Response\Result;
use App\Models\BuyerAddress;
use Illuminate\Http\Request;


class BuyerAddressController extends Controller
{

    private $fields = ['receiver', 'mobile', 'phone', 'province', 'city', 'district', 'detail', 'zip_code', 'is_default', 'buyer_id', 'shop_id', 'user_id'];

    private $buyeraddressHandler;

    public function __construct(BuyerAddressHandler $buyeraddressHandlerHandler)
    {
        $this->buyeraddressHandler = $buyeraddressHandlerHandler;
    }

    /**
     * 收货人地址
     * @param BuyerAddressRequest $request
     * @param Result $result
     * @return array
     */
    public function list(BuyerAddressRequest $request, Result $result)
    {
        //校验店铺归属
        $buyer_id = $request->get('buyer_id');
        $request->offsetSet('buyer_id', $buyer_id);

        $data = $this->buyeraddressHandler->page($request);
        return $result->succeed($data)->toArray();
    }

    /**
     * 查询收货人地址
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function info(Request $request, Result $result)
    {
        $receiver = $request->get('receiver');

        $buyeraddress = BuyerAddress::where('receiver', '=', $receiver)->get();

        if (!$buyeraddress) {
            return $result->failed('信息未查到')->toArray();
        }

        return $result->succeed($buyeraddress)->toArray();
    }

    /**
     * 插入地址
     * @param StoreRequest $request
     * @param Result $result
     * @return array
     */
    public function store(StoreRequest $request, Result $result)
    {
        if ($request->get('is_default') == 1) {
            $this->buyeraddressHandler->defaultNot($request->get('buyer_id'), $request->get('shop_id'));
        }

        try {
            $model = BuyerAddress::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 地址更新
     * @param StoreRequest $request
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreRequest $request, int $id, Result $result)
    {

//        $id = $request->get('id');
        $is_default = $request->get('is_default');
        if ($id) {
            if ($is_default != 0 && $is_default != '') {
                $this->buyeraddressHandler->defaultNot($request->get('buyer_id'), $request->get('shop_id'));
            }

            try {
                $model = BuyerAddress::where('id', $id)->update($request->only($this->fields));
                $result->succeed($model);
            } catch (\Exception $exception) {
                $result->failed($exception->getMessage());
            }
        } else {
            $result->failed('收货地址ID为空！');
        }

        return response()->json($result->toArray());
    }

    /**
     * 地址详情
     * @param int $id
     * @param Result $result
     * @return array
     */
    public function detail(int $id, Result $result)
    {
        $buyerAddress = BuyerAddress::find($id);
        if (!$buyerAddress) {
            $result->failed('信息未查到');
        } else {
            $result->succeed($buyerAddress);
        }

        return $result->toArray();
    }

    /**
     * 地址删除
     * @param int $id
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id, Result $result)
    {
        if ($id) {
            if ($model = BuyerAddress::destroy($id)) {
                $result->succeed($model);
            } else {
                $result->failed("删除失败");
            }
        } else {
            $result->failed('ID不能为空！');
        }

        return response()->json($result->toArray());

    }

    /***
     * 设置默认地址
     * @param IsdefaultRequest $request
     * @param int $id
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function is_default(IsdefaultRequest $request, int $id, Result $result)
    {
        try {
            $request->offsetSet('id', $id);
            $data = $this->buyeraddressHandler->change($request);
            if ($data) {
                $result->succeed($data);
            } else {
                $result->failed("修改失败");
            }
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return response()->json($result->toArray());
    }

    /**
     * 我的 默认收货地址
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function myDefault(Result $result)
    {
        $userId = auth('api')->id();

        $defaultAddress = BuyerAddress::where([
            ['user_id', '=', $userId],
            ['is_default', '=', 1],
        ])->first();

        if (empty($defaultAddress))
            $result->failed('未设置默认地址, 请选择收货地址');
        else
            $result->succeed($defaultAddress);

        return response()->json($result->toArray());

    }

}
<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/12
 * Time: 18:07
 */

namespace App\Http\Controllers\Api;

use App\Handlers\UserHandler;
use App\Http\Requests\Api\ShopManage\ListRequest;
use App\Http\Requests\Api\ShopManage\StoreRequest;
use App\Lib\Tools\VerificationCode;
use App\Models\ShopManager;
use Illuminate\Http\Request;
use App\Lib\Response\Result;
use App\Handlers\ShopManageHandler;

class ShopManageController
{
    private $shopManageHandler;
    private $fields = ['name', 'type'];

    public function __construct(ShopManageHandler $shopManageHandler)
    {
        $this->shopManageHandler = $shopManageHandler;

    }

    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function info(Request $request, Result $result)
    {
        $manage_id = $request->get('manage_id');
        if ($manage_id) {
            $manage = ShopManager::with('user')->where('id', $manage_id)->first();
            if (!$manage) {
                return $result->failed('信息未查到')->toArray();
            }
            return $result->succeed($manage)->toArray();
        } else {
            return $result->failed('管理员ID不能为空')->toArray();
        }
    }

    /**
     * @param Request $request
     * @param Result $result
     * @param VerificationCode $verificationCode
     * @return array|\Ml\Response\Result
     */
    public function sms(Request $request, Result $result, VerificationCode $verificationCode)
    {
        try {
            $phone = $request->get('phone');

            if (!is_phone_number($phone)) {
                return $result->failed('必须是正确的手机号码');
            }

            // 发送短信验证码
            $templateParam = ['code' => rand(100000, 999999)];
            $verificationCode->setTemplateType('register');
            $result = $verificationCode->sendCode($phone, $templateParam);

            \Log::info(__FUNCTION__ . ',templateParam:' . json_encode($templateParam));
            \Log::info(__FUNCTION__ . ',result:' . json_encode($result));
            session(['sms_code' => $templateParam]);
            return $result->toArray();
        } catch (\Exception $exception) {

            if ($exception instanceof LoginException || $exception instanceof VerificationCodeException)
                return $result->failed($exception->getMessage())->toArray();

            return $result->failed('系统繁忙,请稍等')->toArray();
        }
    }

    /**
     * @param StoreRequest $request
     * @param Result $result
     * @param VerificationCode $code
     * @param UserHandler $userHandler
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request, Result $result, VerificationCode $code, UserHandler $userHandler)
    {
        try {
            $phone = $request->get('phone');
            $v_code = $request->get('v_code');
            $code->checkCode($phone, $v_code);
            $manages['type'] = ShopManager::TYPE_GENERAL;
            $manages['status'] = ShopManager::STATUS_Y;
            $shopManager = $this->shopManageHandler->store($request, $userHandler, $manages);
            $result->succeed($shopManager);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return response()->json($result->toArray());
    }

    /**管理员列表
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request, Result $result)
    {
        //校验店铺归属
        $data = $this->shopManageHandler->page($request);
        return $result->succeed($data)->toArray();
    }

    /**
     * @param Request $request
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Result $result)
    {
        $id = $request->get('manage_id');
        if ($id) {
            try {
                $model = $this->shopManageHandler->update($request);
                $result->succeed($model);
            } catch (\Exception $exception) {
                $result->failed($exception->getMessage());
            }
        } else {
            $result->failed('管理员ID为空！');
        }

        return response()->json($result->toArray());
    }

    public function type_list(Result $result)
    {
        $type[0]['name'] = ShopManager::$typeMap[ShopManager::TYPE_GENERAL];
        $type[1]['name'] = ShopManager::$typeMap[ShopManager::TYPE_SERVICE];
        $type[0]['type'] = ShopManager::TYPE_GENERAL;
        $type[1]['type'] = ShopManager::TYPE_SERVICE;
        return $result->succeed($type)->toArray();
    }

    /**管理员删除
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function delete(Request $request, Result $result)
    {
        $id = $request->get('manage_id');
        // $shop_id=$request->get('shop_id');
        try {
            // $this->shopManageHandler->permission($request);
            $data = $this->shopManageHandler->delete($id);
            $result->setMessage('删除成功');
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();

    }

}
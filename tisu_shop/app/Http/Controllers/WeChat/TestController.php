<?php
/**
 * Created by PhpStorm.
 * User: jjg
 * Date: 2019-04-22
 * Time: 11:16
 */

namespace App\Http\Controllers\WeChat;


use App\Handlers\TestHandler;
use App\Lib\Response\Result;
use Illuminate\Http\Request;

class TestController
{

    /**
     * 测试下单
     * @param Request $request
     * @param Result $result
     * @param TestHandler $testHandler
     * @return array
     */
    public function testStore(Request $request, Result $result, TestHandler $testHandler)
    {

        try {

            $data = $testHandler->testCreate($request);

            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }
}
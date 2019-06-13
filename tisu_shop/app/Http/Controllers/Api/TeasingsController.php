<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/15
 * Time: 12:06
 */

namespace App\Http\Controllers\Api;


use App\Handlers\TeasingHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Teasing\StoreRequest;
use App\Lib\Response\Result;

class TeasingsController extends Controller
{
    private $fields = ['title', 'content'];
    private $teasinghandler;

    public function __construct(TeasingHandler $teasingHandler)
    {
        $this->teasinghandler = $teasingHandler;
    }

    /**
     * @param StoreRequest $request
     * @param Result $result
     * @return array
     */
    public function store(StoreRequest $request, Result $result)
    {
        $user_id = \auth('api')->id();
        if ($user_id){
            try {
                $teasing = $this->teasinghandler->store($request,$user_id);
                $result->succeed($teasing);
            } catch (\Exception $exception) {
                $result->failed($exception->getMessage());
            }
        } else {
            $result->failed('请登录');
        }


        return $result->toArray();

    }
}
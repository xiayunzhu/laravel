<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/3/21
 * Time: 10:45
 */

namespace App\Http\Controllers\WeChat;


use App\Handlers\FavoritesHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\WeChat\Favorites\FavoritesRequest;
use App\Lib\Response\Result;
use App\Models\Favorites;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public $fields = ['goods_id', 'buyer_id', 'shop_id'];
    private $favoritesHandler;

    public function __construct(FavoritesHandler $favoritesHandler)
    {
        $this->favoritesHandler = $favoritesHandler;
    }

    /**收藏
     * @param FavoritesRequest $request
     * @param Result $result
     * @return array
     */
    public function store(FavoritesRequest $request, Result $result)
    {

        $data = $request->only($this->fields);
        ## 判断是否存储
        if (!$favorite = Favorites::firstOrCreate($data)) {
            return $result->failed('收藏失败')->toArray();
        }
        return $result->succeed($favorite)->toArray();


    }

    /**取消收藏
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function delete(Request $request, Result $result)
    {
        $id = $request->get('id');
        if (!$favorite = Favorites::destroy($id)) {
            return $result->failed('操作失败')->toArray();
        } else {
            return $result->succeed($favorite)->toArray();
        }
    }

    /**买家收藏商品列表
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function  list(Request $request, Result $result)
    {
        $favorite = $this->favoritesHandler->paging($request);
        return $result->succeed($favorite)->toArray();
    }

}
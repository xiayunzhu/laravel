<?php


/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/19
 * Time: 11:25
 */

namespace App\Handlers;


use App\Lib\Response\Result;
use App\Models\Buyer;
use App\Models\Favorites;
use Illuminate\Http\Request;

class FavoritesHandler
{

    /**查询分页
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paging(Request $request)
    {

        $buyer_id = $request->get('buyer_id');
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $favorite = Favorites::with(['goods', 'goods.specs', 'goods.detail_images', 'goods.main_images', 'goods.logo_image', 'goods.org_goods'])->where('buyer_id', $buyer_id)->orderby('id', 'desc')->paginate($per_page);
        return $favorite;
    }

}
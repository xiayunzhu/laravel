<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/21
 * Time: 12:02
 */

namespace App\Handlers;


use App\Exceptions\GoodsException;
use App\Models\GoodsImage;
use Exception;
use Illuminate\Http\Request;

class GoodsImageHandler
{

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function store($data)
    {
        $row = [];

        foreach (GoodsImage::$fields as $field) {
            if ($data[$field] === '')
                throw new Exception($field."不能为空");
            if (isset($data[$field]))
                $row[$field] = $data[$field];
        }

        return GoodsImage::create($row);
    }

    /**
     * 图片激活隐藏
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function is_show(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('is_show');
        $page_content = GoodsImage::find($id);
        if (!$page_content) {
            throw new Exception('图片不存在');
        }
        if ($status == 'display')
            $page_content->is_show = GoodsImage::SHOW_STATUS_ON_SHOW;
        elseif ($status == 'hidden')
            $page_content->is_show = GoodsImage::SHOW_STATUS_SHOW_OUT;

        $page_content->save();
        return true;
    }

    /**
     * 图片排序  上移下移
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function picMove(Request $request)
    {
        $pics = $request->get('pics');
        foreach ($pics as $pic){
            $goodsImage = GoodsImage::find($pic['id']);
            if (!$goodsImage) {
                throw new Exception('图片不存在');
            }
            $goodsImage->sort = $pic['sort'];
            $tmp = $goodsImage->save();
            if (!$tmp){
                \DB::rollback();
                throw new GoodsException("图片移动失败");
            }
        }

    }

}
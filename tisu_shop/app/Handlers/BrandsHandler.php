<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/15
 * Time: 16:52
 */

namespace App\Handlers;

use App\Models\Brand;

class BrandsHandler
{
    /**
     * 下拉框品牌
     * @param $item
     * @return array
     */
    public static function brands()
    {
        return Brand::get()->pluck('name', 'id');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/21
 * Time: 12:44
 */

namespace App\Handlers;


use App\Models\OrgGoodsSpec;
class OrgGoodsSpecHandler
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $row = [];

        foreach (OrgGoodsSpec::$fields as $field) {
            if (isset($data[$field]))
                $row[$field] = $data[$field];
        }

        return OrgGoodsSpec::create($row);
    }

}
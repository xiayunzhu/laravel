<?php
namespace App\Handlers;


use App\Exceptions\GoodsException;
use App\Models\OrgGoodImage;
use Exception;
use Illuminate\Http\Request;

class OrgGoodsImageHandler
{

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function store($data)
    {
        $row = [];

        foreach (OrgGoodImage::$fields as $field) {
            if ($data[$field] === '')
                throw new Exception($field."不能为空");
            if (isset($data[$field]))
                $row[$field] = $data[$field];
        }

        return OrgGoodImage::create($row);
    }


}
<?php


namespace App\Handlers;


use App\Exceptions\GoodsException;
use App\Models\OrgGoodsHasSpec;
use Illuminate\Http\Request;

class OrgGoodsHasSpecHandler
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $row = [];

        foreach (OrgGoodsHasSpec::$fields as $field) {
            if (isset($data[$field]))
                $row[$field] = $data[$field];
        }

        return OrgGoodsHasSpec::create($row);
    }

}
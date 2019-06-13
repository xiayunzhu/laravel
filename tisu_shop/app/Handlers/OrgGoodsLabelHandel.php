<?php


namespace App\Handlers;


use App\Models\OrgGoodsLabels;

class OrgGoodsLabelHandel
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $row = [];

        foreach (OrgGoodsLabels::$fields as $field) {
            if (isset($data[$field]))
                $row[$field] = $data[$field];
        }

        return OrgGoodsLabels::create($row);
    }

}
<?php

namespace App\Models;


class Deliveries extends BaseModel
{

    const METHOD_PIECE = 1;
    const METHOD_WEIGHT = 2;

    public static $methodMap = [
        self::METHOD_PIECE => "按件数",
        self::METHOD_WEIGHT => "按重量",
    ];

    /**
     * 获取该模板配送规则
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rules()
    {
        $rules = $this->hasMany(DeliveryRule::class, 'delivery_id', 'id');

        foreach ($rules as $key => $value){
            $rules[$key]['region'] = implode(",", $value['region']);
        }
        return $rules;
    }
}

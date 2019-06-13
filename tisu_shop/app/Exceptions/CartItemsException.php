<?php

namespace App\Exceptions;

use Exception;
use App\Models\User;
use App\Models\GoodsSpec;


class CartItemsException extends Exception
{
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0) {

        // 确保所有变量都被正确赋值
        parent::__construct($message, $code);

    }

    /**
     * 自定义字符串输出的样式
     * */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
//    /**
//     * 外键约束
//     */
//    public function foreignKey($model,$id){
//
//        $res = User::find($id);
//
//        if (!$res){
//            if ($model == 'User'){
//                throw new Exception("该用户不存在");
//            }elseif ($model == 'GoodsSpec'){
//                throw new Exception("该规格商品不存在");
//            }
//        }
//        return true;
//
//    }

}

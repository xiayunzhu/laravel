<?php

namespace App\Models;

class Stock extends BaseModel
{
    //
    protected $fillable = ['modified', 'quantity', 'available', 'sku_code', 'storage_code', 'storage_name', 'item_code', 'oln_item_id', 'oln_sku_id'];
}

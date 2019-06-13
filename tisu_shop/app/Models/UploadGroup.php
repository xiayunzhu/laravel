<?php

namespace App\Models;


class UploadGroup extends BaseModel
{
    const GROUP_TYPE_OPERATING = 'operating';
    const GROUP_TYPE_SELLER = 'seller';
    public static $groupTypeMap = [
        self::GROUP_TYPE_OPERATING => '运营上传',
        self::GROUP_TYPE_SELLER => '卖家',
    ];
}

<?php

namespace App\Models;

class Region extends BaseModel
{

    public function children()
    {
        return $this->hasMany(Region::class, 'pid', 'id');
    }

}

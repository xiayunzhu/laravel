<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static get(string $string)
 */
class Specs extends BaseModel
{
    protected $table = 'specs';

    /**
     * 获取该规格下的所有属性
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function specValues()
    {
        return $this->hasMany(SpecValues::class, 'spec_id', 'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecValues extends BaseModel
{
    protected $table='spec_values';
    //
    public function spec()
    {
        return $this->belongsTo(Specs::class, 'spec_id', 'id');
    }


}

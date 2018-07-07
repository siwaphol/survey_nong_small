<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Elec_use_type extends Model
{
    /**
     * @return array
     */
    public function transformer_info()
    {
        return $this->hasMany(Transformer_info::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * @return array
     */
    public function product_times()
    {
        return $this->hasOne('App\Produce_time');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_permonth extends Model
{
    /**
     * @return array
     */
    public function product_times()
    {
        return $this->belongsTo(Produce_time::class);
    }
}

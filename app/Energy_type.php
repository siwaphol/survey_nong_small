<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Energy_type extends Model
{
    const TYPE_NONRENEWABLE_ENERGY = 'พลังงานสิ้นเปลือง';
    const TYPE_RENEWABLE_ENERGY = 'พลังงานหมุนเวียน';

    public function energy_and_juristic()
    {
        return $this->belongsTo('App\Energy_and_juristic');
    }
}

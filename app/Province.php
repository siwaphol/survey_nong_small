<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    public function mains()
    {
        return $this->hasOne(Main::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    //
    public function districts()
    {
        return $this->belongsTo(District::class);
    }
}

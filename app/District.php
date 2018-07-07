<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public function subdistricts()
    {
        return $this->belongsTo(SubDistrict::class);
    }
}

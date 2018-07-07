<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecMap extends Model
{
    public function ind()
    {
        return $this->hasMany(SecMapDetail::class)->where('type','ind');
    }
    public function bud()
    {
        return $this->hasMany(SecMapDetail::class)->where('type','bud');
    }
}

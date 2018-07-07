<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place_data extends Model
{
    /**
     * @return array
     */
    public function mains()
    {
        return $this->hasOne(Main::class);
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Save_energy_plan extends Model
{
    /**
     * @return array
     */
    public function mains()
    {
        return $this->belongsTo(Main::class);
    }
}

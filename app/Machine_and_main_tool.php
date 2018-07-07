<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine_and_main_tool extends Model
{
    /**
     * @return array
     */
    public function electric_used_for_machines()
    {
        return $this->hasOne(Electric_used_for_machine::class);
    }
}

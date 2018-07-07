<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Heat_power_used_for_machine extends Model
{
    public function mains()
    {
        return $this->belongsTo(Main::class);
    }


    public function machine_and_main_tools()
    {
        return $this->hasOne('App\Machine_and_main_tool','id','m_id');
    }
}

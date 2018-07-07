<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place_type extends Model
{

    const BUILDING = "อาคาร";
    const INDUSTRY = "โรงงาน";

	protected $casts = [
        'name' => 'array'
        
    ];
    /**
     * @return array
     */
    public function mains()
    {
        return $this->hasMany(Main::class);
    }
}

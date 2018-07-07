<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Working_per_month extends Model
{
    protected $fillable = [
        'building_information_id','year','month','air_conditioned','non_air_conditioned','sumspace','hotel','hospital'
    ];
    /**
     * @return array
     */
    public function building_informations()
    {
        return $this->belongsTo(Building_information::class);
    }
}

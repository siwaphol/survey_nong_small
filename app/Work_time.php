<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Work_time extends Model
{
	protected $casts = [
        'hour_day' => 'array',
        'day_year' => 'array',
        'total_work' => 'array'
    ];
    /**
     * @return array
     */
    public function mains()
    {
        return $this->hasOne(Main::class);
    }
}

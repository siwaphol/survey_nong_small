<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Energy_used_per_year extends Model
{
	protected $casts = [
        'month' => 'array',
        'unit' => 'array',
        'cost_unit' => 'array'
    ];

	protected $fillable = ['energy_and_juristic_id','year','month','unit','cost_unit','total_cost','mj'];

    /**
     * @return array
     */
    public function energy_and_juristics()
    {
        return $this->belongsTo(Energy_and_juristic::class);
    }
}

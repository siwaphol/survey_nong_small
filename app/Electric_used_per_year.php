<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Electric_used_per_year
 *
 * @property integer $id
 * @property integer $tranformer_info_id
 * @property string $year
 * @property string $month
 * @property string $on_peak
 * @property string $off_peak
 * @property string $holiday
 * @property string $cost_need
 * @property string $power_used
 * @property string $cost_true
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereTranformerInfoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereMonth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereOnPeak($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereOffPeak($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereHoliday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereCostNeed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year wherePowerUsed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereCostTrue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_per_year whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Electric_used_per_year extends Model
{
    protected $fillable = ['tranformer_info_id','year','month',
        'on_peak','off_peak','holiday','cost_need','power_used',
        'cost_true'
    ];

    public function tranformer_infos()
    {
        return $this->belongsTo(Tranformer_info::class);
    }
}

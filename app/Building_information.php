<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Building_information
 *
 * @property integer $id
 * @property integer $main_id
 * @property string $name
 * @property string $open
 * @property string $work_hour_hr_d
 * @property string $work_hour_day_y
 * @property string $air_conditioned
 * @property string $non_air_conditioned
 * @property string $total_1
 * @property string $parking_space
 * @property string $total_2
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Main $mains
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Working_per_month[] $working_per_months
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereMainId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereOpen($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereWorkHourHrD($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereWorkHourDayY($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereAirConditioned($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereNonAirConditioned($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereTotal1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereParkingSpace($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereTotal2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Building_information whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Building_information extends Model
{
    /**
     * @return array
     */
    public function mains()
    {
        return $this->belongsTo(Main::class);
    }

    /**
     * @return array
     */
    public function working_per_months()
    {
        return $this->hasMany(Working_per_month::class);
    }

    protected static function boot()
    {
        parent::boot();

        // ลบข้อมูลตารางที่พ่วง กับ building_information_id ปัจจุบัน
        static::deleting(function($building_information){
            $building_information->working_per_months()->delete();
        });
    }
}

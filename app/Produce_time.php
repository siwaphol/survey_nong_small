<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produce_time extends Model
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
    /*public function product_permonths()
    {
        return $this->hasMany(Product_permonth::class);
    }*/

    /**
     * @return array
     */
    public function products()
    {
        return $this->hasOne('App\Product','id','product_id');
    }

    public function electric_used_for_product_ratios()
    {
        return $this->hasOne('App\Electric_used_for_product_ratio');
    }

    public function fuel_user_for_product_ratios()
    {
        return $this->hasMany('App\Fuel_user_for_product_ratio');
    }

    public function energy_used_for_produce_per_months()
    {
        return $this->hasMany('App\Energy_used_for_produce_per_month');
    }

    public function product_permonths()
    {
        return $this->hasMany('App\Product_permonth','produce_time_id','id');
    }

    public function produce_processes()
    {
        return $this->hasMany('App\Produce_process','produce_time_id','id');
    }

    protected static function boot()
    {
        parent::boot();

        // ลบข้อมูลตารางที่พ่วง กับ produce_time_id ปัจจุบัน
        static::deleting(function($produce_time){
            $produce_time->product_permonths()->delete();
            $produce_time->energy_used_for_produce_per_months()->delete();
            $produce_time->electric_used_for_product_ratios()->delete();
            $produce_time->fuel_user_for_product_ratios()->delete();
            $produce_time->produce_processes()->delete();
        });
    }
}

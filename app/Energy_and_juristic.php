<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Energy_and_juristic extends Model
{
    protected $fillable = ['et_id','main_id','bar','celcious'];
    public function energy_types()
    {
        return $this->hasMany('App\Energy_type','id','et_id');
    }
    public function energy_used_per_years()
    {
        return $this->hasMany('App\Energy_used_per_year');
    }

    /**
     * @return array
     */
    public function mains()
    {
        return $this->belongsTo(Main::class);
    }


    protected static function boot()
    {
        parent::boot();

        // ลบข้อมูลตารางที่พ่วง กับ Energy_and_juristic ปัจจุบัน
        static::deleting(function($EAJ){
            $EAJ->energy_used_per_years()->delete();
        });
    }
}

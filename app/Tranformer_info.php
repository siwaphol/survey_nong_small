<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tranformer_info extends Model
{
    protected $fillable = [
        'electric_user_no', 'elec_meter_no'
        ,'main_id','elec_use_type','electric_ratio',
        'tranformer_power','amount'
    ];
    /**
     * @return array
     */
    public function mains()
    {
        return $this->belongTo(Main::class);
    }

    /**
     * @return array
     */
    public function elec_use_types()
    {
        return $this->belongsTo(Elec_use_type::class);
    }

    public function electric_used_per_years()
    {
        return $this->hasMany('App\Electric_used_per_year','tranformer_info_id','id');
    }

    protected static function boot()
    {
        parent::boot();

        // ลบข้อมูลตารางที่พ่วง กับ tranformer_info_id ปัจจุบัน
        static::deleting(function($TI){
            $TI->electric_used_per_years()->delete();
        });

    }
}

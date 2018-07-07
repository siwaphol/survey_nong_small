<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Main extends Model
{
    const PROGRESS_INDUSTRY = 'industry';
    const PROGRESS_BUILDING = 'building';

    protected $casts = [
        'progress'=>'array'
    ];

    public function place_dataes()
    {
        return $this->hasOne(Place_data::class);
    }

    public function place_types()
    {
        return $this->belongsTo(Place_type::class, 'place_type_id');
    }

    public function work_times()
    {
        return $this->hasOne(Work_time::class);
    }

    public function building_informations()
    {
        return $this->hasMany(Building_information::class);
    }


    public function save_energy_plans()
    {
        return $this->hasMany(Save_energy_plan::class);
    }

    /**
     * @return array
     */
    public function electric_used_for_machines()
    {
        return $this->hasMany(Electric_used_for_machine::class);
    }

    public function heat_power_used_for_machines()
    {
        return $this->hasMany(Heat_power_used_for_machine::class);
    }
    /**
     * @return array
     */
    public function product_times()
    {
        return $this->hasMany(Produce_time::class);
    }

    public function energy_and_juristics()
    {
        return $this->hasMany(Energy_and_juristic::class);
    }
    public function tranformer_infos()
    {
        return $this->hasMany(Tranformer_info::class);
    }
    public function areas()
    {
        return $this->belongsTo(Area::class);
    }


    protected static function boot()
    {
        parent::boot();

        // ลบข้อมูลตารางที่พ่วง กับ building_information_id ปัจจุบัน
        static::deleting(function($main){
            $bi = Building_information::where('main_id',$main->id)->get();
            foreach ($bi as $item)
            {
                $item->delete();
            }
//            $main->building_informations()->delete();
            $pd = Place_data::where('main_id',$main->id)->get();
            foreach ($pd as $item)
            {
                $item->delete();
            }
//            $main->tranformer_infos()->delete();
            $ti = Tranformer_info::where('main_id',$main->id)->get();
            foreach ($ti as $item)
            {
                $item->delete();
            }
//            $main->save_energy_plans()->delete();
            $sep = Save_energy_plan::where('main_id',$main->id)->get();
            foreach ($sep as $item)
            {
                $item->delete();
            }
//            $main->heat_power_used_for_machines()->delete();
            $hpu = Heat_power_used_for_machine::where('main_id',$main->id)->get();
            foreach ($hpu as $item)
            {
                $item->delete();
            }
//            $main->electric_used_for_machines()->delete();
            $eufm = Electric_used_for_machine::where('main_id',$main->id)->get();
            foreach ($eufm as $item)
            {
                $item->delete();
            }
//            $main->energy_and_juristics()->delete();
            $enj = Energy_and_juristic::where('main_id',$main->id)->get();
            foreach ($enj as $item)
            {
                $item->delete();
            }

            $pt = Produce_time::where('main_id',$main->id)->get();
            foreach ($pt as $item)
            {
                $item->delete();
            }


        });

    }

}

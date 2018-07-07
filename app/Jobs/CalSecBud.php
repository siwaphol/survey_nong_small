<?php

namespace App\Jobs;

use App\Building_information;
use App\BuildingType;
use App\Main;
use App\MainGroup;
use App\SecAverage;
use App\SecProduct;
use App\Tranformer_info;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalSecBud implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $allInd = Main::with('place_types', 'place_dataes')
            ->whereHas('place_types', function ($q) {
                $q->where('type', 'อาคาร');
            })->get();
        if ($allInd->count() > 0) {
            foreach ($allInd as $main) {
                //--------------แบ่งกลุ่ม-------------
                $mainGroup = MainGroup::whereMainId($main->id)->first();
                if (is_null($mainGroup)) {
                    $mainGroup = new MainGroup();
                }
                $mainGroup->main_id = $main->id;
                $mainGroup->type = 'BUD';
                $mainGroup->province_id = $main->place_dataes->province;
                $mainGroup->district_id = $main->place_dataes->district;
                $mainGroup->sub_district_id = $main->place_dataes->sub_district;
                //หาขนาดหม้อแปลง
                //$electSize = Tranformer_info::where('main_id', $main->id)->sum();
                $electSize = \DB::select("select sum(tranformer_power*amount) as total_kva from tranformer_infos where main_id = {$main->id} ");
                if ($electSize[0]->total_kva == null) {
                    $electSize = 0;
                } else {
                    $electSize = $electSize[0]->total_kva + 0;
                }
                //-----หาการใช้พลังงาน--------
                $fuelMj = \DB::select("select sum(ey.mj) as sum_fuel from energy_and_juristics ej inner join energy_used_per_years ey on ej.id = ey.energy_and_juristic_id inner join mains on ej.main_id = mains.id inner join energy_types et on ej.et_id = et.id where ej.main_id = '{$main->id}' and et.type = 'พลังงานสิ้นเปลือง' ");
                $renewMj = \DB::select("select sum(ey.mj) as sum_renew from energy_and_juristics ej inner join energy_used_per_years ey on ej.id = ey.energy_and_juristic_id inner join mains on ej.main_id = mains.id inner join energy_types et on ej.et_id = et.id where ej.main_id = '{$main->id}' and et.type = 'พลังงานหมุนเวียน' ");
                $fuelMj = is_null($fuelMj[0]->sum_fuel) ? 0 : $fuelMj[0]->sum_fuel;
                $renewMj = is_null($renewMj[0]->sum_renew) ? 0 : $renewMj[0]->sum_renew;
                $allMj = $fuelMj + $renewMj;

                $allElectUse = \DB::select("select sum(eu.power_used) as sum_elect from tranformer_infos t inner join  mains m on t.main_id = m.id inner join electric_used_per_years eu on t.id = eu.tranformer_info_id 
where m.id = {$main->id}");
                $allElectUse = $allElectUse[0]->sum_elect * 3.6;

                $mainGroup->elect_kva = $electSize;
                $mainGroup->energy_mj = $allMj;
                if ($electSize >= 585) {
                    //-----หม้อแปลงขนาดมากกว่า 585 ------> medium
                    $mainGroup->group = 'medium';
                    if ($electSize * 3.6 + $allMj > 20000000) {
                        $mainGroup->group = 'out';
                    }
                } elseif ($electSize >= 0) {
                    $mainGroup->group = 'small';
                    if ($electSize * 3.6 + $allMj > 20000000) {
                        $mainGroup->group = 'out';
                    }
                } elseif ($electSize <= 0) {
                    if ($allMj > 10000000 && $allMj <= 20000000) {
                        $mainGroup->group = 'medium';
                    } elseif ($allMj <= 10000000) {
                        $mainGroup->group = 'small';
                    } else {
                        $mainGroup->group = 'out';
                    }
                }
                $mainGroup->save();
                //------------ END แบ่งกลุ่ม-------------
                if ($mainGroup->group != 'out') {
                    $electUse = $allElectUse;

                    $building = Building_information::where('main_id', $main->id)->get(['id']);
                    $air = 0;
                    $nonAir = 0;
                    $unit = "ตารางเมตร";
                    $comment = "";
                    if ($building->count() > 0) {
                        foreach ($building as $b) {
                            switch ($main->place_types->name[0]) {
                                case 3: //โรงแรม
                                    $airArea = \DB::select("select sum(wm.hotel) as air_area from mains inner join building_informations bui on mains.id = bui.main_id inner join working_per_months wm on bui.id = wm.building_information_id where bui.id = {$b->id} and mains.id = {$main->id} ");
                                    $unit = "ห้องวัน";
                                    $comment = "ห้อง/วัน = 0";
                                    break;
                                case 4: //โรงพยาบาล
                                    $airArea = \DB::select("select sum(wm.hospital) as air_area from mains inner join building_informations bui on mains.id = bui.main_id inner join working_per_months wm on bui.id = wm.building_information_id where bui.id = {$b->id} and mains.id = {$main->id} ");
                                    $unit = "เตียงวัน";
                                    $comment = "เตียง/วัน = 0";
                                    break;
                                default:
                                    $airArea = \DB::select("select sum(wm.air_conditioned) as air_area from mains inner join building_informations bui on mains.id = bui.main_id inner join working_per_months wm on bui.id = wm.building_information_id where bui.id = {$b->id} and mains.id = {$main->id} ");
                                    $comment = "พท. ปรับอากาศ = 0";
                            }
                            $nonAirArea = \DB::select("select sum(wm.non_air_conditioned) as non_air_area from mains inner join building_informations bui on mains.id = bui.main_id inner join working_per_months wm on bui.id = wm.building_information_id where bui.id = {$b->id} and mains.id = {$main->id} ");
                            $air += (float)$airArea[0]->air_area;
                            $nonAir += (float)$nonAirArea[0]->non_air_area;
                        }

                        $sec = SecProduct::where('main_group_id', $mainGroup->id)
                            ->where('building_type_id', $main->place_types->name[0])->first();
                        if ($sec == null) {
                            $sec = new SecProduct();
                        }
                        $sec->main_group_id = $mainGroup->id;
                        $sec->building_type_id = $main->place_types->name[0];
                        $sec->group = BuildingType::find($main->place_types->name[0])->name;
                        if ($air == 0 || ($allMj + $electUse) == 0) {
                            // 'ข้อมูลไม่เพียงพอ';
                            $sec->elect_mj = $electUse;
                            $sec->energy_mj = $allMj;
                            $sec->total_mj = $allMj + $electUse;
                            $sec->product_amount = null;
                            $sec->sec = null;
                            $sec->sec_unit = "MJ/{$unit}";
                            $sec->comment = $comment;
                            $sec->save();

                        } else {
                            $sec->elect_mj = $electUse;
                            $sec->energy_mj = $allMj;
                            $sec->total_mj = $allMj + $electUse;
                            $sec->product_amount = $air;
                            $sec->sec = ($allMj + $electUse) / $air;
                            $sec->sec_unit = "MJ/{$unit}";
                            $sec->save();
                        }

                    }
                }
            }
        }

        //------------หาค่าเฉลี่ย------------
        $result = \DB::select("select sp.building_type_id, mg.group, avg(sec) as sec_avg, min(sec) as sec_best from sec_products sp inner join main_groups mg on sp.main_group_id = mg.id where sec is not null and sp.building_type_id in (select distinct(building_type_id) from sec_products where main_group_id in (select id from main_groups where type = 'BUD')) group by sp.building_type_id, mg.group order by sp.building_type_id");
        if (count($result) > 0) {
            foreach ($result as $item) {
                $result = SecAverage::where('building_type_id', $item->building_type_id)->first();
                if ($result == null) $result = new SecAverage();
                $result->building_type_id = $item->building_type_id;
                $param1 = "sec_avg_{$item->group}";
                $param2 = "sec_best_{$item->group}";
                $result->$param1 = $item->sec_avg;
                $result->$param2 = $item->sec_best;
                $result->save();
            }
        }
        //----------------หาศักยภาพการประหยัด------------
        \DB::insert("insert into sec_cals (sec_product_id, sec_cal1, sec_cal2, created_at, updated_at ) (select sp.id, (sp.sec - bt.avg_sec)*sp.product_amount as cal1, (sp.sec - if(mg.group = 'small' , sa.sec_best_small , sa.sec_best_medium))*sp.product_amount as cal2, NOW(), NOW() from sec_products sp inner join building_types bt on sp.building_type_id = bt.id inner join main_groups mg on sp.main_group_id = mg.id inner join sec_averages sa on sp.building_type_id = sa.building_type_id where main_group_id in (select id from main_groups where type = 'BUD') and sp.product_amount is not null ) ");
    }
}

<?php

namespace App\Jobs;

use App\Main;
use App\MainGroup;
use App\SecAverage;
use App\SecProduct;
use App\Tranformer_info;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalSecInd implements ShouldQueue
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
                $q->where('type', 'โรงงาน');
            })
            ->get();
        if ($allInd->count() > 0) {
            foreach ($allInd as $main) {
                //--------------แบ่งกลุ่ม-------------
                $mainGroup = MainGroup::whereMainId($main->id)->first();
                if (is_null($mainGroup)) {
                    $mainGroup = new MainGroup();
                }
                $mainGroup->main_id = $main->id;
                $mainGroup->type = 'IND';
                $mainGroup->province_id = $main->place_dataes->province;
                $mainGroup->district_id = $main->place_dataes->district;
                $mainGroup->sub_district_id = $main->place_dataes->sub_district;
                //หาขนาดหม้อแปลง
                $electSize = \DB::select("select sum(tranformer_power*amount) as total_kva from tranformer_infos where main_id = {$main->id} ");
                if ($electSize[0]->total_kva == null) {
                    $electSize = 0;
                } else {
                    $electSize = $electSize[0]->total_kva + 0;
                }
                $allElectUse = \DB::select("select sum(eu.power_used) as sum_elect from tranformer_infos t inner join  mains m on t.main_id = m.id inner join electric_used_per_years eu on t.id = eu.tranformer_info_id 
where m.id = {$main->id}");
                $allElectUse = $allElectUse[0]->sum_elect * 3.6;
                //-----หาการใช้พลังงาน--------
                $fuelMj = \DB::select("select sum(ey.mj) as sum_fuel from energy_and_juristics ej inner join energy_used_per_years ey on ej.id = ey.energy_and_juristic_id inner join mains on ej.main_id = mains.id inner join energy_types et on ej.et_id = et.id where ej.main_id = '{$main->id}' and et.type = 'พลังงานสิ้นเปลือง' ");
                $renewMj = \DB::select("select sum(ey.mj) as sum_renew from energy_and_juristics ej inner join energy_used_per_years ey on ej.id = ey.energy_and_juristic_id inner join mains on ej.main_id = mains.id inner join energy_types et on ej.et_id = et.id where ej.main_id = '{$main->id}' and et.type = 'พลังงานหมุนเวียน' ");
                $fuelMj = is_null($fuelMj[0]->sum_fuel) ? 0 : $fuelMj[0]->sum_fuel;
                $renewMj = is_null($renewMj[0]->sum_renew) ? 0 : $renewMj[0]->sum_renew;
                $allMj = $fuelMj + $renewMj;

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
                    $indType = "";
                    if ($main->place_types->name[0] == 1) {
                        $indType = 'อาหาร เครื่องดื่ม และยาสูบ';
                    } elseif ($main->place_types->name[0] == 2) {
                        $indType = 'กระดาษ';
                    } elseif ($main->place_types->name[0] == 3) {
                        $indType = 'ไม้';
                    } elseif ($main->place_types->name[0] == 4) {
                        $indType = 'สิ่งทอ';
                    } elseif ($main->place_types->name[0] == 5) {
                        $indType = 'โลหะพื้นฐาน';
                    } elseif ($main->place_types->name[0] == 6) {
                        $indType = 'โลหะประยุกต์';
                    } elseif ($main->place_types->name[0] == 7) {
                        $indType = 'อโลหะ';
                    } elseif ($main->place_types->name[0] == 8) {
                        $indType = 'เคมี';
                    } elseif ($main->place_types->name[0] == 9) {
                        $indType = 'หิน กรวด ดิน ทราย';
                    } elseif ($main->place_types->name[0] == 10) {
                        $indType = 'อื่นๆ, ' . $main->place_types->name[1];
                    }
                    //------------CAL SEC FOR EACH PRODUCTS--------------
                    $allProducts = \DB::select(" select * from (SELECT m.id as main_id, ey.kwhr_yr as elect, pt.product_id, pt.id as pt_id, p.name as product_name, p.unit as product_unit  FROM `mains` m inner JOIN produce_times pt on m.id = pt.main_id LEFT JOIN electric_used_for_product_ratios ey on pt.id = ey.produce_time_id  left join products p on pt.product_id = p.id where m.id = {$main->id}) as t1 LEFT JOIN (SELECT fy.produce_time_id as pt_id2, sum(fy.mega_jul_yr) as fuel  FROM fuel_user_for_product_ratios fy group BY fy.produce_time_id) as t4 on t1.pt_id = t4.pt_id2  LEFT  JOIN (SELECT sum(amount) as product_amount, pm.produce_time_id pt_id  FROM `product_permonths` pm GROUP BY pm.produce_time_id) as t2 on t1.pt_id =t2.pt_id ");
                    if (count($allProducts) > 0) {
                        foreach ($allProducts as $key => $product) {
                            $comment = "";
                            $electUse = (double)$product->elect * 3.6;
                            $fuelUse = (double)$product->fuel;
                            $productAmount = (double)$product->product_amount;

                            if (($electUse == 0 && $fuelUse == 0) && count($allProducts) == 1) {
                                //----------- ใช้ค่าของโรงงานรวมมาคิด-------------
                                $electUse = $allElectUse;
                                $fuelUse = $allMj;
                                $comment = "พลังงานที่ใช้ผลิต = 0, โรงงานมี 1 ผลิตภัณฑ์, ใช้ของโรงงาน";
                            }
                            if (($electUse == 0 && $fuelUse == 0) || $productAmount == 0) {
                                $electUse = null;
                                $fuelUse = null;
                                $totalEnergy = null;
                                $comment .= ", product #{$key}OF" . count($allProducts) . " >> พลังงานที่ใช้ = 0  ตัดออก";
                                $mainGroup->comment = ($mainGroup->comment == "" || $mainGroup->comment == null) ? $comment : $mainGroup->comment . $comment;
                                $mainGroup->save();
                            } else {
                                //---------cal SEC----------
                                if ($product->product_unit == 'กิโลกรัม') {
                                    $product->product_unit = 'ตัน';
                                    $productAmount = $productAmount / 1000;
                                }
                                $totalEnergy = $fuelUse + $electUse;
                                $secAmount = null;
                                $comment = "";
                                if ($productAmount != 0) {
                                    $secAmount = $totalEnergy / $productAmount;
                                } else {
                                    $comment = "product = 0";
                                }


                                $sec = SecProduct::where('main_group_id', $mainGroup->id)
                                    ->where('product_id', $product->product_id)->first();
                                if ($sec == null) {
                                    $sec = new SecProduct();
                                    $sec->main_group_id = $mainGroup->id;
                                    $sec->product_id = $product->product_id;
                                    $sec->group = $indType;
                                    $sec->elect_mj = $electUse;
                                    $sec->energy_mj = $fuelUse;
                                    $sec->total_mj = $totalEnergy;
                                    $sec->product_amount = $productAmount;
                                    $sec->sec = $secAmount;
                                    $sec->sec_unit = "MJ/{$product->product_unit}";
                                    $sec->comment = $comment;
                                    $sec->save();
                                } else {
                                    $sec->elect_mj += $electUse;
                                    $sec->energy_mj += $fuelUse;
                                    $sec->total_mj += $totalEnergy;
                                    $sec->product_amount += $productAmount;
                                    if ($sec->product_amount != 0) {
                                        $secAmount = $sec->total_mj / $productAmount;
                                    }
                                    $sec->sec = $secAmount;
                                    $sec->sec_unit = "MJ/{$product->product_unit}";
                                    $sec->save();
                                }
                            }
                        }
                    }
                }
            }
        }
        //------------หาค่าเฉลี่ย------------
        $result = \DB::select("select sp.product_id, mg.group, avg(sec) as sec_avg, min(sec) as sec_best from sec_products sp inner join main_groups mg on sp.main_group_id = mg.id where sec is not null and sp.product_id in (select distinct(product_id) from sec_products where main_group_id in (select id from main_groups where type = 'IND')) group by sp.product_id, mg.group order by sp.product_id");
        if (count($result) > 0) {
            foreach ($result as $item) {
                $result = SecAverage::where('product_id', $item->product_id)->first();
                if ($result == null) $result = new SecAverage();
                $result->product_id = $item->product_id;
                $param1 = "sec_avg_{$item->group}";
                $param2 = "sec_best_{$item->group}";
                $result->$param1 = $item->sec_avg;
                $result->$param2 = $item->sec_best;
                $result->save();
            }
        }
        //----------------หาศักยภาพการประหยัด------------
        \DB::insert("insert into sec_cals (sec_product_id, sec_cal1, sec_cal2, created_at, updated_at ) (select sp.id, (sp.sec - p.avg_sec)*sp.product_amount as cal1, (sp.sec - if(mg.group = 'small' , sa.sec_best_small , sa.sec_best_medium))*sp.product_amount as cal2, NOW(), NOW() from sec_products sp inner join products p on sp.product_id = p.id inner join main_groups mg on sp.main_group_id = mg.id inner join sec_averages sa on sp.product_id = sa.product_id where main_group_id in (select id from main_groups where type = 'IND') and sp.product_amount is not null )");
    }
}

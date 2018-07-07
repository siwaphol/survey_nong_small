<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Building_information;
use App\Electric_used_for_machine;
use App\Energy_and_juristic;
use App\Energy_used_per_year;
use App\Heat_power_used_for_machine;
use App\Place_data;
use App\Save_energy_plan;
use App\Working_per_month;
use Illuminate\Http\Request;
use App\Main;
use App\Tranformer_info;
use App\Elec_use_type;
use App\Place_type;
use App\Electric_used_per_year;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Area;
use Maatwebsite\Excel\Facades\Excel;


class BuildingSecController extends Controller
{
    public function index()
    {
//        dd(auth()->user()->level);
//        dd(auth()->user()->area);
        $areaS = auth()->user()->area;
        $areaU = Area::find($areaS);
        $areaN = $areaU->name;
//dd($areaN);
        if (auth()->user()->level != 'admin') {
            $allBuilding = Place_type::with('mains')->where('type', "อาคาร")->get();
            $area = $areaN;
//            $area = '0';
        } else {
            $allBuilding = Place_type::with('mains')->where('type', "อาคาร")->get();
//            $area = auth()->user()->area;
            $area = '0';
        }

        $secArray = [];
        $toeArray = [];
        $factorArray = [];


        $allMain = Main::join('place_types', 'place_types.id', 'mains.place_type_id')->where('place_types.type', "อาคาร")
            //   ->where('mains.id','606')
            ->get(['mains.*', 'place_types.name']);
        echo "<table border = '1'><tr><td>Code</td><td>Name</td><td>Type</td><td>Air</td><td>Non-Air</td><td>toe</td><td>SEC</td><td>Elect (MJ)</td><td>Heat (MJ)</td><td>Renew (MJ)</td><td>Total (MJ)</td></tr>";
        foreach ($allMain as $main) {
            $elec = Tranformer_info::join('mains', 'mains.id', 'tranformer_infos.main_id')
                ->join('electric_used_per_years', 'electric_used_per_years.tranformer_info_id', 'tranformer_infos.id')
                ->where('mains.id', $main->id)
                ->sum('electric_used_per_years.power_used');
            $sec_elec = $elec * (0.000085984522785899);
//            dd($sec_elec);
            $fuel = Energy_and_juristic::join('energy_used_per_years', 'energy_used_per_years.energy_and_juristic_id', 'energy_and_juristics.id')
                ->join('mains', 'mains.id', 'energy_and_juristics.main_id')
                ->join('energy_types', 'et_id', 'energy_types.id')
                ->where('energy_and_juristics.main_id', $main->id)
                ->where('energy_types.type', 'พลังงานสิ้นเปลือง')
                ->sum('energy_used_per_years.mj');
            $sec_fuel = $fuel * (0.00002388458966275);

            $renew = Energy_and_juristic::join('energy_used_per_years', 'energy_used_per_years.energy_and_juristic_id', 'energy_and_juristics.id')
                ->join('mains', 'mains.id', 'energy_and_juristics.main_id')
                ->join('energy_types', 'et_id', 'energy_types.id')
                ->where('energy_and_juristics.main_id', $main->id)
                ->where('energy_types.type', 'พลังงานหมุนเวียน')
                ->sum('energy_used_per_years.mj');
            $sec_renew = $renew * (0.00002388458966275);

            $all_toe = $sec_elec + $sec_fuel + $sec_renew;


            // $check_type = Place_type::where('place_types.id',$main->place_type_id)
            //     ->select('place_types.name')
            //     ->get();
            // dd($main->name);

            if ($main->name == '["1"]') {
                // $check_type->name = 'สำนักงานเอกชน';
                $type = 'สำนักงานเอกชน';
                $building = Building_information::where('main_id', $main->id)->get(['id']);
                $factor_nonair = 0;
                $factor = 0;
                if ($building->count() > 0) {
                    foreach ($building as $b) {
                        $tmp2 = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.air_conditioned');
                        $factor += $tmp2;
                        $tmp = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.non_air_conditioned');
                        $factor_nonair += $tmp;
                    }
                }

            } elseif ($main->name == '["2"]') {
                // $check_type->name = 'สำนักงานรัฐบาล';
                $type = 'สำนักงานรัฐบาล';
                $building = Building_information::where('main_id', $main->id)->get(['id']);
                $factor_nonair = 0;
                $factor = 0;
                if ($building->count() > 0) {
                    foreach ($building as $b) {
                        $tmp2 = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.air_conditioned');
                        $factor += $tmp2;
                        $tmp = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.non_air_conditioned');
                        $factor_nonair += $tmp;
                    }
                }
            } elseif ($main->name == '["3"]') {
                // $check_type->name = 'โรงแรม';
                $type = 'โรงแรม';

                $building = Building_information::where('main_id', $main->id)->get(['id']);
                $factor_nonair = 0;
                $factor = 0;
                if ($building->count() > 0) {
                    foreach ($building as $b) {
                        $tmp2 = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->sum('working_per_months.hotel');
                        $factor += $tmp2;
                        $tmp = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.non_air_conditioned');
                        $factor_nonair += $tmp;
                    }
                }

            } elseif ($main->name == '["4"]') {
                // $check_type->name = 'โรงพยาบาล';
                $type = 'โรงพยาบาล';
                $building = Building_information::where('main_id', $main->id)->get(['id']);
                $factor_nonair = 0;
                $factor = 0;
                if ($building->count() > 0) {
                    foreach ($building as $b) {
                        $tmp2 = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->sum('working_per_months.hospital');
                        $factor += $tmp2;
                        $tmp = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.non_air_conditioned');
                        $factor_nonair += $tmp;
                    }
                }
            } elseif ($main->name == '["5"]') {
                // $check_type->name = 'ห้างสรรพสินค้า';
                $type = 'ห้างสรรพสินค้า';
                $building = Building_information::where('main_id', $main->id)->get(['id']);
                $factor_nonair = 0;
                $factor = 0;
                if ($building->count() > 0) {
                    foreach ($building as $b) {
                        $tmp2 = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.air_conditioned');
                        $factor += $tmp2;
                        $tmp = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.non_air_conditioned');
                        $factor_nonair += $tmp;
                    }
                }
            } elseif ($main->name == '["6"]') {
                // $check_type->name = 'โรงปศุสัตว์';
                $type = 'โรงปศุสัตว์';
                $building = Building_information::where('main_id', $main->id)->get(['id']);
                $factor_nonair = 0;
                $factor = 0;
                if ($building->count() > 0) {
                    foreach ($building as $b) {
                        $tmp2 = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.air_conditioned');
                        $factor += $tmp2;
                        $tmp = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.non_air_conditioned');
                        $factor_nonair += $tmp;
                    }
                }
            } elseif ($main->name == '["7"]') {
                // $check_type->name = 'โรงเรียน';
                $type = 'โรงเรียน';
                $building = Building_information::where('main_id', $main->id)->get(['id']);
                $factor_nonair = 0;
                $factor = 0;
                if ($building->count() > 0) {
                    foreach ($building as $b) {
                        $tmp2 = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.air_conditioned');
                        $factor += $tmp2;
                        $tmp = DB::table('mains')
                            ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                            ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                            ->where('mains.id', $main->id)
                            ->where('building_information_id', $b->id)
                            ->avg('working_per_months.non_air_conditioned');
                        $factor_nonair += $tmp;
                    }
                }
            }
            // dd($type);


//            dd($check_type);

            if ($factor == 0) {
                // $factor = 'ข้อมูลไม่เพียงพอ';
                $sec = '0';
                $secArray[] = [
                    'id' => $main->id,
                    'code' => $main->code,
                    'place_name' => $main->person_name,
                    'place_type' => $type,
                    'toe' => $all_toe,
                    'factor' => $factor,
                    'non_air' => $factor_nonair,
                    'sec' => $sec];

            } else {
                $sec = ($all_toe / $factor) * 100000;
                $secArray[] = [
                    'id' => $main->id,
                    'code' => $main->code,
                    'place_name' => $main->person_name,
                    'place_type' => $type,
                    'toe' => $all_toe,
                    'factor' => $factor,
                    'non_air' => $factor_nonair,
                    'sec' => $sec];
            }
            $ttt = $elec+$fuel+$renew;
            echo "<tr><td>$main->code</td><td>$main->person_name</td><td>$type</td><td>{$factor}</td><td>{$factor_nonair}</td><td>{$all_toe}</td><td>{$sec}</td><td>{$elec}</td><td>{$fuel}</td><td>{$renew}</td><td>{$ttt}</td></tr>";


        }
        echo "</table>";
        // dd($secArray);

        //return view('sec.building.secall', compact('allBuilding', 'area', 'secArray'));

    }

    public function excel_sec()
    {


//            $allBuilding = Place_type::with('mains')->where('type', "อาคาร")->get();
//            $area = auth()->user()->area;
        $infos = Main::join('place_datas', 'place_datas.main_id', 'mains.id')
            ->join('place_types', 'place_types.id', 'mains.place_type_id')
            ->join('areas', 'areas.name', 'mains.user_area')
            ->where('place_types.type', 'อาคาร')
            ->select(
                'mains.code',
                'mains.person_name',
                'mains.place_name',
                'place_datas.house_number',
                'place_datas.village_number',
                'place_datas.road',
                'place_datas.sub_district',
                'place_datas.district',
                'place_datas.province',
                'place_datas.post_code',
                'place_datas.phone_number',
                'place_datas.fax',
                'place_datas.email',
                'place_datas.latitude',
                'place_datas.longitude',
                'place_types.name',
                'mains.employee_amount',
                'mains.building_amount',
                'mains.contact_name',
                'mains.contact_number',
                'mains.user_name',
                'mains.updated_at',
                'mains.user_area'
            )->get();
        $infoArray = [];
        $infoArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่อนิติบุคคล', 'ชื่อโรงงาน', 'เลขที่', 'หมู่', 'ถนน'
            , 'คำบล/แขลง', 'อำเภอ/เขต', 'จังหวัด', 'รหัสไปรษณีย์', 'โทรศัพท์', 'โทรสาร', 'E-mail', 'latitude', 'longitude', 'ประเภทอาคาร'
            , 'จำนวนพนักงาน', 'จำนวนอาคารทั้งหมด', 'ผู้ประสานงาน', 'โทร', 'ผู้แก้ไขล่าสุด', 'แก้ไขล่าสุด', 'เขต'];
        foreach ($infos as $info) {
            $infoArray[] = $info->toArray();
        }

        $secArray = [];
        $secArray[] = ['เลขที่', 'เลขที่ชุดแบบสอบถาม', 'ชื่อนิติบุคคล', 'ชื่ออาคาร', 'ประเภท', 'การใช้พลังงาน(TOE)', 'ผลผลิต(ตารางเมตร/เตียง-วัน/ห้อง-วัน)', 'พื้นที่ไม่ปรับอากาศ(ตารางเมตร)', 'SEC(*10^-5)'];

        $allMain = Main::join('place_types', 'place_types.id', 'mains.place_type_id')->where('place_types.type', "อาคาร")
            // ->where('mains.id','606')
            ->get(['mains.*', 'place_types.name']);
        foreach ($allMain as $main) {
            $elec = Tranformer_info::join('mains', 'mains.id', 'tranformer_infos.main_id')
                ->join('electric_used_per_years', 'electric_used_per_years.tranformer_info_id', 'tranformer_infos.id')
                ->where('mains.id', $main->id)
                ->sum('electric_used_per_years.power_used');
            $sec_elec = $elec * (0.000085984522785899);
//            dd($sec_elec);
            $fuel = Energy_and_juristic::join('energy_used_per_years', 'energy_used_per_years.energy_and_juristic_id', 'energy_and_juristics.id')
                ->join('mains', 'mains.id', 'energy_and_juristics.main_id')
                ->join('energy_types', 'et_id', 'energy_types.id')
                ->where('energy_and_juristics.main_id', $main->id)
                ->where('energy_types.type', 'พลังงานสิ้นเปลือง')
                ->sum('energy_used_per_years.mj');
            $sec_fuel = $fuel * (0.00002388458966275);

            $renew = Energy_and_juristic::join('energy_used_per_years', 'energy_used_per_years.energy_and_juristic_id', 'energy_and_juristics.id')
                ->join('mains', 'mains.id', 'energy_and_juristics.main_id')
                ->join('energy_types', 'et_id', 'energy_types.id')
                ->where('energy_and_juristics.main_id', $main->id)
                ->where('energy_types.type', 'พลังงานหมุนเวียน')
                ->sum('energy_used_per_years.mj');
            $sec_renew = $renew * (0.00002388458966275);

            $all_toe = $sec_elec + $sec_fuel + $sec_renew;

            if ($main->name == '["1"]') {
                // $check_type->name = 'สำนักงานเอกชน';
                $type = 'สำนักงานเอกชน';
                $factor = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.air_conditioned');

                $factor_nonair = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.non_air_conditioned');
            } elseif ($main->name == '["2"]') {
                // $check_type->name = 'สำนักงานรัฐบาล';
                $type = 'สำนักงานรัฐบาล';
                $factor = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.air_conditioned');

                $factor_nonair = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.non_air_conditioned');
            } elseif ($main->name == '["3"]') {
                // $check_type->name = 'โรงแรม';
                $type = 'โรงแรม';
                $factor = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->sum('working_per_months.hotel');

                $factor_nonair = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.non_air_conditioned');
            } elseif ($main->name == '["4"]') {
                // $check_type->name = 'โรงพยาบาล';
                $type = 'โรงพยาบาล';
                $factor = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->sum('working_per_months.hospital');

                $factor_nonair = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.non_air_conditioned');
            } elseif ($main->name == '["5"]') {
                // $check_type->name = 'ห้างสรรพสินค้า';
                $type = 'ห้างสรรพสินค้า';
                $factor = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.air_conditioned');

                $factor_nonair = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.non_air_conditioned');
            } elseif ($main->name == '["6"]') {
                // $check_type->name = 'โรงปศุสัตว์';
                $type = 'โรงปศุสัตว์';
                $factor = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.air_conditioned');

                $factor_nonair = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.non_air_conditioned');
            } elseif ($main->name == '["7"]') {
                // $check_type->name = 'โรงเรียน';
                $type = 'โรงเรียน';
                $factor = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.air_conditioned');

                $factor_nonair = DB::table('mains')
                    ->join('building_informations', 'building_informations.main_id', '=', 'mains.id')
                    ->join('working_per_months', 'working_per_months.building_information_id', '=', 'building_informations.id')
                    ->where('mains.id', $main->id)
                    ->avg('working_per_months.non_air_conditioned');
            }

            if ($factor == 0) {
                // $factor = 'ข้อมูลไม่เพียงพอ';
                $sec = '0';
                $secArray[] = [
                    'id' => $main->id,
                    'code' => $main->code,
                    'person_name' => $main->person_name,
                    'place_name' => $main->place_name,
                    'place_type' => $type,
                    'toe' => $all_toe,
                    'factor' => $factor,
                    'factor_nonair' => $factor_nonair,
                    'sec' => $sec];

            } else {
                $sec = ($all_toe / $factor) * 100000;
                $secArray[] = [
                    'id' => $main->id,
                    'code' => $main->code,
                    'person_name' => $main->person_name,
                    'place_name' => $main->place_name,
                    'place_type' => $type,
                    'toe' => $all_toe,
                    'factor' => $factor,
                    'factor_nonair' => $factor_nonair,
                    'sec' => $sec];
            }


        }
        //generate spreadsheets
// dd($secArray);
        Excel::create('แบบสอบถามอาคาร', function ($excel) use ($infoArray, $secArray) {
            $excel->sheet('ข้อมูลทั่วไป', function ($sheet) use ($infoArray) {
                $sheet->fromArray($infoArray, null, 'A1', false, false);
            });
            $excel->sheet('ข้อมูลsec', function ($sheet) use ($secArray) {
                $sheet->fromArray($secArray, null, 'A1', false, false);
            });
        }
        )->download('xls');
        return view('building.index');


    }

}

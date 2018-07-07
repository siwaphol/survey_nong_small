<?php
namespace App\Http\Controllers;

use App\Area;
use App\Building_information;
use App\District;
use App\Elec_use_type;
use App\Electric_used_for_machine;
use App\Energy_and_juristic;
use App\Heat_power_used_for_machine;
use App\Http\Requests;
use App\Main;
use App\Place_data;
use App\Place_type;
use App\Province;
use App\Save_energy_plan;
use App\SubDistrict;
use App\Tranformer_info;
use Maatwebsite\Excel\Facades\Excel;


class BuildingController extends Controller
{
    public function index()
    {
        $areaS = auth()->user()->area;
        $areaU = Area::find($areaS);
        $areaN = $areaU->name;
        if (auth()->user()->level != 'admin') {
            $allBuilding = Place_type::with('mains')->where('type', "อาคาร")->get();
            $area = $areaN;
        } else {
            $allBuilding = Place_type::with('mains')->where('type', "อาคาร")->get();
            $area = '0';
        }

        return view('building.index', compact('allBuilding', 'area'));

    }

    public function excel_sum()
    {

        if (auth()->user()->level != 'admin') {
            $infos = Main::join('place_datas', 'place_datas.main_id', 'mains.id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->where('areas.id', auth()->user()->area)
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
            $provinces = Province::get();
            foreach ($infos as $info) {
                if ($info->name == '["1"]') {
                    $info->name = 'สำนักงานเอกชน';
                } elseif ($info->name == '["2"]') {
                    $info->name = 'สำนักงานรัฐบาล';
                } elseif ($info->name == '["3"]') {
                    $info->name = 'โรงแรม';
                } elseif ($info->name == '["4"]') {
                    $info->name = 'โรงพยาบาล';
                } elseif ($info->name == '["5"]') {
                    $info->name = 'ห้างสรรพสินค้า';
                } elseif ($info->name == '["6"]') {
                    $info->name = 'ฟาร์มปศุสัตว์';
                } elseif ($info->name == '["7"]') {
                    $info->name = 'โรงเรียน';
                }
                $province = Province::where('PROVINCE_ID', $info->province)->get();
                if ($province->count() > 0)
                    $info->province = $province->first()->PROVINCE_NAME;
                $districts = District::where('DISTRICT_ID', $info->district)->get();
                if ($districts->count() > 0)
                    $info->district = $districts->first()->DISTRICT_NAME;
                $subdistricts = SubDistrict::where('SUB_DISTRICT_ID', $info->sub_district)->get();
                if ($subdistricts->count() > 0)
                    $info->sub_district = $subdistricts->first()->SUB_DISTRICT_NAME;

                $infoArray[] = $info->toArray();
            }
//                dd($infoArray);

            $BI = Building_information::join('mains', 'mains.id', 'building_informations.main_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->where('areas.id', auth()->user()->area)
                ->select(
                    'mains.code',
                    'building_informations.name',
                    'building_informations.open',
                    'building_informations.work_hour_hr_d',
                    'building_informations.work_hour_day_y',
                    'building_informations.air_conditioned',
                    'building_informations.non_air_conditioned',
                    'building_informations.total_1',
                    'building_informations.parking_space',
                    'building_informations.total_2'
                )->get();
            $BIArray = [];
            $BIArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่ออาคาร', 'เปิดใช้งานใน พ.ศ', 'ชั่วโมงเวลาทำงาน(ชั่วโมง/วัน)', 'ชั่วโมงเวลาทำงาน(วัน/ปี)'
                , 'พื้นที่ปรับอากาศ', 'พื้นที่ไม่ปรับอากาศ', 'รวม', 'พื้นที่จอดรถในตัวอาคาร', 'รวมทั้งหมด'];

            foreach ($BI as $info) {
                $BIArray[] = $info->toArray();
            }
//        dd($BIArray);
            $WPM = Building_information::join('working_per_months', 'working_per_months.building_information_id', 'building_informations.id')
                ->join('mains', 'mains.id', 'building_informations.main_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->where('areas.id', auth()->user()->area)
                ->select(
                    'mains.code',
                    'building_informations.name',
                    'working_per_months.year',
                    'working_per_months.month',
                    'working_per_months.air_conditioned',
                    'working_per_months.non_air_conditioned',
                    'working_per_months.sumspace',
                    'working_per_months.hotel',
                    'working_per_months.hospital'
                )
                ->get();
            $WPMArray = [];
            $WPMArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่ออาคาร', 'ปี', 'เดือน', 'พื้นที่ปรับอากาศ', 'พื้นที่ไม่ปรับอากาศ'
                , 'รวม', 'จำนวนห้องพัก', 'จำนวนคนไข้ใน'];

            foreach ($WPM as $info) {
                $WPMArray[] = $info->toArray();
            }
//        dd($WPMArray);

            $TI = Tranformer_info::join('mains', 'mains.id', 'tranformer_infos.main_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->where('areas.id', auth()->user()->area)
                ->select(
                    'mains.code',
                    'tranformer_infos.electric_user_no',
                    'tranformer_infos.elec_meter_no',
                    'tranformer_infos.elec_use_type',
                    'tranformer_infos.electric_ratio',
                    'tranformer_infos.tranformer_power',
                    'tranformer_infos.amount'
                )->get();
            $TIArray = [];
            $TIArray[] = ['เลขที่แบบสอบถาม', 'หมายเลผู้ใช้ไฟฟ้า', 'หมายเลขเครื่องวัดไฟฟ้า', 'ประเภทผู้ใช้ไฟฟ้า', 'อัตราการใช้ไฟฟ้า'
                , 'ขนาดหม้อแปลง', 'จำนวน'];

            foreach ($TI as $info) {
                $TIArray[] = $info->toArray();
            }


//        dd($TIArray);

            $EUPY = Tranformer_info::join('electric_used_per_years', 'electric_used_per_years.tranformer_info_id', 'tranformer_infos.id')
                ->join('mains', 'mains.id', 'tranformer_infos.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->where('areas.id', auth()->user()->area)
                ->where('place_types.type', 'อาคาร')
                ->select(
                    'mains.code',
                    'tranformer_infos.electric_user_no',
                    'electric_used_per_years.year',
                    'electric_used_per_years.month',
                    'electric_used_per_years.on_peak',
                    'electric_used_per_years.off_peak',
                    'electric_used_per_years.holiday',
                    'electric_used_per_years.cost_need',
                    'electric_used_per_years.power_used',
                    'electric_used_per_years.cost_true'
                )->get();
            $EUPYArray = [];
            $EUPYArray[] = ['เลขที่ชุดแบบสอบถาม', 'หมายเลผู้ใช้ไฟฟ้า', 'ปี', 'เดือน', 'on_peak', 'off_peak'
                , 'holiday', 'ค่าใช้จ่าย', 'พลังงานไฟฟ้า', 'ค่าใช้จ่าย'];

            foreach ($EUPY as $info) {
                $EUPYArray[] = $info->toArray();
            }

            $E_EAJ = Energy_and_juristic::join('energy_used_per_years', 'energy_used_per_years.energy_and_juristic_id', 'energy_and_juristics.id')
                ->join('mains', 'mains.id', 'energy_and_juristics.main_id')
                ->join('energy_types', 'et_id', 'energy_types.id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->where('areas.id', auth()->user()->area)
                ->where('place_types.type', 'อาคาร')
                ->where('energy_types.type', 'พลังงานสิ้นเปลือง')
                ->select(
                    'mains.code',
                    'energy_types.energy_name',
                    'energy_used_per_years.year',
                    'energy_used_per_years.month',
                    'energy_used_per_years.unit',
                    'energy_used_per_years.cost_unit',
                    'energy_used_per_years.total_cost',
                    'energy_used_per_years.mj'
                )->get();
            $E_EAJArray = [];
            $E_EAJArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชนิดพลังงาน', 'ปี', 'เดือน', 'หน่วย'
                , 'บาท/หน่วย', 'รวมเป็นเงิน', 'เป็นพลังงาน(MJ)'];

            foreach ($E_EAJ as $info) {
                $E_EAJArray[] = $info->toArray();
            }

            $R_EAJ = Energy_and_juristic::join('energy_used_per_years', 'energy_used_per_years.energy_and_juristic_id', 'energy_and_juristics.id')
                ->join('mains', 'mains.id', 'energy_and_juristics.main_id')
                ->join('energy_types', 'et_id', 'energy_types.id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->where('areas.id', auth()->user()->area)
                ->where('place_types.type', 'อาคาร')
                ->where('energy_types.type', 'พลังงานหมุนเวียน')
                ->select(
                    'mains.code',
                    'energy_types.energy_name',
                    'energy_used_per_years.year',
                    'energy_used_per_years.month',
                    'energy_used_per_years.unit',
                    'energy_used_per_years.cost_unit',
                    'energy_used_per_years.total_cost',
                    'energy_used_per_years.mj'
                )->get();
            $R_EAJArray = [];
            $R_EAJArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชนิดพลังงาน', 'ปี', 'เดือน', 'หน่วย'
                , 'บาท/หน่วย', 'รวมเป็นเงิน', 'เป็นพลังงาน(MJ)'];

            foreach ($R_EAJ as $info) {
                $R_EAJArray[] = $info->toArray();
            }

//        dd($R_EAJArray);

            $EUFM = Electric_used_for_machine::join('machine_and_main_tools', 'machine_and_main_tools.id', 'electric_used_for_machines.m_id')
                ->join('mains', 'mains.id', 'electric_used_for_machines.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->where('areas.id', auth()->user()->area)
                ->where('place_types.type', 'อาคาร')
                ->select(
                    'mains.code',
                    'machine_and_main_tools.machine_name',
                    'electric_used_for_machines.size',
                    'machine_and_main_tools.unit',
                    'electric_used_for_machines.amount',
                    'electric_used_for_machines.life_time',
                    'electric_used_for_machines.work_hous',
                    'electric_used_for_machines.average_per_year',
                    'electric_used_for_machines.persentage',
                    'electric_used_for_machines.note'
                )->get();
            $EUFMArray = [];
            $EUFMArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่อเครื่องจักร/อุปกรณ์เหล็ก', 'ขนาด', 'หน่วย', 'จำนวน'
                , 'อายุการใช้งาน', 'ชั่วโมงการใช้งานเฉลี่ยต่อปี', 'ปริมาณการใช้พลังงานไฟฟ้า', 'สัดส่วนการใช้พลังงานในระบบ', 'หมายเหตุ'];

            foreach ($EUFM as $info) {
                $EUFMArray[] = $info->toArray();
            }

//        dd( $EUFMArray);

            $HPUFM = Heat_power_used_for_machine::join('machine_and_main_tools', 'machine_and_main_tools.id', 'heat_power_used_for_machines.m_id')
                ->join('mains', 'mains.id', 'heat_power_used_for_machines.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->join('energy_types', 'energy_types.id', 'heat_power_used_for_machines.energy_type')
                ->where('areas.id', auth()->user()->area)
                ->where('place_types.type', 'อาคาร')
                ->select(
                    'mains.code',
                    'machine_and_main_tools.machine_name',
                    'heat_power_used_for_machines.size',
                    'machine_and_main_tools.unit',
                    'heat_power_used_for_machines.amount',
                    'heat_power_used_for_machines.life_time',
                    'heat_power_used_for_machines.work_hous',
                    'energy_types.energy_name',
                    'heat_power_used_for_machines.unit_en',
                    'heat_power_used_for_machines.average_per_year',
                    'heat_power_used_for_machines.persentage',
                    'heat_power_used_for_machines.note'
                )->get();
            $HPUFMArray = [];
            $HPUFMArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่อเครื่องจักร/อุปกรณ์เหล็ก', 'ขนาด', 'หน่วย', 'จำนวน'
                , 'อายุการใช้งาน', 'ชั่วโมงการใช้งานเฉลี่ยต่อปี', 'การใช้เชื้อเเพลิง(ชนิด)', 'การใช้เชื้อเพลิง(หน่วย)', 'ปริมาณการใช้พลังงานไฟฟ้า', 'สัดส่วนการใช้พลังงานในระบบ', 'หมายเหตุ'];

            foreach ($HPUFM as $info) {
                $HPUFMArray[] = $info->toArray();
            }

//        dd($HPUFMArray);

            $SEP = Save_energy_plan::join('plan_refs', 'plan_refs.id', 'save_energy_plans.plan')
                ->join('mains', 'mains.id', 'save_energy_plans.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->where('areas.id', auth()->user()->area)
                ->where('place_types.type', 'อาคาร')
                ->where('timing_plan', 'past')
                ->select(
                    'mains.code',
                    'plan_refs.plan_name',
                    'save_energy_plans.electric_power_bf',
                    'save_energy_plans.kwh_yr_bf',
                    'save_energy_plans.bath_yr_bf',
                    'save_energy_plans.fuel_kg_yr_bf',
                    'save_energy_plans.fuel_bath_yr_bf',
                    'save_energy_plans.electric_power_af',
                    'save_energy_plans.kwh_yr_af',
                    'save_energy_plans.bath_yr_af',
                    'save_energy_plans.fuel_kg_yr_af',
                    'save_energy_plans.fuel_bath_yr_af',
                    'save_energy_plans.investment',
                    'save_energy_plans.payback_time'
                )->get();
            $SEPArray = [];
            $SEPArray[] = ['เลขที่ชุดแบบสอบถาม', 'มาตรการ', 'การใช้พลังงานไฟฟ้า(ก่อน)', 'การใช้พลังงานไฟฟ้าต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(ก่อน)'
                , 'การใช้พลังงานเชื้อเพลิงต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(ก่อน)', 'การใช้พลังงานไฟฟ้า(หลัง)', 'การใช้พลังงานไฟฟ้าต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(หลัง)', 'การใช้พลังงานเชื้อเพลิงต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(หลัง)', 'เงินลงทุน', 'เวลาคืนทุน'];

            foreach ($SEP as $info) {
                $SEPArray[] = $info->toArray();
            }

//        dd($SEPArray);

            $FEP = Save_energy_plan::join('plan_refs', 'plan_refs.id', 'save_energy_plans.plan')
                ->join('mains', 'mains.id', 'save_energy_plans.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->join('areas', 'areas.name', 'mains.user_area')
                ->where('areas.id', auth()->user()->area)
                ->where('place_types.type', 'อาคาร')
                ->where('timing_plan', 'future')
                ->select(
                    'mains.code',
                    'plan_refs.plan_name',
                    'save_energy_plans.electric_power_bf',
                    'save_energy_plans.kwh_yr_bf',
                    'save_energy_plans.bath_yr_bf',
                    'save_energy_plans.fuel_kg_yr_bf',
                    'save_energy_plans.fuel_bath_yr_bf',
                    'save_energy_plans.electric_power_af',
                    'save_energy_plans.kwh_yr_af',
                    'save_energy_plans.bath_yr_af',
                    'save_energy_plans.fuel_kg_yr_af',
                    'save_energy_plans.fuel_bath_yr_af',
                    'save_energy_plans.investment',
                    'save_energy_plans.payback_time'
                )->get();
            $FEPArray = [];
            $FEPArray[] = ['เลขที่ชุดแบบสอบถาม', 'มาตรการ', 'การใช้พลังงานไฟฟ้า(ก่อน)', 'การใช้พลังงานไฟฟ้าต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(ก่อน)'
                , 'การใช้พลังงานเชื้อเพลิงต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(ก่อน)', 'การใช้พลังงานไฟฟ้า(หลัง)', 'การใช้พลังงานไฟฟ้าต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(หลัง)', 'การใช้พลังงานเชื้อเพลิงต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(หลัง)', 'เงินลงทุน', 'เวลาคืนทุน'];

            foreach ($FEP as $info) {
                $FEPArray[] = $info->toArray();
            }


            //generate spreadsheets

            Excel::create('แบบสอบถามอาคาร', function ($excel) use ($infoArray, $BIArray, $WPMArray, $TIArray, $EUPYArray, $E_EAJArray, $R_EAJArray, $EUFMArray, $HPUFMArray, $SEPArray, $FEPArray) {
                $excel->sheet('ข้อมูลทั่วไป', function ($sheet) use ($infoArray) {
                    $sheet->fromArray($infoArray, null, 'A1', false, false);
                });
                $excel->sheet('ข้อมูลทั่วไปของอาคาร', function ($sheet) use ($BIArray) {
                    $sheet->fromArray($BIArray, null, 'A1', false, false);
                });
                $excel->sheet('ข้อมูลการใช้อาคารในแต่ละเดือน', function ($sheet) use ($WPMArray) {
                    $sheet->fromArray($WPMArray, null, 'A1', false, false);
                });
                $excel->sheet('ข้อมูลหม้อแปลงไฟฟ้า', function ($sheet) use ($TIArray) {
                    $sheet->fromArray($TIArray, null, 'A1', false, false);
                });
                $excel->sheet('ข้อมูลการใช้ไฟฟ้าในแต่ละเดือน', function ($sheet) use ($EUPYArray) {
                    $sheet->fromArray($EUPYArray, null, 'A1', false, false);
                });
                $excel->sheet('เชื้อเพลิงสิ้นเปลือง', function ($sheet) use ($E_EAJArray) {
                    $sheet->fromArray($E_EAJArray, null, 'A1', false, false);
                });
                $excel->sheet('เชื้อเพลิงหมุนเวียน', function ($sheet) use ($R_EAJArray) {
                    $sheet->fromArray($R_EAJArray, null, 'A1', false, false);
                });
                $excel->sheet('การใช้ไฟฟ้าของเครื่องจักร', function ($sheet) use ($EUFMArray) {
                    $sheet->fromArray($EUFMArray, null, 'A1', false, false);
                });
                $excel->sheet('การใช้ความร้อนของเครื่องจักร', function ($sheet) use ($HPUFMArray) {
                    $sheet->fromArray($HPUFMArray, null, 'A1', false, false);
                });
                $excel->sheet('มาตราการอนุรักษ์พลังงานในอดีต', function ($sheet) use ($SEPArray) {
                    $sheet->fromArray($SEPArray, null, 'A1', false, false);
                });
                $excel->sheet('มาตราการอนุรักษ์พลังงานในอนาคต', function ($sheet) use ($FEPArray) {
                    $sheet->fromArray($FEPArray, null, 'A1', false, false);

                });

            }
            )->download('xls');
            return view('building.index');
        } else {
//            $allBuilding = Place_type::with('mains')->where('type', "อาคาร")->get();
//            $area = '0';

            $infos = Main::join('place_datas', 'place_datas.main_id', 'mains.id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
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
            $subdistricts = SubDistrict::get();
            $districts = District::get();
            $provinces = Province::get();
            foreach ($infos as $info) {
                if ($info->name == '["1"]') {
                    $info->name = 'สำนักงานเอกชน';
                } elseif ($info->name == '["2"]') {
                    $info->name = 'สำนักงานรัฐบาล';
                } elseif ($info->name == '["3"]') {
                    $info->name = 'โรงแรม';
                } elseif ($info->name == '["4"]') {
                    $info->name = 'โรงพยาบาล';
                } elseif ($info->name == '["5"]') {
                    $info->name = 'ห้างสรรพสินค้า';
                } elseif ($info->name == '["6"]') {
                    $info->name = 'ฟาร์มปศุสัตว์';
                } elseif ($info->name == '["7"]') {
                    $info->name = 'โรงเรียน';
                }
                $province = Province::where('PROVINCE_ID', $info->province)->get();
                if ($province->count() > 0)
                    $info->province = $province->first()->PROVINCE_NAME;
                $districts = District::where('DISTRICT_ID', $info->district)->get();
                if ($districts->count() > 0)
                    $info->district = $districts->first()->DISTRICT_NAME;
                $subdistricts = SubDistrict::where('SUB_DISTRICT_ID', $info->sub_district)->get();
                if ($subdistricts->count() > 0)
                    $info->sub_district = $subdistricts->first()->SUB_DISTRICT_NAME;

                $infoArray[] = $info->toArray();
            }

            $BI = Building_information::join('mains', 'mains.id', 'building_informations.main_id')
                ->select(
                    'mains.code',
                    'building_informations.name',
                    'building_informations.open',
                    'building_informations.work_hour_hr_d',
                    'building_informations.work_hour_day_y',
                    'building_informations.air_conditioned',
                    'building_informations.non_air_conditioned',
                    'building_informations.total_1',
                    'building_informations.parking_space',
                    'building_informations.total_2'
                )->get();
            $BIArray = [];
            $BIArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่ออาคาร', 'เปิดใช้งานใน พ.ศ', 'ชั่วโมงเวลาทำงาน(ชั่วโมง/วัน)', 'ชั่วโมงเวลาทำงาน(วัน/ปี)'
                , 'พื้นที่ปรับอากาศ', 'พื้นที่ไม่ปรับอากาศ', 'รวม', 'พื้นที่จอดรถในตัวอาคาร', 'รวมทั้งหมด'];

            foreach ($BI as $info) {
                $BIArray[] = $info->toArray();
            }
//        dd($BIArray);
            $WPM = Building_information::join('working_per_months', 'working_per_months.building_information_id', 'building_informations.id')
                ->join('mains', 'mains.id', 'building_informations.main_id')
                ->select(
                    'mains.code',
                    'building_informations.name',
                    'working_per_months.year',
                    'working_per_months.month',
                    'working_per_months.air_conditioned',
                    'working_per_months.non_air_conditioned',
                    'working_per_months.sumspace',
                    'working_per_months.hotel',
                    'working_per_months.hospital'
                )
                ->get();
            $WPMArray = [];
            $WPMArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่ออาคาร', 'ปี', 'เดือน', 'พื้นที่ปรับอากาศ', 'พื้นที่ไม่ปรับอากาศ'
                , 'รวม', 'จำนวนห้องพัก', 'จำนวนคนไข้ใน'];

            foreach ($WPM as $info) {
                $WPMArray[] = $info->toArray();
            }
//        dd($WPMArray);

            $TI = Tranformer_info::join('mains', 'mains.id', 'tranformer_infos.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->where('place_types.type', 'อาคาร')
                ->select(
                    'mains.code',
                    'tranformer_infos.electric_user_no',
                    'tranformer_infos.elec_meter_no',
                    'tranformer_infos.elec_use_type',
                    'tranformer_infos.electric_ratio',
                    'tranformer_infos.tranformer_power',
                    'tranformer_infos.amount'
                )->get();
            $TIArray = [];
            $TIArray[] = ['เลขที่แบบสอบถาม', 'หมายเลผู้ใช้ไฟฟ้า', 'หมายเลขเครื่องวัดไฟฟ้า', 'ประเภทผู้ใช้ไฟฟ้า', 'อัตราการใช้ไฟฟ้า'
                , 'ขนาดหม้อแปลง', 'จำนวน'];

            foreach ($TI as $info) {
                $TIArray[] = $info->toArray();
            }


//        dd($TIArray);

            $EUPY = Tranformer_info::join('electric_used_per_years', 'electric_used_per_years.tranformer_info_id', 'tranformer_infos.id')
                ->join('mains', 'mains.id', 'tranformer_infos.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->where('place_types.type', 'อาคาร')
                ->select(
                    'mains.code',
                    'tranformer_infos.electric_user_no',
                    'electric_used_per_years.year',
                    'electric_used_per_years.month',
                    'electric_used_per_years.on_peak',
                    'electric_used_per_years.off_peak',
                    'electric_used_per_years.holiday',
                    'electric_used_per_years.cost_need',
                    'electric_used_per_years.power_used',
                    'electric_used_per_years.cost_true'
                )->get();
            $EUPYArray = [];
            $EUPYArray[] = ['เลขที่ชุดแบบสอบถาม', 'หมายเลผู้ใช้ไฟฟ้า', 'ปี', 'เดือน', 'on_peak', 'off_peak'
                , 'holiday', 'ค่าใช้จ่าย', 'พลังงานไฟฟ้า', 'ค่าใช้จ่าย'];

            foreach ($EUPY as $info) {
                $EUPYArray[] = $info->toArray();
            }

//        dd($EUPYArray);

            $E_EAJ = Energy_and_juristic::join('energy_used_per_years', 'energy_used_per_years.energy_and_juristic_id', 'energy_and_juristics.id')
                ->join('mains', 'mains.id', 'energy_and_juristics.main_id')
                ->join('energy_types', 'et_id', 'energy_types.id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->where('place_types.type', 'อาคาร')
                ->where('energy_types.type', 'พลังงานสิ้นเปลือง')
                ->select(
                    'mains.code',
                    'energy_types.energy_name',
                    'energy_used_per_years.year',
                    'energy_used_per_years.month',
                    'energy_used_per_years.unit',
                    'energy_used_per_years.cost_unit',
                    'energy_used_per_years.total_cost',
                    'energy_used_per_years.mj'
                )->get();
            $E_EAJArray = [];
            $E_EAJArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชนิดพลังงาน', 'ปี', 'เดือน', 'หน่วย'
                , 'บาท/หน่วย', 'รวมเป็นเงิน', 'เป็นพลังงาน(MJ)'];

            foreach ($E_EAJ as $info) {
                $E_EAJArray[] = $info->toArray();
            }

//        dd($E_EAJArray);

            $R_EAJ = Energy_and_juristic::join('energy_used_per_years', 'energy_used_per_years.energy_and_juristic_id', 'energy_and_juristics.id')
                ->join('mains', 'mains.id', 'energy_and_juristics.main_id')
                ->join('energy_types', 'et_id', 'energy_types.id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->where('place_types.type', 'อาคาร')
                ->where('energy_types.type', 'พลังงานหมุนเวียน')
                ->select(
                    'mains.code',
                    'energy_types.energy_name',
                    'energy_used_per_years.year',
                    'energy_used_per_years.month',
                    'energy_used_per_years.unit',
                    'energy_used_per_years.cost_unit',
                    'energy_used_per_years.total_cost',
                    'energy_used_per_years.mj'
                )->get();
            $R_EAJArray = [];
            $R_EAJArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชนิดพลังงาน', 'ปี', 'เดือน', 'หน่วย'
                , 'บาท/หน่วย', 'รวมเป็นเงิน', 'เป็นพลังงาน(MJ)'];

            foreach ($R_EAJ as $info) {
                $R_EAJArray[] = $info->toArray();
            }

            $EUFM = Electric_used_for_machine::join('machine_and_main_tools', 'machine_and_main_tools.id', 'electric_used_for_machines.m_id')
                ->join('mains', 'mains.id', 'electric_used_for_machines.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->where('place_types.type', 'อาคาร')
                ->select(
                    'mains.code',
                    'machine_and_main_tools.machine_name',
                    'electric_used_for_machines.size',
                    'machine_and_main_tools.unit',
                    'electric_used_for_machines.amount',
                    'electric_used_for_machines.life_time',
                    'electric_used_for_machines.work_hous',
                    'electric_used_for_machines.average_per_year',
                    'electric_used_for_machines.persentage',
                    'electric_used_for_machines.note'
                )->get();
            $EUFMArray = [];
            $EUFMArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่อเครื่องจักร/อุปกรณ์เหล็ก', 'ขนาด', 'หน่วย', 'จำนวน'
                , 'อายุการใช้งาน', 'ชั่วโมงการใช้งานเฉลี่ยต่อปี', 'ปริมาณการใช้พลังงานไฟฟ้า', 'สัดส่วนการใช้พลังงานในระบบ', 'หมายเหตุ'];

            foreach ($EUFM as $info) {
                $EUFMArray[] = $info->toArray();
            }

//        dd( $EUFMArray);

            $HPUFM = Heat_power_used_for_machine::join('machine_and_main_tools', 'machine_and_main_tools.id', 'heat_power_used_for_machines.m_id')
                ->join('mains', 'mains.id', 'heat_power_used_for_machines.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->join('energy_types', 'energy_types.id', 'heat_power_used_for_machines.energy_type')
                ->where('place_types.type', 'อาคาร')
                ->select(
                    'mains.code',
                    'machine_and_main_tools.machine_name',
                    'heat_power_used_for_machines.size',
                    'machine_and_main_tools.unit',
                    'heat_power_used_for_machines.amount',
                    'heat_power_used_for_machines.life_time',
                    'heat_power_used_for_machines.work_hous',
                    'energy_types.energy_name',
                    'heat_power_used_for_machines.unit_en',
                    'heat_power_used_for_machines.average_per_year',
                    'heat_power_used_for_machines.persentage',
                    'heat_power_used_for_machines.note'
                )->get();
            $HPUFMArray = [];
            $HPUFMArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่อเครื่องจักร/อุปกรณ์เหล็ก', 'ขนาด', 'หน่วย', 'จำนวน'
                , 'อายุการใช้งาน', 'ชั่วโมงการใช้งานเฉลี่ยต่อปี', 'การใช้เชื้อเเพลิง(ชนิด)', 'การใช้เชื้อเพลิง(หน่วย)', 'ปริมาณการใช้พลังงานไฟฟ้า', 'สัดส่วนการใช้พลังงานในระบบ', 'หมายเหตุ'];

            foreach ($HPUFM as $info) {
                $HPUFMArray[] = $info->toArray();
            }

//        dd($HPUFMArray);

            $SEP = Save_energy_plan::join('plan_refs', 'plan_refs.id', 'save_energy_plans.plan')
                ->join('mains', 'mains.id', 'save_energy_plans.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->where('place_types.type', 'อาคาร')
                ->where('timing_plan', 'past')
                ->select(
                    'mains.code',
                    'plan_refs.plan_name',
                    'save_energy_plans.electric_power_bf',
                    'save_energy_plans.kwh_yr_bf',
                    'save_energy_plans.bath_yr_bf',
                    'save_energy_plans.fuel_kg_yr_bf',
                    'save_energy_plans.fuel_bath_yr_bf',
                    'save_energy_plans.electric_power_af',
                    'save_energy_plans.kwh_yr_af',
                    'save_energy_plans.bath_yr_af',
                    'save_energy_plans.fuel_kg_yr_af',
                    'save_energy_plans.fuel_bath_yr_af',
                    'save_energy_plans.investment',
                    'save_energy_plans.payback_time'
                )->get();
            $SEPArray = [];
            $SEPArray[] = ['เลขที่ชุดแบบสอบถาม', 'มาตรการ', 'การใช้พลังงานไฟฟ้า(ก่อน)', 'การใช้พลังงานไฟฟ้าต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(ก่อน)'
                , 'การใช้พลังงานเชื้อเพลิงต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(ก่อน)', 'การใช้พลังงานไฟฟ้า(หลัง)', 'การใช้พลังงานไฟฟ้าต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(หลัง)', 'การใช้พลังงานเชื้อเพลิงต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(หลัง)', 'เงินลงทุน', 'เวลาคืนทุน'];

            foreach ($SEP as $info) {
                $SEPArray[] = $info->toArray();
            }

//        dd($SEPArray);

            $FEP = Save_energy_plan::join('plan_refs', 'plan_refs.id', 'save_energy_plans.plan')
                ->join('mains', 'mains.id', 'save_energy_plans.main_id')
                ->join('place_types', 'place_types.id', 'mains.place_type_id')
                ->where('place_types.type', 'อาคาร')
                ->where('timing_plan', 'future')
                ->select(
                    'mains.code',
                    'plan_refs.plan_name',
                    'save_energy_plans.electric_power_bf',
                    'save_energy_plans.kwh_yr_bf',
                    'save_energy_plans.bath_yr_bf',
                    'save_energy_plans.fuel_kg_yr_bf',
                    'save_energy_plans.fuel_bath_yr_bf',
                    'save_energy_plans.electric_power_af',
                    'save_energy_plans.kwh_yr_af',
                    'save_energy_plans.bath_yr_af',
                    'save_energy_plans.fuel_kg_yr_af',
                    'save_energy_plans.fuel_bath_yr_af',
                    'save_energy_plans.investment',
                    'save_energy_plans.payback_time'
                )->get();
            $FEPArray = [];
            $FEPArray[] = ['เลขที่ชุดแบบสอบถาม', 'มาตรการ', 'การใช้พลังงานไฟฟ้า(ก่อน)', 'การใช้พลังงานไฟฟ้าต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(ก่อน)'
                , 'การใช้พลังงานเชื้อเพลิงต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(ก่อน)', 'การใช้พลังงานไฟฟ้า(หลัง)', 'การใช้พลังงานไฟฟ้าต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(หลัง)', 'การใช้พลังงานเชื้อเพลิงต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(หลัง)', 'เงินลงทุน', 'เวลาคืนทุน'];

            foreach ($FEP as $info) {
                $FEPArray[] = $info->toArray();
            }


            //generate spreadsheets

            Excel::create('แบบสอบถามอาคาร', function ($excel) use ($infoArray, $BIArray, $WPMArray, $TIArray, $EUPYArray, $E_EAJArray, $R_EAJArray, $EUFMArray, $HPUFMArray, $SEPArray, $FEPArray) {
                $excel->sheet('ข้อมูลทั่วไป', function ($sheet) use ($infoArray) {
                    $sheet->fromArray($infoArray, null, 'A1', false, false);
                });
                $excel->sheet('ข้อมูลทั่วไปของอาคาร', function ($sheet) use ($BIArray) {
                    $sheet->fromArray($BIArray, null, 'A1', false, false);
                });
                $excel->sheet('ข้อมูลการใช้อาคารในแต่ละเดือน', function ($sheet) use ($WPMArray) {
                    $sheet->fromArray($WPMArray, null, 'A1', false, false);
                });
                $excel->sheet('ข้อมูลหม้อแปลงไฟฟ้า', function ($sheet) use ($TIArray) {
                    $sheet->fromArray($TIArray, null, 'A1', false, false);
                });
                $excel->sheet('ข้อมูลการใช้ไฟฟ้าในแต่ละเดือน', function ($sheet) use ($EUPYArray) {
                    $sheet->fromArray($EUPYArray, null, 'A1', false, false);
                });
                $excel->sheet('เชื้อเพลิงสิ้นเปลือง', function ($sheet) use ($E_EAJArray) {
                    $sheet->fromArray($E_EAJArray, null, 'A1', false, false);
                });
                $excel->sheet('เชื้อเพลิงหมุนเวียน', function ($sheet) use ($R_EAJArray) {
                    $sheet->fromArray($R_EAJArray, null, 'A1', false, false);
                });
                $excel->sheet('การใช้ไฟฟ้าของเครื่องจักร', function ($sheet) use ($EUFMArray) {
                    $sheet->fromArray($EUFMArray, null, 'A1', false, false);
                });
                $excel->sheet('การใช้ความร้อนของเครื่องจักร', function ($sheet) use ($HPUFMArray) {
                    $sheet->fromArray($HPUFMArray, null, 'A1', false, false);
                });
                $excel->sheet('มาตราการอนุรักษ์พลังงานในอดีต', function ($sheet) use ($SEPArray) {
                    $sheet->fromArray($SEPArray, null, 'A1', false, false);
                });
                $excel->sheet('มาตราการอนุรักษ์พลังงานในอนาคต', function ($sheet) use ($FEPArray) {
                    $sheet->fromArray($FEPArray, null, 'A1', false, false);

                });

            }
            )->download('xls');
            return view('building.index');
        }
    }


    public function excel($doc_number)
    {

        //get data general information
//        dd($doc_number);
        $infos = Main::join('place_datas', 'place_datas.main_id', 'mains.id')
            ->join('place_types', 'place_types.id', 'mains.place_type_id')
            ->where('mains.id', $doc_number)
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
//        dd($infos);
        $infoArray = [];
        $infoArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่อนิติบุคคล', 'ชื่ออาคาร', 'เลขที่', 'หมู่', 'ถนน'
            , 'คำบล/แขลง', 'อำเภอ/เขต', 'จังหวัด', 'รหัสไปรษณีย์', 'โทรศัพท์', 'โทรสาร', 'E-mail', 'latitude', 'longitude', 'ประเภทอาคาร'
            , 'จำนวนพนักงาน', 'จำนวนอาคารทั้งหมด', 'ผู้ประสานงาน', 'โทร', 'ผู้แก้ไขล่าสุด', 'แก้ไขล่าสุด', 'เขต'];
        $provinces = Province::get();
        foreach ($infos as $info) {
            if ($info->name == '["1"]') {
                $info->name = 'สำนักงานเอกชน';
            } elseif ($info->name == '["2"]') {
                $info->name = 'สำนักงานรัฐบาล';
            } elseif ($info->name == '["3"]') {
                $info->name = 'โรงแรม';
            } elseif ($info->name == '["4"]') {
                $info->name = 'โรงพยาบาล';
            } elseif ($info->name == '["5"]') {
                $info->name = 'ห้างสรรพสินค้า';
            } elseif ($info->name == '["6"]') {
                $info->name = 'ฟาร์มปศุสัตว์';
            } elseif ($info->name == '["7"]') {
                $info->name = 'โรงเรียน';
            }
            $province = Province::where('PROVINCE_ID', $info->province)->get();
            if ($province->count() > 0)
                $info->province = $province->first()->PROVINCE_NAME;
            $districts = District::where('DISTRICT_ID', $info->district)->get();
            if ($districts->count() > 0)
                $info->district = $districts->first()->DISTRICT_NAME;
            $subdistricts = SubDistrict::where('SUB_DISTRICT_ID', $info->sub_district)->get();
            if ($subdistricts->count() > 0)
                $info->sub_district = $subdistricts->first()->SUB_DISTRICT_NAME;
            $infoArray[] = $info->toArray();
        }


        $BI = Building_information::join('mains', 'mains.id', 'building_informations.main_id')
            ->where('main_id', $doc_number)
            ->select(
                'mains.code',
                'building_informations.name',
                'building_informations.open',
                'building_informations.work_hour_hr_d',
                'building_informations.work_hour_day_y',
                'building_informations.air_conditioned',
                'building_informations.non_air_conditioned',
                'building_informations.total_1',
                'building_informations.parking_space',
                'building_informations.total_2'
            )->get();
        $BIArray = [];
        $BIArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่ออาคาร', 'เปิดใช้งานใน พ.ศ', 'ชั่วโมงเวลาทำงาน(ชั่วโมง/วัน)', 'ชั่วโมงเวลาทำงาน(วัน/ปี)'
            , 'พื้นที่ปรับอากาศ', 'พื้นที่ไม่ปรับอากาศ', 'รวม', 'พื้นที่จอดรถในตัวอาคาร', 'รวมทั้งหมด'];

        foreach ($BI as $info) {
            $BIArray[] = $info->toArray();
        }
//        dd($BIArray);
        $WPM = Building_information::join('working_per_months', 'working_per_months.building_information_id', 'building_informations.id')
            ->join('mains', 'mains.id', 'building_informations.main_id')
            ->where('building_informations.main_id', $doc_number)
            ->select(
                'mains.code',
                'building_informations.name',
                'working_per_months.year',
                'working_per_months.month',
                'working_per_months.air_conditioned',
                'working_per_months.non_air_conditioned',
                'working_per_months.sumspace',
                'working_per_months.hotel',
                'working_per_months.hospital'
            )
            ->get();
        $WPMArray = [];
        $WPMArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่ออาคาร', 'ปี', 'เดือน', 'พื้นที่ปรับอากาศ', 'พื้นที่ไม่ปรับอากาศ'
            , 'รวม', 'จำนวนห้องพัก', 'จำนวนคนไข้ใน'];

        foreach ($WPM as $info) {
            $WPMArray[] = $info->toArray();
        }
//        dd($WPMArray);

        $TI = Tranformer_info::join('mains', 'mains.id', 'tranformer_infos.main_id')
            ->where('main_id', $doc_number)
            ->select(
                'mains.code',
                'tranformer_infos.electric_user_no',
                'tranformer_infos.elec_meter_no',
                'tranformer_infos.elec_use_type',
                'tranformer_infos.electric_ratio',
                'tranformer_infos.tranformer_power',
                'tranformer_infos.amount'
            )->get();
        $TIArray = [];
        $TIArray[] = ['เลขที่แบบสอบถาม', 'หมายเลผู้ใช้ไฟฟ้า', 'หมายเลขเครื่องวัดไฟฟ้า', 'ประเภทผู้ใช้ไฟฟ้า', 'อัตราการใช้ไฟฟ้า'
            , 'ขนาดหม้อแปลง', 'จำนวน'];

        foreach ($TI as $info) {
            $TIArray[] = $info->toArray();
        }


//        dd($TIArray);

        $EUPY = Tranformer_info::join('electric_used_per_years', 'electric_used_per_years.tranformer_info_id', 'tranformer_infos.id')
            ->join('mains', 'mains.id', 'tranformer_infos.main_id')
            ->where('main_id', $doc_number)
            ->select(
                'mains.code',
                'tranformer_infos.electric_user_no',
                'electric_used_per_years.year',
                'electric_used_per_years.month',
                'electric_used_per_years.on_peak',
                'electric_used_per_years.off_peak',
                'electric_used_per_years.holiday',
                'electric_used_per_years.cost_need',
                'electric_used_per_years.power_used',
                'electric_used_per_years.cost_true'
            )->get();
        $EUPYArray = [];
        $EUPYArray[] = ['เลขที่ชุดแบบสอบถาม', 'หมายเลผู้ใช้ไฟฟ้า', 'ปี', 'เดือน', 'on_peak', 'off_peak'
            , 'holiday', 'ค่าใช้จ่าย', 'พลังงานไฟฟ้า', 'ค่าใช้จ่าย'];

        foreach ($EUPY as $info) {
            $EUPYArray[] = $info->toArray();
        }

//        dd($EUPYArray);

        $E_EAJ = Energy_and_juristic::join('energy_used_per_years', 'energy_used_per_years.energy_and_juristic_id', 'energy_and_juristics.id')
            ->join('mains', 'mains.id', 'energy_and_juristics.main_id')
            ->join('energy_types', 'et_id', 'energy_types.id')
            ->where('main_id', $doc_number)
            ->where('type', 'พลังงานสิ้นเปลือง')
            ->select(
                'mains.code',
                'energy_types.energy_name',
                'energy_used_per_years.year',
                'energy_used_per_years.month',
                'energy_used_per_years.unit',
                'energy_used_per_years.cost_unit',
                'energy_used_per_years.total_cost',
                'energy_used_per_years.mj'
            )->get();
        $E_EAJArray = [];
        $E_EAJArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชนิดพลังงาน', 'ปี', 'เดือน', 'หน่วย'
            , 'บาท/หน่วย', 'รวมเป็นเงิน', 'เป็นพลังงาน(MJ)'];

        foreach ($E_EAJ as $info) {
            $E_EAJArray[] = $info->toArray();
        }

//        dd($E_EAJArray);

        $R_EAJ = Energy_and_juristic::join('energy_used_per_years', 'energy_used_per_years.energy_and_juristic_id', 'energy_and_juristics.id')
            ->join('mains', 'mains.id', 'energy_and_juristics.main_id')
            ->join('energy_types', 'et_id', 'energy_types.id')
            ->where('main_id', $doc_number)
            ->where('type', 'พลังงานหมุนเวียน')
            ->select(
                'mains.code',
                'energy_types.energy_name',
                'energy_used_per_years.year',
                'energy_used_per_years.month',
                'energy_used_per_years.unit',
                'energy_used_per_years.cost_unit',
                'energy_used_per_years.total_cost',
                'energy_used_per_years.mj'
            )->get();
        $R_EAJArray = [];
        $R_EAJArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชนิดพลังงาน', 'ปี', 'เดือน', 'หน่วย'
            , 'บาท/หน่วย', 'รวมเป็นเงิน', 'เป็นพลังงาน(MJ)'];

        foreach ($R_EAJ as $info) {
            $R_EAJArray[] = $info->toArray();
        }

//        dd($R_EAJArray);

        $EUFM = Electric_used_for_machine::join('machine_and_main_tools', 'machine_and_main_tools.id', 'electric_used_for_machines.m_id')
            ->join('mains', 'mains.id', 'electric_used_for_machines.main_id')
            ->where('main_id', $doc_number)
            ->select(
                'mains.code',
                'machine_and_main_tools.machine_name',
                'electric_used_for_machines.size',
                'machine_and_main_tools.unit',
                'electric_used_for_machines.amount',
                'electric_used_for_machines.life_time',
                'electric_used_for_machines.work_hous',
                'electric_used_for_machines.average_per_year',
                'electric_used_for_machines.persentage',
                'electric_used_for_machines.note'
            )->get();
        $EUFMArray = [];
        $EUFMArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่อเครื่องจักร/อุปกรณ์เหล็ก', 'ขนาด', 'หน่วย', 'จำนวน'
            , 'อายุการใช้งาน', 'ชั่วโมงการใช้งานเฉลี่ยต่อปี', 'ปริมาณการใช้พลังงานไฟฟ้า', 'สัดส่วนการใช้พลังงานในระบบ', 'หมายเหตุ'];

        foreach ($EUFM as $info) {
            $EUFMArray[] = $info->toArray();
        }

//        dd( $EUFMArray);

        $HPUFM = Heat_power_used_for_machine::join('machine_and_main_tools', 'machine_and_main_tools.id', 'heat_power_used_for_machines.m_id')
            ->join('mains', 'mains.id', 'heat_power_used_for_machines.main_id')
            ->join('energy_types', 'energy_types.id', 'heat_power_used_for_machines.energy_type')
            ->where('main_id', $doc_number)
            ->select(
                'mains.code',
                'machine_and_main_tools.machine_name',
                'heat_power_used_for_machines.size',
                'machine_and_main_tools.unit',
                'heat_power_used_for_machines.amount',
                'heat_power_used_for_machines.life_time',
                'heat_power_used_for_machines.work_hous',
                'energy_types.energy_name',
                'heat_power_used_for_machines.unit_en',
                'heat_power_used_for_machines.average_per_year',
                'heat_power_used_for_machines.persentage',
                'heat_power_used_for_machines.note'
            )->get();
        $HPUFMArray = [];
        $HPUFMArray[] = ['เลขที่ชุดแบบสอบถาม', 'ชื่อเครื่องจักร/อุปกรณ์เหล็ก', 'ขนาด', 'หน่วย', 'จำนวน'
            , 'อายุการใช้งาน', 'ชั่วโมงการใช้งานเฉลี่ยต่อปี', 'การใช้เชื้อเเพลิง(ชนิด)', 'การใช้เชื้อเพลิง(หน่วย)', 'ปริมาณการใช้พลังงานไฟฟ้า', 'สัดส่วนการใช้พลังงานในระบบ', 'หมายเหตุ'];

        foreach ($HPUFM as $info) {
            $HPUFMArray[] = $info->toArray();
        }

//        dd($HPUFMArray);

        $SEP = Save_energy_plan::join('plan_refs', 'plan_refs.id', 'save_energy_plans.plan')
            ->join('mains', 'mains.id', 'save_energy_plans.main_id')
            ->where('main_id', $doc_number)
            ->where('timing_plan', 'past')
            ->select(
                'mains.code',
                'plan_refs.plan_name',
                'save_energy_plans.electric_power_bf',
                'save_energy_plans.kwh_yr_bf',
                'save_energy_plans.bath_yr_bf',
                'save_energy_plans.fuel_kg_yr_bf',
                'save_energy_plans.fuel_bath_yr_bf',
                'save_energy_plans.electric_power_af',
                'save_energy_plans.kwh_yr_af',
                'save_energy_plans.bath_yr_af',
                'save_energy_plans.fuel_kg_yr_af',
                'save_energy_plans.fuel_bath_yr_af',
                'save_energy_plans.investment',
                'save_energy_plans.payback_time'
            )->get();
        $SEPArray = [];
        $SEPArray[] = ['เลขที่ชุดแบบสอบถาม', 'มาตรการ', 'การใช้พลังงานไฟฟ้า(ก่อน)', 'การใช้พลังงานไฟฟ้าต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(ก่อน)'
            , 'การใช้พลังงานเชื้อเพลิงต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(ก่อน)', 'การใช้พลังงานไฟฟ้า(หลัง)', 'การใช้พลังงานไฟฟ้าต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(หลัง)', 'การใช้พลังงานเชื้อเพลิงต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(หลัง)', 'เงินลงทุน', 'เวลาคืนทุน'];

        foreach ($SEP as $info) {
            $SEPArray[] = $info->toArray();
        }

//        dd($SEPArray);

        $FEP = Save_energy_plan::join('plan_refs', 'plan_refs.id', 'save_energy_plans.plan')
            ->join('mains', 'mains.id', 'save_energy_plans.main_id')
            ->where('main_id', $doc_number)
            ->where('timing_plan', 'future')
            ->select(
                'mains.code',
                'plan_refs.plan_name',
                'save_energy_plans.electric_power_bf',
                'save_energy_plans.kwh_yr_bf',
                'save_energy_plans.bath_yr_bf',
                'save_energy_plans.fuel_kg_yr_bf',
                'save_energy_plans.fuel_bath_yr_bf',
                'save_energy_plans.electric_power_af',
                'save_energy_plans.kwh_yr_af',
                'save_energy_plans.bath_yr_af',
                'save_energy_plans.fuel_kg_yr_af',
                'save_energy_plans.fuel_bath_yr_af',
                'save_energy_plans.investment',
                'save_energy_plans.payback_time'
            )->get();
        $FEPArray = [];
        $FEPArray[] = ['เลขที่ชุดแบบสอบถาม', 'มาตรการ', 'การใช้พลังงานไฟฟ้า(ก่อน)', 'การใช้พลังงานไฟฟ้าต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(ก่อน)'
            , 'การใช้พลังงานเชื้อเพลิงต่อปี(ก่อน)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(ก่อน)', 'การใช้พลังงานไฟฟ้า(หลัง)', 'การใช้พลังงานไฟฟ้าต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานไฟฟ้าต่อปี(หลัง)', 'การใช้พลังงานเชื้อเพลิงต่อปี(หลัง)', 'ค่าใช้จ่ายพลังงานเชื้อเพลิงต่อปี(หลัง)', 'เงินลงทุน', 'เวลาคืนทุน'];

        foreach ($FEP as $info) {
            $FEPArray[] = $info->toArray();
        }


        //generate spreadsheets

        Excel::create('แบบสอบถามที่', function ($excel) use ($infoArray, $BIArray, $WPMArray, $TIArray, $EUPYArray, $E_EAJArray, $R_EAJArray, $EUFMArray, $HPUFMArray, $SEPArray, $FEPArray
        ) {
            $excel->sheet('ข้อมูลทั่วไป', function ($sheet) use ($infoArray) {
                $sheet->fromArray($infoArray, null, 'A1', false, false);
            });
            $excel->sheet('ข้อมูลทั่วไปของอาคาร', function ($sheet) use ($BIArray) {
                $sheet->fromArray($BIArray, null, 'A1', false, false);
            });
            $excel->sheet('ข้อมูลการใช้อาคารในแต่ละเดือน', function ($sheet) use ($WPMArray) {
                $sheet->fromArray($WPMArray, null, 'A1', false, false);
            });
            $excel->sheet('ข้อมูลหม้อแปลงไฟฟ้า', function ($sheet) use ($TIArray) {
                $sheet->fromArray($TIArray, null, 'A1', false, false);
            });
            $excel->sheet('ข้อมูลการใช้ไฟฟ้าในแต่ละเดือน', function ($sheet) use ($EUPYArray) {
                $sheet->fromArray($EUPYArray, null, 'A1', false, false);
            });
            $excel->sheet('เชื้อเพลิงสิ้นเปลือง', function ($sheet) use ($E_EAJArray) {
                $sheet->fromArray($E_EAJArray, null, 'A1', false, false);
            });
            $excel->sheet('เชื้อเพลิงหมุนเวียน', function ($sheet) use ($R_EAJArray) {
                $sheet->fromArray($R_EAJArray, null, 'A1', false, false);
            });
            $excel->sheet('การใช้ไฟฟ้าของเครื่องจักร', function ($sheet) use ($EUFMArray) {
                $sheet->fromArray($EUFMArray, null, 'A1', false, false);
            });
            $excel->sheet('การใช้ความร้อนของเครื่องจักร', function ($sheet) use ($HPUFMArray) {
                $sheet->fromArray($HPUFMArray, null, 'A1', false, false);
            });
            $excel->sheet('มาตราการอนุรักษ์พลังงานในอดีต', function ($sheet) use ($SEPArray) {
                $sheet->fromArray($SEPArray, null, 'A1', false, false);
            });
            $excel->sheet('มาตราการอนุรักษ์พลังงานในอนาคต', function ($sheet) use ($FEPArray) {
                $sheet->fromArray($FEPArray, null, 'A1', false, false);

            });
        }
        )->download('xls');
        return view('building.index');
    }


    public function destroy($bid)
    {
        $main_del = Main::find($bid);
        $main_del->delete();

        flash("การลบแบบสอบถามสำเร็จ");
        return redirect()->back();
    }

    public function Process1st()
    {
        return view('building.process1st');
    }

    public function Process2nd()
    {
        return view('building.process2nd');
    }

    public function Process3rd()
    {
        return view('building.process3rd');
    }

    public function Process4th()
    {
        $EUT = new Elec_use_type;
        $EUT = Elec_use_type::pluck('elec_user_type', 'id');
        //dd($EUT);
        return view('building.process4th', compact('EUT'));
    }

    public function Process5th()
    {
        return view('building.process5th');
    }

    public function Process6th()
    {
        return view('building.process6th');
    }

    public function Process7th()
    {
        return view('building.process7th');
    }

    public function Process8th()
    {
        return view('building.process8th');
    }

    public function change_data()
    {
        $places = Place_data::get();
        foreach ($places as $place) {
            $provinces = Province::get();

            foreach ($provinces as $item) {
                if ($place->province == $item->PROVINCE_ID) {
//                    $place->province = $item->PROVINCE_ID;
//                    $place->save();
                    $districts = District::where('districts.PROVINCE_ID', '=', $item->PROVINCE_ID)->get();
                    foreach ($districts as $dis) {
                        if ($place->district == $dis->DISTRICT_NAME) {
                            $place->district = $dis->DISTRICT_ID;
                            $sub_dis = SubDistrict::where('sub_districts.DISTRICT_ID', '=', $dis->DISTRICT_ID)->get();
                            foreach ($sub_dis as $sub) {
                                if ($place->sub_district == $sub->SUB_DISTRICT_NAME) {
                                    $place->sub_district = $sub->SUB_DISTRICT_ID;
                                }
                            }
                        }
                    }
                    $place->save();
                }
            }
        }
        return view('building.index');
    }
}
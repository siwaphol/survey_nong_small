<?php

namespace App\Http\Controllers;


use App\Area;
use App\District;
use App\Http\Requests;
use App\Main;
use App\Place_data;
use App\Place_type;
use App\Province;
use App\SubDistrict;
use App\User;
use App\Work_time;
use Illuminate\Http\Request;


class BuildingProcess1Controller extends Controller
{

    protected function getDocNumber($template_code = 'BUD-')
    {
        $user = User::where('id', \Session::get('user.id', 1))
            ->first();

        $last_record = Main::select('code')
            ->where('code', 'like', "{$template_code}" . $user->area . "%")
            ->orderBy('code', 'desc')
            ->first();
        if (count($last_record) == 1) {
            list($y, $a, $n) = explode('-', $last_record->code);
            $n++;
            $invID = str_pad($n, 3, '0', STR_PAD_LEFT);
            return $y . '-' . $user->area . '-' . $invID;
        } else {
            return $template_code . '-' . $user->area . '-001';
        }

    }

    public function index()
    {
        \Session::remove('main');

        $provinces = Province::get()->toArray();
        $province_select[0] = "เลือกจังหวัด";
        foreach ($provinces as $e) {
            $province_select[$e['PROVINCE_ID']] = $e['PROVINCE_NAME'];
        }
        $district_select[0] = "อำเภอ/เขต";
        $sub_district_select[0] = "ตำบล/แขวง";

        return view('building.process1st', compact('province_select', 'district_select', 'sub_district_select'));
    }

    public function store(Request $request)
    {
        $areaS = auth()->user()->area;
        $areaU = Area::find($areaS);
        $areaN = $areaU->name;
        // dd($areaN);
        $work_time = new Work_time;
        $work_time->hour_day = "0";
        $work_time->day_year = "0";
        $work_time->total_work = "0";
        $work_time->start_month = "0";
        $work_time->end_month = "0";
        $work_time->total_month = "0";
        $work_time->save();

        $building_type_radio = [];
        $building_type_radio[] = $request->input('building_type_select');

        // dd($building_type_radio);
        $building_type = new Place_type;
        $building_type->name = $building_type_radio;
        $building_type->type = "อาคาร";
        $building_type->save();

        if (($request->input('total-room')) == "") {
            $total_r = "0";
        } else {
            $total_r = $request->input('total-room');
        }
        if (($request->input('total-bed')) == "") {
            $total_b = "0";
        } else {
            $total_b = $request->input('total-bed');
        }

        $main = new Main;
        $main->code = '';
        $main->user_id = '';
        $main->person_name = $request->input('corporation_name');
        $main->place_name = $request->input('building_name');
        $main->tsic = '00000';
        $main->code = $this->getDocNumber();
        $main->user_id = auth()->user()->id;
        $main->user_name = auth()->user()->name;
        $main->user_area = $areaN;
        $main->created_since = "0";
        $main->employee_amount = $request->input('total_employee');
        $main->building_amount = $request->input('total_building');
        $main->room_amount = $total_r;
        $main->bed_amount = $total_b;
        $main->place_type_id = $building_type->id;
        $main->work_time_id = $work_time->id;
        $main->contact_name = $request->input('contact_name');
        $main->contact_number = $request->input('contact_tel');
        $main->save();

        //dd($main);

        $place = new Place_data;
        $place->main_id = $main->id;
        $place->house_number = $request->input('building_number');
        $place->village_number = $request->input('building_village_number');
        $place->road = $request->input('building_road');
        $place->sub_district = $request->input('building_sub_district');
        $place->district = $request->input('building_district');
        $place->province = $request->input('building_province');
        $place->post_code = $request->input('building_postal_code');
        $place->phone_number = $request->input('building_phone_number');
        $place->fax = $request->input('building_fax');
        $place->email = $request->input('building_email');
        $place->type = "อาคาร";
        $place->latitude = $request->input('building_latitude');
        $place->longitude = $request->input('building_longitude');
        $place->save();

        $doc_number = $main->id;
        $data = $main->code;
        flash('เลขที่แบบสอบถามของท่านคือ: ' . $data);

        // สำหรับเพิ่มความคืบหน้าในแต่ละชุดแบบสอบถาม
        addMainProgress($main->id, Main::PROGRESS_BUILDING, 1);
        \Session::put('main.id', $main->id);

        return redirect('/building/process2/' . $doc_number);
    }

    public function show($main_id)
    {
        return view('errors.404');
    }

    public function edit($doc_number)
    {

        $main = Main::find($doc_number);
        if (is_null($main)) {
            return view('errors.404');
        }

        $work_time = Work_time::find($main['work_time_id']);
        $place = Place_data::where('main_id', $main['id'])->get();
        $place_find = Place_data::where('main_id', $main['id'])->first();
        $place_type = Place_type::find($main['place_type_id']);

        // สำหรับเพิ่มความคืบหน้าในแต่ละชุดแบบสอบถาม
        addMainProgress($main->id, Main::PROGRESS_BUILDING, 1);
        \Session::put('main.id', $main->id);

        $provinces = Province::get()->toArray();
        $province_select[0] = "เลือกจังหวัด";
        foreach ($provinces as $e) {
            $province_select[$e['PROVINCE_ID']] = $e['PROVINCE_NAME'];
        }
//        $districts = District::get()->toArray();
//        $sub_districts = SubDistrict::get()->toArray();
        $districts = District::where('districts.PROVINCE_ID','=',$place_find->province)->get()->toArray();
        $sub_districts = SubDistrict::where('sub_districts.DISTRICT_ID','=',$place_find->district)->get()->toArray();
        $district_select[0] = "เลือกอำเภอ/เขต";
        foreach ($districts as $d) {
            $district_select[$d['DISTRICT_ID']] = $d['DISTRICT_NAME'];
        }
        $sub_district_select[0] = "เลือกตำบล/แขวง";
        foreach ($sub_districts as $s) {
            $sub_district_select[$s['SUB_DISTRICT_ID']] = $s['SUB_DISTRICT_NAME'];
        }

        return view('building.process1st', compact('doc_number', 'main', 'work_time', 'place_type', 'place', 'province_select', 'district_select', 'sub_district_select'));
    }

    public function update($doc_number, Request $request)
    {

        $areaS = auth()->user()->area;
        $areaU = Area::find($areaS);
        $areaN = $areaU->name;

        if (($request->input('total-room')) == "") {
            $total_r = "0";
        } else {
            $total_r = $request->input('total-room');
        }
        if (($request->input('total-bed')) == "") {
            $total_b = "0";
        } else {
            $total_b = $request->input('total-bed');
        }

        $main = Main::find($doc_number);
        $main->user_id = auth()->user()->id;
        $main->user_name = auth()->user()->name;
        $main->tsic = '00000';
        $main->user_area = $areaN;
        $main->person_name = $request->input('corporation_name');
        $main->place_name = $request->input('building_name');
        $main->created_since = "0";
        $main->employee_amount = $request->input('total_employee');
        $main->building_amount = $request->input('total_building');
        $main->room_amount = $total_r;
        $main->bed_amount = $total_b;
        $main->contact_name = $request->input('name');
        $main->contact_number = $request->input('tel');
        $main->save();

        $work_time = new Work_time;
        $work_time->hour_day = "0";
        $work_time->day_year = "0";
        $work_time->total_work = "0";
        $work_time->start_month = "0";
        $work_time->end_month = "0";
        $work_time->total_month = "0";
        $work_time->save();

        $building_type_radio = [];
        $building_type_radio[] = $request->input('building_type_select');

        $building_type = Place_type::find($main->place_type_id);
        $building_type->name = $building_type_radio;
        $building_type->type = "อาคาร";
        $building_type->save();

        $place = Place_data::where('main_id', $main->id)->where('type', 'อาคาร')->get()[0];
        $place->house_number = $request->input('building_number');
        $place->village_number = $request->input('building_village_number');
        $place->road = $request->input('building_road');
        $place->sub_district = $request->input('building_sub_district');
        $place->district = $request->input('building_district');
        $place->province = $request->input('building_province');
        $place->post_code = $request->input('building_postal_code');
        $place->phone_number = $request->input('building_phone_number');
        $place->fax = $request->input('building_fax');
        $place->email = $request->input('building_email');
        $place->type = "อาคาร";
        $place->latitude = $request->input('building_latitude');
        $place->longitude = $request->input('building_longitude');
        $place->save();

        // สำหรับเพิ่มความคืบหน้าในแต่ละชุดแบบสอบถาม
        addMainProgress($main->id, Main::PROGRESS_BUILDING, 1);
        \Session::put('main.id', $main->id);

        return redirect('/building/process2/' . $doc_number . '/edit');
    }

    public function apidistrictIndex()
    {
        return District::get();
    }

    public function apisubdistrictIndex()
    {
        return SubDistrict::get();
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Area;
use App\Main;
use App\Building_information;


class BuildingProcess2Controller extends Controller
{

    public function show($doc_number) {
        $data = Main::find($doc_number);
        return view('building.process2nd', compact('doc_number','data'));
    }

    public function store(Request $request)
    {
        $areaS = auth()->user()->area;
        $areaU = Area::find($areaS);
        $areaN = $areaU->name;
        $main = Main::find($request->input('doc_number'));
        if(!is_null($main)) {
            $main->user_id = auth()->user()->id;
            $main->user_name = auth()->user()->name;
            $main->user_area = $areaN;
            $main->save();
        }
        foreach($request->input('building') as $input_building) {
            $building_info = new Building_information;
            $building_info->main_id = $request->input('doc_number');
            $building_info->name = $input_building['building_name'];
            $building_info->open = $input_building['openy'];
            $building_info->work_hour_hr_d = $input_building['work_hour'];
            $building_info->work_hour_day_y = $input_building['work_day'];
            $building_info->air_conditioned = $input_building['airspace'];
            $building_info->non_air_conditioned = $input_building['nonairspace'];
            $building_info->total_1 = $input_building['a_na'];
            $building_info->parking_space = $input_building['carspace'];
            $building_info->total_2 = $input_building['all'];
            $building_info->save();
        }
        $doc_number = $request->input('doc_number');
        // สำหรับเพิ่มความคืบหน้าในแต่ละชุดแบบสอบถาม
        addMainProgress($main->id,Main::PROGRESS_BUILDING,2);
        \Session::put('main.id', $main->id);

        return redirect('/building/process3/'.$doc_number);
    }

    public function edit($doc_number) {
        $data = Main::find($doc_number);
        if(is_null($data))
        {
            return view('errors.404');
        }
        $building_data = $data->building_informations;

        // สำหรับเพิ่มความคืบหน้าในแต่ละชุดแบบสอบถาม
        addMainProgress($data->id,Main::PROGRESS_BUILDING,2);
        \Session::put('main.id', $data->id);

        return view('building.process2nd_edit', compact('building_data','doc_number','data'));
    }

    public function update($doc_number,Request $request) {

//        dd($request->input('building'));
//        dd($request->input());
        foreach($request->input('building') as $input_building) {
            // ถ้าอาคารที่เพิ่มเข้ามาเป็นของใหม่

            if ($input_building['b_id']==='new' || $input_building['b_id']==='')
            {
                $building_info = new Building_information();
            }
            else
            {
                $building_info = Building_information::find($input_building['b_id']);
            }
            $building_info->main_id = $doc_number;
            $building_info->name = $input_building['building_name'];
            $building_info->open = $input_building['openy'];
            $building_info->work_hour_hr_d = $input_building['work_hour'];
            $building_info->work_hour_day_y = $input_building['work_day'];
            $building_info->air_conditioned = $input_building['airspace'];
            $building_info->non_air_conditioned = $input_building['nonairspace'];
            $building_info->total_1 = $input_building['a_na'];
            $building_info->parking_space = $input_building['carspace'];
            $building_info->total_2 = $input_building['all'];
            $building_info->save();
        }

        $areaS = auth()->user()->area;
        $areaU = Area::find($areaS);
        $areaN = $areaU->name;
        $main = Main::find($request->input('doc_number'));
        if(!is_null($main)) {
            $main->user_id = auth()->user()->id;
            $main->user_name = auth()->user()->name;
            $main->user_area = $areaN;
            $main->save();
        }

        addMainProgress($main->id,Main::PROGRESS_BUILDING,2);
        \Session::put('main.id', $main->id);

        return redirect('/building/process3/'.$doc_number.'/edit');
    }

    public function destroy($building_info_id)
    {
        $buildingInfo = Building_information::find($building_info_id);
        $buildingInfo->delete();

        return json_encode(array('success'=>true));
    }

    public function deleteBuilding($doc_number, $building_id)
    {
        $main = Main::find($doc_number);
        $bInfo = $main->building_informations->find($building_id);
        $bInfo->delete();

        return json_encode(["success"=>true]);
//        return redirect('/building/process2/'.$doc_number.'/edit');
    }
}

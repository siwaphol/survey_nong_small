<?php

namespace App\Http\Controllers;
use App\Main;
use Illuminate\Http\Request;
use App\Save_energy_plan;
use App\Plan_ref;
use App\Http\Requests;
use App\Area;
use App\Http\Controllers\Controller;

class BuildingProcess7Controller extends Controller
{
    public function show($doc_number) {
        $data = Main::find($doc_number);
        //dd($request);
        $plan = new Plan_ref;
        $plan = Plan_ref::pluck('plan_name','id')->toArray();
        $plan[0] = "เลือกมาตรการ";
        ksort($plan);
        //dd($plan);
        return view('building.process7th', compact('doc_number','plan','data'));
    }

    public function store(Request $request) {
        //dd($request);

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

        if($request->has('past_conserve')) {
            $past_data = $request->input('past_conserve');
            for ($i = 0; $i < count($past_data['measure']); $i++) {
//                dd($past_data['before_elec_kw']);
                if (($past_data['measure'][$i]!='0') && ($past_data['before_elec_kw'][$i]!='') && ($past_data['before_elec_kw'][$i]!='')
                    && ($past_data['before_elec_kwh_per_year'][$i]!='') && ($past_data['before_elec_cost_per_year'][$i]!='') && ($past_data['before_fuel_kg_per_year'][$i]!='')
                    && ($past_data['before_fuel_cost_per_year'][$i]!='') && ($past_data['after_elec_kw'][$i]!='') && ($past_data['after_elec_kwh_per_year'][$i]!='')
                    && ($past_data['after_elec_cost_per_year'][$i]!='') && ($past_data['after_fuel_kg_per_year'][$i]!='') && ($past_data['after_fuel_cost_per_year'][$i]!='')
                    && ($past_data['investment'][$i]!='') && ($past_data['payback'][$i]!='')){
                    $SEP = new Save_energy_plan;
                    $SEP->main_id = $request->input('doc_number');
                    $SEP->plan = $past_data['measure'][$i];
                    $SEP->electric_power_bf = $past_data['before_elec_kw'][$i];
                    $SEP->kwh_yr_bf = $past_data['before_elec_kwh_per_year'][$i];
                    $SEP->bath_yr_bf = $past_data['before_elec_cost_per_year'][$i];
                    $SEP->fuel_kg_yr_bf = $past_data['before_fuel_kg_per_year'][$i];
                    $SEP->fuel_bath_yr_bf = $past_data['before_fuel_cost_per_year'][$i];
                    $SEP->electric_power_af = $past_data['after_elec_kw'][$i];
                    $SEP->kwh_yr_af = $past_data['after_elec_kwh_per_year'][$i];
                    $SEP->bath_yr_af = $past_data['after_elec_cost_per_year'][$i];
                    $SEP->fuel_kg_yr_af = $past_data['after_fuel_kg_per_year'][$i];
                    $SEP->fuel_bath_yr_af = $past_data['after_fuel_cost_per_year'][$i];
                    $SEP->timing_plan = "past";
                    $SEP->investment = $past_data['investment'][$i];
                    $SEP->payback_time = $past_data['payback'][$i];
                    $SEP->save();
                }
            }
        }
        if($request->has('future_conserve')) {
            $future_data = $request->input('future_conserve');
            for ($i = 0; $i < count($future_data['measure']); $i++) {
                //dd($past_data['measure'][$i]);
                if (($future_data['measure'][$i]!='0') && ($future_data['before_elec_kw'][$i]!='') && ($future_data['before_elec_kw'][$i]!='')
                    && ($future_data['before_elec_kwh_per_year'][$i]!='') && ($future_data['before_elec_cost_per_year'][$i]!='') && ($future_data['before_fuel_kg_per_year'][$i]!='')
                    && ($future_data['before_fuel_cost_per_year'][$i]!='') && ($future_data['after_elec_kw'][$i]!='') && ($future_data['after_elec_kwh_per_year'][$i]!='')
                    && ($future_data['after_elec_cost_per_year'][$i]!='') && ($future_data['after_fuel_kg_per_year'][$i]!='') && ($future_data['after_fuel_cost_per_year'][$i]!='')
                    && ($future_data['investment'][$i]!='') && ($future_data['payback'][$i]!='')) {
                    $SEP = new Save_energy_plan;
                    $SEP->main_id = $request->input('doc_number');
                    $SEP->plan = $future_data['measure'][$i];
                    $SEP->electric_power_bf = $future_data['before_elec_kw'][$i];
                    $SEP->kwh_yr_bf = $future_data['before_elec_kwh_per_year'][$i];
                    $SEP->bath_yr_bf = $future_data['before_elec_cost_per_year'][$i];
                    $SEP->fuel_kg_yr_bf = $future_data['before_fuel_kg_per_year'][$i];
                    $SEP->fuel_bath_yr_bf = $future_data['before_fuel_cost_per_year'][$i];
                    $SEP->electric_power_af = $future_data['after_elec_kw'][$i];
                    $SEP->kwh_yr_af = $future_data['after_elec_kwh_per_year'][$i];
                    $SEP->bath_yr_af = $future_data['after_elec_cost_per_year'][$i];
                    $SEP->fuel_kg_yr_af = $future_data['after_fuel_kg_per_year'][$i];
                    $SEP->fuel_bath_yr_af = $future_data['after_fuel_cost_per_year'][$i];
                    $SEP->timing_plan = "future";
                    $SEP->investment = $future_data['investment'][$i];
                    $SEP->payback_time = $future_data['payback'][$i];
                    $SEP->save();
                }
            }
        }
        addMainProgress($main->id,Main::PROGRESS_BUILDING,7);
        \Session::put('main.id', $main->id);

        $doc_number = $request->input('doc_number');
//        dd($doc_number);
        return redirect('/');
        //return redirect('building/index');
        //dd($request);

    }

    public function edit($doc_number) {

        $plan = Plan_ref::pluck('plan_name','id')->toArray();
        $plan[0] = "เลือกมาตรการ";
        ksort($plan);

        $SEP = Save_energy_plan::where('main_id',$doc_number)->get();

        $data = Main::find($doc_number);
        if(is_null($data))
        {
            return view('errors.404');
        }

        if ($SEP->count()<=0)
            return redirect('/building/process7/'.$doc_number);

        $edit="edit";
        return view('building.process7th', compact('doc_number','edit','SEP','plan','data'));
    }

    public function update($doc_number,Request $request)
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

        //dd($request);
        $SEP = new Save_energy_plan;
        if($request->has('SEP_id')) {
            Save_energy_plan::whereIn('id', $request->input('SEP_id'))->delete();
        }

        if($request->has('past_conserve')) {
            $past_data = $request->input('past_conserve');
            for ($i = 0; $i < count($past_data['measure']); $i++) {
//                dd($past_data['before_elec_kw'][$i]);
                if (($past_data['measure'][$i]!='0') && ($past_data['before_elec_kw'][$i]!='') && ($past_data['before_elec_kw'][$i]!='')
                    && ($past_data['before_elec_kwh_per_year'][$i]!='') && ($past_data['before_elec_cost_per_year'][$i]!='') && ($past_data['before_fuel_kg_per_year'][$i]!='')
                    && ($past_data['before_fuel_cost_per_year'][$i]!='') && ($past_data['after_elec_kw'][$i]!='') && ($past_data['after_elec_kwh_per_year'][$i]!='')
                    && ($past_data['after_elec_cost_per_year'][$i]!='') && ($past_data['after_fuel_kg_per_year'][$i]!='') && ($past_data['after_fuel_cost_per_year'][$i]!='')
                    && ($past_data['investment'][$i]!='') && ($past_data['payback'][$i]!='')) {

                    $SEP = new Save_energy_plan;
                    $SEP->main_id = $request->input('doc_number');
                    $SEP->plan = $past_data['measure'][$i];
                    $SEP->electric_power_bf = $past_data['before_elec_kw'][$i];
                    $SEP->kwh_yr_bf = $past_data['before_elec_kwh_per_year'][$i];
                    $SEP->bath_yr_bf = $past_data['before_elec_cost_per_year'][$i];
                    $SEP->fuel_kg_yr_bf = $past_data['before_fuel_kg_per_year'][$i];
                    $SEP->fuel_bath_yr_bf = $past_data['before_fuel_cost_per_year'][$i];
                    $SEP->electric_power_af = $past_data['after_elec_kw'][$i];
                    $SEP->kwh_yr_af = $past_data['after_elec_kwh_per_year'][$i];
                    $SEP->bath_yr_af = $past_data['after_elec_cost_per_year'][$i];
                    $SEP->fuel_kg_yr_af = $past_data['after_fuel_kg_per_year'][$i];
                    $SEP->fuel_bath_yr_af = $past_data['after_fuel_cost_per_year'][$i];
                    $SEP->timing_plan = "past";
                    $SEP->investment = $past_data['investment'][$i];
                    $SEP->payback_time = $past_data['payback'][$i];
                    $SEP->save();
                }
            }
        }
        if($request->has('future_conserve')) {
            $future_data = $request->input('future_conserve');
            for ($i = 0; $i < count($future_data['measure']); $i++) {
                //dd($past_data['measure'][$i]);
                if (($future_data['measure'][$i]!='0') && ($future_data['before_elec_kw'][$i]!='') && ($future_data['before_elec_kw'][$i]!='')
                    && ($future_data['before_elec_kwh_per_year'][$i]!='') && ($future_data['before_elec_cost_per_year'][$i]!='') && ($future_data['before_fuel_kg_per_year'][$i]!='')
                    && ($future_data['before_fuel_cost_per_year'][$i]!='') && ($future_data['after_elec_kw'][$i]!='') && ($future_data['after_elec_kwh_per_year'][$i]!='')
                    && ($future_data['after_elec_cost_per_year'][$i]!='') && ($future_data['after_fuel_kg_per_year'][$i]!='') && ($future_data['after_fuel_cost_per_year'][$i]!='')
                    && ($future_data['investment'][$i]!='') && ($future_data['payback'][$i]!='')) {
                    $SEP = new Save_energy_plan;
                    $SEP->main_id = $request->input('doc_number');
                    $SEP->plan = $future_data['measure'][$i];
                    $SEP->electric_power_bf = $future_data['before_elec_kw'][$i];
                    $SEP->kwh_yr_bf = $future_data['before_elec_kwh_per_year'][$i];
                    $SEP->bath_yr_bf = $future_data['before_elec_cost_per_year'][$i];
                    $SEP->fuel_kg_yr_bf = $future_data['before_fuel_kg_per_year'][$i];
                    $SEP->fuel_bath_yr_bf = $future_data['before_fuel_cost_per_year'][$i];
                    $SEP->electric_power_af = $future_data['after_elec_kw'][$i];
                    $SEP->kwh_yr_af = $future_data['after_elec_kwh_per_year'][$i];
                    $SEP->bath_yr_af = $future_data['after_elec_cost_per_year'][$i];
                    $SEP->fuel_kg_yr_af = $future_data['after_fuel_kg_per_year'][$i];
                    $SEP->fuel_bath_yr_af = $future_data['after_fuel_cost_per_year'][$i];
                    $SEP->timing_plan = "future";
                    $SEP->investment = $future_data['investment'][$i];
                    $SEP->payback_time = $future_data['payback'][$i];
                    $SEP->save();
                }
            }
        }
        addMainProgress($main->id,Main::PROGRESS_BUILDING,7);
        \Session::put('main.id', $main->id);
        return redirect('/');
    }

    public function show_addplan(Request $request)
    {

//        dd($request->input('doc_number'));
        /*
        $allType = \DB::table('plan_refs')->distinct('type')->get(['type']);
        $allList = \DB::table('plan_refs')->distinct('list')->get(['list'])->toArray();
        foreach ($allList as $list){
            $lists[$list->list] = $list->list;
        }

        foreach ($allType as $item){
            $types[$item->type] = $item->type;
        }

        return view('plan.addplan', compact('types', 'lists'));*/
    }

    public function addplan(Request $request)
    {
        /*
        $AP = new Plan_ref;
        $AP->plan_name = $request->input('name');
        $AP->type = $request->input('type');
        $AP->list = $request->input('list');
        $AP->level = 0;
        $AP->process = '';
        $AP->process_initials = '';
        $AP->save();

        Return view('/user')->with('status', 'เพิ่มผู้ใช้เรียบร้อยแล้ว และส่งรหัสผ่านไปที่ ' . $user->email)*/
    }
}

<?php

namespace App\Http\Controllers;


use App\Main;
use App\Machine_and_main_tool;
use App\Energy_and_juristic;
use App\Heat_power_used_for_machine;
use App\Electric_used_for_machine;
use App\Energy_type;
use Illuminate\Http\Request;
use App\Area;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BuildingProcess6Controller extends Controller
{
    public function show($doc_number) {
        $data = Main::find($doc_number);
        $energy4select = new Energy_type;
        $energy4select = array();
        $MAMT = new Machine_and_main_tool;
        $MAMT = Machine_and_main_tool::pluck('machine_name', 'id')->toArray();
        $MAMT[0]="เลือกเครื่องจักร";
        ksort($MAMT);

        $energy = Energy_type::get(['id','energy_name','unit','heat_rate'])->toArray();
        $energy4select[0]= "เลือกเชื้อเพลิง";
        foreach ($energy as $e) {
            $energy4select[$e['id']] = $e['energy_name'];
        }
        //$MAMT->toArray();
        //dd($MAMT);
        return view('building.process6th', compact('doc_number','MAMT','data','energy4select'));
    }

    public function store(Request $request) {

        if ($request->has('e_machine_id'))
        {
            for($i=0;$i < count($request->input('e_machine_id')); $i++)
            {
                if( $request->input('e_machine_id')[$i] == 0)
                {

                }
                else
                {
                    $EUFM = new Electric_used_for_machine;
                    $EUFM->m_id = $request->input('e_machine_id')[$i];
                    $EUFM->size = $request->input('e_machine_size')[$i];
                    $EUFM->amount = $request->input('e_machine_amount')[$i];
                    $EUFM->life_time = $request->input('e_machine_life_time')[$i];
                    $EUFM->work_hous = $request->input('e_hour_per_year')[$i];
                    $EUFM->average_per_year = $request->input('e_electric_using')[$i];
                    $EUFM->persentage = $request->input('e_system_energy')[$i];
                    $EUFM->note = $request->input('e_ps')[$i];
                    $EUFM->main_id = $request->input('doc_number');
                    $EUFM->save();
                }

            }
        }

        if ($request->has('f_machine_id')){
            for($i=0;$i < count($request->input('f_machine_id')); $i++)
            {
                if( $request->input('f_machine_id')[$i] == 0)
                {

                }
                else
                {
                    $HPUFM = new Heat_power_used_for_machine;
                    $HPUFM->m_id = $request->input('f_machine_id')[$i];
                    $HPUFM->size = $request->input('f_machine_size')[$i];
                    $HPUFM->amount = $request->input('f_machine_amount')[$i];
                    $HPUFM->life_time = $request->input('f_machine_life_time')[$i];
                    $HPUFM->work_hous = $request->input('f_hour_per_year')[$i];
                    $HPUFM->energy_type = $request->input('energy_type')[$i];
                    $HPUFM->unit_en = $request->input('unit_en')[$i];
                    $HPUFM->average_per_year = $request->input('f_electric_using')[$i];
                    $HPUFM->persentage = $request->input('f_system_energy')[$i];
                    $HPUFM->note = $request->input('f_ps')[$i];
                    $HPUFM->main_id = $request->input('doc_number');
                    $HPUFM->save();
                }
            }
        }

        addMainProgress($request->input('doc_number'),Main::PROGRESS_BUILDING,6);
        \Session::put('main.id', $request->input('doc_number'));

        $doc_number = $request->input('doc_number');
        return redirect('/building/process7/'.$doc_number);
    }

    public function edit($doc_number) {
        //dd($request);

        $EUFM = Electric_used_for_machine::with('machine_and_main_tools')->where('main_id',$doc_number)->get();

        $energy4select = new Energy_type;
        $energy4select = array();

        $MAMT = new Machine_and_main_tool;
        $MAMT = Machine_and_main_tool::pluck('machine_name', 'id')->toArray();
        $MAMT[0]="เลือกเครื่องจักร";
        ksort($MAMT);

        $HPUFM = Heat_power_used_for_machine::with('machine_and_main_tools')->where('main_id',$doc_number)->get();

        if ($EUFM->count()===0 && $HPUFM->count()===0)
            return redirect('/building/process6/'.$doc_number);

        $edit="edit";
        //dd($HPUFM);
        $data = Main::find($doc_number);
        if(is_null($data))
        {
            return view('errors.404');
        }
        $energy = Energy_type::get(['id','energy_name','unit','heat_rate'])->toArray();
        $energy4select[0]= "เลือกเชื้อเพลิง";
        foreach ($energy as $e) {
            $energy4select[$e['id']] = $e['energy_name'];
        }

        addMainProgress($data->id,Main::PROGRESS_BUILDING,6);
        \Session::put('main.id', $data->id);
        return view('building.process6th', compact('doc_number','EUFM','HPUFM','edit','MAMT','data','energy4select'));
    }

    public function update($doc_number,Request $request) {
        if  ($request->has('EUFM_id')) {
            Electric_used_for_machine::whereIn('id', $request->input('EUFM_id'))->delete();
        }
        if  ($request->has('HPUFM_id')) {
            Heat_power_used_for_machine::whereIn('id', $request->input('HPUFM_id'))->delete();
        }

        if  ($request->has('e_machine_id')){
            for($i=0;$i < count($request->input('e_machine_id')); $i++) {
                if ($request->input('e_machine_id')[$i] == '0') {
                } else {
                    $EUFM = new Electric_used_for_machine;
                    $EUFM->m_id = $request->input('e_machine_id')[$i];;
                    $EUFM->size = $request->input('e_machine_size')[$i];
                    $EUFM->amount = $request->input('e_machine_amount')[$i];
                    $EUFM->life_time = $request->input('e_machine_life_time')[$i];
                    $EUFM->work_hous = $request->input('e_hour_per_year')[$i];
                    $EUFM->average_per_year = $request->input('e_electric_using')[$i];
                    $EUFM->persentage = $request->input('e_system_energy')[$i];
                    $EUFM->note = $request->input('e_ps')[$i];
                    $EUFM->main_id = $request->input('doc_number');
                    $EUFM->save();
                }
            }
        }

        if ($request->has('f_machine_id')){
            for ($i = 0; $i < count($request->input('f_machine_id')); $i++) {
                if($request->input('f_machine_id')[$i] == '0') {
                }
                else {

                    $HPUFM = new Heat_power_used_for_machine;
                    $HPUFM->m_id = $request->input('f_machine_id')[$i];
                    $HPUFM->size = $request->input('f_machine_size')[$i];
                    $HPUFM->amount = $request->input('f_machine_amount')[$i];
                    $HPUFM->life_time = $request->input('f_machine_life_time')[$i];
                    $HPUFM->work_hous = $request->input('f_hour_per_year')[$i];
                    $HPUFM->energy_type = $request->input('energy_type')[$i];
                    $HPUFM->unit_en = $request->input('unit_en')[$i];
                    $HPUFM->average_per_year = $request->input('f_electric_using')[$i];
                    $HPUFM->persentage = $request->input('f_system_energy')[$i];
                    $HPUFM->note = $request->input('f_ps')[$i];
                    $HPUFM->main_id = $request->input('doc_number');
                    $HPUFM->save();
                }
            }
        }

        addMainProgress($doc_number,Main::PROGRESS_BUILDING,6);
        \Session::put('main.id', $doc_number);

        return redirect('/building/process7/'.$doc_number.'/edit');
    }

    public function machine_unit(Request $request) {
        $unit = new Machine_and_main_tool;
        $unit = Machine_and_main_tool::where('id',$request->input('id'))->pluck('unit');
        return $unit;
    }

    public function energy_unit(Request $request)
    {
        $en_unit = new Energy_type;
        $en_unit = Energy_type::where('id',$request->input('id'))->pluck('unit');
//        dd($en_unit);
        return $en_unit;
    }
}

<?php

namespace App\Http\Controllers;
use App\Main;
use Illuminate\Http\Request;
use App\Energy_type;
use App\Energy_and_juristic;
use App\Energy_used_per_year;
use App\Area;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BuildingProcess5Controller extends Controller
{
    public function show($doc_number) {

        $action = "insert";
        $energy4select = array();
        $recycle4select = array();

        $energy4select[0]= "เลือกเชื้อเพลิง";
        $recycle4select[0]= "เลือกพลังงาน";

        $energy = Energy_type::where('type', 'พลังงานสิ้นเปลือง')->
        get(['id','energy_name','unit','heat_rate'])->toArray();
        $recycle = Energy_type::where('type', 'พลังงานหมุนเวียน')->
        get(['id','energy_name','unit','heat_rate'])->toArray();

        foreach ($energy as $e) {
            $energy4select[$e['id']] = $e['energy_name'];
        }

        foreach ($recycle as $e) {
            $recycle4select[$e['id']] = $e['energy_name'];
        }

        return view('building.process5_vue', compact('doc_number','energy','recycle','energy4select','recycle4select','action'));
    }

    public function showAjax($main_id)
    {
        $renewableEnergyTypeArr = Energy_type::where('type', Energy_type::TYPE_RENEWABLE_ENERGY)
            ->pluck('id');
        $nonRenewableEnergyTypeArr = Energy_type::where('type', Energy_type::TYPE_NONRENEWABLE_ENERGY)
            ->pluck('id');

        $renewableEnergyEAJ = Energy_and_juristic::with('energy_used_per_years')
            ->whereIn('et_id',$renewableEnergyTypeArr)
            ->where('main_id', $main_id)
            ->get()->toArray();
        $nonRenewableEnergyEAJ = Energy_and_juristic::with('energy_used_per_years')
            ->whereIn('et_id',$nonRenewableEnergyTypeArr)
            ->where('main_id', $main_id)
            ->get()->toArray();
        $returnArr = array('renewable'=>$renewableEnergyEAJ,'nonrenewable'=>$nonRenewableEnergyEAJ);

        return json_encode($returnArr);
    }

    public function saveAjax(Request $request, $mainId)
    {
        $energy_and_juristic = $request->json('energy_and_juristic');

        if (!empty($energy_and_juristic)){
            $oldEAJ = Energy_and_juristic::where('main_id',$mainId)
                ->get();

            // ลบ energy_and_juristic อันที่ถูกกดลบออก
            foreach ($oldEAJ as $item){
                $notFound = true;
                foreach ($energy_and_juristic as $eaj){
                    if (isset($eaj["id"]) && (int)$item->id===(int)$eaj["id"])
                        $notFound = false;
                }
                if ($notFound)
                    $item->delete();
            }

            foreach ($energy_and_juristic as $EAJ){
                $EAJObj = new Energy_and_juristic();
                if (isset($EAJ['id'])){
                    $EAJObj = Energy_and_juristic::find($EAJ['id']);

                    $temp_EUPY = $EAJObj->energy_used_per_years()->get();
                    // ลบ เดือนที่ถูกกดลบออก
                    foreach ($temp_EUPY as $item){
                        $notFound = true;
                        foreach ($EAJ["energy_used_per_years"] as $subItem){
                            if (isset($subItem["id"]) && (int)$item->id===(int)$subItem["id"])
                                $notFound = false;
                        }
                        if ($notFound)
                            $item->delete();
                    }
                }
                $EAJObj->fill($EAJ);
                $EAJObj->main_id = $mainId;
                $EAJObj->save();

                // สำหรับ energy_used_per_years
                foreach ($EAJ["energy_used_per_years"] as $subItem){
                    $elecObj = new Energy_used_per_year();
                    if (isset($subItem["id"])){
                        $elecObj = Energy_used_per_year::find($subItem["id"]);
                    }

                    $elecObj->fill($subItem);
                    if (is_null($subItem['total_cost']))
                        $elecObj->total_cost = 0;
                    if (is_null($subItem['mj']))
                        $elecObj->mj = 0;
                    $elecObj->energy_and_juristic_id = $EAJObj->id;
                    $elecObj->save();
                }
            }
        }

        addMainProgress($mainId,Main::PROGRESS_BUILDING,5);
        \Session::put('main.id', $mainId);

        return json_encode(array('success'=>true));
    }

    public function edit($doc_number) {

        $action = "insert";
        $energy4select = array();
        $recycle4select = array();

        $energy4select[0]= "เลือกเชื้อเพลิง";
        $recycle4select[0]= "เลือกพลังงาน";

        $energy = Energy_type::where('type', 'พลังงานสิ้นเปลือง')->
        get(['id','energy_name','unit','heat_rate'])->toArray();
        $recycle = Energy_type::where('type', 'พลังงานหมุนเวียน')->
        get(['id','energy_name','unit','heat_rate'])->toArray();

        foreach ($energy as $e) {
            $energy4select[$e['id']] = $e['energy_name'];
        }

        foreach ($recycle as $e) {
            $recycle4select[$e['id']] = $e['energy_name'];
        }

        return view('building.process5_vue', compact('doc_number','energy','recycle','energy4select','recycle4select','action'));
    }

}

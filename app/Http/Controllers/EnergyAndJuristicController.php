<?php

namespace App\Http\Controllers;

use App\Electric_used_per_year;
use App\Energy_and_juristic;
use App\Energy_type;
use App\Energy_used_per_year;
use App\Main;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnergyAndJuristicController extends Controller
{
    public function show(Request $request, $main_id)
    {
        $energyType = $request->input('energy_type');
        if (empty($energyType))
            return json_encode(array());

        $energyTypeArr = Energy_type::where('type', $energyType)
            ->pluck('id');

        return Energy_and_juristic::with('energy_used_per_years')
            ->whereIn('et_id',$energyTypeArr)
            ->where('main_id', $main_id)
            ->get();
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
                        foreach ($EAJ["energy_used_per_years"] as $elec){
                            if (isset($elec["id"]) && (int)$item->id===(int)$elec["id"])
                                $notFound = false;
                        }
                        if ($notFound)
                            $item->delete();
                    }
                }
                $EAJObj->fill($EAJ);
                $EAJObj->main_id = $mainId;
                $EAJObj->save();

                // สำหรับ electric_used_per_years
                foreach ($EAJ["energy_used_per_years"] as $elec){
                    $elecObj = new Energy_used_per_year();
                    if (isset($elec["id"])){
                        $elecObj = Energy_used_per_year::find($elec["id"]);
                    }

                    $elecObj->fill($elec);
                    $elecObj->energy_and_juristic_id = $EAJObj->id;
                    $elecObj->save();
                }
            }
        }

        if ($request->has('progress_type') && $request->has('progress_no')){
            $progressType = $request->has('progress_type');
            $progressNo = (int)$request->has('progress_no');
            addMainProgress($mainId,$progressType,$progressNo);
        }
        \Session::put('main.id', $mainId);

        return json_encode(array('success'=>true));
    }
}

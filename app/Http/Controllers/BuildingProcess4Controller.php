<?php

namespace App\Http\Controllers;
use App\Main;
use App\Tranformer_info;
use App\Elec_use_type;
use App\Electric_used_per_year;
use Illuminate\Http\Request;
use App\Area;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BuildingProcess4Controller extends Controller
{
    public function show($doc_number) {
        $data = Main::find($doc_number);
        $EUT = Elec_use_type::pluck('elec_user_type', 'id');

        return view('building.process4_vue', compact('doc_number','EUT','data'));
    }

    public function edit($doc_number) {

        $EUT = Elec_use_type::pluck('elec_user_type', 'id');

        $TI = Tranformer_info::where('main_id',$doc_number)->get();
        if ($TI->count()<=0)
            return redirect('building/process4/'. $doc_number);

        $data = Main::find($doc_number);
        $main = Main::find($doc_number);

        if(is_null($main))
        {
            return view('errors.404');
        }

        addMainProgress($main->id,Main::PROGRESS_BUILDING,4);
        \Session::put('main.id', $main->id);

        return view('building.process4_vue', compact('doc_number','EUT','TI','data','main'));
    }

    public function showAjax($main_id)
    {
        return Tranformer_info::with('electric_used_per_years')
            ->where('main_id',$main_id)
            ->get();
    }

    public function saveAjax(Request $request, $mainId)
    {
        $tranformer_info = $request->json('tranformer_info');

        if (!empty($tranformer_info)){
            $oldTI = Tranformer_info::where('main_id',$mainId)
                ->get();

            // ลบ tranformer_info อันที่ถูกกดลบออก
            foreach ($oldTI as $item){
                $notFound = true;
                foreach ($tranformer_info as $ti){
                    if (isset($ti["id"]) && (int)$item->id===(int)$ti["id"])
                        $notFound = false;
                }
                if ($notFound)
                    $item->delete();
            }

            foreach ($tranformer_info as $TI){
                $TIObj = new Tranformer_info();
                if (isset($TI['id'])){
                    $TIObj = Tranformer_info::find($TI['id']);

                    $temp_EUPY = $TIObj->electric_used_per_years()->get();
                    // ลบ เดือนที่ถูกกดลบออก
                    foreach ($temp_EUPY as $item){
                        $notFound = true;
                        foreach ($TI["electric_used_per_years"] as $elec){
                            if (isset($elec["id"]) && (int)$item->id===(int)$elec["id"])
                                $notFound = false;
                        }
                        if ($notFound)
                            $item->delete();
                    }
                }
                $TIObj->fill($TI);
                $TIObj->main_id = $mainId;
                $TIObj->save();

                // สำหรับ electric_used_per_years
                foreach ($TI["electric_used_per_years"] as $elec){
                    $elecObj = new Electric_used_per_year();
                    if (isset($elec["id"])){
                        $elecObj = Electric_used_per_year::find($elec["id"]);
                    }

                    foreach ($elec as $key => $value) {
                        if (is_null($value)) {
                            $elec[$key] = 0;
                        }
                    }

                    $elecObj->fill($elec);
                    $elecObj->tranformer_info_id = $TIObj->id;
                    $elecObj->save();
                }
            }
        }

        addMainProgress($mainId,Main::PROGRESS_BUILDING,4);
        \Session::put('main.id', $mainId);

        return json_encode(array('success'=>true));
    }
}

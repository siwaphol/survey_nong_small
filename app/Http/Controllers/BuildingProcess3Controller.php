<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Building_information;
use App\Working_per_month;
use App\Main;
use App\Area;
use Illuminate\Support\Facades\Input;
class BuildingProcess3Controller extends Controller
{
    public function show($doc_number) {
        $data = Main::find($doc_number);

        return view('building.process3_vue', compact('doc_number','data'));
    }

    public function showAjax($main_id)
    {
        return Building_information::with('working_per_months')
            ->where('main_id', $main_id)
            ->get();
    }

    public function saveAjax(Request $request, $mainId)
    {
        $building_information = $request->json('building_information');

        foreach ($building_information as $bi){

            $biModel = Building_information::find($bi["id"]);
            $temp = $biModel->working_per_months()->get();
            // หาว่าของเดิมถูกลบไปแล้ว
            foreach ($temp as $oldWPM){
                $notFound = true;
                foreach ($bi["working_per_months"] as $wpm){
                    if (isset($wpm["id"]) && (int)$oldWPM->id===(int)$wpm["id"])
                        $notFound = false;
                }
                if ($notFound)
                    $oldWPM->delete();
            }

            foreach ($bi["working_per_months"] as $wpm){
                $wpmObj = new Working_per_month();
                if (isset($wpm["id"])){
                    $wpmObj = Working_per_month::find($wpm["id"]);

                }

                $wpmObj->fill($wpm);
                $wpmObj->sumspace = (float)$wpmObj->air_conditioned + (float)$wpmObj->non_air_conditioned;
                $wpmObj->building_information_id = $bi["id"];
                $wpmObj->save();
            }
        }

        addMainProgress($mainId,Main::PROGRESS_BUILDING,3);
        \Session::put('main.id', $mainId);

        return json_encode(array(
            'success'=>true
        ));
    }

    public function edit($doc_number) {
        $main = Main::find($doc_number);
        if(is_null($main))
        {
            return view('errors.404');
        }

        $data = Main::find($doc_number);
        $WPM = Building_information::with('working_per_months')->where('main_id',$doc_number)->get();

        if ($WPM->count()<=0)
            return redirect('building/process3/'. $doc_number);

        return view('building.process3_vue', compact('doc_number','data'));
    }
}
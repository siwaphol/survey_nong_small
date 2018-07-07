<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('/result/map', function () {
    return view('map.potential_map');
});

Route::get('xml-potential', function () {
    // Start XML file, create parent node
    $doc = new DOMDocument("1.0");
    $node = $doc->createElement("provinces");
    $parnode = $doc->appendChild($node);
    $allProvince = \App\SecMap::all();

    header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
    foreach ($allProvince as $province) {
        $node = $doc->createElement("province");
        $pNode = $parnode->appendChild($node);
        $pNode->setAttribute("name", $province->province_name);
        $pNode->setAttribute("potential_sum", $province->potential_sum);
        $pNode->setAttribute("potential_ind", $province->potential_ind);
        $pNode->setAttribute("potential_bud", $province->potential_bud);
        $pNode->setAttribute("lat", $province->lat);
        $pNode->setAttribute("long", $province->long);
		$pNode->setAttribute("color", $province->color);
        if ($province->ind->count() > 0) {
            foreach ($province->ind as $ind) {
                $node = $doc->createElement("ind");
                $indNode = $pNode->appendChild($node);
                $indNode->setAttribute("tsic", $ind->tsic_id);
				$indNode->setAttribute("tsic_name", $ind->tsic_name);
                $indNode->setAttribute("potential", $ind->potential);
            }
        }
        if ($province->bud->count() > 0) {
            foreach ($province->bud as $bud) {
                $node = $doc->createElement("bud");
                $budNode = $pNode->appendChild($node);
                $budNode->setAttribute("type", $bud->bud_type);
                $budNode->setAttribute("potential", $bud->potential);
            }
        }
    }
    $xmlfile = $doc->saveXML();
    echo $xmlfile;
});


/*Route::get('/import-xml', function () {
    $xml = simplexml_load_file(URL::to('download/potential.xml'), "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    foreach ($array['province'] as $province) {
        $sm = new \App\SecMap();
        $sm->province_name = $province['@attributes']['name'];
        $sm->potential_sum = $province['@attributes']['potential_sum'];
        $sm->potential_ind = $province['@attributes']['potential_ind'];
        $sm->potential_bud = $province['@attributes']['potential_bud'];
        $sm->lat = $province['@attributes']['lat'];
        $sm->long = $province['@attributes']['long'];
        $sm->save();
        if (isset($province['ind'])) {
            if (count($province['ind']) == 1) {
                $sd = new \App\SecMapDetail();
                $sd->sec_map_id = $sm->id;
                $sd->type = 'ind';
                $sd->potential = $province['ind']['@attributes']['potential'];
                $sd->tsic_id = $province['ind']['@attributes']['tsic'];
                $sd->save();

            } elseif (count($province['ind']) > 1) {
                foreach ($province['ind'] as $ind) {
                    $sd = new \App\SecMapDetail();
                    $sd->sec_map_id = $sm->id;
                    $sd->type = 'ind';
                    $sd->potential = $ind['@attributes']['potential'];
                    $sd->tsic_id = $ind['@attributes']['tsic'];
                    $sd->save();
                }
            }
        }
        if (isset($province['bud'])) {
            if (count($province['bud']) == 1) {
                $sd = new \App\SecMapDetail();
                $sd->sec_map_id = $sm->id;
                $sd->type = 'bud';
                $sd->potential = $province['bud']['@attributes']['potential'];
                $sd->bud_type = $province['bud']['@attributes']['type'];
                $sd->save();
            } elseif (count($province['bud']) > 1) {
                foreach ($province['bud'] as $bud) {
                    $sd = new \App\SecMapDetail();
                    $sd->sec_map_id = $sm->id;
                    $sd->type = 'bud';
                    $sd->potential = $bud['@attributes']['potential'];
                    $sd->bud_type = $bud['@attributes']['type'];
                    $sd->save();
                }
            }
        }

    }
    echo "Done";
});*/

Route::get('/download/{file}', function ($file) {
    $path = storage_path($file);
    return response()->download($path);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', "HomeController@index");

    Route::get('/test', function () {
        return view('test2');
    });


    Route::get('/total', 'TotalQuestion@total');
    Route::get('/sec/building/secall', 'BuildingSecController@index');
    Route::get('/sec/building/secall/excel', 'BuildingSecController@excel_sec');

    Route::resource('/sec/industry/secall', 'IndustrySecController');

//    Route::get('/form_1_2', function () {
//        return view('Form_1_2');
//    });

    Route::get('/test-flash-noti', function () {
        $data = 123;
        flash('This is message with data: ' . $data);

        return view('test2');
    });

    /* User */
    Route::resource('user', "UserController");
    Route::get('change-password', "UserController@showChangePasswordForm");
    Route::post('change-password', "UserController@changePassword");
    /*---------------------------------------------*/
    Route::get('/report_bug', function () {
        return view('contact.report_bug');
    });

    /* Building */

    Route::get('building/{doc_number}/excel', 'BuildingController@excel');
    Route::get('building/excelall', 'BuildingController@excel_sum');
    Route::get('building/change_data', 'BuildingController@change_data');

    Route::get('building', "BuildingController@index");
    Route::get('building/process1st', "BuildingController@Process1st");
    Route::get('building/process2nd', "BuildingController@Process2nd");
    Route::get('building/process3rd', "BuildingController@Process3rd");
    Route::get('building/process4th', "BuildingController@Process4th");
    Route::get('building/process5th', "BuildingController@Process5th");
    Route::get('building/process6th', "BuildingController@Process6th");
    Route::get('building/process7th', "BuildingController@Process7th");

//Route::get('building/process8th', "BuildingController@Process8th");
//Route::post('building/process1st',"BuildingProcess1Controller@store");
    Route::delete('building/{doc_number}', "BuildingController@destroy");

    Route::resource('building/process1', "BuildingProcess1Controller");
    Route::resource('building/process2', "BuildingProcess2Controller");
    Route::resource('building/process3', "BuildingProcess3Controller");
    Route::resource('building/process4', "BuildingProcess4Controller");
//Route::resource('building/process4th', "BuildingProcess4Controller");
    Route::resource('building/process5', "BuildingProcess5Controller");
    Route::resource('building/process6', "BuildingProcess6Controller");
    Route::resource('building/process7', "BuildingProcess7Controller");

    /*Route::get('building/process7/{doc_number}/plan/addplan',"BuildingProcess7Controller@show_addplan");
    Route::post('building/process7/plan/addplan',"BuildingProcess7Controller@addplan");*/

    Route::get('deleteMonthB3', "BuildingProcess3Controller@DeleteMonth");

    /*---------------------------------------------*/

    /* Industry */
    Route::get('industry/{doc_number}/excel', 'IndustryController@excel');
    Route::get('industry/excelall', 'IndustryController@excel_sum');

    Route::get('industry', "IndustryController@index");
    Route::delete('industry/{doc_number}', "IndustryController@destroy");
    /* Industry --> product */
    Route::get('industry/{doc_number}/product', "ProductController@index");
    Route::get('industry/{doc_number}/product/{product_id}/detail', "ProductController@detail");
    Route::get('process2/{doc_number}/product/{product_id}/edit', "ProductController@MonthProduceEdit");
    Route::resource('process2/{doc_number}/product/{product_id}/update', "ProductController@MonthProduceUpdate");

    /* Industry --> electric */
    Route::get('industry/{doc_number}/electric', "ElectricController@index");
    Route::get('industry/{doc_number}/meter/{meter_id}/detail', "ElectricController@MeterDetail");
    Route::get('industry/{doc_number}/meter/{meter_id}/edit', "ElectricController@MeterEdit");
    Route::resource('process3/{doc_number}/meter/{meter_id}/update', "ElectricController@MeterUpdate");
    /* Industry --> energy */
    Route::get('industry/{doc_number}/energy', "EnergyController@index");

    Route::resource('process1', "IndustryProcess1Controller");
    Route::resource('process2', "IndustryProcess2Controller");
    Route::resource('process3', "IndustryProcess3Controller");
    Route::resource('process4', "IndustryProcess4Controller");
    Route::resource('process5', "IndustryProcess5Controller");
    Route::resource('process6', "IndustryProcess6Controller");
    Route::resource('process7', "IndustryProcess7Controller");
    Route::resource('process8', "IndustryProcess8Controller");
    Route::resource('process9', "IndustryProcess9Controller");
    Route::resource('process10', "IndustryProcess10Controller");

    /*Route::get('process9/{doc_number}/plan/addplan',"IndustryProcess9Controller@show_addplan");
    Route::post('process9/plan/addplan',"IndustryProcess9Controller@addplan");*/

    Route::resource('productUnit', "IndustryProcess2Controller@product_unit");
    Route::resource('machineUnit', "IndustryProcess8Controller@machine_unit");
    Route::resource('energyUnit', "IndustryProcess8Controller@energy_unit");

    Route::get('deleteMonth2/{monthId}', "IndustryProcess2Controller@DeleteMonth");
    Route::resource('deleteMonth3', "IndustryProcess3Controller@DeleteMonth");
    Route::resource('deleteMonth4', "IndustryProcess4Controller@DeleteMonth");

    Route::get('building/process2/{doc_number}/delete/{building_id}', 'BuildingProcess2Controller@deleteBuilding');

    Route::resource('energyDetail', "IndustryProcess4Controller@EnergyDetail");
    Route::resource('recycleDetail', "IndustryProcess4Controller@RecycleDetail");

    Route::resource('deleteMeter', "IndustryProcess3Controller@DeleteMeter");

    Route::resource('deleteEnergy', "IndustryProcess4Controller@DeleteEnergy");

    Route::resource('plan', 'PlanController');


    Route::group(['prefix' => 'api/v1'], function () {
        Route::resource('/industry/process2nd', 'Api\IndustryProcess2Controller');

        //Energy and juristic
        Route::get('energy-and-juristic/{main_id}', 'EnergyAndJuristicController@show');
        Route::post('energy-and-juristic/{mainId}', 'EnergyAndJuristicController@saveAjax');
        //Energy type
        Route::get('energy-type', 'EnergyTypeController@index');
        Route::get('energy-id/{mainId}', 'EnergyTypeController@show');

        //Produce_time
        Route::get('produce-time/{produceTimeId}/delete', 'IndustryProcess2Controller@destroy');

//        Building
        Route::get('get-building-ajax/{main_id}', 'BuildingProcess3Controller@showAjax');
        Route::get('/building/process4/{main_id}', 'BuildingProcess4Controller@showAjax');
        Route::get('/building/process5/{main_id}', 'BuildingProcess5Controller@showAjax');
//        Industry
        Route::get('/industry/process3/{main_id}', 'IndustryProcess3Controller@showAjax');
        Route::get('/industry/process4/{main_id}', 'IndustryProcess4Controller@showAjax');

        //save Building
        Route::post('/building/process3/{mainId}', 'BuildingProcess3Controller@saveAjax');
        Route::post('/building/process4/{mainId}', 'BuildingProcess4Controller@saveAjax');
        Route::post('/building/process5/{mainId}', 'BuildingProcess5Controller@saveAjax');
        //save Industry
        Route::post('/industry/process3/{mainId}', 'IndustryProcess3Controller@saveAjax');
        Route::post('/industry/process4/{mainId}', 'IndustryProcess4Controller@saveAjax');

        Route::get('/district', 'BuildingProcess1Controller@apidistrictIndex');
        Route::get('/subdistrict', 'BuildingProcess1Controller@apisubdistrictIndex');
    });

});


/*
Route::resource('process1/{doc_number}/edit', "IndustryProcess1Controller");
Route::resource('process1/{doc_number}', "IndustryProcess1Controller");

Route::resource('process2/{doc_number}/edit', "IndustryProcess2Controller");
Route::resource('process2/{doc_number}', "IndustryProcess2Controller");

Route::resource('process3/{doc_number}/edit', "IndustryProcess3Controller");
Route::resource('process3/{doc_number}', "IndustryProcess3Controller");

Route::resource('process4/{doc_number}/edit', "IndustryProcess4Controller");
Route::resource('process4/{doc_number}', "IndustryProcess4Controller");

Route::resource('process5/{doc_number}/edit', "IndustryProcess5Controller");
Route::resource('process5/{doc_number}', "IndustryProcess5Controller");

Route::resource('process6/{doc_number}/edit', "IndustryProcess6Controller");
Route::resource('process6/{doc_number}', "IndustryProcess6Controller");

Route::resource('process7/{doc_number}/edit', "IndustryProcess7Controller");
Route::resource('process7/{doc_number}', "IndustryProcess7Controller");

Route::resource('process8/{doc_number}/edit', "IndustryProcess8Controller");
Route::resource('process8/{doc_number}', "IndustryProcess8Controller");

Route::resource('process9/{doc_number}/edit', "IndustryProcess9Controller");
Route::resource('process9/{doc_number}', "IndustryProcess9Controller");

Route::resource('process10/{doc_number}/edit', "IndustryProcess10Controller");
Route::resource('process10/{doc_number}', "IndustryProcess10Controller");*/
/*---------------------------------------------*/

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

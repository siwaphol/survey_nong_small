<?php

namespace App\Http\Controllers;

use App\Energy_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnergyTypeController extends Controller
{
    public function index(Request $request)
    {
        $energy_type = $request->input('type');
        if (empty($energy_type))
            return Energy_type::get();

        return Energy_type::where('type', $energy_type)->select('*','energy_name as text')->get();
    }
    public function show($id) {
//        $test = Energy_type::where('id',$id)->select('unit')->get();
//        dd($test);
//        $id = $id->find('id');
//        if(empty($id))
//            return Energy_type::get();
        return Energy_type::where('id',$id)->select('unit')->get();
    }
}

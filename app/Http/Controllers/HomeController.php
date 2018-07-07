<?php

namespace App\Http\Controllers;

use App\Main;
use App\Place_type;
use App\Area;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areaS = auth()->user()->area;
        $areaU = Area::find($areaS);
        $areaN = $areaU->name;
//        dd($areaN);
        if(auth()->user()->level != 'admin')
        {
            $buildings = Place_type::with(['mains'=>function($query){
                $query->orderBy('mains.updated_at', 'DESC');
            }])
                ->where('type', Place_type::BUILDING)
                ->get();

            $industrys = Place_type::with(['mains'=>function($query){
                $query->orderBy('mains.updated_at', 'DESC');
            }])
                ->where('type', Place_type::INDUSTRY)
                ->get();
            $area = $areaN;
        }else
        {
            $buildings = Place_type::with(['mains'=>function($query){
                $query->orderBy('mains.updated_at', 'DESC');
            }])
                ->where('type', Place_type::BUILDING)
                ->get();

            $industrys = Place_type::with(['mains'=>function($query){
                $query->orderBy('mains.updated_at', 'DESC');
            }])
                ->where('type', Place_type::INDUSTRY)
                ->get();
            $area = '0';
        }

//        $model_b = array ('id' => 0,'building' => array('1','2','3','4','5','6','7'),'industry' => array());
//        json_encode($model_b);
//        $model_i = array ('id' => 0,'building' => array(),'industry' => array('1','2','3','4','5','6','7','8','9','10'));
//        json_encode($model_i);

//        $buildings = Place_type::with(['mains'=>function($query){
//            $query->orderBy('mains.updated_at', 'DESC');
//        }])
//        ->where('type', Place_type::BUILDING)
//        ->get();
//
//        $industrys = Place_type::with(['mains'=>function($query){
//            $query->orderBy('mains.updated_at', 'DESC');
//        }])
//        ->where('type', Place_type::INDUSTRY)
//        ->get();

        return view('index', compact('buildings','industrys','area'));
    }
}
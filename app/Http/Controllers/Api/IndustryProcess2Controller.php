<?php

namespace App\Http\Controllers\Api;

use App\Produce_time;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndustryProcess2Controller extends Controller
{
    public function index()
    {
        dd('this is api industry index function');
    }

    public function show($doc_number)
    {
        $product_data = Produce_time::with('products','product_permonths')->where('main_id',$doc_number)->get();

        return $product_data;
    }

//    public function store(Request $request)
//    {
//
//    }

//    public function update($doc_number, Request $request)
//    {
//
//    }

//    public function destroy($doc_number)
//    {
//
//    }
}

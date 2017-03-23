<?php

namespace App\Http\Controllers;

use Log;
use App\City;
use Illuminate\Http\Request;

class CityController extends Controller{
    public function index($name,Request $rq){

        $res = City::where('name','like','%'.$name.'%');
        Log::debug($res->toSql());
        $res = $res->get();
        $ret = count($res)?200:404;
        return response()->json($res,$ret,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    public function create($name, Request $rq){
        $res = City::create(['name'=>$name]);
        $ret = count($res)?200:404;
        return response()->json($res,$ret,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}

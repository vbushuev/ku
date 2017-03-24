<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\City;
use App\User;
use App\Contact;
use App\ContactType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    protected $basic = [1000 => 14400];
    public function statuses(){
        $res = DB::table('statuses')->get();
        return response()->json($res,is_null($res)?500:200,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    public function change($status,Request $rq){
        $status = DB::table('statuses')->where("code","=",$status)->first();
        $data = app('App\Http\Controllers\UserController')->userInfo(Auth::user()->id);
        DB::table('user_status')->insert(["user_id"=>Auth::user()->id,"status_id"=>$status->id,"period"=>$data["status"]->left/60]);
        $res = ["result"=>"ok"];
        return response()->json($res,is_null($res)?500:200,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    public function pay($amount, Request $rq){
        DB::table('payments')->insert(["amount"=>$amount,"user_id"=>Auth::user()->id]);
        $status = DB::table('statuses')->where("code","=","working")->first();
        $data = app('App\Http\Controllers\UserController')->userInfo(Auth::user()->id);
        $period = $this->_periodByAmount($amount);
        if($data["status"]->left>0)$period+=($data["status"]->left/60);
        DB::table('user_status')->insert(["user_id"=>Auth::user()->id,"status_id"=>$status->id,"period"=>$period]);
        $res = ["result"=>"ok"];
        return response()->json($res,is_null($res)?500:200,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    protected function _periodByAmount($amount){
        $period = $this->basic[1000]*$amount/1000;
        return $period;
    }
}

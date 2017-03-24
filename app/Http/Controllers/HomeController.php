<?php

namespace App\Http\Controllers;
use DB;
use Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $data = app('App\Http\Controllers\UserController')->userInfo(Auth::user()->id);
        return view('home',$data);
    }
    public function statuses(){
        $data = app('App\Http\Controllers\UserController')->userInfo(Auth::user()->id);
        return response()->json($res,is_null($res)?500:200,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}

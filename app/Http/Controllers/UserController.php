<?php

namespace App\Http\Controllers;

use Log;
use App\City;
use App\User;
use App\Contact;
use App\ContactType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index($id,Request $rq){
        $user = User::find($id);
        $ret = 404;
        $res = [];
        if(!is_null($user)){
            $ret = 200;
            $res = $user->toArray();
            $res["city"] = City::find($user->city_id)->toArray();
            $contacts = Contact::where("user_id",$user->id)->get()->toArray();
            foreach ($contacts as $c) {
                $cts = ContactType::find($c["type_id"]);
                $contact = $c;
                $contact["type"] = $cts->name;
                $contact["code"] = $cts->code;
                $res["contacts"][] = $contact;
            }

        }
        Auth::login($user,true);
        //$user = Auth::user();
        Log::debug($user->getRememberTokenName().'='.$user->getRememberToken().';');
        return response()->json($res,$ret,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    public function create(Request $rq){
        $user = User::where("email",$rq->input("email"))->first();
        if(!is_null($user))return $this->index($user->id,$rq);
        $user = User::create([
            "name"=>$rq->input("name"),
            "email"=>$rq->input("email"),
            "password" => bcrypt($rq->input("email")),
            "city_id" => $rq->input("city_id"),
            "status_id" => $rq->input("status_id"),
            "image" => $rq->input("image",""),
            "type" => $rq->input("type")
        ]);
        $cts = ContactType::all();
        foreach ($cts as $ct) {
            if($rq->input($ct->code,"no")!="no" && $rq->input($ct->code,"") != ""){
                $contact = Contact::create([
                    "type_id"=>$ct->id,
                    "user_id"=>$user->id,
                    "value"=>$rq->input($ct->code)
                ]);
            }
        }
        //return redirect('/',['Set-Cookie'=>Auth::getRememberTokenName().'='.Auth::getRememberToken().';']);
        return $this->index($user->id,$rq);
        //return response()->json($user,count($res)?500:200,['Content-Type' => 'application/json; charset=utf-8','Set-Cookie'=>Auth::getRememberTokenName().'='.Auth::getRememberToken().';'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    public function update(Request $rq){
        $res = User::find($rq->input("id","-1"));
        $res->fill([
            "name"=>$rq->input("name"),
            "email"=>$rq->input("email"),
            "password" => bcrypt($rq->input("email")),
            "city_id" => $rq->input("city_id"),
            "status_id" => $rq->input("status_id"),
            "image" => $rq->input("image",""),
            "type" => $rq->input("type")
        ]);
        $res->save();
        return response()->json($res,is_null($res)?500:200,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}

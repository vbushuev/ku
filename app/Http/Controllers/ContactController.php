<?php

namespace App\Http\Controllers;


use Log;
use App\User;
use App\Contact;
use App\ContactType;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function get($user_id){
        $res = Contact::where("user_id",$user_id)->get();
        return response()->json($res,(count($res)?200:400),['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    public function create(Request $rq){
        $res = null;
        $type = ContactType::where("code",$rq->input("type",""));
        $user = User::find($rq->input("user_id",'-1'));
        if(!is_null($type) && !is_null($user)){
            $res = Contact::create([
                "type_id" => $type->id,
                "user_id" => $user->id,
                "value" => $rq->input("value","")
            ]);
        }
        return response()->json($res,(!is_null($res)?200:400),['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}

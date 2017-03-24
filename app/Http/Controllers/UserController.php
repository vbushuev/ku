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

class UserController extends Controller
{
    public function index($id,Request $rq){
        $res = $this->userInfo($id);
        return response()->json($res,count($res)?200:404,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    public function create(Request $rq){
        $user = User::where("email",$rq->input("email"))->first();
        //if(!is_null($user))return $this->index($user->id,$rq);
        if(!is_null($user))return $this->clientAuth($user);
        $user = User::create([
            "name"=>$rq->input("name"),
            "email"=>$rq->input("email"),
            "password" => bcrypt($rq->input("email")),
            "city_id" => $rq->input("city_id"),
            "status_id" => $rq->input("status_id"),
            "image" => $rq->input("image",""),
            "type" => $rq->input("type")
        ]);
        $this->contact($user->id,$rq);
        return $this->clientAuth($user);
    }
    public function contact($id,Request $rq){
        $res = User::find($id);
        $cts = ContactType::all();
        $contact = null;
        foreach ($cts as $ct) {
            if($rq->input($ct->code,"no")!="no" && $rq->input($ct->code,"") != ""){
                $contact = Contact::where("user_id",$res->id)->where("type_id",$ct->id)->first();
                if(is_null($contact)){
                    $contact = Contact::create([
                        "type_id"=>$ct->id,
                        "user_id"=>$res->id,
                        "value"=>$rq->input($ct->code)
                    ]);
                }else{
                    $contact->fill([
                        "value"=>$rq->input($ct->code)
                    ]);
                    $contact->save();
                }
            }
        }
        return response()->json($contact,is_null($contact)?500:200,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    public function update($id,Request $rq){
        $res = User::find($id);
        if(!is_null($res)){
            $data = $rq->all();
            if(isset($data["image"])){//store image
                $img = $rq->file("image");
                Log::debug("Loading file ".$id.".".$img->extension());
                $img->storeAs('public',$id.".".$img->extension());
                $data["image"] = $id.".".$img->extension();
            }
            if(isset($data["password"]))$data["password"]=bcrypt($data["password"]);
            $res->fill($data);
            $res->save();
        }
        return response()->json($res,is_null($res)?500:200,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    public function userInfo($id){
        $user = User::find($id);
        $res = [];
        if(!is_null($user)){
            $res = $user->toArray();
            $city =  City::find($user->city_id);
            $res["city"] = $city->name;
            $contacts = Contact::where("user_id",$user->id)->get()->toArray();
            foreach ($contacts as $c) {
                $cts = ContactType::find($c["type_id"]);
                $contact = $c;
                $contact["type"] = $cts->name;
                $contact["code"] = $cts->code;
                $res["contacts"][$cts->code] = $contact;
            }
            if($res["type"]=="1"){
                $clients = User::whereRaw("id in (select client_id from user_clients where user_id='".$res["id"]."')")->get();
                foreach ($clients as $client) {
                    $res["clients"][]=$this->userInfo($client->id);
                }
                $sel = DB::table('user_status')->join('statuses','statuses.id','=','user_status.status_id')
                    ->select(
                        'statuses.code as code',
                        'statuses.name as name',
                        'user_status.created_at as timestart',
                        'user_status.period',
                        DB::raw('date_add(user_status.created_at, INTERVAL period MINUTE) as timeend'),
                        DB::raw('TIMESTAMPDIFF(SECOND,if(statuses.code=\'working\',now(),user_status.created_at),date_add(user_status.created_at, INTERVAL period MINUTE)) as `left`')
                    )
                    ->where('user_status.user_id','=',$user->id)
                    ->orderBy('user_status.created_at','desc');
                //Log::debug($sel->toSql());
                $status = $sel->first();
                $res["status"]= $status;
            }

        }
        return $res;
    }
    protected function clientAuth($user){
        $user = Auth::login($user,true);
        return response()->json($user,200,['Content-Type' => 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}

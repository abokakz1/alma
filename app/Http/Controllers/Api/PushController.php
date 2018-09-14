<?php

namespace App\Http\Controllers\Api;

use App\Models\Push;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Users;

class PushController extends Controller
{
    public function addDevice(Request $request){
        $push = Push::where('registration_id','=',$request->registration_id)->delete();

        $push = new Push();
        $push->registration_id = $request->registration_id;
        $push->os = $request->os;
        $push->save();

        $result['success'] = true;
        return response()->json($result);
    }

    public function deleteDevice(Request $request,$token){
        $user = Users::where('remember_token','=',$token)->first();
        if($user === null){
            $result['error'] = 'Пользователь с указанным token не существует';
            $result['success'] = false;
            return response()->json($result);
        }

        $push = Push::where('user_id','=',$user->user_id)
            ->where('registration_id','=',$request->registration_id)
            ->count();
        if($push == 0){
            $result['success'] = false;
            return response()->json($result);
        }

        Push::where('user_id','=',$user->user_id)
            ->where('registration_id','=',$request->registration_id)
            ->delete();

        $result['success'] = true;
        return response()->json($result);
    }
}

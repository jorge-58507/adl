<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\adl_option;

class controller_option extends Controller
{
    
    public function update_unit (Request $request) 
    {
        $raw_user = $request->user();
        $user_id = $raw_user['id'];
        $model_option = new adl_option;
        $rs_option = $model_option->where('option_ai_user_id',$user_id)->get();
        $unit_option = [
            'distance'=>$request->input('a'),
            'time'=>$request->input('b'),
            'volume'=>$request->input('c'),
            'currency'=>$request->input('d')
        ];
        $model_option->where('ai_option_id',$rs_option[0]['ai_option_id'])->update(['tx_option_unit' => json_encode($unit_option)]);
// ANSWER
        return response()->json(['message'=>'Guardado exitosamente.','data_list'=>$unit_option]);

    }
}

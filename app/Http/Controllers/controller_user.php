<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\adl_option;
use App\adl_company;
use App\adl_user_company;
use App\role_user;
class controller_user extends Controller
{    
    public function get_user_by_company ($company_id) {
        $model_user = new User;
        $rs_user = $model_user->select('id','name','email')
        ->join('adl_user_companies', 'users.id', '=', 'adl_user_companies.company_ai_user_id')
        ->join('adl_companies', 'adl_user_companies.user_ai_company_id', '=', 'adl_companies.ai_company_id')
        ->where('adl_companies.ai_company_id',$company_id)
        ->get();
        $array_user_id=[];
        foreach ($rs_user as $a => $raw_user) {
            array_push($array_user_id,$raw_user['id']);
        }

        $returned = ['user_list'=>$rs_user, 'array_user'=>$array_user_id];
        return $returned;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model_company = new adl_company;
        $rs_company = $model_company->select('ai_company_id','tx_company_description','tx_company_ruc','tx_company_direction','tx_company_telephone')->get();
        $model_user = new User;
        $rs_user = $model_user->select('id','name','email')->get();
        $model_role = new role;
        $rs_role = $model_role->get();
        $model_user_company = new adl_user_company;
        $rs_user_company = $model_user_company->get();
        $compacted = ['company_list'=>$rs_company,'role_list'=>$rs_role,'user_list'=>$rs_user, 'rel_user_company'=>$rs_user_company];
        return view('auth.register',compact('compacted'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     */
    public function store(Request $request)
    {
        $model_user = new User;
        $check_user = $model_user->where('email',$request->input('c'))->count();
        if ($check_user > 0) {
            $rs_user = $model_user->select('id','name','email')->get();
            $compacted = ['user_list' => $rs_user];
            return response()->json(['message'=>'Esta dirección de correo se encuentra en uso','data'=>$compacted]);
        }
        $answer = User::create([
            'name' => $request->input('b'),
            'email' => $request->input('c'),
            'password' => Hash::make($request->input('d')),
        ]);
        $model_user_role = new role_user;
        $model_user_role->user_id = $answer['id']; 
        $model_user_role->role_id = $request->input('e');
        $model_user_role->save();

        $model_rel = new adl_user_company;
        $model_rel->user_ai_company_id = $request->input('a');
        $model_rel->company_ai_user_id = $answer['id'];
        $model_rel->save();
        
        $model_option = new adl_option;
        $model_option->option_ai_user_id = $answer['id'];
        $model_option->tx_option_unit = json_encode(['distance'=>'Km','time'=>'Hr','volume'=>'Ltr','currency'=>'Bl']);
        $model_option->save();

        // ##################   ANSWER     ###########
        $rs_user = $model_user->select('id','name','email')->get();
        $compacted = ['user_list' => $rs_user];
        return response()->json(['message'=>'Usuario Creado.','data'=>$compacted]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
      $user = $request->user();
			$user_id = $user['id'];
			$model_option = new adl_option;
			$qry_option = $model_option->where('option_ai_user_id',$user_id)->select('tx_option_unit');
			$rs_option = $qry_option->get();
			$compacted = [
				'user_id'=> $user_id,
				'option'=> $rs_option
			];
      return view('user.edit', compact('compacted'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_id = $request->input('a');
        $user_name = $request->input('b');
        $email = $request->input('c');
        $model_user = new User;
        $model_user->where('id',$user_id)->update(['name' => $user_name, 'email' => $email, 'password' => Hash::make($request->input('d'))]);
            // ANSWER
        return response()->json(['message'=>'Guardado exitosamente.','data_list'=>$unit_option]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    // public function store_userlinkup(Request $request) 
    // {
    // }

    // public function destroy_userlinkup($user_id,$company_id){

    // }
}

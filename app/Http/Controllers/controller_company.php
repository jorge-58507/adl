<?php

namespace App\Http\Controllers;

use App\adl_company;

use Illuminate\Http\Request;

class controller_company extends Controller
{
    public function get_company_by_user ($user) {
        $model_company = new adl_company;
        $rs_company = $model_company->select('ai_company_id','tx_company_description','tx_company_ruc','tx_company_direction','tx_company_telephone')
        ->join('adl_user_companies', 'adl_companies.ai_company_id', '=', 'adl_user_companies.user_ai_company_id')
        ->join('users', 'adl_user_companies.company_ai_user_id', '=', 'users.id')
        ->where('users.id',$user)
        ->get();
        $array_company_id=[];
        foreach ($rs_company as $a => $company_id) {
            array_push($array_company_id,$company_id['ai_company_id']);
        }

        $returned = ['company_list'=>$rs_company, 'array_company'=>$array_company_id];
        return $returned;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
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
     */
    public function store(Request $request)
    {
        $model_company = new adl_company;
        $user = $request->user(); 
        $check_company = $model_company->where('company_ai_user_id',$user['id'])->where('tx_company_ruc',$request['b']);
        if ($check_company>0) {
            $rs_company = $this->get_company_by_user($user['id']);
            $compacted = ['company_list'=>$rs_company];
            return response()->json(['message'=>'Esta compa&ntilde;&iacute;a ya existe.','data'=>$compacted]);
        }
        $model_company->company_ai_user_id = $user['id'];
        $model_company->tx_company_description = $request['a'];
        $model_company->tx_company_ruc =  $request['b'];
        $model_company->tx_company_direction =  $request['c'];
        $model_company->tx_company_telephone =  $request['d'];
        $model_company->save();
/*          ANSWER            */
        $rs_company = $this->get_company_by_user($user['id']);
        $compacted = ['company_list'=>$rs_company];

        return response()->json(['message'=>'Guardado exitosamente.','data'=>$compacted]);
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
    public function edit($id)
    {
        //
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
        //
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
}

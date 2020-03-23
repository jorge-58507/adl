<?php

namespace App\Http\Controllers;

use App\adl_company;

use Illuminate\Http\Request;

class controller_company extends Controller
{
    public function get_company_by_user ($user) {
        $model_company = new adl_company;
        $rs_company = $model_company->select('ai_company_id','tx_company_description','tx_company_ruc','tx_company_direction','tx_company_telephone','int_company_status')
        ->join('adl_user_companies', 'adl_companies.ai_company_id', '=', 'adl_user_companies.user_ai_company_id')
        ->join('users', 'adl_user_companies.company_ai_user_id', '=', 'users.id')
        ->where('adl_companies.int_company_status','=',1)
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
        if ($check_company->count()>0) {
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
        $rs_company = $model_company->select('ai_company_id','tx_company_description','tx_company_ruc','tx_company_direction','tx_company_telephone','int_company_status')->get();
        $compacted = ['company_list'=>$rs_company];
        return response()->json(['message'=>'Guardado exitosamente.','data'=>$compacted]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($str)
    {
        $model_company = new adl_company;
        if ($str === 'All') {
            $rs_company = $model_company->select('ai_company_id','tx_company_description','tx_company_ruc','tx_company_telephone','tx_company_direction','int_company_status')->get();
            return response()->json(['company_list'=>$rs_company, 'company_count'=>$model_company->count()]);
        }
        $array_str = explode(' ',$str);
        $qry_company = $model_company->select('ai_company_id','tx_company_description','tx_company_ruc','tx_company_telephone','tx_company_direction','int_company_status')->where(
            function ($query) use ($array_str) {
                foreach ($array_str as $key => $str) {
                    if ($str === $array_str[0]) {
                        $query->where('tx_company_description', 'LIKE', '%'.$str.'%');
                    }else{
                        $query->orWhere('tx_company_description', 'LIKE', '%'.$str.'%');
                    }
                }
            }
        )
        ->orwhere(
            function ($query) use ($array_str) {
                foreach ($array_str as $key => $str) {
                    if ($str === $array_str[0]) {
                        $query->where('tx_company_ruc', 'LIKE', '%'.$str.'%');
                    }else{
                        $query->orWhere('tx_company_ruc', 'LIKE', '%'.$str.'%');
                    }
                }
            }
        )
        ->orwhere(
            function ($query) use ($array_str) {
                foreach ($array_str as $key => $str) {
                    if ($str === $array_str[0]) {
                        $query->where('tx_company_telephone', 'LIKE', '%'.$str.'%');
                    }else{
                        $query->orWhere('tx_company_telephone', 'LIKE', '%'.$str.'%');
                    }
                }
           }
        )
        ->orwhere(
            function ($query) use ($array_str) {
                foreach ($array_str as $key => $str) {
                    if ($str === $array_str[0]) {
                        $query->where('tx_company_direction', 'LIKE', '%'.$str.'%');
                    }else{
                        $query->orWhere('tx_company_direction', 'LIKE', '%'.$str.'%');
                    }
                }
            }
        );
        $rs_company = $qry_company->get();
        return response()->json(['company_list'=>$rs_company, 'company_count'=>$qry_company->count()]);
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
        $company_id = $request->input('a');
        $company_description = $request->input('b');
        $company_ruc = $request->input('c');
        $company_telephone = $request->input('d');
        $company_direction = $request->input('e');
        $message = 'Guardado exitosamente.';
        $raw_update = ['tx_company_description' => $company_description, 'tx_company_ruc' => $company_ruc, 'tx_company_telephone' => $company_telephone, 'tx_company_direction' => $company_direction];
        $model_company = new adl_company;
        $model_company->where('ai_company_id',$company_id)->update($raw_update);
            // ANSWER
        $rs_company = $model_company->select('ai_company_id','tx_company_description','tx_company_ruc','tx_company_telephone','tx_company_direction','int_company_status')->get();
        return response()->json(['message'=>$message,'company_list'=>$rs_company]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id)
    {
        $model_company = new adl_company;
        $rs_company_check = $model_company->where('ai_company_id',$company_id)->first();
        $toggle = ($rs_company_check['int_company_status'] === 0) ? 1 : 0 ;
        $message = ($rs_company_check['int_company_status'] === 0) ? 'Compañia Desbloqueada.' : 'Compañia Bloqueada.';
        $model_company->where('ai_company_id',$company_id)->update(['int_company_status'=>$toggle]);
        // ANSWER
        $rs_company = $model_company->select('ai_company_id','tx_company_description','tx_company_ruc','tx_company_telephone','tx_company_direction','int_company_status')->get();
        return response()->json(['message'=>$message,'company_list'=>$rs_company, 'company_count'=>$model_company->count()]);
    }
}

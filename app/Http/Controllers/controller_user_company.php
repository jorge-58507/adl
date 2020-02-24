<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\adl_user_company;
use App\adl_data;
use App\User;

use App\Http\Controllers\controller_company;
use App\Http\Controllers\controller_user;


class controller_user_company extends Controller
{
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
        $model_user_company = new adl_user_company;
        $controller_company = new controller_company;
        $controller_user = new controller_user;

        $count_user_company = $model_user_company->where('company_ai_user_id',$request->input('a'))->where('user_ai_company_id',$request->input('b'))->count();
        if ($count_user_company > 0) {
            $status = 'BAD';
            $message = 'Enlace Ya existe.';
        }else{
            $model_user_company->company_ai_user_id = $request->input('a');
            $model_user_company->user_ai_company_id = $request->input('b');
            $model_user_company->save();
            // ANSWER
            $status = 'GOOD';
            $message = 'Enlace Creado';
        }
        switch ($request->input('c')) {
            case 'user':
                $rs_company = $controller_company->get_company_by_user($request->input('a'));
                $rs_datalist = $rs_company['company_list'];
                break;
            case 'company':
                $rs_user = $controller_user->get_user_by_company($request->input('b'));
                $rs_datalist = $rs_user['user_list'];
                break;
        }
        return response()->json(['status'=>$status, 'message'=>$message, 'data_list'=>$rs_datalist]);
    }
    public function delete(Request $request){
        $model_user_company = new adl_user_company;
        $controller_company = new controller_company;
        $controller_user = new controller_user;

        $rs_user_company = $model_user_company->where('company_ai_user_id',$request->input('a'))->where('user_ai_company_id',$request->input('b'))->get();
        $answer = $this->destroy($rs_user_company[0]['ai_rel_id']);
        // ANSWER
        switch ($request->input('c')) {
            case 'user':
                $rs_company = $controller_company->get_company_by_user($request->input('a'));
                $rs_datalist = $rs_company['company_list'];
                break;
            case 'company':
                $rs_user = $controller_user->get_user_by_company($request->input('b'));
                $rs_datalist = $rs_user['user_list'];
                break;
        }
        return response()->json(['status'=>'GOOD', 'message'=>'Eliminado Correctamente.', 'data_list'=>$rs_datalist]);

        // $controller_company = new controller_company;
        // $rs_company = $controller_company->get_company_by_user($request->input('a'));
        // return response()->json(['message'=>'Eliminado Correctamente.', 'company_list'=>$rs_company['company_list']]);
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
        $model_user_company = new adl_user_company;
        $qry_user_company = $model_user_company->where('ai_rel_id',$id);
        $qry_user_company->delete();
        return 'eliminado';
        // $rs_user_company = $qry_user_company->get();
                //    ANSWER
        // $model_user = new User;
        // $controller_company = new controller_company;
        // $rs_company = $controller_company->get_company_by_user($rs_user_company[0]['company_ai_user_id']);
        // return response()->json(['message'=>'Enlace Eliminado.', 'company_list'=>$rs_company['company_list']]);
        // return response()->json(['message'=>'Enlace Eliminado.']);

    }
}

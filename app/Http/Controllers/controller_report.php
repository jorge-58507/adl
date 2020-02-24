<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\adl_vehicule;
use App\adl_option;
use App\adl_data;
use App\adl_company;

class controller_report extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model_option = new adl_option;
        $model_vehicule = new adl_vehicule;
        $controller_vehicule = new controller_vehicule;
        $user = $request->user(); 
        $rs_vehicule = $controller_vehicule->get_vehicule_by_user($user['id']);
        $rs_option = $model_option->where('option_ai_user_id',$user['id'])->get();
        // $qry_vehicule = $model_vehicule->where('vehicule_ai_company_id',$user['id']);
        // $rs_vehicule = $qry_vehicule->get();
        $compacted = ['vehicule_list'=>$rs_vehicule, 'option'=>$rs_option];
        return view('report.index', compact('compacted'));
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
        //
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
    public function edit($from,$until,$str_vehicule, Request $request)
    {
        $model_vehicule = new adl_vehicule;
        $controller_vehicule = new controller_vehicule;
        $user = $request->user(); 

        $array_vehicule = explode(',',$str_vehicule);
        $array_data = [];
        $rs_vehicule = $controller_vehicule->get_vehicule_by_user($user['id']);

        foreach ($array_vehicule as $vehicule_slug) {
            $array_data[$vehicule_slug] = $this->get_fromto($from,$until,$vehicule_slug);
        }
        $compacted = ['data'=>$array_data,'vehicule_list'=>$rs_vehicule];
        return view('report.dashboard', compact('compacted'));
    }
    public function get_fromto ($from,$until,$vehicule_slug)
    {
        $model_data = new adl_data;
        $model_vehicule = new adl_vehicule;

        $from = date('Y-m-d', strtotime($from));
        $to = date('Y-m-d', strtotime($until));

        $rs_vehicule = $model_vehicule->where('tx_vehicule_slug',$vehicule_slug)->get();
        $rs_data = $model_data->where('data_ai_vehicule_id',$vehicule_slug)->where('tx_data_date','>=',$from)->where('tx_data_date','<=',$to)
			->select('adl_datas.tx_data_date','adl_datas.tx_data_sample','adl_datas.tx_data_slug','adl_datas.tx_data_unit')->get();
        $raw_datalist = ['datasample'=>$rs_data, 'vehicule'=>$rs_vehicule[0]['tx_vehicule_licenseplate']];
        return $raw_datalist;
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

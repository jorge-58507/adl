<?php
namespace App\Http\Controllers;

use App\adl_vehicule;
use App\adl_option;
use App\adl_company;
 
use Illuminate\Http\Request;

class controller_vehicule extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_vehicule_by_user ($user_id)
    {
        $model_company = new adl_company;
        $model_vehicule = new adl_vehicule;
        $controller_company = new controller_company;

        $array_company_id = $controller_company->get_company_by_user($user_id);
        $qry_vehicule = $model_vehicule->whereIn('vehicule_ai_company_id',$array_company_id['array_company']);
        $rs_vehicule = $qry_vehicule->get();
        return $rs_vehicule;
    }
    public function index(Request $request)
    {   
        if(empty($request->user())){ 
            return view('auth.login');
        }
        $request->user()->authorizeRole(['user','admin']);
        $model_option = new adl_option;
        $model_company = new adl_company;
        $controller_company = new controller_company;
        $user = $request->user(); 
        $rs_option = $model_option->where('option_ai_user_id',$user['id'])->get();
        $rs_vehicule = $this->get_vehicule_by_user($user['id']);
        $rs_company = $controller_company->get_company_by_user($user['id']);

        $compacted = ['vehicule_list'=>$rs_vehicule, 'option'=>$rs_option, 'company_list' =>$rs_company['company_list']];
        return view('home', compact('compacted'));
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
        $model_vehicule = new adl_vehicule;
        $vehicule_info = $request->input('a');
        $controller_company = new controller_company;
        $user = $request->user(); 

        $rs_company = $controller_company->get_company_by_user($user['id']);

        $check_vehicule = $model_vehicule->whereIn('vehicule_ai_company_id',$rs_company['array_company'])
        ->where('tx_vehicule_licenseplate', $vehicule_info['licenseplate']);
        if ($check_vehicule->count() > 0) {
            $message = 'Esta placa ya existe.';
        }else{
            $message = '¡Datos Guardados!';
            $model_vehicule->vehicule_ai_company_id = $vehicule_info['company'];
            $model_vehicule->tx_vehicule_licenseplate = $vehicule_info['licenseplate'];
            $model_vehicule->tx_vehicule_brand = $vehicule_info['brand'];
            $model_vehicule->tx_vehicule_model = $vehicule_info['model'];
            $model_vehicule->tx_vehicule_slug = $vehicule_info['licenseplate'].time();
            $model_vehicule->save();
        }
        // ANSWER
        $rs_vehicule = $this->get_vehicule_by_user($user['id']);
        return response()->json(['message'=>$message,'data_list'=>$rs_vehicule]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($str)
    {
        $raw_str = explode('*-*',$str);
        $rs_vehicule = $this->filter(["filter_str"=>$raw_str[0],"limit"=>$raw_str[1]]);
        return response()->json(['vehicule_list'=>$rs_vehicule,'vehicule_count'=>count($rs_vehicule)]);
    }
    public function filter ($filter_option)
    {
        $str = $filter_option['filter_str'];
        $limit = $filter_option['limit'];
        $model_vehicule = new adl_vehicule;
        if ($str === 'All') {
            $rs_vehicule = $model_vehicule->select('adl_vehicules.vehicule_ai_company_id','adl_companies.tx_company_description','adl_vehicules.tx_vehicule_slug','adl_vehicules.tx_vehicule_licenseplate','adl_vehicules.tx_vehicule_brand','adl_vehicules.tx_vehicule_model','adl_vehicules.int_vehicule_status')
            ->join('adl_companies','adl_companies.ai_company_id','=','adl_vehicules.vehicule_ai_company_id')
            ->limit($limit)->get();
            return $rs_vehicule;
        }
        $array_str = explode(' ',$str);
        $qry_vehicule = $model_vehicule->select('adl_vehicules.vehicule_ai_company_id','adl_companies.tx_company_description','adl_vehicules.tx_vehicule_slug','adl_vehicules.tx_vehicule_licenseplate','adl_vehicules.tx_vehicule_brand','adl_vehicules.tx_vehicule_model','adl_vehicules.int_vehicule_status')
        ->join('adl_companies','adl_companies.ai_company_id','=','adl_vehicules.vehicule_ai_company_id')
        ->where(
            function ($query) use ($array_str) {
                foreach ($array_str as $key => $str) {
                    if ($str === $array_str[0]) {
                        $query->where('adl_vehicules.tx_vehicule_licenseplate', 'LIKE', '%'.$str.'%');
                    }else{
                        $query->orWhere('adl_vehicules.tx_vehicule_licenseplate', 'LIKE', '%'.$str.'%');
                    }
                }
            }
        )
        ->orwhere(
            function ($query) use ($array_str) {
                foreach ($array_str as $key => $str) {
                    if ($str === $array_str[0]) {
                        $query->where('adl_vehicules.tx_vehicule_model', 'LIKE', '%'.$str.'%');
                    }else{
                        $query->orWhere('adl_vehicules.tx_vehicule_model', 'LIKE', '%'.$str.'%');
                    }
                }
            }
        )
        ->orwhere(
            function ($query) use ($array_str) {
                foreach ($array_str as $key => $str) {
                    if ($str === $array_str[0]) {
                        $query->where('adl_vehicules.tx_vehicule_brand', 'LIKE', '%'.$str.'%');
                    }else{
                        $query->orWhere('adl_vehicules.tx_vehicule_brand', 'LIKE', '%'.$str.'%');
                    }
                }
            }
        )
        ->orwhere(
            function ($query) use ($array_str) {
                foreach ($array_str as $key => $str) {
                    if ($str === $array_str[0]) {
                        $query->where('adl_companies.tx_company_description', 'LIKE', '%'.$str.'%');
                    }else{
                        $query->orWhere('adl_companies.tx_company_description', 'LIKE', '%'.$str.'%');
                    }
                }
            }
        )
        ->limit($limit);
        $rs_vehicule = $qry_vehicule->get();
        return $rs_vehicule;
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
        $vehicule_slug = $request->input('a');
        $brand = $request->input('b');
        $model = $request->input('c');
        $company_id = $request->input('d');
        $message = 'Guardado exitosamente.';
        $raw_update = ['tx_vehicule_brand' => $brand, 'tx_vehicule_model' => $model, 'vehicule_ai_company_id' => $company_id];
        
        $model_vehicule = new adl_vehicule;
        $model_vehicule->where('tx_vehicule_slug',$vehicule_slug)->update($raw_update);
            // ANSWER
        $rs_vehicule = $this->filter(['filter_str'=>'All','limit'=>$request->input('limit')]);
        return response()->json(['message'=>$message,'vehicule_list'=>$rs_vehicule]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($vehicule_slug, Request $request)
    {
        $model_vehicule = new adl_vehicule;
        $qry_vehicule = $model_vehicule->where('tx_vehicule_slug',$vehicule_slug);
        $rs_check = $qry_vehicule->first();
        $toggle = ($rs_check['int_vehicule_status'] === 0) ? 1 : 0 ;
        $message = ($rs_check['int_vehicule_status'] === 0) ? 'Veh&iacute;culo Desbloqueado.' : 'Veh&iacute;culo Bloqueado.';
        $qry_vehicule->update(['int_vehicule_status'=>$toggle]);
        // ANSWER
        $rs_vehicule = $this->filter(['filter_str'=>'All','limit'=>$request->input('a')]);
        return response()->json(['message'=>$message,'vehicule_list'=>$rs_vehicule]);

    }
}

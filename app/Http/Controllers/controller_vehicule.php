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

        $rs_company = $model_company->select('ai_company_id')
        ->join('adl_user_companies', 'adl_companies.ai_company_id', '=', 'adl_user_companies.user_ai_company_id')
        ->join('users', 'adl_user_companies.company_ai_user_id', '=', 'users.id')
        ->where('users.id',$user_id)
        ->get();
        $array_company_id=[];
        foreach ($rs_company as $a => $company_id) {
            array_push($array_company_id,$company_id['ai_company_id']);
        }
        $qry_vehicule = $model_vehicule->whereIn('vehicule_ai_company_id',$array_company_id);
        $rs_vehicule = $qry_vehicule->get();
        return $rs_vehicule;
 
    }
    public function index(Request $request)
    {   
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
            $model_vehicule->tx_vehicule_slug = time();
            // $model_vehicule->save();
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

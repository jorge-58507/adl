<?php

namespace App\Http\Controllers;

use App\adl_data;
use App\adl_vehicule;
use Illuminate\Http\Request;

class controller_data extends Controller
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
			$date = date("Y-m-d", strtotime($request->input('a')));
			$vehicule = $request->input('b');
      $sample = json_encode($request->input('c'));
      $unit = json_encode($request->input('d'));
			$user = $request->user();
			$model_data = new adl_data;

			$check_data = $model_data->where('data_ai_vehicule_id',$vehicule)->where('tx_data_date',$date)->where('tx_data_sample',$sample);
			if ($check_data->count() > 0) {
        $message = 'Este dato ya fue ingresado.';
        $status = 'failed';
			}else{
        $message = '¡Datos Guardados!';	
        $status = 'success';
				$model_data->data_ai_user_id = $user['id'];
				$model_data->data_ai_vehicule_id = $vehicule;
				$model_data->tx_data_date = $date;
				$model_data->tx_data_sample = $sample;
				$model_data->tx_data_unit = $unit;
				$model_data->tx_data_slug = time().$vehicule;
				$model_data->save();
			}

			 // ANSWER			
			$qry_data = $model_data->join('users', 'users.id', '=', 'adl_datas.data_ai_user_id')->where('data_ai_vehicule_id',$vehicule)
			->select('users.name','adl_datas.tx_data_date','adl_datas.tx_data_sample','adl_datas.tx_data_slug','adl_datas.tx_data_unit');
			$rs_data = $qry_data->get();
      return response()->json(['status'=>$status, 'message'=>$message,'data_list'=>$rs_data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
			$model_data = new adl_data;
			$qry_data = $model_data->join('users', 'users.id', '=', 'adl_datas.data_ai_user_id')->where('data_ai_vehicule_id',$id)
			->select('users.name','adl_datas.tx_data_date','adl_datas.tx_data_sample','adl_datas.tx_data_slug','adl_datas.tx_data_unit')->orderby('adl_datas.tx_data_date','ASC');
			$rs_data = $qry_data->get();
      return response()->json(['status'=>'success','message'=>'no_message','data_list'=>$rs_data]);
    }
    public function show_fromto(Request $request)
    {
      $model_data = new adl_data;
      $model_vehicule = new adl_vehicule;

      $from = date('Y-m-d', strtotime($request->input('a')));
      $to = date('Y-m-d', strtotime($request->input('b')));
      $vehicule_slug = $request->input('c');
      $array_checked = $request->input('d');
      
      $rs_vehicule = $model_vehicule->where('tx_vehicule_slug',$vehicule_slug)->get();
			$qry_data = $model_data->where('data_ai_vehicule_id',$vehicule_slug)->where('tx_data_date','>=',$from)->where('tx_data_date','<=',$to)
			->select('adl_datas.tx_data_date','adl_datas.tx_data_sample','adl_datas.tx_data_slug','adl_datas.tx_data_unit')->orderby('adl_datas.tx_data_date','ASC');
      $rs_data = $qry_data->get();
      $raw_datalist = ['datasample'=>$rs_data, 'vehicule'=>$rs_vehicule[0]['tx_vehicule_licenseplate']];
      return response()->json(['status'=>'success','message'=>'no_message','data_list'=>$raw_datalist]);

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
			$model_data = new adl_data;
			$qry_vehicule = $model_data->where('tx_data_slug',$id)->select('data_ai_vehicule_id');
			$rs_vehicule = $qry_vehicule->get();

			$model_data->where('tx_data_slug',$id)->delete();
			//    ANSWER
			$qry_data = $model_data->join('users', 'users.id', '=', 'adl_datas.data_ai_user_id')->where('data_ai_vehicule_id',$rs_vehicule[0]['data_ai_vehicule_id'])
			->select('users.name','adl_datas.tx_data_date','adl_datas.tx_data_sample','adl_datas.tx_data_slug','adl_datas.tx_data_unit');
			$rs_data = $qry_data->get();
      return response()->json(['status'=>'success','message'=>'Borrado exitosamente.','data_list'=>$rs_data]);
    }

    public function store_ralenti (Request $request)
    {
      $model_data = new adl_data;
      $model_vehicule = new adl_vehicule;
      $ralenti_array = $request->input('a');
      $message = '';
      foreach ($ralenti_array as $licenseplate => $data) {
        $qry_vehicule = $model_vehicule->select('tx_vehicule_slug')->where('tx_vehicule_licenseplate',$licenseplate);
        if ($qry_vehicule->count() > 0) {
          $rs_vehicule = $qry_vehicule->get();
          foreach ($data as $date => $time) {
            $vehicule = $rs_vehicule[0]['tx_vehicule_slug'];
            $date = date("Y-m-d", strtotime($date));
            $qry_data = $model_data->select('ai_data_id','tx_data_sample')->where('data_ai_vehicule_id',$vehicule)->where('tx_data_date',$date);
            if ($qry_data->count() > 0) { /* Si ya esta fecha existe solamente agrega el ralenti */
              $rs_data = $qry_data->get();
              $sample = json_decode($rs_data[0]['tx_data_sample'], true);
              $sample['ralenti'] = $time;
            } else {  /* Si no existia crea un array vacio */
              $sample = ['distance'=>null,'volume'=>null, 'currency'=>null, 'time'=>null, 'ralenti'=>$time];
            }
            $sample = json_encode($sample);
            $unit = json_encode($request->input('b'));
            $user = $request->user();

            if ($qry_data->count() > 0) {  /* Si existe actualiza el array */
              $raw_update = ['tx_data_sample' => $sample];
              $model_data->where('ai_data_id',$rs_data[0]['ai_data_id'])->update($raw_update);
              $status = 'failed';
              $message .= "Placa \"$licenseplate\" $date Actualizado.<br/> ";	
            }else{ /* Si no existe inserta uno nuevo  */
              $status = 'success';
              $model_data->insert(
                ['data_ai_user_id' => $user['id'], 
                'data_ai_vehicule_id' => $vehicule,
                'tx_data_date' => $date,
                'tx_data_sample' => $sample,
                'tx_data_unit' => $unit,
                'tx_data_slug' => time().$vehicule,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                ]
              );
              $message .= "Placa \"$licenseplate\" $date Guardado.<br/> ";	
            }

          }
        }else{ $status = 'failed'; $message .= "Placa \"$licenseplate\" No Existe.<br/>"; }
      }
      return response()->json(['status'=>$status, 'message'=>$message]);

    }
}

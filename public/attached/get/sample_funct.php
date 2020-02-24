<?php
require '../../ar_conexion.php';

$method = $_SERVER['REQUEST_METHOD'];
$URI = $_SERVER['REQUEST_URI'];
class sampleController  {
  public function save () {
    $request_body = file_get_contents('php://input');
    $array_body = json_decode($request_body,true);
// PROCEDER A GUARDAR AQUI
    $link = conexion();
    $date = date("Y-m-d", strtotime($array_body['a']));
    $vehicule = $array_body['b'];
    $sample = json_encode($array_body['c']);
    $date_today = date("Y-m-d H:i:s");
    $slug = time().'-'.strtotime($array_body['a']);
    // $link->query("INSERT INTO ar_data (data_ai_vehicule_id, tx_data_date, tx_data_sample, tx_data_slug, tx_data_create, tx_data_update) VALUES ('$vehicule','$date','$sample','$slug','$date_today','$date_today')")or die($link->error);

    //  #### ANSWER
    $qry_data = $link->query("SELECT tx_data_date, tx_data_sample, tx_data_slug FROM ar_data WHERE data_ai_vehicule_id = '$vehicule'")or die($link->error);
    $array_data = array();
    while ($raw_data = $qry_data->fetch_array(MYSQLI_ASSOC)) {
      $array_data[] = $raw_data;
    }
    echo json_encode($raw_answer = ["status"=>'success',"message"=>'¡Guardado Exitosamente!',"data"=>json_encode($array_data)]);
  }
}
$cls_sample = new sampleController;

switch ($method) {
  case 'POST':
    $cls_sample->save();
  break;
  
  default:
  case 'GET':
    echo json_encode($raw_answer = ["status"=>'fail',"message"=>'LLAMADA GET',]);
  break;
}
?>
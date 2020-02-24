@extends('layouts.report')
@section('nav_item')
  <a class="nav-link side_panel_item" href="#list-item-1"><i class="fas fa-circle-notch fa-xs"></i> <span class="font-weight-normal" >Totales </span></a>
  <a class="nav-link side_panel_item" href="#list-item-2"><i class="fas fa-circle-notch fa-xs"></i> <span>Gr&aacute;ficas Simples</span></a>
  <a class="nav-link side_panel_item" href="#list-item-3"><i class="fas fa-circle-notch fa-xs"></i> <span>Gr&aacute;ficas Combinadas </span></a>
@endsection
@section('content')
<?php
$date_today = date('d-m-Y'); 
$rs_vehicule = $compacted['vehicule_list'];
$array_data = $compacted['data'];
$qty_data = 0;  $ttl_distance = 0;
$alert_warning = '';
$array_vehicule = []; //   ARREGLOS DE VEHICULOS
foreach ($array_data as $slug => $data) { 
  ${"obj_distance_$slug"} = []; ${"obj_time_$slug"} = []; ${"obj_volume_$slug"} = []; ${"obj_currency_$slug"} = []; ${"obj_distancexvolume_$slug"} = []; ${"obj_timexvolume_$slug"} = []; ${"obj_currencyxvolume_$slug"} = [];
  $qty_data++; 
  $distance_sum = 0;  $time_sum = 0; $volume_sum = 0; $currency_sum = 0;
  $data_unit = '';

  foreach ($data['datasample'] as $key => $array_data) {
    if ($array_data === $data['datasample'][0]) {
      $data_unit = $array_data['tx_data_unit'];
    }
    if ( $array_data['tx_data_unit'] === $data_unit) {
      $sample = json_decode($array_data['tx_data_sample'],true);
      if ($sample['distance'] != null) {
        ${"obj_distance_$slug"}[$array_data['tx_data_date']] = '';
        ${"obj_distance_$slug"}[$array_data['tx_data_date']] = $sample['distance'];
        $distance_sum += $sample['distance'];
      }
      if ($sample['time'] != null) {
        ${"obj_time_$slug"}[$array_data['tx_data_date']] = '';
        ${"obj_time_$slug"}[$array_data['tx_data_date']] = $sample['time'];
        $time_sum += $sample['time'];
      }
      if ($sample['volume'] != null) {
        ${"obj_volume_$slug"}[$array_data['tx_data_date']] = '';
        ${"obj_volume_$slug"}[$array_data['tx_data_date']] = $sample['volume'];
        $volume_sum += $sample['volume'];
      }
      if ($sample['currency'] != null) {
        ${"obj_currency_$slug"}[$array_data['tx_data_date']] = '';
        ${"obj_currency_$slug"}[$array_data['tx_data_date']] = $sample['currency'];
        $currency_sum += $sample['currency'];
      }
    }else{
      $alert_warning = 'Hay unidades que no coinciden.';
    }
  }
  $array_vehicule[$slug]['name'] = $data['vehicule'];
  $array_vehicule[$slug]['total_distance'] = $distance_sum;
  $array_vehicule[$slug]['total_time'] = $time_sum;
  $array_vehicule[$slug]['total_volume'] = $volume_sum;
  $array_vehicule[$slug]['total_currency'] = $currency_sum;
  if (count(${"obj_distance_$slug"}) > 0) {
    foreach (${"obj_distance_$slug"} as $fecha => $distance) {
      if (!empty(${"obj_volume_$slug"}[$fecha])) {
        ${"obj_distancexvolume_$slug"}[$fecha] = 0;
        ${"obj_distancexvolume_$slug"}[$fecha] = $distance/${"obj_volume_$slug"}[$fecha];
      }
    }
  }
  if (count(${"obj_time_$slug"}) > 0) {
    foreach (${"obj_time_$slug"} as $fecha => $time) {
      if (!empty(${"obj_volume_$slug"}[$fecha])) {
        ${"obj_timexvolume_$slug"}[$fecha] = 0;
        ${"obj_timexvolume_$slug"}[$fecha] = $time/${"obj_volume_$slug"}[$fecha];
      }
    }
  }
  if (count(${"obj_currency_$slug"}) > 0) {
    foreach (${"obj_currency_$slug"} as $fecha => $currency) {
      if (!empty(${"obj_volume_$slug"}[$fecha])) {
        ${"obj_currencyxvolume_$slug"}[$fecha] = 0;
        ${"obj_currencyxvolume_$slug"}[$fecha] = $currency/${"obj_volume_$slug"}[$fecha];
      }
    }
  }
  $array_distance = []; $array_time = []; $array_volume = []; $array_currency = []; $array_distancexvolume = [];  $array_timexvolume = []; $array_currencyxvolume = [];
  $distance_axis = [];  $time_axis = [];  $volume_axis = [];  $currency_axis = [];  $distancexvolume_axis = [];   $timexvolume_axis = [];  $currencyxvolume_axis = [];
  foreach (${"obj_distance_$slug"} as $date => $distance) { $array_distance[$date]=$distance; array_push($distance_axis,$date); }
  foreach (${"obj_time_$slug"} as $date => $time)         { $array_time[$date]=$time;         array_push($time_axis,$date);     }
  foreach (${"obj_volume_$slug"} as $date => $volume)     { $array_volume[$date]=$volume;     array_push($volume_axis,$date);   }
  foreach (${"obj_currency_$slug"} as $date => $currency) { $array_currency[$date]=$currency; array_push($currency_axis,$date); }
  foreach (${"obj_distancexvolume_$slug"} as $date => $distancexvolume) { $array_distancexvolume[$date]=$distancexvolume; array_push($distancexvolume_axis,$date);}
  foreach (${"obj_timexvolume_$slug"} as $date => $timexvolume) {         $array_timexvolume[$date]=$timexvolume;         array_push($timexvolume_axis,$date);}
  foreach (${"obj_currencyxvolume_$slug"} as $date => $currencyxvolume) { $array_currencyxvolume[$date]=$currencyxvolume; array_push($currencyxvolume_axis,$date);}
  $array_vehicule[$slug]['chartdata']['distance']['data'] = $array_distance;
  $array_vehicule[$slug]['chartdata']['distance']['axis'] = $distance_axis;
  $array_vehicule[$slug]['chartdata']['time']['data'] = $array_time;
  $array_vehicule[$slug]['chartdata']['time']['axis'] = $time_axis;
  $array_vehicule[$slug]['chartdata']['volume']['data'] = $array_volume;
  $array_vehicule[$slug]['chartdata']['volume']['axis'] = $volume_axis;
  $array_vehicule[$slug]['chartdata']['currency']['data'] = $array_currency;
  $array_vehicule[$slug]['chartdata']['currency']['axis'] = $currency_axis;
  
  $array_vehicule[$slug]['chartdata']['distancexvolume']['data'] = $array_distancexvolume;
  $array_vehicule[$slug]['chartdata']['distancexvolume']['axis'] = $distancexvolume_axis;
  $array_vehicule[$slug]['chartdata']['timexvolume']['data'] = $array_timexvolume;
  $array_vehicule[$slug]['chartdata']['timexvolume']['axis'] = $timexvolume_axis;
  $array_vehicule[$slug]['chartdata']['currencyxvolume']['data'] = $array_currencyxvolume;
  $array_vehicule[$slug]['chartdata']['currencyxvolume']['axis'] = $currencyxvolume_axis;
  
};
$distance_piedata = []; $time_piedata = []; $volume_piedata = []; $currency_piedata = [];
$distance_chartdata=[]; $time_chartdata=[]; $volume_chartdata=[]; $currency_chartdata=[];
$distance_xaxis=["x"]; $time_xaxis=["x"]; $volume_xaxis=['x']; $currency_xaxis=['x'];
foreach ($array_vehicule as $slug => $vehicule_data) {
  $distance_piedata[] = [$vehicule_data['name'],$vehicule_data['total_distance']];
  $time_piedata[] = [$vehicule_data['name'],$vehicule_data['total_time']];
  $volume_piedata[] = [$vehicule_data['name'],$vehicule_data['total_volume']];
  $currency_piedata[] = [$vehicule_data['name'],$vehicule_data['total_currency']];
  $distance_xaxis = array_unique(array_merge($distance_xaxis,$vehicule_data['chartdata']['distance']['axis']));
  $time_xaxis = array_unique(array_merge($time_xaxis,$vehicule_data['chartdata']['time']['axis']));
  $volume_xaxis = array_unique(array_merge($volume_xaxis,$vehicule_data['chartdata']['volume']['axis']));
  $currency_xaxis = array_unique(array_merge($currency_xaxis,$vehicule_data['chartdata']['currency']['axis']));
  if (!empty($vehicule_data['total_volume'])) {
    if (!empty($vehicule_data['total_distance'])) {
      $distancexvolume_piedata = [$vehicule_data['name'],($vehicule_data['total_distance']/$vehicule_data['total_volume'])];
    }
  }
}
foreach ($array_vehicule as $slug => $vehicule_data) {
  $distance_predata=[]; //  #################  ARRAY PARA DISTANCE
  for ($i=1; $i < count($distance_xaxis); $i++) {
    if (!empty($vehicule_data['chartdata']['distance']['data'][$distance_xaxis[$i]])) {
      $distance_predata[] = $vehicule_data['chartdata']['distance']['data'][$distance_xaxis[$i]];
    }else{
      $distance_predata[] = null;
    }
  }
  $distance_chartdata[] = array_merge([$vehicule_data['name']],$distance_predata);
  $time_predata=[]; //  #################  ARRAY PARA TIEMPO
  for ($i=1; $i < count($time_xaxis); $i++) { 
    if (!empty($vehicule_data['chartdata']['time']['data'][$time_xaxis[$i]])) {
      $time_predata[] = $vehicule_data['chartdata']['time']['data'][$time_xaxis[$i]];
    }else{
      $time_predata[] = null;
    }
  }
  $time_chartdata[] = array_merge([$vehicule_data['name']],$time_predata);
  $volume_predata=[]; //  #################  ARRAY PARA VOLUMEN
  for ($i=1; $i < count($volume_xaxis); $i++) { 
    if (!empty($vehicule_data['chartdata']['volume']['data'][$volume_xaxis[$i]])) {
      $volume_predata[] = $vehicule_data['chartdata']['volume']['data'][$volume_xaxis[$i]];
    }else{
      $volume_predata[] = null;
    }
  }
  $volume_chartdata[] = array_merge([$vehicule_data['name']],$volume_predata);
  $currency_predata=[]; //  #################  ARRAY PARA CURRENCY
  for ($i=1; $i < count($currency_xaxis); $i++) { 
    if (!empty($vehicule_data['chartdata']['currency']['data'][$currency_xaxis[$i]])) {
      $currency_predata[] = $vehicule_data['chartdata']['currency']['data'][$currency_xaxis[$i]];
    }else{
      $currency_predata[] = null;
    }
  }
  $currency_chartdata[] = array_merge([$vehicule_data['name']],$currency_predata);
}
$distancexvolume_chartdata=[];  $timexvolume_chartdata=[];  $currencyxvolume_chartdata=[];
$distancexvolume_array=[];      $timexvolume_array=[];      $currencyxvolume_array=[];
foreach ($array_vehicule as $slug => $vehicule_data) {
  if (!empty($vehicule_data['chartdata']['volume']['data'])) {
    $distancexvolume_predata=[]; //  #################  ARRAY PARA DISTANCEXVOLUME
    for ($i=1; $i < count($volume_xaxis); $i++) { 
      // if ($date != $volume_xaxis[0]) { 
        if (!empty($vehicule_data['chartdata']['distance']['data'][$volume_xaxis[$i]])) {
          $distancexvolume_predata[] = round($vehicule_data['chartdata']['distance']['data'][$volume_xaxis[$i]]/$vehicule_data['chartdata']['volume']['data'][$volume_xaxis[$i]],2);
          $distancexvolume_array[$slug][$volume_xaxis[$i]]=round($vehicule_data['chartdata']['distance']['data'][$volume_xaxis[$i]]/$vehicule_data['chartdata']['volume']['data'][$volume_xaxis[$i]],2);
        }else{
          $distancexvolume_predata[] = null;
        }
      // }
    }
    $distancexvolume_chartdata[] = array_merge([$vehicule_data['name']],$distancexvolume_predata);
    $timexvolume_predata=[]; //  #################  ARRAY PARA TIMEXVOLUME
    for ($i=1; $i < count($volume_xaxis); $i++) { 
        if (!empty($vehicule_data['chartdata']['time']['data'][$volume_xaxis[$i]])) {
          $timexvolume_predata[] = round($vehicule_data['chartdata']['time']['data'][$volume_xaxis[$i]]/$vehicule_data['chartdata']['volume']['data'][$volume_xaxis[$i]],2);
          $timexvolume_array[$slug][$volume_xaxis[$i]]=round($vehicule_data['chartdata']['time']['data'][$volume_xaxis[$i]]/$vehicule_data['chartdata']['volume']['data'][$volume_xaxis[$i]],2);
        }else{
          $timexvolume_predata[] = null;
        }
    }
    $timexvolume_chartdata[] = array_merge([$vehicule_data['name']],$timexvolume_predata);
    $currencyxvolume_predata=[]; //  #################  ARRAY PARA CURRENCYXVOLUME
    for ($i=1; $i < count($volume_xaxis); $i++) { 
        if (!empty($vehicule_data['chartdata']['currency']['data'][$volume_xaxis[$i]])) {
          $currencyxvolume_predata[] = round($vehicule_data['chartdata']['currency']['data'][$volume_xaxis[$i]]/$vehicule_data['chartdata']['volume']['data'][$volume_xaxis[$i]],2);
          $currencyxvolume_array[$slug][$volume_xaxis[$i]]=round($vehicule_data['chartdata']['currency']['data'][$volume_xaxis[$i]]/$vehicule_data['chartdata']['volume']['data'][$volume_xaxis[$i]],2);
        }else{
          $currencyxvolume_predata[] = null;
        }
    }
    $currencyxvolume_chartdata[] = array_merge([$vehicule_data['name']],$currencyxvolume_predata);
  }
}

$total_distance = 0;  $total_time = 0; $total_volume = 0; $total_currency = 0;
foreach ($array_vehicule as $slug => $vehicule) {
  $total_distance += $vehicule['total_distance'];
  $total_time += $vehicule['total_time'];
  $total_volume += $vehicule['total_volume'];
  $total_currency += $vehicule['total_currency'];
}
if ($total_distance === 0 && $total_time === 0 && $total_volume === 0 && $total_currency === 0) {
  $alert_warning .= '/No hay datos para mostrar, cambie las fechas o los vehiculos.';
}

$unit = (!empty($data_unit)) ? json_decode($data_unit, true) : json_decode('{"distance":"Km","time":"Hr","volume":"Ltr","currency":"B/"}',true);
// @##############    ARRAYS PARA PPTX
$distance_pptdata=[];
foreach ($distance_chartdata as $a => $array_data) {
  $raw_array = [];
  foreach ($array_data as $key => $value) {
    if ($value === $array_data[0]) {
      $raw_array['name'] = $value;
    }else{
      // $raw_array['labels'][]=$key-1;
      $raw_array['values'][]=$value;
    }
  }
  $distance_x = $distance_xaxis;
  array_splice($distance_x, 0, 1);
  $raw_array['labels'] = $distance_x;
  $distance_pptdata[] = $raw_array;
}
$time_pptdata=[];
foreach ($time_chartdata as $a => $array_data) {
  $raw_array = [];
  foreach ($array_data as $key => $value) {
    if ($value === $array_data[0]) {
      $raw_array['name'] = $value;
    }else{
      $raw_array['values'][]=$value;
    }
  }
  $time_x = $time_xaxis;
  array_splice($time_x, 0, 1);
  $raw_array['labels'] = $time_x;
  $time_pptdata[] = $raw_array;
}
$volume_pptdata=[];
foreach ($volume_chartdata as $a => $array_data) {
  $raw_array = [];
  foreach ($array_data as $key => $value) {
    if ($value === $array_data[0]) {
      $raw_array['name'] = $value;
    }else{
      $raw_array['values'][]=$value;
    }
  }
  $volume_x = $volume_xaxis;
  array_splice($volume_x, 0, 1);
  $raw_array['labels'] = $volume_x;
  $volume_pptdata[] = $raw_array;
}
$currency_pptdata=[];
foreach ($currency_chartdata as $a => $array_data) {
  $raw_array = [];
  foreach ($array_data as $key => $value) {
    if ($value === $array_data[0]) {
      $raw_array['name'] = $value;
    }else{
      $raw_array['values'][]=$value;
    }
  }
  $currency_x = $currency_xaxis;
  array_splice($currency_x, 0, 1);
  $raw_array['labels'] = $currency_x;
  $currency_pptdata[] = $raw_array;
}
$distancexvolume_pptdata=[];
foreach ($distancexvolume_chartdata as $a => $array_data) {
  $raw_array = [];
  foreach ($array_data as $key => $value) {
    if ($value === $array_data[0]) {
      $raw_array['name'] = $value;
    }else{
      $raw_array['values'][]=$value;
    }
  }
  $volume_x = $volume_xaxis;
  array_splice($volume_x, 0, 1);
  $raw_array['labels'] = $volume_x;
  $distancexvolume_pptdata[] = $raw_array;
}
$timexvolume_pptdata=[];
foreach ($timexvolume_chartdata as $a => $array_data) {
  $raw_array = [];
  foreach ($array_data as $key => $value) {
    if ($value === $array_data[0]) {
      $raw_array['name'] = $value;
    }else{
      $raw_array['values'][]=$value;
    }
  }
  $volume_x = $volume_xaxis;
  array_splice($volume_x, 0, 1);
  $raw_array['labels'] = $volume_x;
  $timexvolume_pptdata[] = $raw_array;
}
$currencyxvolume_pptdata=[];
foreach ($currencyxvolume_chartdata as $a => $array_data) {
  $raw_array = [];
  foreach ($array_data as $key => $value) {
    if ($value === $array_data[0]) {
      $raw_array['name'] = $value;
    }else{
      $raw_array['values'][]=$value;
    }
  }
  $volume_x = $volume_xaxis;
  array_splice($volume_x, 0, 1);
  $raw_array['labels'] = $volume_x;
  $currencyxvolume_pptdata[] = $raw_array;
}

$ppt_data=['distance'=>$distance_pptdata,'time'=>$time_pptdata, 'volume'=>$volume_pptdata, 'currency'=>$currency_pptdata,'distancexvolume'=>$distancexvolume_pptdata,'timexvolume'=>$timexvolume_pptdata,'currencyxvolume'=>$currencyxvolume_pptdata];
?>    
<div id="toast_container" style="position: fixed; top: 130px; right: 100px; z-index: 10"></div>
  <!-- Modal -->
<div class="modal fade" id="modal_efficiency" tabindex="-1" role="dialog" aria-labelledby="efficiency" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Elija las Opciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <label for="txt_efficiency_from">Desde</label>
            <input type="text" id="txt_efficiency_from" class="form-control" placeholder="" value="{{ date('d-m-Y', strtotime('-1 week', strtotime($date_today)))}}" readonly>
          </div>
          <div class="col-sm-12 col-md-6">
            <label for="txt_efficiency_until">Hasta</label>
            <input type="text" id="txt_efficiency_until" class="form-control" placeholder="" value="{{ date('d-m-Y', strtotime('+1 week', strtotime($date_today)))}}" readonly>
          </div>
          
          <div class="col-sm-6" style="height:300px; overflow:auto;">
            <label for="txt_licenseplate">Matricula Vehicular</label>
            @foreach ($rs_vehicule as $item)
              <div  class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" value="{{ $item['tx_vehicule_slug'] }}" id="{{ $item['tx_vehicule_slug'] }}">
                <label class="custom-control-label" for="{{ $item['tx_vehicule_slug'] }}">
                  {{ $item['tx_vehicule_licenseplate'] }}
                </label>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-lg btn-primary" id="btn_efficiency_report"><i class="fas fa-chart-line"></i> Procesar</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->   
<div class="row mr-0 pt-3">    
  <div id="body_panel" data-spy="scroll" data-target="#side_panel" data-offset="0" class="col-sm-12 col-md-10">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Tablero de Resultados</h1>
      <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
          <button type="button"  data-toggle="modal" data-target="#modal_efficiency" class="btn btn-sm btn-outline-secondary">Modificar Opciones</button>
          <button type="button" onclick="cls_report.new_report({{ json_encode($ppt_data) }})" class="btn btn-sm btn-outline-secondary">Exportar</button>
        </div>
      </div>
    </div>
<?php
    if ($alert_warning != '') {
      $array_alert=explode('/',$alert_warning);
      foreach ($array_alert as $key => $alert) {
        if ($alert != '') {
          echo "
          <div class='alert alert-warning' role='alert'>
            $alert    
          </div>";
        }
      }
    }
        ?>
    <h5 id="list-item-1">Totales</h4>
    <div class="row">
      <div class="col-sm-6 col-md-3 border-1 div_total d-flex justify-content-center p-0 border-right">
        <div class="thumbnail"> <i class="fas fa-truck"></i> </div>
        <div>Distancia Total  <br /> {{ $total_distance }} {{ $unit['distance'] }}</div>
      </div>
      <div class="col-sm-6 col-md-3 border-1 div_total d-flex justify-content-center p-0 border-right">
        <div class="thumbnail"> <i class="fas fa-clock"></i> </div>
        <div>Tiempo Total     <br /> {{ $total_time }} {{ $unit['time'] }}</div>
      </div>
      <div class="col-sm-6 col-md-3 border-1 div_total d-flex justify-content-center p-0 border-right">
        <div class="thumbnail"> <i class="fas fa-gas-pump"></i> </div>
        <div>Surtido Total    <br /> {{ $total_volume }} {{ $unit['volume'] }}</div>
      </div>
      <div class="col-sm-6 col-md-3 border-1 div_total d-flex justify-content-center p-0">
        <div class="thumbnail"> <i class="fas fa-coins"></i> </div>
        <div>Dinero Total     <br /> {{ $total_currency }} {{ $unit['currency'] }}</div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-3 p-0" id="pie_distance"></div>
      <div class="col-sm-3 p-0" id="pie_time"></div>
      <div class="col-sm-3 p-0" id="pie_volume"></div>
      <div class="col-sm-3 p-0" id="pie_currency"></div>
    </div>
    <h4 id="list-item-2">Gr&aacute;ficas Simples</h4>
    <div class="pt-3">
      <span class="font-weight-bold">Distancia 
        <button type="button" class="btn btn-default" onclick="chart_distance.transform('line');"><i class="fas fa-chart-line" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_distance.transform('area-spline');"><i class="fas fa-chart-area" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_distance.transform('bar');"><i class="fas fa-chart-bar" ></i></button>
      </span>
    </div>
    <div class="row">
      <div class="col-sm-12 p-0" id="distance_chart"></div>
    </div>
    <div class="row pb-2 border-bottom">
      <?php   foreach ($array_vehicule as $slug => $vehicule_data) {  ?>
        <div class="col-sm-2 p-2 border-right" id="table_data_top">
          <span class="font-weight-bold">{{ $vehicule_data['name'] }}</span>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">Fecha</th>
                <th scope="col">{{$unit['distance']}}</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($vehicule_data['chartdata']['distance']['data'] as $date => $value) {
                echo "<tr><td>".date('d-m-Y',strtotime($date))."</td><td class='text-center'>{$value}</td></tr>";
              } ?>
            <tbody>
          </table>
        </div>
      <?php } ?>
    </div>
    <div class="pt-3">
      <span class="font-weight-bold">Tiempo
        <button type="button" class="btn btn-default" onclick="chart_time.transform('line');"><i class="fas fa-chart-line" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_time.transform('area-spline');"><i class="fas fa-chart-area" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_time.transform('bar');"><i class="fas fa-chart-bar" ></i></button>
      </span>
    </div>
    <div class="row">
      <div class="col-sm-12 p-0" id="time_chart"></div>
    </div>
    <div class="row pb-2 border-bottom">
      <?php   foreach ($array_vehicule as $slug => $vehicule_data) {  ?>
        <div class="col-sm-2 p-2 border-right" id="table_data_top">
          <span class="font-weight-bold">{{ $vehicule_data['name'] }}</span>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">Fecha</th>
                <th scope="col">{{$unit['time']}}</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($vehicule_data['chartdata']['time']['data'] as $date => $value) {
                echo "<tr><td>".date('d-m-Y',strtotime($date))."</td><td class='text-center'>{$value}</td></tr>";
              } ?>
            <tbody>
          </table>
        </div>
      <?php } ?>
    </div>
    <div class="pt-3">
      <span class="font-weight-bold">Surtido
        <button type="button" class="btn btn-default" onclick="chart_volume.transform('line');"><i class="fas fa-chart-line" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_volume.transform('area-spline');"><i class="fas fa-chart-area" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_volume.transform('bar');"><i class="fas fa-chart-bar" ></i></button>
      </span>
    </div>
    <div class="row">
      <div class="col-sm-12 p-0" id="volume_chart"></div>
    </div>
    <div class="row pb-2 border-bottom">
      <?php   foreach ($array_vehicule as $slug => $vehicule_data) {  ?>
        <div class="col-sm-2 p-2 border-right" id="table_data_top">
          <span class="font-weight-bold">{{ $vehicule_data['name'] }}</span>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">Fecha</th>
                <th scope="col">{{$unit['volume']}}</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($vehicule_data['chartdata']['volume']['data'] as $date => $value) {
                echo "<tr><td>".date('d-m-Y',strtotime($date))."</td><td class='text-center'>{$value}</td></tr>";
              } ?>
            <tbody>
          </table>
        </div>
      <?php } ?>
    </div>
    <div class="pt-3">
      <span class="font-weight-bold">Dinero
        <button type="button" class="btn btn-default" onclick="chart_currency.transform('line');"><i class="fas fa-chart-line" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_currency.transform('area-spline');"><i class="fas fa-chart-area" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_currency.transform('bar');"><i class="fas fa-chart-bar" ></i></button>
      </span>
    </div>
    <div class="row">
      <div class="col-sm-12 p-0" id="currency_chart"></div>
    </div>
    <div class="row pb-2 border-bottom">
      <?php   foreach ($array_vehicule as $slug => $vehicule_data) {  ?>
        <div class="col-sm-2 p-2 border-right" id="table_data_top">
          <span class="font-weight-bold">{{ $vehicule_data['name'] }}</span>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">Fecha</th>
                <th scope="col">{{$unit['currency']}}</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($vehicule_data['chartdata']['currency']['data'] as $date => $value) {
                echo "<tr><td>".date('d-m-Y',strtotime($date))."</td><td class='text-center'>{$value}</td></tr>";
              } ?>
            <tbody>
          </table>
        </div>
      <?php } ?>
    </div>
    <h4 id="list-item-3">Gr&aacute;ficas Combinadas</h4>
    <div class="pt-3">
      <span class="font-weight-bold">Eficiencia de Distancia sobre Surtido
        <button type="button" class="btn btn-default" onclick="chart_distancexvolume.transform('line');"><i class="fas fa-chart-line" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_distancexvolume.transform('area-spline');"><i class="fas fa-chart-area" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_distancexvolume.transform('bar');"><i class="fas fa-chart-bar" ></i></button>
      </span>
    </div>
    <div class="row">
      <div class="col-sm-12 p-0" id="distancexvolume_chart"></div>
    </div>
    <div class="row pb-2 border-bottom">
      <?php   foreach ($distancexvolume_array as $slug => $distancexvolume) {  ?>
        <div class="col-sm-2 p-2 border-right" id="table_data_top">
          <span class="font-weight-bold">{{ $vehicule_data['name'] }}</span>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">Fecha</th>
                <th scope="col"> {{$unit['distance']}}/{{$unit['volume']}}</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($distancexvolume as $date => $value) {
                echo "<tr><td>".date('d-m-Y',strtotime($date))."</td><td class='text-center'>{$value}</td></tr>";
              } ?>
            <tbody>
          </table>
        </div>
      <?php } ?>
    </div>
    <div class="pt-3">
      <span class="font-weight-bold">Eficiencia de Tiempo sobre Surtido
        <button type="button" class="btn btn-default" onclick="chart_timexvolume.transform('line');"><i class="fas fa-chart-line" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_timexvolume.transform('area-spline');"><i class="fas fa-chart-area" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_timexvolume.transform('bar');"><i class="fas fa-chart-bar" ></i></button>
      </span>
    </div>
    <div class="row">
      <div class="col-sm-12 p-0" id="timexvolume_chart"></div>
    </div>
    <div class="row pb-2 border-bottom">
      <?php   foreach ($timexvolume_array as $slug => $timexvolume) {  ?>
        <div class="col-sm-2 p-2 border-right" id="table_data_top">
          <span class="font-weight-bold">{{ $vehicule_data['name'] }}</span>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">Fecha</th>
                <th scope="col">{{$unit['time']}}/{{$unit['volume']}}</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($timexvolume as $date => $value) {
                echo "<tr><td>".date('d-m-Y',strtotime($date))."</td><td class='text-center'>{$value}</td></tr>";
              } ?>
            <tbody>
          </table>
        </div>
      <?php } ?>
    </div>
    <div class="pt-3">
      <span class="font-weight-bold">Eficiencia de Dinero sobre Surtido
        <button type="button" class="btn btn-default" onclick="chart_currencyxvolume.transform('line');"><i class="fas fa-chart-line" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_currencyxvolume.transform('area-spline');"><i class="fas fa-chart-area" ></i></button>
        <button type="button" class="btn btn-default" onclick="chart_currencyxvolume.transform('bar');"><i class="fas fa-chart-bar" ></i></button>
      </span>
    </div>
    <div class="row">
      <div class="col-sm-12 p-0" id="currencyxvolume_chart"></div>
    </div>
    <div class="row pb-2 border-bottom">
      <?php   foreach ($currencyxvolume_array as $slug => $currencyxvolume) {  ?>
        <div class="col-sm-2 p-2 border-right" id="table_data_top">
          <span class="font-weight-bold">{{ $vehicule_data['name'] }}</span>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">Fecha</th>
                <th scope="col">{{$unit['currency']}}/{{$unit['volume']}}</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($currencyxvolume as $date => $value) {
                echo "<tr><td>".date('d-m-Y',strtotime($date))."</td><td class='text-center'>{$value}</td></tr>";
              } ?>
            <tbody>
          </table>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
  @endsection
  @section('optional_javascript')
  <link rel="stylesheet" href="{{ URL::asset('attached/css/report.css')}}">
  <link rel="stylesheet" href="{{ URL::asset('attached/js/c3_js/c3.css')}}">
	<script type="text/javascript" src="{{ URL::asset('attached/js/report.js')}}"></script>
  <script type="text/javascript" src="{{ URL::asset('attached/js/data_sample.js')}}"></script>
  <script type="text/javascript" src="{{ URL::asset('attached/js/c3_js/c3.min.js')}}"></script>
  <script type="text/javascript" src="{{ URL::asset('attached/js/c3_js/docs/js/d3-5.8.2.min.js')}}"></script>
  <script type="text/javascript" src="{{ URL::asset('attached/js/moment.js')}}"></script>
	<script src="https://cdn.jsdelivr.net/gh/gitbrent/pptxgenjs@2.5.0/libs/jszip.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/gitbrent/pptxgenjs@2.5.0/dist/pptxgen.min.js"></script>
  
  <script type="text/javascript">
  const cls_general_funct = new general_funct;
  const cls_report = new class_report(<?php echo json_encode($rs_vehicule); ?>);
  const cls_data_sample = new data_sample (<?php echo json_encode($unit); ?>);
  data_sample.prototype.unit = cls_data_sample.unit;
  class_report.prototype.vehicule_list = cls_report.vehicule_list;
  $( function() {
    var dateFormat = "dd-mm-yy",
    from = $( "#txt_efficiency_from" )
    .datepicker({ defaultDate: "+1w", changeMonth: true, numberOfMonths: 2, dateFormat: 'dd-mm-yy' })
    .on( "change", function() {
      to.datepicker( "option", "minDate", getDate( this ) );
    }),
    to = $( "#txt_efficiency_until" ).datepicker({ defaultDate: "+1w", changeMonth: true, numberOfMonths: 2, dateFormat: 'dd-mm-yy' })
    .on( "change", function() {
      from.datepicker( "option", "maxDate", getDate( this ) );
    });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
      return date;
    }
  }); 
  var distance_piedata = <?php echo json_encode($distance_piedata)?>;  
  var chart = c3.generate({ bindto: '#pie_distance',  padding: {top: 60, right: 150, bottom: 60, left: 150,}, size: { height: 300},
    data: {
      columns: distance_piedata,
      type : 'pie',
    },
    labels: {
      format: function (v, id, i, j) { return v; },
    }
  });
  var volume_piedata = <?php echo json_encode($volume_piedata)?>;  
  var chart = c3.generate({ bindto: '#pie_volume',  padding: {top: 60, right: 150, bottom: 60, left: 150,},
    data: {
      columns: volume_piedata,
      type : 'pie',
    }
  });
  var time_piedata = <?php echo json_encode($time_piedata)?>;  
  var chart = c3.generate({ bindto: '#pie_time',  padding: {top: 60, right: 150, bottom: 60, left: 150,},
    data: {
      columns: time_piedata,
      type : 'pie',
    }
  });
  var currency_piedata = <?php echo json_encode($currency_piedata)?>;
  var chart = c3.generate({ bindto: '#pie_currency',  padding: {top: 60, right: 150, bottom: 60, left: 150,},
    data: {
      columns: currency_piedata,
      type : 'pie',
    }
  });
  var distance_chartdata = <?php echo json_encode($distance_chartdata); ?>;
  var group_distance = [];
  for (const a in distance_chartdata) { group_distance.push(distance_chartdata[a][0]);  }
  var obj_distance_xaxis = <?php unset($distance_xaxis[0]); echo json_encode($distance_xaxis); ?>;
  var distance_xaxis = [];
  for (const a in obj_distance_xaxis) { distance_xaxis.push(cls_general_funct.date_converter('ymd','dmy',obj_distance_xaxis[a])); }
  var chart_distance = c3.generate({ bindto: '#distance_chart',
    data: { columns: distance_chartdata,  type: 'bar', groups: [ group_distance ],  labels: true, },
    axis: {
        x: {
            type: 'category',
            categories: distance_xaxis
        }
    },
    bar: {  width: {  ratio: 0.8  } },
    tooltip: { show: false  },
  });
  var time_chartdata = <?php echo json_encode($time_chartdata); ?>;
  var obj_time_xaxis = <?php unset($time_xaxis[0]); echo json_encode($time_xaxis); ?>;
  var time_xaxis = [];
  for (const a in obj_time_xaxis) { time_xaxis.push(cls_general_funct.date_converter('ymd','dmy',obj_time_xaxis[a])); }
  var chart_time = c3.generate({ bindto: '#time_chart',
    data: { columns: time_chartdata,  type: 'bar', groups: [ group_distance ],  labels: true, },
    axis: {
        x: {
            type: 'category',
            categories: time_xaxis
        }
    },
    bar: {  width: {  ratio: 0.8  } },
    tooltip: { show: false },
  });
  var volume_chartdata = <?php echo json_encode($volume_chartdata); ?>;
  var obj_volume_xaxis = <?php unset($volume_xaxis[0]); echo json_encode($volume_xaxis); ?>;
  var volume_xaxis = [];
  for (const a in obj_volume_xaxis) { volume_xaxis.push(cls_general_funct.date_converter('ymd','dmy',obj_volume_xaxis[a])); }
  var chart_volume = c3.generate({ bindto: '#volume_chart',
    data: { columns: volume_chartdata,  type: 'bar', groups: [ group_distance ],  labels: true, },
    axis: {
        x: {
            type: 'category',
            categories: volume_xaxis
        }
    },
    bar: {  width: {  ratio: 0.8  } },
    tooltip: { show: false },
  });
  var currency_chartdata = <?php echo json_encode($currency_chartdata); ?>;
  var obj_currency_xaxis = <?php unset($currency_xaxis[0]); echo json_encode($currency_xaxis); ?>;
  var currency_xaxis = [];
  for (const a in obj_currency_xaxis) { currency_xaxis.push(cls_general_funct.date_converter('ymd','dmy',obj_currency_xaxis[a])); }
  var chart_currency = c3.generate({ bindto: '#currency_chart',
    data: { columns: currency_chartdata,  type: 'bar', groups: [ group_distance ],  labels: true, },
    axis: {
        x: {
            type: 'category',
            categories: currency_xaxis
        }
    },
    bar: {  width: {  ratio: 0.8  } },
    tooltip: { show: false },
  });

  var distancexvolume_chartdata = <?php echo json_encode($distancexvolume_chartdata); ?>;
  var obj_volume_xaxis = <?php unset($volume_xaxis[0]); echo json_encode($volume_xaxis); ?>;
  var volume_xaxis = [];
  for (const a in obj_volume_xaxis) { volume_xaxis.push(cls_general_funct.date_converter('ymd','dmy',obj_volume_xaxis[a])); }
  var chart_distancexvolume = c3.generate({ bindto: '#distancexvolume_chart',
    data: { columns: distancexvolume_chartdata,  type: 'bar', groups: [ group_distance ],  labels: true, },
    axis: {
        x: {
            type: 'category',
            categories: volume_xaxis
        }
    },
    bar: {  width: {  ratio: 0.8  } },
    tooltip: { show: false },
  });
  var timexvolume_chartdata = <?php echo json_encode($timexvolume_chartdata); ?>;
  var obj_volume_xaxis = <?php unset($volume_xaxis[0]); echo json_encode($volume_xaxis); ?>;
  var volume_xaxis = [];
  for (const a in obj_volume_xaxis) { volume_xaxis.push(cls_general_funct.date_converter('ymd','dmy',obj_volume_xaxis[a])); }
  var chart_timexvolume = c3.generate({ bindto: '#timexvolume_chart',
    data: { columns: timexvolume_chartdata,  type: 'bar', groups: [ group_distance ],  labels: true, },
    axis: {
        x: {
            type: 'category',
            categories: volume_xaxis
        }
    },
    bar: {  width: {  ratio: 0.8  } },
    tooltip: { show: false },
  });
  var currencyxvolume_chartdata = <?php echo json_encode($currencyxvolume_chartdata); ?>;
  var obj_volume_xaxis = <?php unset($volume_xaxis[0]); echo json_encode($volume_xaxis); ?>;
  var volume_xaxis = [];
  for (const a in obj_volume_xaxis) { volume_xaxis.push(cls_general_funct.date_converter('ymd','dmy',obj_volume_xaxis[a])); }
  var chart_currencyxvolume = c3.generate({ bindto: '#currencyxvolume_chart',
    data: { columns: currencyxvolume_chartdata,  type: 'bar', groups: [ group_distance ],  labels: true, },
    axis: {
        x: {
            type: 'category',
            categories: volume_xaxis
        }
    },
    bar: {  width: {  ratio: 0.8  } },
    tooltip: { show: false },
  });

  $("#btn_efficiency_report").on('click', function(){
    cls_report.redirect_dashboard();
  })

  </script>
@endsection


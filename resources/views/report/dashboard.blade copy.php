@extends('layouts.internal')

@section('content')
<?php
$date_today = date('d-m-Y'); 
$array_data = $compacted['data'];
$qty_data = 0;  $ttl_distance = 0;
$array_vehicule = []; //   ARREGLOS DE VEHICULOS
foreach ($array_data as $slug => $data) { 
  ${"obj_distance_$slug"} = []; ${"obj_time_$slug"} = []; ${"obj_volume_$slug"} = []; ${"obj_currency_$slug"} = []; ${"obj_distancexvolume_$slug"} = []; ${"obj_timexvolume_$slug"} = []; ${"obj_currencyxvolume_$slug"} = [];
  $qty_data++; 
  $distance_sum = 0;  $time_sum = 0; $volume_sum = 0; $currency_sum = 0;

  foreach ($data['datasample'] as $key => $array_data) {
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
  foreach (${"obj_distance_$slug"} as $date => $distance) { array_push($distance_axis,$date); array_push($array_distance,$distance); }
  foreach (${"obj_time_$slug"} as $date => $time)         { array_push($time_axis,$date);     array_push($array_time,$time);         }
  foreach (${"obj_volume_$slug"} as $date => $volume)     { array_push($volume_axis,$date);   array_push($array_volume,$volume);     }
  foreach (${"obj_currency_$slug"} as $date => $currency) { array_push($currency_axis,$date); array_push($array_currency,$currency); }
  foreach (${"obj_distancexvolume_$slug"} as $date => $distancexvolume) { array_push($distancexvolume_axis,$date); array_push($array_distancexvolume,$distancexvolume); }
  foreach (${"obj_timexvolume_$slug"} as $date => $timexvolume) { array_push($timexvolume_axis,$date); array_push($array_timexvolume,$timexvolume); }
  foreach (${"obj_currencyxvolume_$slug"} as $date => $currencyxvolume) { array_push($currencyxvolume_axis,$date); array_push($array_currencyxvolume,$currencyxvolume); }
  $array_vehicule[$slug]['chartdata']['distance']['data'] = array_merge([$data['vehicule']],$array_distance);
  $array_vehicule[$slug]['chartdata']['distance']['axis'] = $distance_axis;
  $array_vehicule[$slug]['chartdata']['time']['data'] = array_merge([$data['vehicule']],$array_time);
  $array_vehicule[$slug]['chartdata']['time']['axis'] = $time_axis;
  $array_vehicule[$slug]['chartdata']['volume']['data'] = array_merge([$data['vehicule']],$array_volume);
  $array_vehicule[$slug]['chartdata']['volume']['axis'] = $volume_axis;
  $array_vehicule[$slug]['chartdata']['currency']['data'] = array_merge([$data['vehicule']],$array_currency);
  $array_vehicule[$slug]['chartdata']['currency']['axis'] = $currency_axis;
  
  $array_vehicule[$slug]['chartdata']['distancexvolume']['data'] = array_merge([$data['vehicule']],$array_distancexvolume);
  $array_vehicule[$slug]['chartdata']['distancexvolume']['axis'] = $distancexvolume_axis;
  $array_vehicule[$slug]['chartdata']['timexvolume']['data'] = array_merge([$data['vehicule']],$array_timexvolume);
  $array_vehicule[$slug]['chartdata']['timexvolume']['axis'] = $timexvolume_axis;
  $array_vehicule[$slug]['chartdata']['currencyxvolume']['data'] = array_merge([$data['vehicule']],$array_currencyxvolume);
  $array_vehicule[$slug]['chartdata']['currencyxvolume']['axis'] = $currencyxvolume_axis;
  
};
//             ['data1', 30, 20, 50, 40, 60, 50],
//             ['data2', 200, 130, 90, 240, 130, 220],
//             ['data3', 300, 200, 160, 400, 250, 250],
//             ['data4', 200, 130, 90, 240, 130, 220],
//             ['data5', 130, 120, 150, 140, 160, 150],
//             ['data6', 90, 70, 20, 50, 60, 120],
// echo json_encode($array_vehicule);
?>    
<div id="toast_container" style="position: fixed; top: 130px; right: 100px; z-index: 10">
  </div>
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
            
            
            <div class="col-sm-12 col-md-6">
              Gr&aacute;ficos
              <div class="custom-control custom-checkbox pl-4">
                <input class="custom-control-input" type="checkbox" value="chart_line" id="cb_efficiency_chartline" checked>
                <label class="custom-control-label" for="cb_efficiency_chartline">
                  Lineal
                </label>
              </div>
              <div class="custom-control custom-checkbox pl-4">
                <input class="custom-control-input" type="checkbox" value="chart_bar" id="cb_efficiency_chartbar">
                <label class="custom-control-label" for="cb_efficiency_chartbar">
                  Barras
                </label>
              </div>
              <div class="custom-control custom-checkbox pl-4">
                <input class="custom-control-input" type="checkbox" value="chart_pie" id="cb_efficiency_chartpie">
                <label class="custom-control-label" for="cb_efficiency_chartpie">
                  Circular
                </label>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="type" class="btn btn-lg btn-primary" id="btn_efficiency_report"><i class="fas fa-chart-line"></i> Procesar</button>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row mr-0">
    <div id="side_panel" class="col-sm-12 col-md-2">
      <div class="nav nav-pills flex-column">
        <a class="nav-link" href="#list-item-1"><i class="far fa-circle"></i> <span>Item 1 </span><i class="menu-arrow fas fa-arrow-right"></i></a>
        <a class="nav-link" href="#list-item-2"><i class="far fa-circle"></i> <span>Item 1 </span><i class="fas fa-arrow-right"></i></a>
        <a class="nav-link" href="#list-item-3"><i class="far fa-circle"></i> <span>Item 1 </span><i class="fas fa-arrow-right"></i></a>
        <a class="nav-link" href="#list-item-3"><i class="far fa-circle"></i> <span>Item 1 </span><i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
    
    
    <div data-spy="scroll" data-target="#side_panel" data-offset="0" class="scrollspy-example col-sm-12 col-md-10">
      <h5 id="list-item-1">Totales</h4>
      <div class="row">
        <?php $total_distance = 0;  $total_time = 0; $total_volume = 0; $total_currency = 0;
        foreach ($array_vehicule as $slug => $vehicule) {
          $total_distance = $vehicule['total_distance'];
          $total_time = $vehicule['total_time'];
          $total_volume = $vehicule['total_volume'];
          $total_currency = $vehicule['total_currency'];
        } 
        ?>
        <div class="col-sm-6 col-md-3 border-1 div_total d-flex justify-content-center p-0 border-right">
          <div class="thumbnail"> <i class="fas fa-truck"></i> </div>
          <div>Distancia Total  <br /> {{ $total_distance }} </div>
        </div>
        <div class="col-sm-6 col-md-3 border-1 div_total d-flex justify-content-center p-0 border-right">
          <div class="thumbnail"> <i class="fas fa-clock"></i> </div>
          <div>Tiempo Total     <br /> {{ $total_time }} </div>
        </div>
        <div class="col-sm-6 col-md-3 border-1 div_total d-flex justify-content-center p-0 border-right">
          <div class="thumbnail"> <i class="fas fa-gas-pump"></i> </div>
          <div>Surtido Total    <br /> {{ $total_volume }} </div>
        </div>
        <div class="col-sm-6 col-md-3 border-1 div_total d-flex justify-content-center p-0">
          <div class="thumbnail"> <i class="fas fa-coins"></i> </div>
          <div>Dinero Total     <br /> {{ $total_currency }} </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3 p-0" id="pie_distance"></div>
        <div class="col-sm-3 p-0" id="pie_time"></div>
        <div class="col-sm-3 p-0" id="pie_volume"></div>
        <div class="col-sm-3 p-0" id="pie_currency"></div>
      </div>
      <h5 id="list-item-2">Graficos Lineales</h4>
      <div class="row">
        <div class="col-sm-8 p-0" id="distance_chart"></div>
        <div class="col-sm-4 p-0" id="table_data_top"></div>
      </div>
      <br />
      <br />
      <br />
      <p>...</p>
      <h4 id="list-item-3">Item 3</h4>
      <p>...</p>
      <h4 id="list-item-4">Item 4</h4>
      <p>...</p>
    </div>
    
  </div>


  <?php
  // echo json_encode($array_vehicule);
  $distance_piedata = []; $time_piedata = []; $volume_piedata = []; $currency_piedata = [];
  $distance_chartdata = []; 
  $distance_xaxis = ["x"];
  foreach ($array_vehicule as $slug => $vehicule_data) {
    $distance_piedata[] = [$vehicule_data['name'],$vehicule_data['total_distance']];
    $time_piedata[] = [$vehicule_data['name'],$vehicule_data['total_time']];
    $volume_piedata[] = [$vehicule_data['name'],$vehicule_data['total_volume']];
    $currency_piedata[] = [$vehicule_data['name'],$vehicule_data['total_currency']];
    
    $distance_chartdata[] = $vehicule_data['chartdata']['distance']['data'];
    $distance_xaxis = array_merge($distance_xaxis,$vehicule_data['chartdata']['distance']['axis']);
    if ($vehicule_data === end($array_vehicule)) {
      $distance_chartdata[] = $distance_xaxis;
    }
  }
  
  // $distance_count = 0;
  // foreach ($distance_chartdata as $a => $chartdata) {
  //   if ($chartdata != end($distance_chartdata)) {
  //     if (count($chartdata) > $distance_count) {
  //       $distance_count = count($chartdata);
  //     }
  //   }
  // }
  // foreach ($distance_chartdata as $a => $chartdata) {
  //   if ($chartdata != end($distance_chartdata)) {
  //     for ($i=0; $i < $distance_count; $i++) { 
  //       if (empty($distance_chartdata[$a][$i])) {
  //         $distance_chartdata[$a][$i] = "0";
  //       }
  //     }
  //   }else{
  //     // $distance_chartdata[$a] = array_splice($distance_chartdata[$a],-1,1);
  //   }
  //   // else{
  //   //   $last_chartdata = end($distance_chartdata);
  //   //   $chartdata_count = count($last_chartdata);
  //   //   $slice_count = $chartdata_count - $distance_count;

  //   // }
  // }

  echo json_encode($distance_chartdata);
  ?>

  @endsection
  @section('optional_javascript')
  <link rel="stylesheet" href="{{ URL::asset('attached/css/report.css')}}">
  <link rel="stylesheet" href="{{ URL::asset('attached/js/c3_js/c3.css')}}">
	<script type="text/javascript" src="{{ URL::asset('attached/js/report.js')}}"></script>
  <script type="text/javascript" src="{{ URL::asset('attached/js/data_sample.js')}}"></script>
  <script type="text/javascript" src="{{ URL::asset('attached/js/c3_js/c3.min.js')}}"></script>
  <script type="text/javascript" src="{{ URL::asset('attached/js/c3_js/docs/js/d3-5.8.2.min.js')}}"></script>
  
  <script type="text/javascript">
  const cls_general_funct = new general_funct;
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
  var distance_chartdata = <?php echo json_encode($distance_chartdata)?>;
  var chart = c3.generate({ bindto: '#distance_chart',
    data: {
      x: 'x',
      columns: distance_chartdata,
      // [
      //   // ['x', "2013-01-01", "2013-01-02", "2013-01-03", "2013-01-06"],
      //   ['sample', 30, 200, 100, 400],
      //   ['sample2', 130, 210, 120, 10]
      // ],
      type: 'bar',
      labels: true,
    },
    bar: {
      width: {
        ratio: 0.5 // this makes bar width 50% of length between ticks
      }
    },
    tooltip: { 
      show: false
    },
    axis: {
      x: {
        type: 'timeseries',
        height: 130,
        tick: {
          fit: false,  
          rotate: 90,
          multiline: false,
          count: 2,
          format: '%d-%m-%Y'
        }
      }
    },
    bar: {
      width: {
        ratio: 0.3 // this makes bar width 50% of length between ticks
      }
    }
  });



  $("#btn_efficiency_report").on('click', function(){
    cls_report.redirect_dashboard();
  })
  </script>

@endsection


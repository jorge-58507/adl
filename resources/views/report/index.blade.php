@extends('layouts.inside')

@section('content')
<?php
$date_today = date('d-m-Y'); 
$array_option = json_decode($compacted['option'], true);
$array_unit = json_decode($array_option[0]['tx_option_unit'], true);
$rs_vehicule = $compacted['vehicule_list'];
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
        <button type="type" class="btn btn-lg btn-primary" id="btn_efficiency_report"><i class="fas fa-chart-line"></i> Procesar</button>
      </div>
    </div>
  </div>
</div>
      
<div class="container py-4">
  <div class="row">
    <div class="col-sm-12 col-md-4 col-lg-3">
      <div class="card" style="width: 18rem;">
        <img src="{{ URL::asset('/attached/image/fuel_efficiency.jpg')}}" width="200px" height="200px" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Informe Eficiencia</h5>
          <p class="card-text">Informe sobre los consumos y usos del combustible, ademas de la eficiencia de uso.</p>
          <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal_efficiency">Obtener</a>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@section('optional_javascript')
<script type="text/javascript" src="{{ URL::asset('/attached/js/report.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('/attached/js/data_sample.js')}}"></script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/pptxgenjs@2.5.0/libs/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/pptxgenjs@2.5.0/dist/pptxgen.min.js"></script>
<script type="text/javascript">
  const cls_data_sample = new data_sample (<?php echo json_encode($array_unit); ?>);
  const cls_general_funct = new general_funct;
  const cls_report = new class_report (<?php echo json_encode($rs_vehicule) ?>);
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
  $("#btn_efficiency_report").on('click', function(){
    cls_report.redirect_dashboard();
  })
  </script>

@endsection


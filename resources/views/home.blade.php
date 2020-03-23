@extends('layouts.inside')

@section('content')
<?php $date_today = date('d-m-Y'); 
$array_option = json_decode($compacted['option'], true);
$array_unit = json_decode($array_option[0]['tx_option_unit'], true);
$rs_vehicule = $compacted['vehicule_list'];
$rs_company = $compacted['company_list'];
?>    
<div class="container">
	<div id="toast_container" style="position: fixed; top: 130px; right: 100px; z-index: 10">  <!-- TOAST MESSAGE CONTAINER --></div>
	{{-- MODAL UPLOAD FILE --}}
	<div class="modal fade" id="modal_uploadfile" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Subir Archivo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm 12">
							<div class="custom-file p-3">
								<label for="upload_file" id="lbl_fileupload" class="custom-file-label">Reporte de Excel</label>
								<input type="file" id="fileUpload" class="custom-file-input" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 p-3">
							<select name="sel_sourcefile" id="sel_sourcefile" class="form-control">
								<option value="delta">Informe Delta</option>
								<option value="ralenti" selected>Informe Ralenti</option>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="cls_vehicule.UploadProcess()"><i class="fa fa-save"></i> Subir</button>
				</div>
			</div>
		</div>
	</div>

	<div class="border rounded">
		<form action="#" method="post" id="form_new_data">
			<h3>Ingreso de Informaci&oacute;n</h3>
			<div class="row">
				<div class="col-sm d-none d-md-block"></div>
				<div class="col-sm d-none d-lg-block"></div>
				<div class="col-sm-12 col-md-6 col-lg-3">
					<label for="txt_date" class="label label-primary" >Fecha</label>
					<input type="text" id="txt_date" class="form-control" value="<?php echo $date_today; ?>" readonly>
				</div>
			</div>      
	<!-- One "tab" for each step in the form: -->
			<div class="tab">
				<div class="row d-flex justify-content-center">
					<div id="container_vehicule" class="col-sm-10 col-md-5 col-lg-3">
						<label for="sel_vehicule">Veh&iacute;culo</label>
						<select class="form-control" name="sel_vehicule" id="sel_vehicule" onchange="cls_data_sample.read_all_byvehicule(this.value)">
							@foreach ($rs_vehicule as $item)
							<option value="{{ $item['tx_vehicule_slug'] }}">{{ $item['tx_vehicule_licenseplate'] }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="alert alert-info my-5" role="alert">
					Seleccione la placa del veh&iacute;culo.
				</div>
			</div>

			<div class="tab">Datos de Uso y Consumo:
				<div class="row d-flex justify-content-center">
					<div class="col-sm-4 py-3 input-group">
						<input type="text" id="txt_distance" class="form-control" placeholder="Distancia Recorrida" value="">
						<div class="input-group-append">
							<span class="input-group-text" id="basic-addon2">{{ $array_unit['distance'] }}</span>
						</div>
					</div>
				</div>
				<div class="row d-flex justify-content-center">
					<div class="col-sm-4 py-3 input-group">
						<input type="text" id="txt_time" class="form-control" placeholder="Tiempo de Uso" value="">
						<div class="input-group-append">
							<span class="input-group-text" id="basic-addon2">{{ $array_unit['time'] }}</span>
						</div>
					</div>
				</div> 
				<div class="row d-flex justify-content-center">
					<div class="col-sm-4 py-3 input-group">
						<input type="text" id="txt_volume" class="form-control" placeholder="Litros Surtidos" value="">
						<div class="input-group-append">
							<span class="input-group-text" id="basic-addon2">{{ $array_unit['volume'] }}</span>
						</div>
					</div>
				</div>
				<div class="row d-flex justify-content-center">
					<div class="col-sm-4 py-3 input-group">
						<input type="text" id="txt_currency" class="form-control" placeholder="Cantidad Pagada" value="">
						<div class="input-group-append">
							<span class="input-group-text" id="basic-addon2">{{ $array_unit['currency'] }}</span>
						</div>
					</div>
				</div> 
			</div>

			<div class="d-flex flex-row-reverse h_100">
				<div class="col-sm-12 col-md-7 text-right">
					@if ( auth()->user()->checkRole('admin'))
						<button type="button" id="btn_uploadfile" class="btn btn-dark" data-toggle="modal" data-target="#modal_uploadfile">Subir Archivo</button>&nbsp;&nbsp;&nbsp;&nbsp;
					@endif
					<button type="button" id="prevBtn" class="btn btn-secondary" onclick="nextPrev(-1)">Volver</button>
					<button type="button" id="nextBtn" class="btn btn-primary" onclick="nextPrev(1)">Siguiente</button>
				</div>
				<div id="container_uploadfile" class="col-sm-12 col-md-5 h_100 d-none">
					<div id="upload_file">Arrastre los archivos aqui.</div>
					<output id="list"></output>
				</div>
			</div>
	<!-- Circles which indicates the steps of the form: -->
			<div style="text-align:center;margin-top:40px;">
				<span class="step"></span>
				<span class="step"></span>
			</div>
			<button class="d-sm-none" type="button" id="btn_submit_form">cx</button>
		</form>
	</div>
	<div class="row d-flex justify-content-center">
		<div class="col-sm-12 col-md-12 col-lg-8 pt-5" id="container_data">
			<table class="table table-striped table-sm table-bordered">
				<thead class="table-secondary">
					<tr>
						<th scope="col" class="text-center">#</th>
						<th scope="col" class="text-center">Fecha</th>
						<th scope="col" class="text-center">Distancia</th>
						<th scope="col" class="text-center">Tiempo</th>
						<th scope="col" class="text-center">Volumen</th>
						<th scope="col" class="text-center">Dinero</th>
						<th scope="col" class="text-center">Acciones</th>
					</tr>
				</thead>
				<tfoot class="table-secondary"><tr><td colspan="7"></td></tr></tfoot>
				<tbody>
					<tr>
						<th scope="row"></th>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

</div>
@endsection
@section('optional_javascript')
	<script type="text/javascript" src="{{ URL::asset('attached/js/data_sample.js')}}"></script>
	<script type="text/javascript" src="{{ URL::asset('attached/js/vehicule.js')}}"></script>
	<script type="text/javascript" src="{{ URL::asset('attached/js/multistep_form.js')}}"></script>
	<script src="https://cdn.jsdelivr.net/gh/gitbrent/pptxgenjs@2.5.0/libs/jszip.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/gitbrent/pptxgenjs@2.5.0/dist/pptxgen.min.js"></script>

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>

	<script type="text/javascript">
		const cls_general_funct = new general_funct;
		const cls_data_sample = new data_sample (<?php echo json_encode($array_unit); ?>);
		const cls_vehicule = new vehicule;
		data_sample.prototype.unit = cls_data_sample.unit;
		$( function(){
			$("#txt_date").datepicker({
				changeMonth: true,
				changeYear: true, 
				dateFormat: 'dd-mm-yy'
			});
		});
		$("#btn_submit_form").on("click", function(){
			cls_data_sample.save_data();
		});
		$("#form_new_data").on("submit", function(){
			event.preventDefault();
		});
		$(document).ready(function(){
			var option = {"delay":2000};
			$('.toast').toast(option);
		});
		tippy('#btn_add_vehicule', {
			content: 'Agregar Vehículo'
		});
		$("#txt_distance,#txt_time,#txt_volume,#txt_currency").validCampoFranz("0123456789.");
		$("#fileUpload").on("change", function(){
			var label = (this.value).split('\\');			
			$("#lbl_fileupload").html(label[2]);
		})


	</script>
@endsection

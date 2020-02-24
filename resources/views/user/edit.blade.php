@extends('layouts.inside')

@section('content')
<div class="container">
<?php 
$array_option = json_decode($compacted['option'], true);
$array_unit = json_decode($array_option[0]['tx_option_unit'], true);
?>    
			<div id="toast_container" style="position: fixed; top: 130px; right: 100px; z-index: 10">
			</div>
			<!-- Modal -->
			<div class="modal fade" id="modal_new_vehicule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Agregar Veh&iacute;culo</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-sm-12">
									<label for="txt_licenseplate">Matricula Vehicular</label>
									<input type="text" id="txt_vehicule_licenseplate" class="form-control" placeholder="" value="">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-md-6">
									<label for="txt_brand">Marca</label>
									<input type="text" id="txt_vehicule_brand" class="form-control" placeholder="" value="">
								</div>
								<div class="col-sm-12 col-md-6">
									<label for="txt_model">Modelo</label>
									<input type="text" id="txt_vehicule_model" class="form-control" placeholder="" value="">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-primary" id="btn_save_vehicule"><i class="fa fa-save"></i> Guardar</button>
						</div>
					</div>
				</div>
			</div>

			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="unit-tab" data-toggle="tab" href="#unitmeasurement" role="tab" aria-controls="unit-tab"
						aria-selected="true">Generales</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
						aria-selected="false">Profile</a>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="unitmeasurement" role="tabpanel" aria-labelledby="unit-tab">
					<div class="pt-5"> <h4>Unidades de Medida:</h4>


						<div class="row d-flex justify-content-center">
							<div class="col-sm-12 col-md-4">

								<div class="row d-flex justify-content-center">
									<label for="txt_unit_distance">Distancias</label>
									<div class="col-sm-12">
										<input type="text" id="txt_unit_distance" 	class="form-control" placeholder="" value="{{ $array_unit['distance'] }}">
									</div>
								</div>

								<div class="row d-flex justify-content-center">
									<label for="txt_unit_time">Tiempo</label>
									<div class="col-sm-12">
										<input type="text" id="txt_unit_time" 			class="form-control" placeholder="" value="{{ $array_unit['time'] }}">
									</div>
								</div>
								<div class="row d-flex justify-content-center">
									<label for="txt_unit_volume">Volumen</label>
									<div class="col-sm-12">
										<input type="text" id="txt_unit_volume" 		class="form-control" placeholder="" value="{{ $array_unit['volume'] }}">
									</div>
								</div>
								<div class="row d-flex justify-content-center">
									<label for="txt_unit_currency">Moneda</label>
									<div class="col-sm-12">
										<input type="text" id="txt_unit_currency" 	class="form-control" placeholder="" value="{{ $array_unit['currency'] }}">
									</div>
								</div>
								<div class="row d-flex justify-content-center pt-4">
									<button type="button" class="btn btn-success" onclick="cls_option.save_unit(1)"><i class="fa fa-save"></i> Guardar</button>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Food truck fixie
					locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit,
					blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee.
					Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum
					PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS
					salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit,
					sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester
					stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.
				</div>



		</div>
@endsection
@section('optional_javascript')
	<script type="text/javascript" src="{{ URL::asset('/attached/js/option.js')}}"></script>
	<script type="text/javascript">
		const cls_general_funct = new general_funct;
		const cls_option = new class_option;
		$( function(){
			$("#txt_date").datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'dd-mm-yy'
			});
		});
		$("#btn_save_vehicule").on("click", function(){
			var licenseplate = document.getElementById('txt_vehicule_licenseplate').value;
			var brand = document.getElementById('txt_vehicule_brand').value;
			var model = document.getElementById('txt_vehicule_model').value;
			cls_vehicule.save_vehicule('select','container_vehicule',licenseplate,brand,model);
			cls_general_funct.set_empty(document.getElementById('txt_vehicule_licenseplate'));
			cls_general_funct.set_empty(document.getElementById('txt_vehicule_brand'));
			cls_general_funct.set_empty(document.getElementById('txt_vehicule_model'));
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
		$("#txt_unit_distance,#txt_unit_time,#txt_unit_volume,#txt_unit_currency").validCampoFranz("abcdefghijklmnopqrstu");
	</script>
@endsection

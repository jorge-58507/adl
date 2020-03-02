@extends('layouts.inside')
{{-- controller_user@index --}}
@section('content')
@php
		$userlist = [];
		foreach($compacted['user_list'] as $a => $user){
			$userlist[$user['id']] = $user['name']; 
		}
		$companylist = [];
		foreach ($compacted['company_list'] as $b => $company) {
			$companylist[$company['ai_company_id']] = $company['tx_company_description'];
		}
@endphp
<div class="container">
	<div id="toast_container" style="position: fixed; top: 130px; right: 100px; z-index: 10"></div>
  <!-- Modal EDIT USER-->
	<div class="modal fade" id="modal_edituser" tabindex="-1" role="dialog" aria-labelledby="efficiency" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Modifique la informaci&oacute;n</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<input type="hidden" id="txt_edituser_selected" class="form-control">
						</div>
						<div class="col-sm-12">
							<label for="txt_edituser_name">Descripci&oacute;n</label>
							<input type="text" id="txt_edituser_name" class="form-control" placeholder="">
						</div>
						<div class="col-sm-12">
							<label for="txt_edituser_email">Correo E.</label>
							<input type="email" id="txt_edituser_email" class="form-control" placeholder="">
						</div>
						<div class="col-sm-12">
							<label for="txt_edituser_password">Contrase&ntilde;a</label>
							<input type="text" id="txt_edituser_password" class="form-control" placeholder="">
						</div>
						<div class="col-sm-12">
							<label for="txt_edituser_passwordconfirm">Confirmar Contrase&ntilde;a</label>
							<input type="text" id="txt_edituser_passwordconfirm" class="form-control" placeholder="">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" id="btn_upd_user"><i class="fas fa-save"></i> Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal -->   
  <!-- Modal EDIT COMPANY-->
	<div class="modal fade" id="modal_editcompany" tabindex="-1" role="dialog" aria-labelledby="efficiency" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Modifique la informaci&oacute;n</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<input type="hidden" id="txt_editcompany_selected" class="form-control">
						</div>
						<div class="col-sm-12">
							<label for="txt_editcompany_description">Descripci&oacute;n</label>
							<input type="text" id="txt_editcompany_description" class="form-control" placeholder="">
						</div>
						<div class="col-sm-12">
							<label for="txt_editcompany_ruc">RUC</label>
							<input type="text" id="txt_editcompany_ruc" class="form-control" placeholder="">
						</div>
						<div class="col-sm-12">
							<label for="txt_editcompany_telephone">Tel&eacute;fono</label>
							<input type="text" id="txt_editcompany_telephone" class="form-control" placeholder="">
						</div>
						<div class="col-sm-12">
							<label for="txt_editcompany_direction">Direcci&oacute;n</label>
							<input type="text" id="txt_editcompany_direction" class="form-control" placeholder="">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" id="btn_upd_company"><i class="fas fa-save"></i> Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal -->   
	<ul class="nav nav-tabs" id="myTab" role="tablist" style="justify-content: center; border: none;">
		<li class="nav-item bg-light">
			<a class="nav-link active" id="user-tab" data-toggle="tab" href="#user_profile" role="tab" aria-controls="user-tab"
					aria-selected="true">Usuario</a>
		</li>
		<li class="nav-item bg-light">
			<a class="nav-link" id="company-tab" data-toggle="tab" href="#company" role="tab" aria-controls="company-tab"
					aria-selected="false">Compa&ntilde;&iacute;a</a>
		</li>
		<li class="nav-item bg-light">
			<a class="nav-link" id="linkup-tab" data-toggle="tab" href="#linkup" role="tab" aria-controls="link-tab"
					aria-selected="false">Vincular</a>
		</li>
		<li class="nav-item bg-light">
			<a class="nav-link" id="vehicule-tab" data-toggle="tab" href="#tab_vehicule" role="tab" aria-controls="vehicule-tab"
					aria-selected="false">Veh&iacute;culos</a>
		</li>
	</ul>
	<div class="tab-content" id="myTabContent">

		<div class="tab-pane fade show" id="user_profile" role="tabpanel" aria-labelledby="user-tab">
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="card">
						<div class="card-header">{{ __('Registro de Usuario') }}</div>

						<div class="card-body">
							<form method="POST" action="">
								@csrf
								<div class="form-group row">
									<label for="sel_user_company" class="col-md-4 col-form-label text-md-right">{{ __('Compañia') }}</label>

									<div id="container_select_company" class="col-md-6">
										<select class="form-control" name="sel_user_company" id="sel_user_company">
											<?php
											foreach ($compacted['company_list'] as $a => $company) { ?>
												<option value="{{ $company['ai_company_id'] }}">{{ $company['tx_company_description'] }}</option>
											<?php
											}
											?>
										</select>
									</div>
								</div>

								<div class="form-group row">
									<label for="txt_user_name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

									<div class="col-md-6">
										<input id="txt_user_name" type="text" class="form-control" name="name" value="" autocomplete="off" autofocus>
									</div>
								</div>

								<div class="form-group row">
									<label for="txt_user_email" class="col-md-4 col-form-label text-md-right">{{ __('Correo Electrónico') }}</label>

									<div class="col-md-6">
										<input id="txt_user_email" type="email" class="form-control" name="email" value=""  autocomplete="off">
									</div>
								</div>

								<div class="form-group row">
									<label for="sel_user_role" class="col-md-4 col-form-label text-md-right">{{ __('Compañía') }}</label>

									<div class="col-md-6">
										<select name="sel_user_role" id="sel_user_role" class="form-control">
<?php									foreach ($compacted['role_list'] as $b => $role) { ?>
												<option value="{{ $role['id'] }}">{{ $role['description'] }}</option>
<?php									}	?>
										</select>
									</div>
								</div>

								<div class="form-group row">
									<label for="txt_user_password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

									<div class="col-md-6">
										<input id="txt_user_password" type="password" class="form-control" name="txt_user_password" value=""  autocomplete="off">
									</div>
								</div>

								<div class="form-group row">
									<label for="txt_user_passwordconfirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Contraseña') }}</label>

									<div class="col-md-6">
										<input id="txt_user_passwordconfirm" type="password" class="form-control" name="txt_user_passwordconfirm" value=""  autocomplete="off">
									</div>
								</div>

								<div class="form-group row mb-0">
									<div class="col-md-6 offset-md-4">
										<button type="button" id="btn_saveuser" class="btn btn-primary">
											{{ __('Crear Usuario') }}
										</button>
										<button type="button" id="btn_goback" class="btn btn-secondary">
											{{ __('Volver') }}
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
{{-- vv LISTADO DE USUARIOS vv --}}
				<div class="col-md-8 p-2">
					<h5>Listado de Usuarios</h5>
					<div class="row">
						<div class="col-sm-10">
							<div class="form-group row">
								<label for="txt_user_filter" class="col-md-4 col-form-label text-md-right">{{ __('Buscar') }}</label>
								<div class="col-md-8">
									<input id="txt_user_filter" type="text" class="form-control" name="txt_user_filter" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<button type="button" id="btn_user_filter" class="btn btn-success"><i class="fas fa-search"></i></button>
						</div>
						<div id="container_label_filteruser" class="col-sm-12">&nbsp;</div>
					</div>
					<div id="container_user_saved" class="list-group col-sm-12 h_500 overflow_auto"></div>
				</div>
{{-- ^^ LISTADO DE USUARIOS ^^ --}}
			</div>
		</div>  
		<div class="tab-pane fade show" id="company" role="tabpanel" aria-labelledby="company-tab">
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="card">
						<div class="card-header">{{ __('Registro de Compañía') }}</div>

						<div class="card-body">
							<form method="POST" action="">
								@csrf
								<div class="form-group row">
									<label for="txt_company_description" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

									<div class="col-md-6">
										<input id="txt_company_description" type="text" class="form-control" name="txt_company_description" autocomplete="off" autofocus>
									</div>
								</div>

								<div class="form-group row">
									<label for="txt_company_ruc" class="col-md-4 col-form-label text-md-right">{{ __('RUC') }}</label>

									<div class="col-md-6">
										<input id="txt_company_ruc" type="text" class="form-control" name="email" autocomplete="off">
									</div>
								</div>

								<div class="form-group row">
									<label for="txt_company_direction" class="col-md-4 col-form-label text-md-right">{{ __('Dirección') }}</label>

									<div class="col-md-6">
										<input id="txt_company_direction" type="text" class="form-control" name="txt_company_direction" autocomplete="off">
									</div>
								</div>

								<div class="form-group row">
									<label for="txt_company_telephone" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>

									<div class="col-md-6">
										<input id="txt_company_telephone" type="text" class="form-control" name="txt_company_telephone" autocomplete="off">
									</div>
								</div>

								<div class="form-group row mb-0">
									<div class="col-md-6 offset-md-4">
										<button type="button" id="btn_savecompany" class="btn btn-primary">
											{{ __('Guardar') }}
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
{{-- vv LISTADO DE COMPAÑIAS vv --}}
				<div class="col-md-8 p-2">
					<h5>Listado de Compa&ntilde;&iacute;as</h5>
					<div class="row">
						<div class="col-sm-10">
							<div class="form-group row">
								<label for="txt_company_filter" class="col-md-4 col-form-label text-md-right">{{ __('Buscar') }}</label>
								<div class="col-md-8">
									<input id="txt_company_filter" type="text" class="form-control" name="txt_company_filter" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<button type="button" id="btn_company_filter" class="btn btn-success"><i class="fas fa-search"></i></button>
						</div>
						<div id="container_label_filtercompany" class="col-sm-12">&nbsp;</div>
					</div>
					<div id="container_company_saved" class="list-group col-sm-12 h_500 overflow_auto"></div>
				</div>
{{-- ^^ LISTADO DE USUARIOS ^^ --}}
			</div>
		</div>  
		<div class="tab-pane fade show" id="linkup" role="tabpanel" aria-labelledby="link-tab">
			<div class="row justify-content-center bg-white h_500 pt-3 overflow_auto">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group row">
								<label for="txt_link_filter" class="col-md-4 col-form-label text-md-right">{{ __('Filtrar') }}</label>
								<div class="col-md-8">
									<input id="txt_link_filter" type="text" class="form-control" name="txt_link_filter" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-1">
							<button type="button" id="btn_filter" class="btn btn-success"><i class="fas fa-search"></i></button>
						</div>
						<div class="col-sm-12 col-md-3">
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="r_user" name="r_filter" class="custom-control-input" value="user" checked>
								<label class="custom-control-label" for="r_user">Usuario</label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="r_company" name="r_filter" class="custom-control-input" value="company">
								<label class="custom-control-label" for="r_company">Compa&ntilde;&iacute;a</label>
							</div>
						</div>
					</div>
					<div class="row border-top pt-2">
						<div class="col-sm-12 h_40 text-center" id="title_linkup">
							
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 list-group" id="container_data_filtered">
						</div>
						<div class="col-md-6 list-group" id="container_data_objective">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane fade show active" id="tab_vehicule" role="tabpanel" aria-labelledby="vehicule-tab">
			<div class="row justify-content-center bg-white h_500 pt-3 overflow_auto">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group row">
								<label for="txt_vehicule_filter" class="col-md-4 col-form-label text-md-right">{{ __('Buscar') }}</label>
								<div class="col-md-8">
									<input id="txt_vehicule_filter" type="text" class="form-control" name="txt_vehicule_filter" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-1">
							<button type="button" id="btn_vehicule_filter" class="btn btn-success"><i class="fas fa-search"></i></button>
						</div>
						<div class="col-sm-12 col-md-2">
							<select name="sel_vehicule_quantity" id="sel_vehicule_quantity" class="form-control">
								<option value="20">Mostrar 20</option>
								<option value="50">Mostrar 50</option>
								<option value="100">Mostrar 100</option>
								<option value="200">Mostrar 200</option>
							</select>
						</div>
						<div class="col-sm-12" id="label_vehiculefilter">&nbsp;</div>
					</div>
					<div class="row">
						<div class="col-md-6 list-group" id="container_vehicule_filtered">ddd</div>
					</div>
				</div>
			</div>
		</div>		
	</div>  

    
</div>
@endsection
@section('optional_javascript')
<script src="{{ URL::asset('attached/js/user.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('attached/js/vehicule.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	const cls_general_funct = new general_funct;
	const cls_company = new class_company;
	const cls_user = new class_user;
	const cls_user_company = new class_user_company
	const cls_vehicule = new vehicule;
	document.getElementById("btn_savecompany").addEventListener("click",()=>{ cls_company.create_company(); })
	document.getElementById("btn_saveuser").addEventListener("click",()=>{ cls_user.create_user(); })
	document.getElementById("btn_goback").addEventListener("click",()=>{ document.location = "{{ URL::asset('login') }}"; })
	cls_general_funct.validFranz("txt_user_name",['word','punctuation']);
	cls_general_funct.validFranz("txt_company_description",['word','number','punctuation']);
	cls_general_funct.validFranz("txt_company_ruc",['number'],'-dv');
	cls_general_funct.validFranz("txt_company_direction",['word','number','punctuation']);
	cls_general_funct.validFranz("txt_company_telephone",['number'],'-');

	const array_userlist = JSON.parse('<?php echo json_encode($userlist); ?>');
	const array_companylist = JSON.parse('<?php echo json_encode($companylist); ?>');
	$("#btn_filter").on("click", ()=>{
		var str = document.getElementById("txt_link_filter").value;
    var r_link = $('input[name=r_filter]:checked').val();
		switch (r_link) {
			case 'company':
				cls_user_company.filter_linkup('company',str);
			break;
			case 'user':
				cls_user_company.filter_linkup('user',str);
			break;
		}
	})
	// ##########    USUARIOS

	cls_user.render_user_saved(JSON.parse('<?php echo json_encode($compacted['user_list']); ?>'));
	$("#txt_user_filter").on('keypress', function(event){
		if (event.which === 13) {	$("#btn_user_filter").click();	}
	})
	$("#btn_user_filter").on('click', ()=>{
		var str = document.getElementById("txt_user_filter").value;
		str = (cls_general_funct.is_empty_var(str)===0) ? 'All' : str;
		var limit = 20;
		var url = 'user/'+str;
    var method = 'GET';
    var funcion = function (data_obj) {
			var label = `Se buscó "${str}". Mostrando`;
			label += (data_obj['user_count'] > limit) ? ` ${limit} de ${data_obj['user_count']}.` : ` ${data_obj['user_count']} de ${data_obj['user_count']}.`;
      cls_user.render_user_saved(data_obj['user_list'],limit,label);
    }
    cls_general_funct.async_laravel_request(url, method, funcion)
	})
	$('#btn_upd_user').on("click", ()=>{
		cls_user.update_user();
	})
	// ###################   COMPANY

	cls_company.render_company_saved(JSON.parse('<?php echo json_encode($compacted['company_list']); ?>'));
	$("#txt_company_filter").on('keypress', function(event){
		if (event.which === 13) {	$("#btn_company_filter").click();	}
	})
	$("#btn_company_filter").on('click', ()=>{
		var str = document.getElementById("txt_company_filter").value;
		str = (cls_general_funct.is_empty_var(str)===0) ? 'All' : str;
		var limit = 20;
		var url = 'company/'+str;
    var method = 'GET';
    var funcion = function (data_obj) {
			var label = `Se buscó "${str}". Mostrando`;
			label += (data_obj['company_count'] > limit) ? ` ${limit} de ${data_obj['company_count']}.` : ` ${data_obj['company_count']} de ${data_obj['company_count']}.`;
      cls_company.render_company_saved(data_obj['company_list'],limit,label);
    }
    cls_general_funct.async_laravel_request(url, method, funcion)
	})
	$("#btn_upd_company").on("click", ()=>{
		cls_company.update_company();
	})
	// ###################   VEHICULE

	var array_head ={
		'Placa':'tx_vehicule_licenseplate',
		'Compa&ntilde;&iacute;a':'tx_company_description', 
		'Marca':'tx_vehicule_brand',
		'Modelo':'tx_vehicule_model'
	}
	cls_vehicule.render_vehicule_list(JSON.parse('<?php echo json_encode($compacted['vehicule_list']); ?>'),array_head)
</script>		
@endsection
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
  <!-- Modal -->
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
							<input type="text" id="txt_edituser_email" class="form-control" placeholder="">
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
	</ul>
	<div class="tab-content" id="myTabContent">

		<div class="tab-pane fade show active" id="user_profile" role="tabpanel" aria-labelledby="user-tab">
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
									<label for="sel_user_role" class="col-md-4 col-form-label text-md-right">{{ __('Correo Electrónico') }}</label>

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
										<button type="button" id="btn_goback" class="btn btn-primary">
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
					<div id="container_user_saved" class="list-group">
					</div>
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
	</div>  

    
</div>
@endsection
@section('optional_javascript')
<script src="{{ URL::asset('attached/js/user.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	const cls_general_funct = new general_funct;
	const cls_company = new class_company;
	const cls_user = new class_user;
	const cls_user_company = new class_user_company
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
	cls_user.render_user_saved(JSON.parse('<?php echo json_encode($compacted['user_list']); ?>'));
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
	$('#btn_upd_user').on("click", ()=>{
		cls_user.update_user();
	})
</script>		
@endsection
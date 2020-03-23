// javascript document
// ##########    CLASS COMPANY
class class_company 
{
  render_company_saved(company_list, limit = 20, label_filter = ''){
    var counter = 1;
    var content = '';
    for (const a in company_list) {
      if (counter > limit) { break; }
      var bg = (company_list[a]['int_company_status'] === 0) ? 'bg_disabled' : 'bg-white';
      var block_icon = (company_list[a]['int_company_status'] === 0) ? 'check' : 'trash-alt';
      content += `
        <div class="list-group-item d-flex justify-content-between align-items-center ${bg} ">${parseInt(a) + 1}- ${company_list[a]['tx_company_description']} RUC ${company_list[a]['tx_company_ruc']}
          <div>
            <button type="button" onclick="cls_company.edit_company(${company_list[a]['ai_company_id']},'${company_list[a]['tx_company_description']}','${company_list[a]['tx_company_ruc']}','${company_list[a]['tx_company_telephone']}','${company_list[a]['tx_company_direction']}')" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_editcompany"><i class="fas fa-wrench"></i></button>
            &nbsp;&nbsp;
            <button type="button" onclick="cls_company.block_company(${company_list[a]['ai_company_id']})" class="btn btn-sm btn-warning"><i class="fas fa-${block_icon}"></i></button>
          </div>
        </div>
      `;
      counter++;
    }
    $("#container_company_saved").fadeOut('fast', ()=>{
      document.getElementById("container_company_saved").innerHTML = content;
      document.getElementById("container_label_filtercompany").innerHTML = label_filter;
    });
    $("#container_company_saved").fadeIn();
  }
  validate_form(array_form) {
    var valid = true;
    for (let a = 0; a < array_form.length; a++) {
      if (cls_general_funct.isEmpty(array_form[a]) === 0) {
        valid = false;
      }
    }
    return valid;
  }
  create_company () {
    const array_form = [
      document.getElementById('txt_company_description'),
      document.getElementById('txt_company_ruc'),
      document.getElementById('txt_company_direction'),
      document.getElementById('txt_company_telephone')
    ];
    var valid = this.validate_form(array_form);    
    if (!valid) {  cls_general_funct.shot_toast("Verifique los Datos."); return false; }
    
    var url = 'company';
    var method = 'POST';
    var body = JSON.stringify({ a: array_form[0].value, b: array_form[1].value, c: array_form[2].value, d: array_form[3].value });
    var funcion = function (data_obj) {
      cls_company.render_company_saved(data_obj['data']['company_list']);
      cls_company.render_select_company(data_obj['data']['company_list']);
      cls_company.clear_form();
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)

    
  }
  render_select_company(array_company){
    var select = `<select class="form-control" name="sel_user_company" id="sel_user_company">`;
    for (const a in array_company) {
      select += `<option value="${array_company[a]['ai_company_id']}">${array_company[a]['tx_company_description']}</option>`;
    }
    select += `</select>`;
    document.getElementById("container_select_company").innerHTML = select;
  }
  clear_form(){
    document.getElementById('txt_company_description').value = '';
    document.getElementById('txt_company_ruc').value = '';
    document.getElementById('txt_company_direction').value = '';
    document.getElementById('txt_company_telephone').value = '';

    document.getElementById('txt_editcompany_selected').value = '';
    document.getElementById('txt_editcompany_description').value = '';
    document.getElementById('txt_editcompany_ruc').value = '';
    document.getElementById('txt_editcompany_direction').value = '';
    document.getElementById('txt_editcompany_telephone').value = '';
  }
  edit_company(company_id, description, ruc, telephone, direction){
    document.getElementById('txt_editcompany_selected').value = company_id;
    document.getElementById('txt_editcompany_description').setAttribute('placeholder', description);
    document.getElementById('txt_editcompany_ruc').setAttribute('placeholder',ruc);
    document.getElementById('txt_editcompany_telephone').setAttribute('placeholder',telephone);
    document.getElementById('txt_editcompany_direction').setAttribute('placeholder',direction);
  }

  update_company(){
    var company_id = document.getElementById('txt_editcompany_selected').value;
    var description = document.getElementById('txt_editcompany_description');
    description = (cls_general_funct.is_empty_var(description.value) === 0) ? description.getAttribute('placeholder') : description.value;
    var ruc = document.getElementById('txt_editcompany_ruc');
    ruc = (cls_general_funct.is_empty_var(ruc.value) === 0) ? ruc.getAttribute('placeholder') : ruc.value;
    var telephone = document.getElementById('txt_editcompany_telephone');
    telephone = (cls_general_funct.is_empty_var(telephone.value) === 0) ? telephone.getAttribute('placeholder') : telephone.value;
    var direction = document.getElementById('txt_editcompany_direction');
    direction = (cls_general_funct.is_empty_var(direction.value) === 0) ? direction.getAttribute('placeholder') : direction.value;

    var url = 'company/whyouseethis';
    var method = 'PUT';
    var body = JSON.stringify({ a: company_id, b: description, c: ruc, d: telephone, e: direction });
    var funcion = function (data_obj) {
      $('#modal_editcompany').modal('hide')
      cls_company.render_company_saved(data_obj['company_list']);
      cls_company.clear_form();
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)
  }
  block_company(company_id){
    var url = 'company/' + company_id;
    var method = 'DELETE';
    var funcion = function (data_obj) {
      var limit = 20;
      var label = `Mostrando`;
      label += (data_obj['company_count'] > limit) ? ` ${limit} de ${data_obj['company_count']}.` : ` ${data_obj['company_count']} de ${data_obj['company_count']}.`;
      cls_company.render_company_saved(data_obj['company_list'], limit, label);
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion)
  }
}
// ##################    CLASS USER     #############
class class_user
{
  render_user_saved(user_list, limit=20, label_filter=''){
    var counter = 1;
    var content = '';
    for (const a in user_list) {
      if (counter > limit) { break; }
      var bg = (user_list[a]['status'] === 0) ? 'bg_disabled' : 'bg-white';
      var block_icon = (user_list[a]['status'] === 0) ? 'check' : 'trash-alt';
      content += `
        <div class="list-group-item d-flex justify-content-between align-items-center ${bg} ">${parseInt(a)+1}- ${user_list[a]['name']}
          <div>
            <button type="button" onclick="cls_user.edit_user(${user_list[a]['id']},'${user_list[a]['name']}','${user_list[a]['email']}')" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_edituser"><i class="fas fa-wrench"></i></button>
            &nbsp;&nbsp;
            <button type="button" onclick="cls_user.block_user(${user_list[a]['id']})" class="btn btn-sm btn-warning"><i class="fas fa-${block_icon}"></i></button>
          </div>
        </div>
      `;
      counter++;
    }
    $("#container_user_saved").fadeOut('fast', ()=>{
      document.getElementById("container_user_saved").innerHTML = content;
      document.getElementById("container_label_filteruser").innerHTML = label_filter;
    });
    $("#container_user_saved").fadeIn();
  }
  validate_form(array_form) {  // array_form es un array de selectores (inputs)
    var valid = true;
    for (let a = 0; a < array_form.length; a++) {
      if (cls_general_funct.isEmpty(array_form[a]) === 0) {
        valid = false;
      }
    }
    return valid;
  }

  create_user(){
    if (document.getElementById('txt_user_password').value != document.getElementById('txt_user_passwordconfirm').value) {
      cls_general_funct.shot_toast("Las claves no coinciden."); return false; 
    }
    const array_form = [
      document.getElementById('txt_user_name'),
      document.getElementById('txt_user_email'),
      document.getElementById('txt_user_password'),
      document.getElementById('txt_user_passwordconfirm'),
      document.getElementById('sel_user_role')
    ];
    var valid = this.validate_form(array_form);
    if (!valid) { cls_general_funct.shot_toast("Verifique los Datos."); return false; }
    var val_email = cls_general_funct.isEmail(array_form[1].value);
    if (!val_email) { cls_general_funct.shot_toast("Correo Incorrecto."); return false; }    
    var url = 'user';
    var method = 'POST';
    var body = JSON.stringify({ a: document.getElementById("sel_user_company").value, b: array_form[0].value, c: array_form[1].value, d: array_form[2].value, e: array_form[4].value });
    var funcion = function (data_obj) {
      cls_user.render_user_saved(data_obj['data']['user_list']);
      cls_user.clear_form();
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)

  }
  clear_form () {
    document.getElementById('txt_user_name').value = '';
    document.getElementById('txt_user_email').value = '';
    document.getElementById('txt_user_password').value = '';
    document.getElementById('txt_user_passwordconfirm').value = '';

    document.getElementById('txt_edituser_selected').value = '';
    document.getElementById('txt_edituser_name').value = '';
    document.getElementById('txt_edituser_email').value = '';
  }
  edit_user(user_id,name,email){
    document.getElementById('txt_edituser_selected').value = user_id;
    document.getElementById('txt_edituser_name').setAttribute('placeholder', name);
    document.getElementById('txt_edituser_email').setAttribute('placeholder', email);
  }
  update_user(){
    var user_id = document.getElementById('txt_edituser_selected').value;
    var name = document.getElementById('txt_edituser_name');
    name = (cls_general_funct.is_empty_var(name.value) === 0) ? name.getAttribute('placeholder') : name.value;
    var email = document.getElementById('txt_edituser_email');
    email = (cls_general_funct.is_empty_var(email.value) === 0) ? email.getAttribute('placeholder') : email.value;
    var password = document.getElementById('txt_edituser_password').value;
    var passwordconfirm = document.getElementById('txt_edituser_passwordconfirm').value;
    var val_email = cls_general_funct.isEmail(email);
    if (!val_email) { cls_general_funct.shot_toast("Correo Incorrecto."); return false; }    
    if (password != passwordconfirm) {  cls_general_funct.shot_toast('Las Contraseñas no Coinciden'); return false; }
    var url = 'user/whyouseethis';
    var method = 'PUT';
    var body = JSON.stringify({ a: user_id, b: name, c: email, d: password });
    var funcion = function (data_obj) {
      $('#modal_edituser').modal('hide')
      cls_user.render_user_saved(data_obj['user_list']);
      cls_user.clear_form();
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)
  }
  block_user(user_id){
    var url = 'user/'+user_id;
    var method = 'DELETE';
    var funcion = function (data_obj) {
      var limit = 20;
      var label = `Mostrando`;
      label += (data_obj['user_count'] > limit) ? ` ${limit} de ${data_obj['user_count']}.` : ` ${data_obj['user_count']} de ${data_obj['user_count']}.`;
      cls_user.render_user_saved(data_obj['user_list'],limit,label);
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion)
  }
}

class class_user_company 
{
  reorder_list(obj) {
    var raw_ordered = []; var raw_returned = new Object;
    for (var a in obj) { raw_ordered.push(obj[a] + '*-*' + a); }
    raw_ordered.sort();
    for (var i = 0; i < raw_ordered.length; i++) {
      var splited = raw_ordered[i].split("*-*");
      raw_returned["'" + splited[1] + "'"] = splited[0];
    }
    return raw_returned
  }
  filter_linkup(base_filter,str, limit = 300) {
    switch (base_filter) {
      case 'user':
        var haystack = array_userlist;
        break;
      case 'company':
        var haystack = array_companylist;
        break;
    }
    var needles = str.split(' ');
    var raw_filtered = new Object;
    var counter = 0;
    for (var i in haystack) {
      var ocurrencys = 0;
      for (const a in needles) {
        if (haystack[i].toLowerCase().indexOf(needles[a].toLowerCase()) > -1) { ocurrencys++ }
      }
      if (ocurrencys === needles.length && counter < limit) {
        raw_filtered[i] = haystack[i];
        counter++;
      }
    }
    this.render_linkup(base_filter, this.reorder_list(raw_filtered));
  }
  render_linkup(base_filter,data_list) {
    $("#container_data_objective").fadeOut();
    document.getElementById("title_linkup").innerHTML = '';
    content = '';
    for (const a in data_list) {
      content += `
      <button onclick="cls_user_company.set_linkup('${base_filter}',${a},'${data_list[a]}')" class="list-group-item list-group-item-action">${data_list[a]}</button>
      `;
    }
    document.getElementById("container_data_filtered").innerHTML = content;
  }
  set_linkup(base_filter, id, description) {   
    $("#container_data_objective").fadeOut();
    document.getElementById("title_linkup").innerHTML = `<h5>${description}</h5>`;
    var content_base = '';
    switch (base_filter) {
      case 'user':
        var iterable_array = array_companylist;
        for (const a in iterable_array) {
              content_base += `
            <div class="list-group-item d-flex justify-content-between align-items-center">${iterable_array[a]}
            <button type="button" onclick="cls_user_company.save_linkup('${base_filter}',${id},${a})" class="btn btn-sm btn-primary"><i class="fa fa-chevron-right"></i></span>
            </div>
          `;
        }
        var url = `company/get_by_user/${id}`;  var method = 'GET';
        var funcion = function (data_obj) {
          cls_user_company.set_objective(base_filter, id, data_obj['company_list']);
        }
      break;
      case 'company':
        var iterable_array = array_userlist;
        for (const a in iterable_array) {
          content_base += `
          <div class="list-group-item d-flex justify-content-between align-items-center">${iterable_array[a]}
          <button type="button" onclick="cls_user_company.save_linkup('${base_filter}',${a},${id})" class="btn btn-sm btn-primary"><i class="fa fa-chevron-right"></i></span>
          </div>
          `;
        }
        var url = `user/get_by_company/${id}`; var method = 'GET';
        var funcion = function (data_obj) {
          cls_user_company.set_objective(base_filter, id, data_obj['user_list']);
        }
      break;
    }    
    cls_general_funct.async_laravel_request(url, method, funcion)
    document.getElementById("container_data_filtered").innerHTML = content_base;
  }
  set_objective(base_filter, id, data_list) {
    var content_base = '';
    for (const a in data_list) {
      switch (base_filter) {
        case 'user':
          var userid = id;
          var companyid = data_list[a]['ai_company_id'];
          var description = data_list[a]['tx_company_description']
          break;
        case 'company':
          var userid = data_list[a]['id'];
          var companyid = id;
          var description = data_list[a]['name']
          break;
      }
      content_base += `
      <div class="list-group-item d-flex justify-content-between align-items-center">
      <button type="button" onclick="cls_user_company.delete_linkup('${base_filter}',${userid},${companyid})" class="btn btn-sm btn-danger order-0"><i class="fa fa-chevron-left"></i>
      </button>
      ${description}
      </div>
      `;
    }
    document.getElementById("container_data_objective").innerHTML = content_base;
    $("#container_data_objective").fadeIn()
  }
  save_linkup(base_filter, user_id, company_id) {
    var url = 'user_company';
    var method = 'POST';
    var body = JSON.stringify({ a: user_id, b: company_id, c: base_filter });
    var funcion = function (data_obj) {
      switch (base_filter) {
        case 'user':
          cls_user_company.set_objective(base_filter, user_id, data_obj['data_list']);
          break;
        case 'company':
          cls_user_company.set_objective(base_filter, company_id, data_obj['data_list']);
          break;
      }
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)
  }
  delete_linkup(base_filter, user_id, company_id) {
    var url = 'user_company/delete';
    var method = 'POST';
    var body = JSON.stringify({ a: user_id, b: company_id, c: base_filter });
    var funcion = function (data_obj) {
      switch (base_filter) {
        case 'user':
          cls_user_company.set_objective(base_filter, user_id, data_obj['data_list']);
          break;
        case 'company':
          cls_user_company.set_objective(base_filter, company_id, data_obj['data_list']);
          break;
      }
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)
  }
}
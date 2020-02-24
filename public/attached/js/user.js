// javascript document
// ##########    CLASS COMPANY
class class_company 
{
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
  }
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
  filter_company_linkup(str, limit = 300) {
    var haystack = array_companylist;
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
    this.render_company_linkup(this.reorder_list(raw_filtered));
  }
  render_company_linkup(data_list) {
    content = '';
    for (const a in data_list) {
      content += `
      <button type="button" class="list-group-item list-group-item-action">${data_list[a]}</button>
      `;
    }
    document.getElementById("container_data_filtered").innerHTML = content;
  }

}
// ##################    CLASS USER     #############
class class_user
{
  render_user_saved(user_list){
    var content = '';
    for (const a in user_list) {
      content += `
        <div class="list-group-item d-flex justify-content-between align-items-center">${user_list[a]['name']}
          <div>
            <button type="button" onclick="cls_user.edit_user(${user_list[a]['id']},'${user_list[a]['name']}','${user_list[a]['email']}')" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_edituser"><i class="fas fa-wrench"></i></button>
            &nbsp;&nbsp;
            <button type="button" onclick="cls_user.block_user(${user_list[a]['id']})" class="btn btn-sm btn-warning"><i class="fas fa-trash-alt"></i></button>
          </div>
        </div>
      `;
    }
    document.getElementById("container_user_saved").innerHTML = content;
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

// VERIFICAR EL FORMATO DE EMAIL PRIMERO

    

    var url = 'user';
    var method = 'POST';
    var body = JSON.stringify({ a: document.getElementById("sel_user_company").value, b: array_form[0].value, c: array_form[1].value, d: array_form[2].value, e: array_form[4].value });
    var funcion = function (data_obj) {
      // cls_company.render_select_company(data_obj['data']['company_list']);
      cls_company.clear_form();
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)

  }
  clear_form () {
    document.getElementById('txt_user_name').value = '';
    document.getElementById('txt_user_email').value = '';
    document.getElementById('txt_user_password').value = '';
    document.getElementById('txt_user_passwordconfirm').value = '';
  }
  edit_user(user_id,name,email){
    document.getElementById('txt_edituser_selected').value = user_id;
    document.getElementById('txt_edituser_name').setAttribute('placeholder', name);
    document.getElementById('txt_edituser_email').setAttribute('placeholder', email);
  }
  update_user(){
    var user_id = document.getElementById('txt_edituser_selected');
    var name = document.getElementById('txt_edituser_name');
    var email = document.getElementById('txt_edituser_email');
    var password = document.getElementById('txt_edituser_password');
    var passwordconfirm = document.getElementById('txt_edituser_passwordconfirm');
    // const array_form = [
    //   document.getElementById('txt_edituser_selected'),
    //   document.getElementById('txt_edituser_name'),
    //   document.getElementById('txt_edituser_email'),
    //   document.getElementById('txt_edituser_password'),
    //   document.getElementById('txt_edituser_passwordconfirm')
    // ];
    // var answer = this.validate_form(array_form);
    var answer = this.validate_form([user_id,name,email,password,passwordconfirm]);
    if (answer === false) { cls_general_funct.shot_toast('Faltan Campos por llenar'); return false; }
    if (password.value != passwordconfirm.value) {  cls_general_funct.shot_toast('Las Contraseñas no Coinciden'); return false; }
    var url = 'user/whyouseethis';
    var method = 'POST';
    var body = JSON.stringify({ a: user_id.value, b: name.value, c: email.value });
    var funcion = function (data_obj) {
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)
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
// JavaScript Document

class vehicule
{
  validate_form (array_form) {
    var valid = true;
    for (let a = 0; a < array_form.length; a++) {
      if (cls_general_funct.is_empty_var(array_form[a]) === 0) {
        valid = false;
      }
    }
    return valid;
  }
  save_vehicule (selector_type,target,licenseplate,brand,model,company) {
    document.getElementById("btn_save_vehicule").disabled = true;
    setTimeout(() => { document.getElementById("btn_save_vehicule").disabled = false; }, 5000)
    var form_vehicule = [licenseplate,brand,model];
    var valid = this.validate_form(form_vehicule);
    if (!valid) { return false; }

    var raw_data = {};
    raw_data.licenseplate = licenseplate.toUpperCase();
    raw_data.brand = brand.toUpperCase();
    raw_data.model = model.toUpperCase();
    raw_data.company = company;
    
    var url = 'vehicule';
    var method = 'POST';
    var body = JSON.stringify({ a: raw_data });
    var funcion = function (data_obj) {
      $('#modal_new_vehicule').modal('hide');
      switch (selector_type) {
        case 'select':
          cls_vehicule.render_select(data_obj['data_list'], target);
          cls_general_funct.shot_toast(data_obj['message']);
        break;
        case 'table':
          // cls_vehicule.render_table(data_obj['data_list'], target);
          cls_general_funct.shot_toast(data_obj['message']);
        break;
      }
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)
  }
  render_select(array_data,target)  {    
    var selector = `
      <label for="sel_vehicule">Veh&iacute;culo</label>
      <select class="form-control" name = "sel_vehicule" id = "sel_vehicule" >`;
    for (const a in array_data) {
      selector += `<option value="${array_data[a]['tx_vehicule_slug']}">${array_data[a]['tx_vehicule_licenseplate']}</option>`
    }
    selector += `</select>`;
    document.getElementById(target).innerHTML = selector;
  }

  // CONFIGURACIÓN

  render_vehicule_list(array_vehicule,array_head,caption=''){
    
    var field_list = [];
    var content_head
    for (const a in array_head) {
        content_head += `<th>${a}</th>`;
        field_list.push(array_head[a]);
      }
    var content = `<table class="table table-condensed table-bordered table-striped"><thead><tr>${content_head}</tr></thead><tbody>`;
    var content_body = '';
      for (const a in array_vehicule) {
        var row_body = '';
        for (let b = 0; b < field_list.length; b++) {
          row_body += `
          <td>${array_vehicule[a][field_list[b]]}</td>
          `;
        }
        content_body += `<tr>${row_body}</tr>`;
      }
      content += `${content_body}</tbody></table>`;
      document.getElementById("container_vehicule_filtered").innerHTML = content;

      // FALTA EL FOOT Y EL CAPTION
  }
}
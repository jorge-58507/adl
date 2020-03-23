// JavaScript Document

class vehicule
{

  GetTableFromExcel(data) {
    //Read the Excel File data in binary
    var workbook = XLSX.read(data, {
      type: 'binary'
    });

    //get the name of First Sheet.
    var Sheet = workbook.SheetNames[0];

    //Read all rows from First Sheet into an JSON array.
    var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[Sheet]);
    return excelRows;
  };
  build_delta_array(array_file){
    var builded_array = {}; var indexed_array = {};
    // for (const a in array_file) {
    for (const b in array_file[0]) {
      // console.log(array_file[0][b]);

      indexed_array[array_file[0][b]] = b;
    }
    // }
  }
  get_date_ralenti (string) {
    var str_splited = string.split(' ');
    var date = str_splited[0];
    return date;
  }
  get_time_ralenti (string) {
    var total_second = 0;
    var str_splited = string.split(' ');
    for (let i = 0; i < str_splited.length; i++) {
      if (/[h]/.test(str_splited[i])) {
        var hour_replaced = str_splited[i].replace('h', '');
        total_second += parseInt(hour_replaced) * 3600;
      }
      if (/[m]/.test(str_splited[i])) {
        var minute_replaced = str_splited[i].replace('m', '');
        total_second += parseInt(minute_replaced) * 60;
      }
      if (/[s]/.test(str_splited[i])) {
        var second_replaced = str_splited[i].replace('s', '');
        total_second += parseInt(second_replaced);
      }
    }    
    return total_second;
  }
  build_ralenti_array(array_file){
    var term = ['Fecha Hora','Tiempo','Estado','Placa'];
    var builded_array = {}; var indexed_array = {}; var line = 0;    
    for (const a in array_file) {
      line++;
      if (array_file[a][term[0]] === undefined) { 
        if (line === 1) { cls_general_funct.shot_toast(`Retire el membrete`); return false; }
        cls_general_funct.shot_toast(`Archivo Corrupto, Fecha (Linea: ${line})`); return false; 
      }
      if (array_file[a][term[1]] === undefined) { cls_general_funct.shot_toast(`Archivo Corrupto, Tiempo (Linea: ${line})`); return false; }
      if (array_file[a][term[2]] === undefined) { cls_general_funct.shot_toast(`Archivo Corrupto, Estado (Linea: ${line})`); return false;  }
      if (array_file[a][term[3]] === undefined) { cls_general_funct.shot_toast(`Archivo Corrupto, Placa (Linea: ${line})`); return false; }

      var date = this.get_date_ralenti(array_file[a][term[0]]);
      var time = this.get_time_ralenti(array_file[a][term[1]]);
      if (array_file[a][term[2]] === 'Inmovil/Encendido') {
        if (builded_array[array_file[a][term[3]]] === undefined) {
          builded_array[array_file[a][term[3]]] = {}
        }
        if (builded_array[array_file[a][term[3]]][date] != undefined) {
          var toplus = parseInt(builded_array[array_file[a][term[3]]][date]);          
          builded_array[array_file[a][term[3]]][date] = toplus+time;
        } else { 
          builded_array[array_file[a][term[3]]][date] = time;
        }
      }
    }
    return builded_array;
  }
  build_array(array_file){
    var sourcefile = document.getElementById("sel_sourcefile").value;    
    switch (sourcefile) {
      case 'delta':
        break;
        this.build_delta_array(array_file);
      case 'ralenti':   //    RALENTI
        var ralenti_array = this.build_ralenti_array(array_file);
        console.log(ralenti_array);
        
        if (!ralenti_array) { console.log('No se ejecuta'); return false; }
        var array_unit = cls_data_sample.unit;
        var url = 'data/ralenti';
        var method = 'POST';
        var body = JSON.stringify({ a: ralenti_array, b: array_unit });
        var funcion = function (data_obj) {
          cls_general_funct.shot_toast(data_obj['message'],{autohide:false});
        }
        cls_general_funct.async_laravel_request(url, method, funcion, body)        
      break;
    }
  }
  UploadProcess() {
    var fileUpload = document.getElementById("fileUpload");
    var sourcefile = document.getElementById("sel_sourcefile").value;
    switch (sourcefile) {
      case 'delta':     //    DELTA
        var regex_delta = /^delta/g;
        if (regex_delta.test(fileUpload.value.toLowerCase())) {
          cls_general_funct.shot_toast('Archivo Incorrecto.');
          return false;
        }
      break;
      case 'ralenti':   //    RALENTI
        var regex_ralenti = /^ralenti/g;
        if (regex_ralenti.test(fileUpload.value.toLowerCase())) {
          cls_general_funct.shot_toast('Archivo Incorrecto.');
          return false;
        }
      break;
    }

    //Validate whether File is valid Excel file.
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
    if (regex.test(fileUpload.value.toLowerCase())) {
      if (typeof (FileReader) != "undefined") {
        var reader = new FileReader();
        //For Browsers other than IE.
        if (reader.readAsBinaryString) {
          reader.onload = function (e) {
            
            var array_file =  cls_vehicule.GetTableFromExcel(e.target.result);
            cls_vehicule.build_array(array_file);
          };
          reader.readAsBinaryString(fileUpload.files[0]);
        } else {
          //For IE Browser.
          reader.onload = function (e) {
            var data = "";
            var bytes = new Uint8Array(e.target.result);
            for (var i = 0; i < bytes.byteLength; i++) {
              data += String.fromCharCode(bytes[i]);
            }
            var array_file = cls_vehicule.GetTableFromExcel(data);
            cls_vehicule.build_array(array_file);
          };
          reader.readAsArrayBuffer(fileUpload.files[0]);
        }
      } else {
        cls_general_funct.shot_toast('This browser does not support HTML5.');
      }
    } else {
      cls_general_funct.shot_toast('Please upload a valid Excel file.');
    }


  };




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
    var content_head = '';
    for (const a in array_head) {
      content_head += `<th>${a}</th>`;
      field_list.push(array_head[a]);
    }
    var content_body = '';
    for (const a in array_vehicule) {
      var row_body = '';
      for (let b = 0; b < field_list.length; b++) {
        row_body += `
        <td>${array_vehicule[a][field_list[b]]}</td>
        `;
      }
      var block_icon = (array_vehicule[a]['int_vehicule_status'] === 0) ? 'check' : 'trash-alt';
      var background = (array_vehicule[a]['int_vehicule_status'] === 0) ? 'bg-info' : '';
      content_body += `<tr class="${background}">${row_body}
        <td class="text-center">
            <button type="button" onclick="cls_vehicule.edit_vehicule(${array_vehicule[a]['tx_vehicule_slug']},'${array_vehicule[a]['tx_vehicule_brand']}','${array_vehicule[a]['tx_vehicule_model']}','${array_vehicule[a]['vehicule_ai_company_id']}')" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_editvehicule"><i class="fas fa-wrench"></i></button>
            &nbsp;&nbsp;
            <button type="button" onclick="cls_vehicule.block_vehicule(${array_vehicule[a]['tx_vehicule_slug']})" class="btn btn-sm btn-warning"><i class="fas fa-${block_icon}"></i></button>
        </td>
      </tr>`;
    }
    var content = `<table class="table table-condensed table-bordered table-striped"><thead class="bg-secondary text-center"><tr>${content_head}<td></td></tr></thead><tbody>`;
    content += `${content_body}</tbody><tfoot class="bg-secondary"><tr><td colspan="${field_list.length+1}"></td></table>`;
    document.getElementById("label_vehiculefilter").innerHTML = caption;
    $("#container_vehicule_filtered").fadeOut('fast', () => {
      document.getElementById("container_vehicule_filtered").innerHTML = content;
    });
    $("#container_vehicule_filtered").fadeIn();
  }
  filter_vehicule(){
    var str = document.getElementById("txt_vehicule_filter");
    str = (cls_general_funct.is_empty_var(str.value) === 0) ? 'All' : str.value;
    var limit = document.getElementById("sel_vehicule_quantity").value;
    var url = 'vehicule/'+str+'*-*'+limit;
    var method = 'GET';
    var funcion = function (data_obj) {
      var array_head = {
        'Placa': 'tx_vehicule_licenseplate',
        'Compa&ntilde;&iacute;a': 'tx_company_description',
        'Marca': 'tx_vehicule_brand',
        'Modelo': 'tx_vehicule_model'
      }
      var label = `Se buscó "${str}". Mostrando`;
      label += (data_obj['vehicule_count'] > limit) ? ` ${limit} de ${data_obj['vehicule_count']}.` : ` ${data_obj['vehicule_count']} de ${data_obj['vehicule_count']}.`;
      cls_vehicule.render_vehicule_list(data_obj['vehicule_list'], array_head, label)

    }
    cls_general_funct.async_laravel_request(url, method, funcion)
  }

  edit_vehicule(slug,brand,model,company){
    document.getElementById('txt_editvehicule_selected').value = slug;
    document.getElementById('txt_editvehicule_brand').setAttribute('placeholder', brand);
    document.getElementById('txt_editvehicule_model').setAttribute('placeholder', model);
    document.getElementById('sel_editvehicule_company').value = company;
  }
  update_vehicule(){
    var vehicule_id = document.getElementById('txt_editvehicule_selected').value;
    var brand = document.getElementById('txt_editvehicule_brand');
    brand = (cls_general_funct.is_empty_var(brand.value) === 0) ? brand.getAttribute('placeholder') : brand.value;
    var model = document.getElementById('txt_editvehicule_model');
    model = (cls_general_funct.is_empty_var(model.value) === 0) ? model.getAttribute('placeholder') : model.value;
    var company = document.getElementById('sel_editvehicule_company').value;
    var limit = document.getElementById("sel_vehicule_quantity").value;

    var url = 'vehicule/whyouseethis';
    var method = 'PUT';
    var body = JSON.stringify({ a: vehicule_id, b: brand, c: model, d: company, limit: limit });
    var funcion = function (data_obj) {
      $('#modal_editvehicule').modal('hide');
      var array_head = {
        'Placa': 'tx_vehicule_licenseplate',
        'Compa&ntilde;&iacute;a': 'tx_company_description',
        'Marca': 'tx_vehicule_brand',
        'Modelo': 'tx_vehicule_model'
      }
      cls_vehicule.render_vehicule_list(data_obj['vehicule_list'], array_head)
      cls_vehicule.clear_editvehicule_form();
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)
  }
  clear_editvehicule_form(){
    document.getElementById('txt_editvehicule_selected').value = '';
    document.getElementById('txt_editvehicule_brand').value = '';
    document.getElementById('txt_editvehicule_model').value = '';
  }
  block_vehicule(vehicule_slug) {
    var url = 'vehicule/' + vehicule_slug;
    var method = 'DELETE';
    var limit = document.getElementById("sel_vehicule_quantity").value;
    var body = JSON.stringify({ a: limit});
    var funcion = function (data_obj) {
      var array_head = {
        'Placa': 'tx_vehicule_licenseplate',
        'Compa&ntilde;&iacute;a': 'tx_company_description',
        'Marca': 'tx_vehicule_brand',
        'Modelo': 'tx_vehicule_model'
      }
      cls_vehicule.render_vehicule_list(data_obj['vehicule_list'], array_head)
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)
  }




}
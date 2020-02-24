// JavaScript Document
class class_option
{
  validate_form () {   
    var valid = true; var selector; var message = ''
    var array_field = ['txt_unit_distance', 'txt_unit_time', 'txt_unit_volume', 'txt_unit_currency'];
    for (let a = 0; a < array_field.length; a++) {
      selector = document.getElementById(array_field[a]);
      if (cls_general_funct.isEmpty(selector) === 0) { valid = false; message = 'Faltan datos.'; cls_general_funct.set_invalid(selector); } else { cls_general_funct.set_valid(selector); };
      var selector_value = selector.value;
      if (parseInt(selector.value.length) > 4) { valid = false; message = 'Esta excediendo la cantidad de caracteres.'; cls_general_funct.set_invalid(selector) };
    }
    
    var array_answer = {'valid' : valid, 'message' : message};
    return array_answer;
  }
  save_unit () {
    var valid = this.validate_form();
    if (!valid['valid']) { cls_general_funct.shot_toast(valid['message']); return false; }
    var distance = document.getElementById('txt_unit_distance').value;
    var time = document.getElementById('txt_unit_time').value;
    var volume = document.getElementById('txt_unit_volume').value;
    var currency = document.getElementById('txt_unit_currency').value;
    var url = '/option/unit/update';
    var method = 'PUT';
    var body = JSON.stringify({ a: distance, b: time, c: volume, d: currency });
    var funcion = function (data_obj) {
      cls_general_funct.shot_toast(data_obj['message']);
      cls_general_funct.set_neutral(document.getElementById('txt_unit_distance'));
      cls_general_funct.set_neutral(document.getElementById('txt_unit_time'));
      cls_general_funct.set_neutral(document.getElementById('txt_unit_volume'));
      cls_general_funct.set_neutral(document.getElementById('txt_unit_currency'));
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)

  }
}
// JavaScript Document

class general_funct {
  set_invalid(selector) {
    selector.classList.remove("input_valid");
    selector.className += " input_invalid";
  }
  set_valid(selector) {
    selector.classList.remove("input_invalid");
    selector.className += " input_valid";
  }
  set_neutral(selector) {
    selector.classList.remove("input_valid");
    selector.classList.remove("input_invalid");
  }
  validatedate(inputText) {
    var valid = true;
    var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
    // Match the date format through regular expression
    if (inputText.value.match(dateformat)) {
      //Test which seperator is used '/' or '-'
      var opera1 = inputText.value.split('/');
      var opera2 = inputText.value.split('-');
      var lopera1 = opera1.length;
      var lopera2 = opera2.length;
      // Extract the string into month, date and year
      if (lopera1 > 1) {
        var pdate = inputText.value.split('/');
      }
      else if (lopera2 > 1) {
        var pdate = inputText.value.split('-');
      }
      var dd = parseInt(pdate[0]);
      var mm = parseInt(pdate[1]);
      var yy = parseInt(pdate[2]);
      // Create list of days of a month [assume there is no leap year by default]
      var ListofDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
      if (mm == 1 || mm > 2) {
        if (dd > ListofDays[mm - 1]) {
          cls_general_funct.shot_toast('Fecha Invalida');
          return valid = false;
        }
      }
      if (mm == 2) {
        var lyear = false;
        if ((!(yy % 4) && yy % 100) || !(yy % 400)) {
          lyear = true;
        }
        if ((lyear == false) && (dd >= 29)) {
          cls_general_funct.shot_toast('Fecha Invalida');
          return valid = false;
        }
        if ((lyear == true) && (dd > 29)) {
          cls_general_funct.shot_toast('Fecha Invalida');
          return valid = false;
        }
      }
    }
    else {
      inputText.className += " invalid";
      cls_general_funct.shot_toast('Fecha Invalida');
      return valid = false;
    }
    return valid;
  }
  // ###############3   VERIFICAR VACIOS
  isEmpty(field,set_invalid=true) {
    if (field.value.length === 0 || /^\s+$/.test(field.value)) {
      if (set_invalid) {  field.className += " invalid";  }
      return 0;  //Vacio
    } else {
      if (set_invalid) { field.classList.remove('invalid'); }
      return 1;  //Lleno
    }
  } 
  is_empty_var(string) {
    if (string.length === 0 || /^\s+$/.test(string)) {
      return 0;  //Vacio
    } else {
      return 1;  //Lleno
    }
  }
  set_empty(selector) {
    selector.value = '';
    this.set_neutral(selector);
  }
  // #########        LARAVEL REQUEST-fetch
  laravel_request(url, method, funcion, body_json = '') //method es un string
  {
    var myHeaders = new Headers({ "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'), "Content-Type": "application/json" });
    var myInit = { method: method, headers: myHeaders, mode: 'cors', cache: 'default' };
    if (body_json != '') {
      myInit['body'] = body_json
    }
    var myRequest = new Request(url, myInit);
    fetch(myRequest)
      .then(function (response) {
        return response.json();
      })
      .then(function (json_obj) {
        if (json_obj) {
          funcion(json_obj);
        }
      })
      .catch(function (error) {
        console.log(error);
      });
  }
  // #########        ASYNC-AWAIT LARAVEL REQUEST-fetch
  async async_laravel_request(url, method, funcion, body_json = '') //method es un string
  {
    var myHeaders = new Headers({ "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'), "Content-Type": "application/json" });
    var myInit = { method: method, headers: myHeaders, mode: 'cors', cache: 'default' };
    if (body_json != '') {
      myInit['body'] = body_json
    }
    var myRequest = new Request(url, myInit);
    let response = await fetch(myRequest)
    let json_obj = await response.json();
    if (json_obj) { funcion(json_obj); }
  }

  shot_toast(message) {
    document.getElementById("toast_container").innerHTML = `
      <div id="toast_message" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-dark">
          <strong class="mr-auto text-light">Mensaje</strong>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="toast-body" id="toast_message_body bg-danger">
          ${message}
        </div>
      </div>
    `;
    var option = { "delay": 3000 };
    $('.toast').toast(option);
    $('#toast_message').toast('show');
  }
  date_converter(from, to, string) {
    var raw_fecha = string.split('-');
    var from_splited = from.split('');
    var array_fecha = {};
    for (const a in from_splited) {
      array_fecha[from_splited[a]] = raw_fecha[a];
    }
    var to_splited = to.split('');
    return array_fecha[to_splited[0]] + '-' + array_fecha[to_splited[1]] + '-' + array_fecha[to_splited[2]];
  }
  //  ###### FUNCION DE REPORTE A PPT
  //  report_ppt (name = '', array_data = {}) {
  report_ppt() {
    const name = 'BJ6761';
    const array_data = {
      "2019-08-01": { "distance": "120", "volume": "60", "symbol": "240" },
      "2019-08-08": { "distance": "140", "volume": "70", "symbol": "280" },
      "2019-08-15": { "distance": "210", "volume": "105", "symbol": "420" },
      "2019-08-22": { "distance": "185", "volume": "92.5", "symbol": "370" },
      "2019-08-29": { "distance": "", "volume": "44", "symbol": "220" }
    };

    var array_distance = {};
    var array_volume = {};
    var array_symbol = {};

    for (const a in array_data) {
      if (array_data[a]['distance'].length > 0) {
        array_distance[a] = array_data[a]['distance'];
      }
      if (array_data[a]['volume'].length > 0) {
        array_volume[a] = array_data[a]['volume'];
      }
      if (array_data[a]['symbol'].length > 0) {
        array_symbol[a] = array_data[a]['symbol'];
      }
    }

    var pptx = new PptxGenJS();
    pptx.setAuthor('All Data Logistic');
    pptx.setCompany('All Data Logistic, S.A.');
    pptx.setSubject('Reportes de Consumo');
    pptx.setTitle('Reporte Vehiculo ' + name);
    // #############################     SLIDE MASTER    ####################
    pptx.defineSlideMaster({
      title: 'MASTER_SLIDE',
      bkgd: 'FFFFFF',
      objects: [
        { 'rect': { x: 0.0, y: 0.1, w: '100%', h: 0.5, fill: '4054b2' } },
        { 'text': { text: 'Reporte de Eficiencia', options: { x: 0.5, y: 0.1, w: 5.5, h: 0.5, bold: true, color: 'FFFFFF' } } },
        { 'image': { x: 8.5, y: 0.15, w: 1.2, h: 0.4, path: 'attached/image/logo.png' } }
      ],
      // slideNumber: { x: 0.1, y: '95%' }
    });
    // SLIDE MASTER

    //   ######################                  SLIDE DE BIENVENIDA AQUI


    cls_data_sample.generate_chart(pptx, array_distance, 'distance');
    cls_data_sample.generate_chart(pptx, array_volume, 'volume');
    cls_data_sample.generate_chart(pptx, array_symbol, 'symbol');
    pptx.save('Reporte de Eficiencia ' + name);

  }
  validFranz(selector, raw_acept, alt = '') { // raw_acept es un array
  // var raw_acept = acept.split(' '); // Allowed separated by spaces
    var characters = '';
    for (var i in raw_acept) {
      switch (raw_acept[i]) {
        case 'number': characters += '0123456789'; break;
        case 'simbol': characters += '¡!¿?@&%$"#º\''; break;
        case 'punctuation': characters += ',.:;'; break;
        case 'mathematic': characters += '+-*/='; break;
        case 'close': characters += '[]{}()<>'; break;
        case 'word': characters += ' abcdefghijklmnñopqrstuvwxyzáéíóú'; break;
      }
    }
    $("#" + selector).validCampoFranz(characters + alt);
  }
  isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }
}
//   #######################     GENERALES




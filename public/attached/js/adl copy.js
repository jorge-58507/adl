// JavaScript Document

class general_funct {
  validatedate(inputText) {
    var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
    // Match the date format through regular expression
    if (inputText.value.match(dateformat)) {
      // document.form1.text1.focus();
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
          alert('Fecha Invalida');
          return false;
        }
      }
      if (mm == 2) {
        var lyear = false;
        if ((!(yy % 4) && yy % 100) || !(yy % 400)) {
          lyear = true;
        }
        if ((lyear == false) && (dd >= 29)) {
          alert('Fecha Invalida');
          return false;
        }
        if ((lyear == true) && (dd > 29)) {
          alert('Fecha Invalida');
          return false;
        }
      }
    }
    else {
      alert('Fecha Invalida');
      // document.form1.text1.focus();
      return false;
    }
  }
  // ###############3   VERIFICAR VACIOS
  isEmpty (field){
    if (field.value.length === 0 || /^\s+$/.test(field.value)) {
      return 0;
    } else {
      return 1;
    }
  }
  // #########        LARAVEL REQUEST-fetch
  laravel_request(url, method, funcion, body_json = '') //method es un string
  {
    myHeaders = new Headers({ "Content-Type": "application/json" });
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
    var myHeaders = new Headers({"Content-Type": "application/json" });
    var myInit = { method: method, headers: myHeaders, mode: 'cors', cache: 'default' };
    if (body_json != '') {
      myInit['body'] = body_json
    }
    var myRequest = new Request(url, myInit);
    let response = await fetch(myRequest)
    let json_obj = await response.json();
    if (json_obj) { funcion(json_obj); }
  }

  shot_toast (message) {   
    document.getElementById("toast_container").innerHTML += `
      <div id="toast_message" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="mr-auto">Mensaje</strong>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="toast-body" id="toast_message_body">
          ${message}
        </div>
      </div>
    `;
    var option = { "delay": 2000 };
    $('.toast').toast(option);
    $('#toast_message').toast('show');
  }
  date_converter(from,to,string) {
    var raw_fecha = string.split('-');
    var from_splited = from.split('');
    var array_fecha = {};
    for (const a in from_splited) {
      array_fecha[from_splited[a]] = raw_fecha[a];
    }
    var to_splited = to.split('');
    return array_fecha[to_splited[0]] + '-' + array_fecha[to_splited[1]] + '-' + array_fecha[to_splited[2]];
  }
  report_ppt () {
    var pptx = new PptxGenJS();
    pptx.setAuthor('All Data Logistic');
    pptx.setCompany('All Data Logistic, S.A.');
    pptx.setSubject('Reportes de Consumo');
    pptx.setTitle('Reporte Vehiculo');

    var gArrNamesF = ['Markiplier', 'Jack', 'Brian', 'Paul', 'Ev', 'Ann', 'Michelle', 'Jenny', 'Lara', 'Kathryn'];
    var gArrNamesL = ['Johnson', 'Septiceye', 'Lapston', 'Lewis', 'Clark', 'Griswold', 'Hart', 'Cube', 'Malloy', 'Capri'];
    var gStrHello = 'BONJOUR - CIAO - GUTEN TAG - HELLO - HOLA - NAMASTE - OLÀ - ZDRAS-TVUY-TE - こんにちは - 你好';
    var gOptsTabOpts = { x: 0.4, y: 0.13, w: 12.5, colW: [9, 3.5] };
    var gOptsTextL = { color: '9F9F9F', margin: 3, border: [0, 0, { pt: '1', color: 'CFCFCF' }, 0] };
    var gOptsOptsR = { color: '9F9F9F', margin: 3, border: [0, 0, { pt: '1', color: 'CFCFCF' }, 0], align: 'right' };
    var gOptsTextR = { text: 'PptxGenJS', options: gOptsOptsR };
    var gOptsCode = { color: '9F9F9F', margin: 3, border: { pt: '1', color: 'CFCFCF' }, fill: 'F1F1F1', fontFace: 'Courier', fontSize: 12 };
    var gOptsSubTitle = { x: 0.5, y: 0.7, w: 4, h: 0.3, fontSize: 18, fontFace: 'Arial', color: '0088CC', fill: 'FFFFFF' };
    var gDemoTitleText = { fontSize: 14, color: '0088CC', bold: true };
    var gDemoTitleOpts = { fontSize: 13, color: '9F9F9F' };


    pptx.setLayout('LAYOUT_WIDE');

    pptx.defineSlideMaster({
      title: 'MASTER_SLIDE',
      bkgd: '4054b2',
      objects: [
        { 'rect': { x: 0.0, y: 6.00, w: '100%', h: 0.85, fill: 'F1F1F1' } },
        { 'text': { text: 'Reporte de Eficiencia', options: { x: 2.0, y: 6.10, w: 5.5, h: 0.75, bold: true } } },
        { 'image': { x: 11.3, y: 6.10, w: 1.67, h: 0.75, path: 'attached/image/logo.png' } }
      ],
      slideNumber: { x: 0.3, y: '95%' }
    });

    var slide = pptx.addNewSlide('MASTER_SLIDE');
    slide.addText([{ text: 'Grafica de Lineas', options: { x: 4.0, y: 1.0, fontSize: 16, bold: true, color: 'FFFFFF' } }]);
    slide.addText('Hello', { x: 0.5, y: 0.7, w: 3, color: 'FFFFFF', fontSize: 64 });
    var dataChartAreaLine = [
      {
        name: 'Actual Sales',
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        values: [1500, 4600, 5156, 3167, 8510, 8009, 6006, 7855, 12102, 12789, 10123, 15121]
      },
      {
        name: 'Projected Sales',
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        values: [1000, 2600, 3456, 4567, 5010, 6009, 7006, 8855, 9102, 10789, 11123, 12121]
      }
    ];
    slide.addChart(pptx.charts.LINE, dataChartAreaLine, { x: 0.5, y: 0.5, w: 10, h: 4 });

    
    var slide = pptx.addNewSlide('MASTER_SLIDE');
    // var slide = pptx.addNewSlide();
    slide.addNotes('API Docs: https://gitbrent.github.io/PptxGenJS/docs/api-charts.html');
    slide.addTable([[{ text: 'Chart Examples: Pie Charts: Legends', options: gOptsTextL }, gOptsTextR]], gOptsTabOpts);

    var dataChartPieStat = [
      {
        name: 'Project Status',
        labels: ['Red', 'Amber', 'Green', 'Unknown'],
        values: [8, 20, 30, 2]
      }
    ];

    // INTERNAL USE: Not visible to user (its behind a chart): Used for ensuring ref counting works across obj types (eg: `rId` check/test)
    if (NODEJS) slide.addImage({ path: (NODEJS ? gPaths.ccCopyRemix.path.replace(/http.+\/examples/, '../examples') : gPaths.ccCopyRemix.path), x: 0.5, y: 1.0, w: 1.2, h: 1.2 });

    // TOP-LEFT
    slide.addText('.', { x: 0.5, y: 0.5, w: 4.2, h: 3.2, fill: 'F1F1F1', color: 'F1F1F1' });
    slide.addChart(
      pptx.charts.PIE, dataChartPieStat,
      { x: 0.5, y: 0.5, w: 4.2, h: 3.2, showLegend: true, legendPos: 'l', legendFontFace: 'Courier New' }
    );
    pptx.save('');

  }
}
//   #######################     GENERALES
class data_sample
{
  constructor () {
    this.unit = {"distance" : "Km", "volume" : "Ltrs", "symbol" : "Bl"};
  }
  save_data () {
    document.getElementById("btn_submit_form").disabled = true;
    var vehicule = document.getElementById("sel_vehicule").value;
    cls_general_funct.validatedate(document.getElementById("txt_date"));
    var date = document.getElementById("txt_date").value;
    var data_exist = 0;
    var raw_data = {};
    if (cls_general_funct.isEmpty(document.getElementById("txt_distance")) === 1) {
      data_exist = 1;
      raw_data = { "distance" : document.getElementById("txt_distance").value}
    } else { raw_data = { "distance": '' } }
    if (cls_general_funct.isEmpty(document.getElementById("txt_litre")) === 1) {
      data_exist = 1;
      raw_data.volume = document.getElementById("txt_litre").value
    } else { raw_data.volume = '' }
    if (cls_general_funct.isEmpty(document.getElementById("txt_pab")) === 1) {
      data_exist = 1;
      raw_data.symbol = document.getElementById("txt_pab").value
    } else { raw_data.symbol = '' }
    if (data_exist === 1 && cls_general_funct.isEmpty(document.getElementById("txt_date")) === 1) {
      var url = 'attached/get/sample_funct.php';
      var method = 'POST';
      var body = JSON.stringify({ a: date, b: vehicule, c: raw_data });
      var funcion = function (data_obj) {       
        cls_data_sample.render_sample_table(JSON.parse(data_obj['data']))
        cls_general_funct.shot_toast(data_obj['message']);
      }
      cls_general_funct.async_laravel_request(url, method, funcion, body)
    } else {  
      cls_general_funct.shot_toast('Faltan Datos.');
    }
    setTimeout(() => {
      document.getElementById("btn_submit_form").disabled = false;
    }, 5000)
    // VERIFICAR VACIOS
  }
  render_sample_table (array_object){   
    var table =`
      <table class="table table-striped table-sm table-bordered">
        <thead class="table-secondary">
          <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">Fecha</th>
            <th scope="col" class="text-center">Kms</th>
            <th scope="col" class="text-center">Litros</th>
            <th scope="col" class="text-center">Balboas</th>
            <th scope="col" class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
    `;
    for (const a in array_object) {
      var array_sample = JSON.parse(array_object[a]['tx_data_sample']);
      var i = parseInt(a)+1;
      var distance = (array_sample['distance'].length > 0) ? array_sample['distance'] + ' ' + this.unit['distance'] : '';
      var volume = (array_sample['volume'].length > 0) ? array_sample['volume'] + ' ' + this.unit['volume'] : '';
      var symbol = (array_sample['symbol'].length > 0) ? array_sample['symbol'] + ' ' + this.unit['symbol'] : '';
      table += `
      <tr>
        <th scope="row">${i}</th>
        <td>${cls_general_funct.date_converter('ymd', 'dmy', array_object[a]['tx_data_date'])} </td>
        <td>${distance} </td>
        <td>${volume} </td>
        <td>${symbol} </td>
        <td>
          <button type="button" class="btn btn-danger" onclick="cls_data_sample.delete_sample(${array_object[a]['tx_data_slug']})"><i class="fa fa-times"></i> </button>
        </td>
      </tr>
      `;
    }
    table += `
      </tbody>
    <tfoot class="table-secondary"><tr><td colspan="6"></td></tr></tfoot>
    `;
    document.getElementById("container_data").innerHTML = table;
  }
}
class data_sample {
  constructor(array_unit) {
    if (array_unit['distance'].length > 0 && array_unit['time'].length > 0 && array_unit['volume'].length > 0 && array_unit['currency'].length > 0) {
      this.unit = { "distance": array_unit['distance'], "time": array_unit['time'], "volume": array_unit['volume'], "currency": array_unit['currency'] };
    } else {     
      this.unit = { "distance": 'Km', "time": 'Hr', "volume": 'Ltr', "currency": 'Bl' };
    }
  }
  validate_form (){
    var valid = true;
    valid = cls_general_funct.validatedate(document.getElementById("txt_date"));

    var data_exist = 0;                                                                           //VERIFICAR QUE EXISTAN DATOS
    if (cls_general_funct.isEmpty(document.getElementById("txt_distance")) === 1) {
      data_exist = 1;
    }
    if (cls_general_funct.isEmpty(document.getElementById("txt_volume")) === 1) {
      data_exist = 1;
    }
    if (cls_general_funct.isEmpty(document.getElementById("txt_currency")) === 1) {
      data_exist = 1;
    }
    if (cls_general_funct.isEmpty(document.getElementById("txt_time")) === 1) {
      data_exist = 1;
    }
    if (data_exist === 0) {
      cls_general_funct.set_invalid(document.getElementById("txt_distance"));
      cls_general_funct.set_invalid(document.getElementById("txt_volume"));
      cls_general_funct.set_invalid(document.getElementById("txt_currency"));
      cls_general_funct.set_invalid(document.getElementById("txt_time"));
      cls_general_funct.shot_toast('Debe ingresar al menos un (1) dato.');
      nextPrev(0);
      valid = false;
    } else {
      $("#txt_distance,#txt_volume,#txt_currency,#txt_time").removeClass('input_invalid');
      $("#txt_distance,#txt_volume,#txt_currency,#txt_time").addClass('input_neutral');
    }
    var ans_vehicule = cls_general_funct.isEmpty(document.getElementById("sel_vehicule"));      //VERIFICAR VEHICULO SELECTO
    if (ans_vehicule === 0) { 
      nextPrev(-1);
      cls_general_funct.set_invalid(document.getElementById("sel_vehicule"));
      cls_general_funct.shot_toast('Debe seleccionar un vehículo.');
      valid = false;
      return valid;
    }else{
      if (document.getElementById("sel_vehicule").classList.contains('input_invalid')){
        cls_general_funct.set_valid(document.getElementById("sel_vehicule"));
      }
    }
    return valid;
  }
  clear_form () {
    cls_general_funct.set_empty(document.getElementById("txt_distance"));
    cls_general_funct.set_empty(document.getElementById("txt_volume"));
    cls_general_funct.set_empty(document.getElementById("txt_currency"));
    cls_general_funct.set_empty(document.getElementById("txt_time"));
  }
  save_data() {
    document.getElementById("btn_submit_form").disabled = true;
    setTimeout(() => {  document.getElementById("btn_submit_form").disabled = false;  }, 5000)
    var valid = this.validate_form();    
    if (!valid) { return valid; }
    var date = document.getElementById("txt_date").value;
    var vehicule = document.getElementById("sel_vehicule").value;
    var raw_data = {};
    raw_data.distance = (cls_general_funct.isEmpty(document.getElementById("txt_distance")) === 1) ? document.getElementById("txt_distance").value  : '';
    raw_data.volume   = (cls_general_funct.isEmpty(document.getElementById("txt_volume"))   === 1) ? document.getElementById("txt_volume").value    : '';
    raw_data.currency = (cls_general_funct.isEmpty(document.getElementById("txt_currency")) === 1) ? document.getElementById("txt_currency").value  : '';
    raw_data.time     = (cls_general_funct.isEmpty(document.getElementById("txt_time"))     === 1) ? document.getElementById("txt_time").value      : '';    
    var array_unit = this.unit;
    var url = 'data';
    var method = 'POST';
    var body = JSON.stringify({ a: date, b: vehicule, c: raw_data, d: array_unit});
    var funcion = function (data_obj) {
      cls_data_sample.render_sample_table(data_obj['data_list']);
      cls_data_sample.clear_form();
      cls_general_funct.shot_toast(data_obj['message']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion, body)
  }
  render_sample_table(array_object) {   
    var table = `
      <table class="table table-striped table-sm table-bordered">
        <thead class="table-secondary">
          <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">Fecha</th>
            <th scope="col" class="text-center">Distancia</th>
            <th scope="col" class="text-center">Tiempo</th>
            <th scope="col" class="text-center">Volumen</th>
            <th scope="col" class="text-center">Dinero</th>
            <th scope="col" class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
    `;
    for (const a in array_object) {
      var array_sample = JSON.parse(array_object[a]['tx_data_sample']);
      var array_unit = JSON.parse(array_object[a]['tx_data_unit']);
      var i = parseInt(a) + 1;
      var distance = (array_sample['distance'] != null && array_sample['distance'].length > 0) ? array_sample['distance'] + ' ' + array_unit['distance'] : '';
      var time = (array_sample['time'] != null && array_sample['time'].length > 0) ? array_sample['time'] + ' ' + array_unit['time'] : '';
      var volume = (array_sample['volume'] != null && array_sample['volume'].length > 0) ? array_sample['volume'] + ' ' + array_unit['volume'] : '';
      var currency = (array_sample['currency'] != null && array_sample['currency'].length > 0) ? array_sample['currency'] + ' ' + array_unit['currency'] : '';
      table += `
      <tr>
        <th scope="row" data-toggle="tooltip" data-placement="left" title="${array_object[a]['name']}">${i}</th>
        <td>${cls_general_funct.date_converter('ymd', 'dmy', array_object[a]['tx_data_date'])} </td>
        <td>${distance} </td>
        <td>${time} </td>
        <td>${volume} </td>
        <td>${currency} </td>
        <td class="text-center">
          <button type="button" class="btn btn-danger" onclick="cls_data_sample.delete_sample('${array_object[a]['tx_data_slug']}')"><i class="fa fa-times"></i> </button>
        </td>
      </tr>
      `;
    }
    table += `
      </tbody>
    <tfoot class="table-secondary"><tr><td colspan="7"></td></tr></tfoot>
    `;
    document.getElementById("container_data").innerHTML = table;
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })  }
  read_all_byvehicule(vehicule) {
    var url = `data\\${vehicule}`;
    var method = 'GET';
    var funcion = function (data_obj) { 
      cls_data_sample.render_sample_table(data_obj['data_list']);
    }
    cls_general_funct.async_laravel_request(url, method, funcion)

  }
  delete_sample (data_slug) {    
    var url = '/data/' + data_slug; var method = 'DELETE';
    var body = '';    
    var funcion = function (data_obj) {
      cls_data_sample.render_sample_table(data_obj['data_list']);
    }
    cls_general_funct.laravel_request(url, method, funcion, body);
  }
  // ##############################       TOTAL    CHART     ##############################
  generate_total_chart(pptx,array_total,title,base_url){

    var total_distance = 0; 
    var distance_piechart = [{name: 'Distancia', labels: [],values: []}];
    for (const a in array_total['distance']) {
      total_distance += array_total['distance'][a][1];
      distance_piechart[0]['labels'].push(array_total['distance'][a][0])
      distance_piechart[0]['values'].push(array_total['distance'][a][1])
    }
    var total_time = 0;
    var time_piechart = [{ name: 'Tiempo', labels: [], values: [] }];
    for (const a in array_total['time']) {
      total_time += array_total['time'][a][1];
      time_piechart[0]['labels'].push(array_total['time'][a][0])
      time_piechart[0]['values'].push(array_total['time'][a][1])
    }
    var total_volume = 0;
    var volume_piechart = [{ name: 'Surtido', labels: [], values: [] }];
    for (const a in array_total['volume']) {
      total_volume += array_total['volume'][a][1];
      volume_piechart[0]['labels'].push(array_total['volume'][a][0])
      volume_piechart[0]['values'].push(array_total['volume'][a][1])
    }
    var total_currency = 0;
    var currency_piechart = [{ name: 'Dinero', labels: [], values: [] }];
    for (const a in array_total['currency']) {
      total_currency += array_total['currency'][a][1];
      currency_piechart[0]['labels'].push(array_total['currency'][a][0])
      currency_piechart[0]['values'].push(array_total['currency'][a][1])
    }

    var slide = pptx.addNewSlide('MASTER_SLIDE');
    slide.addText(title, { x: 0.0, y: 0.7, bold: true, fontSize: 18, w: '100%', align: 'c' });    
    
    var distance_unit = eval('data_sample.prototype.unit.distance');
    slide.addText('Distancia Total:', { x: 0.9, y: 1.1, bold: false, fontSize: 16, w: '100%' });
    slide.addImage({ path: base_url + 'attached/image/distance.jpeg', x: 1, y: 1.4, w: 0.5, h: 0.5 });
    slide.addText(`${Number(total_distance.toFixed(2))} ${distance_unit}`, { x: 1.5, y: 1.5, bold: false, fontSize: 14, w: '100%' });

    var time_unit = eval('data_sample.prototype.unit.time');
    slide.addText('Tiempo Total:', { x: 3.1, y: 1.1, bold: false, fontSize: 16, w: '100%' });
    slide.addImage({ path: base_url + 'attached/image/time.jpeg', x: 3.2, y: 1.4, w: 0.5, h: 0.5 });
    slide.addText(`${Number(total_time.toFixed(2))} ${time_unit}`, { x: 3.7, y: 1.5, bold: false, fontSize: 14, w: '100%' });

    var volume_unit = eval('data_sample.prototype.unit.volume');
    slide.addText('Surtido Total:', { x: 5.3, y: 1.1, bold: false, fontSize: 16, w: '100%' });
    slide.addImage({ path: base_url + 'attached/image/volumen.jpeg', x: 5.4, y: 1.4, w: 0.5, h: 0.5 });
    slide.addText(`${Number(total_volume.toFixed(2))} ${volume_unit}`, { x: 5.9, y: 1.5, bold: false, fontSize: 14, w: '100%' });

    var currency_unit = eval('data_sample.prototype.unit.currency');
    slide.addText('Dinero Total:', { x: 7.5, y: 1.1, bold: false, fontSize: 16, w: '100%' });
    slide.addImage({ path: base_url + 'attached/image/currency.jpeg', x: 7.6, y: 1.4, w: 0.5, h: 0.5 });
    slide.addText(`${Number(total_currency.toFixed(2))} ${currency_unit}`, { x: 8.1, y: 1.5, bold: false, fontSize: 14, w: '100%' });

    slide.addChart(pptx.charts.PIE, distance_piechart,  { x: 0.5, y: 2.6, w: 2.8, h: 1.5, showLegend: true, legendPos: 'l', legendFontSize: 8, legendFontFace: 'Courier New' });
    slide.addChart(pptx.charts.PIE, time_piechart,      { x: 3.1, y: 2.6, w: 2.8, h: 1.5, showLegend: true, legendPos: 'l', legendFontSize: 8, legendFontFace: 'Courier New' });
    slide.addChart(pptx.charts.PIE, volume_piechart,    { x: 4.9, y: 3.6, w: 2.8, h: 1.5, showLegend: true, legendPos: 'l', legendFontSize: 8, legendFontFace: 'Courier New' });
    slide.addChart(pptx.charts.PIE, currency_piechart,  { x: 7.4, y: 3.6, w: 2.8, h: 1.5, showLegend: true, legendPos: 'l', legendFontSize: 8, legendFontFace: 'Courier New' });

  }
  // ##############################       GENERATE    CHART     ##############################
  generate_chart(pptx, array_data, chart_checked, array_unit, title) {
    var gOptsTabOpts = { x: 0.4, y: 0.13, w: 12.5, colW: [9, 3.5] };
    var gOptsTextL = { color: '9F9F9F', margin: 3, border: [0, 0, { pt: '1', color: 'CFCFCF' }, 0] };
    var gOptsOptsR = { color: '9F9F9F', margin: 3, border: [0, 0, { pt: '1', color: 'CFCFCF' }, 0], align: 'right' };
    var gOptsTextR = { text: 'PptxGenJS', options: gOptsOptsR };


    var slide = pptx.addNewSlide('MASTER_SLIDE');

    slide.addText(title, { x: 0.0, y: 0.7, bold: true, fontSize: 18, w: '100%', align: 'c' });

    var dataChart = array_data;
    var y_axis =  (chart_checked.length <= 2) ? 1.2 : 3.1;
    for (let a = 0; a < chart_checked.length; a++) {
      var chart = chart_checked[a];
      switch (chart) {
        case 'LINE':
          slide.addChart(pptx.charts.LINE, dataChart, { x: 0.2, y: 1.2, w: 4.7, h: 2.1, showLegend: true });
          break;
        case 'BAR':
          slide.addChart(pptx.charts.BAR, dataChart, { x: 5.0, y: 1.2, w: 5, h: 2.1 });
          break;
        case 'PIE':          
          slide.addChart(pptx.charts.PIE, dataChart, { x: 5.0, y: y_axis, w: 5, h: 2.5, showLegend: true, legendPos: 'l', showValue: true, legendFontFace: 'Courier New' }    );
          break;
      } 
    }
    var unit_of = '';    
    for (let a = 0; a < array_unit.length; a++) {
      if (a == 0) {
        unit_of += eval('data_sample.prototype.unit.' + array_unit[a]);        
      } else {
        unit_of += '/' + eval('data_sample.prototype.unit.' + array_unit[a]);
      }
    }
    var row_head = [{ text: 'FECHA', options: { valign: 'm', align: 'c', fontFace: 'Arial', bold: true, fill: '7182d5', color: 'FFFFFF', fontSize: '12' }}]
    for (const i in dataChart) {
      row_head.push(
        { text: dataChart[i]['name'], options: { valign: 'm', align: 'c', fontFace: 'Arial', bold: true, fill: '7182d5', color: 'FFFFFF', fontSize: '12' } }
      )
    }
    var array_tablebody = {}
    for (const a in dataChart) {
      for (let b = 0; b < dataChart[a]['labels'].length; b++) {
        if (!array_tablebody[dataChart[a]['labels'][b]]) {
          array_tablebody[dataChart[a]['labels'][b]]='';  
        }
        array_tablebody[dataChart[a]['labels'][b]] += dataChart[a]['values'][b]+'*';
      }
    }
    var row_body = [];
    for (const a in array_tablebody) {
      var str_splited = array_tablebody[a].split("*");
      var raw_obj = [{ text: a, options: { align: 'c' } }]
      for (let b = 0; b < str_splited.length-1; b++) {
        var str = (str_splited[b] == 'null') ? 0 : str_splited[b];
        raw_obj.push({ text: `${str} ${unit_of}`, options: { align: 'c' } })
      }
      row_body.push(raw_obj);
    }    
    var rows = [row_head];
    for (let x = 0; x < row_body.length; x++) {
      rows.push(row_body[x])
    }

    var tabOpts = { x: 1.5, y: 3.5, w: 4, h: 1.5, fill: 'FFFFFF', fontSize: 8, color: '000000' };
    slide.addTable(rows, tabOpts);
  }


}

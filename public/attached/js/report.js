// javascript document

class class_report
{
constructor (vehicule_list)
{
  this.vehicule_list = vehicule_list;
}

  validate_form () {
    var valid = true;
    valid = cls_general_funct.validatedate(document.getElementById('txt_efficiency_from'));
    valid = cls_general_funct.validatedate(document.getElementById('txt_efficiency_until'));
    var cb_checked = false; var array_checked = [];
    // if (document.getElementById("cb_efficiency_chartline").checked === true) { cb_checked = true; array_checked.push('LINE') }
    // if (document.getElementById("cb_efficiency_chartbar").checked === true) { cb_checked = true; array_checked.push('BAR') }
    // if (document.getElementById("cb_efficiency_chartpie").checked === true) { cb_checked = true; array_checked.push('PIE') }
    var raw_answer = {'valid' : valid, 'cb_checked' : cb_checked, 'array_checked' : array_checked};
    return raw_answer;
  }
  // new_report () {
  //   var valid = this.validate_form ();
  //   if (!valid['valid'] || !valid['cb_checked']) { return false; }
  //   var array_checked = valid['array_checked'];
  //   var vehicule_slug = document.getElementById('sel_efficiency_vehicule').value;
  //   var from = document.getElementById('txt_efficiency_from').value;
  //   var until = document.getElementById('txt_efficiency_until').value;

  //   var url = '/report/'+vehicule_slug;
  //   var method = 'POST';
  //   var body = JSON.stringify({ a: from, b: until, c: vehicule_slug, d: array_checked });
  //   var funcion = function (data_obj) {
  //     $('#modal_efficiency').modal('hide');
  //     var array_data = {};
  //     var data_list = data_obj['data_list'];
  //     var name = data_list['vehicule'];

  //     var raw_data = data_list['datasample'];
  //     var array_unit; var array_obj;
  //     for (const a in raw_data) {
  //       if (a != 0 && array_unit != raw_data[a]['tx_data_unit']) {
  //         cls_general_funct.shot_toast('Los datos no conservan las mismas medidas, verifique.');  return false;
  //       }else{  
  //         array_unit = raw_data[a]['tx_data_unit'];
  //         if (array_data[raw_data[a]['tx_data_date']]) {
  //           var cooked_array = array_data[raw_data[a]['tx_data_date']]; 
  //           var raw_array = JSON.parse(raw_data[a]['tx_data_sample'])
  //           for (const a in cooked_array) {
  //             if (cooked_array[a] === null && raw_array[a] != null) {
  //               cooked_array[a] = raw_array[a];
  //             }
  //           }
  //           array_obj = cooked_array;
  //         }else{
  //           array_obj = JSON.parse(raw_data[a]['tx_data_sample']);
  //         }
  //         array_data[raw_data[a]['tx_data_date']] = array_obj;          

  //       }
  //     }
  //     cls_report.report_ppt(name,array_data,array_checked);      
  //   }
  //   cls_general_funct.async_laravel_request(url, method, funcion, body)

    
  // }

  new_report(data_list) {
    var array_data = {};
    var name = moment().format("DD-MM-YYYY");
    var array_data = data_list['distance'];
    var array_checked = ['LINE', 'BAR'];
    if (data_list['distance'].length === 1) {
      array_checked.push('PIE');
    }    
    cls_report.report_ppt(name, data_list, array_checked);
  }


  report_ppt(name, data_list, array_checked) {
    var array_distance = data_list['distance'];
    var array_time = data_list['time'];
    var array_volume = data_list['volume'];
    var array_currency = data_list['currency'];
    
    var array_distXvol = data_list['distancexvolume'];
    var array_timeXvol = data_list['timexvolume'];
    var array_currencyXvol = data_list['currencyxvolume'];

    // for (const a in array_data) {
      // if (array_data[a]['distance'] != null && array_data[a]['distance'].length > 0) {
      //   array_distance[a] = array_data[a]['distance'];
      // }
      
      // if (array_data[a]['time'] != null && array_data[a]['time'].length > 0) {
      //   array_time[a] = array_data[a]['time'];
      // }
      // if (array_data[a]['volume'] != null && array_data[a]['volume'].length > 0) {
      //   array_volume[a] = array_data[a]['volume'];
      // }
      // if (array_data[a]['currency'] != null && array_data[a]['currency'].length > 0) {
      //   array_currency[a] = array_data[a]['currency'];
      // }
    // }

    // for (const a in array_distance) {
    //   if (array_volume[a] != undefined && array_distance[a].length > 0 && array_volume[a].length > 0) {
    //     array_distXvol[a] = (array_distance[a] / array_volume[a]).toFixed(2);
    //   }
    // }

    // for (const a in array_time) {
    //   if (array_volume[a] != undefined && array_time[a].length > 0 && array_volume[a].length > 0) {
    //     array_timeXvol[a] = (array_time[a] / array_volume[a]).toFixed(2);
    //   }
    // }
    
    // for (const a in array_currency) {
    //   if (array_volume[a] != undefined  && array_currency[a].length > 0 && array_volume[a].length > 0) {
    //     array_currencyXvol[a] = (array_currency[a] / array_volume[a]).toFixed(2);
    //   }
    // }

    var pptx = new PptxGenJS();
    pptx.setAuthor('All Data Logistic');
    pptx.setCompany('All Data Logistic, S.A.');
    pptx.setSubject('Reportes de Consumo');
    pptx.setTitle('Reporte Vehicular ' + name);
    // #############################     SLIDE MASTER    ####################
    pptx.defineSlideMaster({
      title: 'MASTER_SLIDE',
      bkgd: 'FFFFFF',
      objects: [
        { 'rect': { x: 0.0, y: 0.1, w: '100%', h: 0.5, fill: '7182d5' } },
        { 'text': { text: 'Reporte de Eficiencia', options: { x: 0.5, y: 0.1, w: 5.5, h: 0.5, bold: true, color: 'FFFFFF' } } },
        { 'image': { x: 8.5, y: 0.15, w: 1.2, h: 0.4, path: '../../../../../attached/image/rect_long_logo.png' } },
        { 'image': { x: 1.0, y: 0.0, w: 8.0, h: 8.0, path: '../../../../../attached/image/watermark_logo.png' } }
      ],
      // slideNumber: { x: 0.1, y: '95%' }
    });
    pptx.defineSlideMaster({
      title: 'WELCOME_SLIDE',
      bkgd: '7182d5',
      objects: [
        { 'rect': { x: 0.0, y: 0.0, w: '100%', h: 0.5, fill: 'FFFFFF' } },
        { 'text': { text: 'Reporte de Eficiencia', options: { x: 0.5, y: 1.5, w: 5.5, h: 0.5, bold: true, color: 'FFFFFF', fontSize: 28 } } },
        { 'text': { text: 'de Combustible', options: { x: 2.0, y: 2.0, w: 5.5, h: 0.5, bold: true, color: 'FFFFFF', fontSize: 28 } } },
        { 'image': { x: 6.5, y: 2.0, w: 2.5, h: 2.5, path: '../../../../../attached/image/squared_logo.png' } },
        { 'rect': { x: 0.0, y: 4.0, w: '100%', h: 1.65, fill: 'FFFFFF' } }
      ],
    });

    // SLIDE MASTER

    //   ######################                  SLIDE DE BIENVENIDA AQUI
    var slide = pptx.addNewSlide('WELCOME_SLIDE');

    var counter_distance = 0;
    for (const a in array_distance) { counter_distance++; }    
    if (counter_distance > 0) { cls_data_sample.generate_chart(pptx, array_distance, array_checked, ['distance'],'Distancia Recorrida'); } 

    var counter_time = 0;
    for (const a in array_time) { counter_time++; }
    if (counter_time > 0) { cls_data_sample.generate_chart(pptx, array_time, array_checked, ['time'], 'Tiempo de Maquina'); }

    var counter_volume = 0;
    for (const a in array_volume) { counter_volume++; }
    if (counter_volume > 0) { cls_data_sample.generate_chart(pptx, array_volume, array_checked, ['volume'], 'Volumen Suministrado'); }

    var counter_currency = 0;
    for (const a in array_currency) { counter_currency++; }
    if (counter_currency > 0) { cls_data_sample.generate_chart(pptx, array_currency, array_checked, ['currency'], 'Dinero Gastado'); }

    var counter_distXvol = 0;
    for (const a in array_distXvol) { counter_distXvol++; }
    if (counter_distXvol > 0) { cls_data_sample.generate_chart(pptx, array_distXvol, array_checked, ['distance','volume'], 'Relación Distancia/Volumen'); }

    var counter_timeXvol = 0;
    for (const a in array_timeXvol) { counter_timeXvol++; }
    if (counter_timeXvol > 0) { cls_data_sample.generate_chart(pptx, array_timeXvol, array_checked, ['time','volume'], 'Relación Tiempo/Volumen'); }

    var counter_currencyXvol = 0;
    for (const a in array_currencyXvol) { counter_currencyXvol++; }
    if (counter_currencyXvol > 0) { cls_data_sample.generate_chart(pptx, array_currencyXvol, array_checked, ['currency', 'volume'], 'Relación Dinero/Volumen'); }
    
    pptx.save('ADL Reporte de Eficiencia ' + name);

  }

  redirect_dashboard() {
    $('#modal_efficiency').modal('hide');
    var some_checked = false; var vehicule_checked = [];
    var valid = cls_general_funct.validatedate(document.getElementById('txt_efficiency_from'));
    if (!valid) { cls_general_funct.shot_toast('Fecha Inicio Invalida'); return false; }
    var valid = cls_general_funct.validatedate(document.getElementById('txt_efficiency_until'));
    if (!valid) { cls_general_funct.shot_toast('Fecha Final Invalida'); return false; }

    for (const a in this.vehicule_list) {
      if (document.getElementById(this.vehicule_list[a]['tx_vehicule_slug']).checked === true) { some_checked = true; vehicule_checked.push(this.vehicule_list[a]['tx_vehicule_slug']) }
    }
    if (!some_checked) { cls_general_funct.shot_toast('Debe seleccionar un vehiculo'); return false; }

    var from = document.getElementById('txt_efficiency_from').value;
    var until = document.getElementById('txt_efficiency_until').value;


    var url = `/report/dashboard/${from}/${until}/${vehicule_checked}/`;
    window.location.href = url;
    return false;
  }
}
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
    var raw_answer = {'valid' : valid, 'cb_checked' : cb_checked, 'array_checked' : array_checked};
    return raw_answer;
  }
  new_report(data_list, base_url, array_total) {
    var name = moment().format("DD-MM-YYYY");
    var array_checked = ['LINE', 'BAR'];
    if (data_list['distance'].length === 1) {
      array_checked.push('PIE');
    }    
    cls_report.report_ppt(name, data_list, array_checked, base_url, array_total);
  }


  report_ppt(name, data_list, array_checked, base_url='',array_total) {
    var array_distance = data_list['distance'];
    var array_time = data_list['time'];
    var array_volume = data_list['volume'];
    var array_currency = data_list['currency'];
    var array_ralenti = data_list['ralenti'];
    
    var array_distXvol = data_list['distancexvolume'];
    var array_timeXvol = data_list['timexvolume'];
    var array_currencyXvol = data_list['currencyxvolume'];

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
        { 'image': { x: 8.5, y: 0.15, w: 1.2, h: 0.4, path: base_url+'attached/image/rect_long_logo.png' } },
        { 'image': { x: 1.0, y: 0.0, w: 8.0, h: 8.0, path: base_url +'attached/image/watermark_logo.png' } }
        // { 'image': { x: 8.5, y: 0.15, w: 1.2, h: 0.4, path: '../../../../../attached/image/rect_long_logo.png' } },
        // { 'image': { x: 1.0, y: 0.0, w: 8.0, h: 8.0, path: '../../../../../attached/image/watermark_logo.png' } }
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
        { 'image': { x: 6.5, y: 2.0, w: 2.5, h: 2.5, path: base_url+'attached/image/squared_logo.png' } },
        // { 'image': { x: 6.5, y: 2.0, w: 2.5, h: 2.5, path: '../../../../../attached/image/squared_logo.png' } },
        { 'rect': { x: 0.0, y: 4.0, w: '100%', h: 1.65, fill: 'FFFFFF' } }
      ],
    });

    // SLIDE MASTER

    //   ######################                  SLIDE DE BIENVENIDA AQUI
    var slide = pptx.addNewSlide('WELCOME_SLIDE');

    cls_data_sample.generate_total_chart(pptx, array_total, 'Totales', base_url);
    var limit = 8;
    var counter_distance = 0;
    for (const a in array_distance) { counter_distance++; }   //ZZZ
    if (counter_distance > 8) { cls_general_funct.shot_toast('El limite de vehiculos para este informe es (8)'); return false;}    
    if (counter_distance > 0) { 
      if (array_distance[0]['values'].length > limit) { array_distance = this.split_arrayslide(array_distance); }else{ array_distance=[array_distance];  }
      for (const a in array_distance) {
        cls_data_sample.generate_chart(pptx, array_distance[a], array_checked, ['distance'],'Distancia Recorrida'); 
      }
    }

    var counter_time = 0;
    for (const a in array_time) { counter_time++; }
    if (counter_time > 0) { 
      if (array_time[0]['values'].length > limit) { array_time = this.split_arrayslide(array_time); } else { array_time = [array_time]; }
      for (const a in array_time) {
        cls_data_sample.generate_chart(pptx, array_time[a], array_checked, ['time'], 'Tiempo de Maquina'); 
      }
    }

    var counter_volume = 0;
    for (const a in array_volume) { counter_volume++; }
    if (counter_volume > 0) { 
      if (array_volume[0]['values'].length > limit) { array_volume = this.split_arrayslide(array_volume); } else { array_volume = [array_volume]; }
      for (const a in array_volume) {
        cls_data_sample.generate_chart(pptx, array_volume[a], array_checked, ['volume'], 'Volumen Suministrado'); 
      }
    }

    var counter_currency = 0;
    for (const a in array_currency) { counter_currency++; }
    if (counter_currency > 0) { 
      if (array_currency[0]['values'].length > limit) { array_currency = this.split_arrayslide(array_currency); } else { array_currency = [array_currency]; }
      for (const a in array_currency) {
        cls_data_sample.generate_chart(pptx, array_currency[a], array_checked, ['currency'], 'Dinero Gastado'); 
      }
    }

    var counter_ralenti = 0;
    for (const a in array_ralenti) { counter_ralenti++; }
    if (counter_ralenti > 0) {
      if (array_ralenti[0]['values'].length > limit) { array_ralenti = this.split_arrayslide(array_ralenti); } else { array_ralenti = [array_ralenti]; }
      for (const a in array_ralenti) {
        cls_data_sample.generate_chart(pptx, array_ralenti[a], array_checked, ['currency'], 'Encendido sin Movimiento'); 
      }
    }

    var counter_distXvol = 0;
    for (const a in array_distXvol) { counter_distXvol++; }
    if (counter_distXvol > 0) { 
      if (array_distXvol[0]['values'].length > limit) { array_distXvol = this.split_arrayslide(array_distXvol); } else { array_distXvol = [array_distXvol]; }
      for (const a in array_distXvol) {
        cls_data_sample.generate_chart(pptx, array_distXvol[a], array_checked, ['distance','volume'], 'Relación Distancia/Volumen'); 
      }
    }

    var counter_timeXvol = 0;
    for (const a in array_timeXvol) { counter_timeXvol++; }
    if (counter_timeXvol > 0) { 
      if (array_timeXvol[0]['values'].length > limit) { array_timeXvol = this.split_arrayslide(array_timeXvol); } else { array_timeXvol = [array_timeXvol]; }
      for (const a in array_timeXvol) {
        cls_data_sample.generate_chart(pptx, array_timeXvol[a], array_checked, ['time','volume'], 'Relación Tiempo/Volumen'); 
      }
    }

    var counter_currencyXvol = 0;
    for (const a in array_currencyXvol) { counter_currencyXvol++; }
    if (counter_currencyXvol > 0) { 
      if (array_currencyXvol[0]['values'].length > limit) { array_currencyXvol = this.split_arrayslide(array_currencyXvol); } else { array_currencyXvol = [array_currencyXvol]; }
      for (const a in array_currencyXvol) {
        cls_data_sample.generate_chart(pptx, array_currencyXvol[a], array_checked, ['currency', 'volume'], 'Relación Dinero/Volumen'); 
      }
    }
    
    pptx.save('ADL Reporte de Eficiencia ' + name);

  } 

  split_arrayslide(data_chart){
    var data_length = data_chart[0]['labels'].length - 1;
    var max_length = 8;
    if (data_length > max_length) {
      var slice_number = data_length / max_length;
      var splited_arrayslide = [];
      var array_chart = {name:'',value:[],label:[]};
      for (const a in data_chart) {
        var array_data = data_chart[a];
        var slice_start = 0; var slice_end = max_length;
        var prearray_value = []; var prearray_label = [];
        for (let i = 0; i < slice_number; i++) {
          var array_sliced_value = array_data['values'].slice(slice_start, slice_end);    //  formar un array de arrays
          var array_sliced_label = array_data['labels'].slice(slice_start, slice_end);    //  formar un array de arrays
          slice_start = slice_end;
          slice_end = slice_end + max_length;
          prearray_value.push(array_sliced_value);
          prearray_label.push(array_sliced_label);
        }
        array_chart.name = data_chart[a]['name'];
        array_chart.label.push(prearray_label);
        array_chart.value.push(prearray_value);
        // ADDING DATA
        for (let b = 0; b < prearray_value.length; b++) {
          if (splited_arrayslide[b] === undefined) { splited_arrayslide[b] = []; }
          if (splited_arrayslide[b][a] === undefined) {  splited_arrayslide[b][a]={};  }

          splited_arrayslide[b][a].name = data_chart[a]['name'];
          splited_arrayslide[b][a].labels = prearray_label[b];
          splited_arrayslide[b][a].values = prearray_value[b];
        }
      }
      return splited_arrayslide;
    }
  }


  redirect_dashboard(base) { 
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


    var url = `report/dashboard/${from}/${until}/${vehicule_checked}/`;
    window.location.href = base+url;
    return false;
  }
  dashboard_piechart(data_piechart, target) {
    var chart = c3.generate({
      bindto: `#${target}`, padding: { top: 60, right: 150, bottom: 60, left: 150, }, size: { height: 300 },
      data: {
        columns: data_piechart,
        type: 'pie',
      },
      labels: {
        format: function (v, id, i, j) { return v; },
      }
    });
  }
  
  built_html_chart(data,container,chart_xaxis,bind,title,unit){
    var data_chart = data[0];
    var xaxis = chart_xaxis;
    var chart_unit = '';
    for (const c in unit) {
      if (unit[c] === unit[unit.length - 1]) {
        chart_unit += `${cls_data_sample.unit[unit[c]]}`;
      } else {
        chart_unit += `${cls_data_sample.unit[unit[c]]}/`;
      }
    }
    var top = `
    <div class="pt-3">
      <span class="font-weight-bold" id="span_${bind}">${title}
        <button type="button" class="btn btn-default" onclick="cls_report.chart_instance['${title}'].transform('line');"><i class="fas fa-chart-line" ></i></button>
        <button type="button" class="btn btn-default" onclick="cls_report.chart_instance['${title}'].transform('area-spline');"><i class="fas fa-chart-area" ></i></button>
        <button type="button" class="btn btn-default" onclick="cls_report.chart_instance['${title}'].transform('bar');"><i class="fas fa-chart-bar" ></i></button>
      </span>
    </div>
    `;
    
    var chart = `
    <div class="row">
      <div class="col-sm-12 p-0" id="chart_${container}"></div>`;
      if (data.length > 1) {
        chart += `
          <div class="col-sm-12 p-0 text-center">
            <button type="button" class="btn btn-secondary" onclick="cls_report.previous_data('${title}')"><i class="fas fa-chevron-left"> </i></button>&nbsp;&nbsp;
            <input type="hidden" id="inputchart_${title}" alt="${chart_unit}" value="${bind}"/>
            <button type="button" class="btn btn-secondary" onclick="cls_report.next_data('${title}')"><i class="fas fa-chevron-right"> </i></button>
          </div>
        `;
      }
    chart += `</div>`;
    
    var table = `
    <div class="row pb-2 border-bottom maxh_400 overflow_auto" id="table_${title}">`;
    for (const a in data_chart) {
      var vehicule_name = data_chart[a][0];
      var array_iterable = data_chart[a].slice(1);
      if (xaxis[0] === 'x') { xaxis.shift();  }
      var tbody = ``;        
      for (const b in array_iterable) {
        if (cls_general_funct.is_empty_var(array_iterable[b]) === 1) {
        tbody+=`
          <tr>
            <td>${xaxis[b]}</td>
            <td>${array_iterable[b]}</td>
          </tr>`;
        }
      }
      table+=`
        <div class="col-sm-2 p-2 border-right" id="table_data_top">
          <span class="font-weight-bold">${vehicule_name}</span>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">Fecha</th>
                <th scope="col">${chart_unit}</th>
              </tr>
            </thead>
            <tbody>
            ${tbody}
            </tbody>
          </table>
        </div>
      `;
    }
    table +=`</div>`;
    var content = top+chart+table;
    document.getElementById(container).innerHTML += content;
    return `chart_${ container }`;
  }
  generate_chart(title, unit, data_chart, container, type, group=[], categories, tooltip){
    var data_length = data_chart[0].length-1;
    var max_length = 16;
    if (data_length > max_length) {
      var slice_number = data_length/max_length;
      var array_chart = [];
      for (const a in data_chart) {
        var array_prepared = data_chart[a].slice(1);
        var slice_start = 0; var slice_end = max_length;
        var array_data=[];
        for (let i = 0; i < slice_number; i++) {
          var array_sliced = array_prepared.slice(slice_start, slice_end);    //  formar un array de arrays
          slice_start = slice_end;
          slice_end = slice_end+max_length;
          array_sliced.unshift(data_chart[a][0]);
          array_data.push(array_sliced);
        }
        array_chart.push(array_data);
      }
      var data = this.built_arraychart(array_chart);
      
      var xaxis = [];
      var slice_start = 0; var slice_end = max_length;
      for (let i = 0; i < slice_number; i++) {
        var xaxis_sliced = categories.slice(slice_start, slice_end);
        slice_start = slice_end;
        slice_end = slice_end + max_length;
        xaxis.push(xaxis_sliced);
      }
    }else{
      var data = [];
      data.push(data_chart);
      var xaxis = [];
      xaxis.push(categories);
    } 

    var bind = this.built_html_chart(data, container, xaxis[0], 0, title,unit);
    cls_report.chart_instance[title] = this.dashboard_chart(data[0], bind, type, group, xaxis[0], tooltip);
    if (cls_report.chart_data[title] === undefined) { cls_report.chart_data[title] = {};}
    cls_report.chart_data[title]['data'] = data;
    cls_report.chart_data[title]['xaxis'] = xaxis;
  }
  built_arraychart(array_data){
    var array_complex = [];
    for (let index = 0; index < array_data[0].length; index++) {
      var prearray = [];
      for (const a in array_data) {
        prearray.push(array_data[a][index]);
      }
      array_complex[index] = prearray;
    }
    return array_complex;
  }



  dashboard_chart(data_chart,target,type,group,categories,tooltip){
    if (categories[0] != 'x') {
      categories.unshift('x');
    }
    data_chart.unshift(categories);     
    var chart = c3.generate({
      bindto: `#${target}`,
      data: { x: 'x', columns: data_chart, type: type, groups: [group], labels: true, },
      axis: {
        x: {
          type: 'category'
        }
      },
      bar: { width: { ratio: 0.8 } },
      tooltip: { show: tooltip },
      zoom: { enabled: true },
    });
    return chart;
  }






  previous_data(title) {
    var name_input = 'inputchart_'+title;
    var index = document.getElementById(name_input).value;
    var unit = document.getElementById(name_input).getAttribute('alt');
    index = parseInt(index) - 1;
    if (index > -1) {
      document.getElementById(name_input).value = index;
      this.set_chart(title, index, cls_report.chart_instance[title],unit);
    } else {     
      cls_general_funct.shot_toast('Has llegado al inicio.');
    }
  }
  next_data(title) {
    var name_input = 'inputchart_' + title;
    var index = document.getElementById(name_input).value;
    var unit = document.getElementById(name_input).getAttribute('alt');
    index = parseInt(index) + 1;
    var data_chart = cls_report.chart_data[title]['data'];
    if (index < data_chart.length) {
      document.getElementById(name_input).value = index;
      this.set_chart(title, index, cls_report.chart_instance[title],unit);
    }else{
      cls_general_funct.shot_toast('Has llegado al final.')
    }
  }
  set_chart(title,value,chart,unit){
    console.log(unit);
    
    var axis_chart = cls_report.chart_data[title]['xaxis'];
    var data_chart = cls_report.chart_data[title]['data'];

// ACT DE TABLAS
    var table = '';    
    if (data_chart[value][0][0] != 'x') {
      var table_axis = axis_chart[value];
      var table_data = data_chart[value];
    }else{
      var table_axis = data_chart[value][0].slice(1);
      var table_data = data_chart[value].slice(1);
    }
    for (const a in table_data) {
      var vehicule_name = table_data[a][0];
      var array_iterable = table_data[a].slice(1);
      var tbody = ``;
      for (const b in array_iterable) {
        if (cls_general_funct.is_empty_var(array_iterable[b]) === 1) {
          tbody += `
            <tr>
              <td>${table_axis[b]}</td>
              <td>${array_iterable[b]}</td>
            </tr>`;
        }
      }
      table += `
        <div class="col-sm-2 p-2 border-right">
          <span class="font-weight-bold">${vehicule_name}</span>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">Fecha</th>
                <th scope="col">${unit}</th>
              </tr>
            </thead>
            <tbody>
            ${tbody}
            </tbody>
          </table>
        </div>
      `;
    }
    document.getElementById("table_"+title).innerHTML = table;
//   ACT CHART
    setTimeout(function () { chart.unload(); }, 500);
    if (axis_chart[value][0] != 'x') {
      axis_chart[value].unshift('x');
    }
    var colum = data_chart[value];
    if (colum[0] != axis_chart[value]) {
      colum.unshift(axis_chart[value]);
    }
    setTimeout(function () {
      chart.load({ columns: colum, });
    }, 1000);

  }
}
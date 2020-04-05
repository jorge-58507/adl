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

    var counter_ralenti = 0;
    for (const a in array_ralenti) { counter_ralenti++; }
    if (counter_ralenti > 0) { cls_data_sample.generate_chart(pptx, array_ralenti, array_checked, ['currency'], 'Encendido sin Movimiento'); }

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
  // <button type="button" class="btn btn-default" onclick="transform_graph(${cls_report.chart_instance[bind]},'line')"><i class="fas fa-chart-line" ></i></button>
  
  built_html_chart(data,container,xaxis,bind,title){
    // var data_chart = data[0];
    var data_chart = data;
    var top = `
    <div class="pt-3">
      <span class="font-weight-bold" id="span_${bind}">${title}
        <button type="button" class="btn btn-default" onclick="cls_report.chart_instance[${bind}].transform('line');"><i class="fas fa-chart-line" ></i></button>
        <button type="button" class="btn btn-default" onclick="cls_report.chart_instance[${bind}].transform('area-spline');"><i class="fas fa-chart-area" ></i></button>
        <button type="button" class="btn btn-default" onclick="cls_report.chart_instance[${bind}].transform('bar');"><i class="fas fa-chart-bar" ></i></button>
      </span>
    </div>
    `;
    var chart = `
    <div class="row">
      <div class="col-sm-12 p-0" id="chart_${bind}"></div>`;
      if (data.length > 1) {
        chart += `
          <div class="col-sm-12 p-0 text-center">
            <button type="button" class="btn btn-default" onclick="previous_data(${bind})"><i class="fas fa-chevron-left"> </i></button>
            <input type="text" id="inputchart_${title}" value="${bind}"/>
            <button type="button" class="btn btn-default" onclick="next_data(${bind})"><i class="fas fa-chevron-right"> </i></button>
          </div>
        `;
      }
    chart += `</div>`;
    var table = `
      <div class="row pb-2 border-bottom">`;
      for (const a in data_chart) {
        var vehicule_name = data_chart[a][0];
        var array_iterable = data_chart[a].slice(1);
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
                  <th scope="col">${cls_data_sample.unit['distance']}</th>
                </tr>
              </thead>
              ${tbody}
              </tbody>
            </table>
          </div>
        `;
      }
    table +=`</div>`;
    var content = top+chart+table;
    document.getElementById(container).innerHTML += content;

    return `chart_${ bind }`;
    
  }
  generate_chart(title, data_chart, container, type, group, categories, tooltip){
    var data_length = data_chart[0].length-1;
    var max_length = 2;
    
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

    // var bind = this.built_html_chart(data, container, xaxis[0], 0, title);
    // cls_report.chart_instance.push(this.dashboard_chart(data[0], bind, type, group, xaxis[0], tooltip));
    // cls_report.chart_data[title] = data;

    for (let b = 0; b < data.length; b++) {      
      var bind = this.built_html_chart(data,container,xaxis[b],b,title);
      cls_report.chart_instance.push(this.dashboard_chart(data[b], bind, type, group, xaxis[b], tooltip));
      // cls_report.chart_data[title] = data;
    }    
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
    var chart = c3.generate({
      bindto: `#${target}`,
      data: { columns: data_chart, type: type, groups: [group], labels: true, },
      axis: {
        x: {
          type: 'category',
          categories: categories
        }
      },
      bar: { width: { ratio: 0.8 } },
      tooltip: { show: tooltip },
    });
    return chart;
  }
}
    function previous_data(title) {
      var name_input = 'inputchart_'+title;
      var index = document.getElementById(name_input).value;
      console.log(index);
      
    }
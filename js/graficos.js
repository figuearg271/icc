google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);
       google.charts.setOnLoadCallback(drawVisualization2);
 
	function errorHandler(errorMessage) {
            //curisosity, check out the error in the console
            console.log(errorMessage);
            //simply remove the error, the user never see it
            google.visualization.errors.removeError(errorMessage.id);
        }
		
      function drawVisualization() {
        // Some raw data (not necessarily accurate)
		var periodo=$("#periodo").val(); //Datos que enviaremos para generar una consulta en la base de datos
		var vendedor=$("#nvendedor").val();
		
		var jsonData= $.ajax({
                        url: 'chart.php',
						data: {'nvend':vendedor,'periodo':periodo,'action':'ajax'},
                        dataType: 'json',
                        async: false
                    }).responseText;
   
		var obj = jQuery.parseJSON(jsonData);
		var data = google.visualization.arrayToDataTable(obj);	
 
    var options = {
      title : 'REPORTE DE VENTAS VS METAS DEL PERIODO '+periodo,
      vAxis: {title: 'Monto'},
      hAxis: {title: 'Meses'},
      seriesType: 'bars',
      series: {5: {type: 'line'}}
    };
	
    var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
	google.visualization.events.addListener(chart, 'error', errorHandler);
    chart.draw(data, options);
  }
  
  // Haciendo los graficos responsivos
      jQuery(document).ready(function(){
        jQuery(window).resize(function(){
         drawVisualization();
        });
      });

function drawVisualization2() 
{      
    // Some raw data (not necessarily accurate)
    var periodo=$("#periodo2").val(); //Datos que enviaremos para generar una consulta en la base de datos
    //var mes=$("#meses").val();
    var vendedor=$("#nvendedor").val();

    var jsonData= $.ajax({
                        url: 'chart.php',
            data: {'nvend':vendedor,'periodo':periodo,'action':'ajax2'},
                        dataType: 'json',
                        async: false
                    }).responseText;
   
    var obj = jQuery.parseJSON(jsonData);
    var data = google.visualization.arrayToDataTable(obj);   
 
    var options = {
      title : 'REPORTE DE VENTAS X CATEGORIA DEL PERIODO '+periodo,
      vAxis: {title: 'Ventas'},
      hAxis: {title: 'Meses'},
      //seriesType: 'bars',
      //series: {5: {type: 'line'}}
    };
  
    var chart = new google.visualization.ComboChart(document.getElementById('chart_div2'));
  google.visualization.events.addListener(chart, 'error', errorHandler);
    chart.draw(data, options);
}
  
  // Haciendo los graficos responsivos
      jQuery(document).ready(function(){
        jQuery(window).resize(function(){
         drawVisualization2();
        });
      });
<head>
	<title>Distribuidora Cuscatlan</title>
	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="pixelhint.com">
	<meta name="description" content="La casa free real state fully responsive html5/css3 home page website template"/>

	<!--<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />-->


	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">


	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">
	<link href='img/dist.png' rel='shortcut icon' type='image/png'>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>




	<!-- encabezados para la pantalla del login
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">




	<!-- encabezados para el carrusel de imagenes -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
	<!-- Minified JS library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Compiled and minified Bootstrap JavaScript
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script> -->


	<!-- GRAFICOS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script>
//creacion de scripts nuevos

function setPanels()
{
  var windowWidth = window.innerWidth;
  if(windowWidth <= 500)
  {
    $("header .logo").attr("src","logo");
  }
  else
  {
    $("header .logo").attr("src","img/logo.png");
  }
}

//setear valores de precios
function setPrecios(co){
	//alert('test'); 
	var pre = $("#precio_venta_"+co).val();
 
	$.ajax({
		url:'./ajax/calcularPrecios.php',
		data: '',
		beforeSend: function(objeto){
			//$('#loader_modificando').html('<img src="./img/ajax-loader.gif"> Procesando, espere por favor...');
		},
		success:function(data){
			//alert(data);

		}
	});

}

function autCotizacion(doc){
	var emp = '<?=$_SESSION['empre_numero']?>';
	var r = confirm("¿Desea autorizar este pedido?");
		if (r == true) {
			$.ajax({
			method: 'POST',
			url: './ajax/autorizarCoti.php',
			data: {documento:doc,emp:emp},
			success: function(data) {
				//location.reload();
				if(data==1){
					$(".docsaut_"+doc).attr('disabled','disabled');
						alert("Documento Autorizado!");
				}
				else{
					alert("No se ha podido actualizar");
					$(".docsaut_"+doc).attr('checked',false);
				}
			}
			});
		} else {
			$(".docsaut_"+doc).attr('checked',false);
		}
}
 



$( document ).ready(function() {
 $( "#n_art" ).keyup(function() {
	 if($("#n_art").val()!=""){
		 $("#buscaclientes").attr("disabled",false);
		 }
		 else{
		 $("#buscaclientes").attr("disabled","disabled");
		 }
	 });


//VALIDACION DE PEDIDOS CONTADO Y REPARTO

		
		$('input[name=document]').change(function(){
			if($('input[name=document]').is(':checked')){
					//alert('Checked');
					//$("#tiptext1").text("CONTADO");
					$("#tipc").val("CREDITO");    
				} else {
					//$("#tiptext1").text("CREDITO");
					$("#tipc").val("CONTADO");
				}
		});

		$('input[name=document2]').change(function(){
			if($('input[name=document2]').is(':checked')){
					//alert('Checked');
					//$("#tiptext2").text("REPARTO");
					$("#tipc2").val("VENDEDOR");    
				} else {
					//$("#tiptext2").text("VENDEDOR");
					$("#tipc2").val("REPARTO");
				}
		});
});
</script>

</head>

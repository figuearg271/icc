<?php
	
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }
	
	$active_clientes="";
	$active_envios="";
	$active_reclamos="active";
	$active_sugerencias="";	
	$active_configuracion="";
	$title="DIACO";

	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php include("head.php");?>
  </head>
  <body onload="procesa_reclamo(<?php echo base64_decode($_GET['id']); ?>)">
	<?php
	include("navbar.php");
	?>  
    <div class="container">
		<div class="panel panel-info">
		<div class="panel-heading">
		    <div class="btn-group pull-right">
				<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
			</div>
			<h4 id="nclientes"><a href="reclamosproductos.php" class='btn btn-default' title='Regresar a reclamos' id="backreclamos" ><img src="img/back_clie.png"/></a>  Procesa Reclamos </h4> 
		</div>
			<div class="panel-body" >

					<form class="form-horizontal" role="form" id="procesa_reclamos">
				
						<div class="form-group row">
							
							<div class="col-md-5">
								<input type="hidden" id="numero_reclamo" value="<?php echo base64_decode($_GET['id']); ?>">

							</div>	
							
							<div class="col-md-3">								
							</div>							
						</div>	
				
				
			</form>

				<?php include("modal/muestra_reclamo.php"); ?>
				
				<div id="resultados5"></div><!-- Carga los datos ajax -->
				<div class='outer_div5'></div><!-- Carga los datos ajax -->

				<div class="pull-right">
					<?php  if (trim($_SESSION['user_tipo'])=="C") { ?>
			  		<button type="button" class="btn btn-default" onclick="graba_reclamo_procesado(1)">Grabar reclamo </button> <?php }?>
				  	<button type="button" class="btn btn-default" onclick="printDiv('contenedor_muestrareclamo')">Imprimir </button>
					<button type="button" class="btn btn-default" class="close" data-dismiss="modal" >Cerrar</button>
				</div>
			</div>
		</div>	

		
	</div>
	<br/><br/>
	
	<?php
	/*include("footer.php");*/
	?>
	<script type="text/javascript" src="./js/VentanaCentrada.js"></script>
	<script type="text/javascript" src="./js/reclamos.js"></script>
  </body>
</html>
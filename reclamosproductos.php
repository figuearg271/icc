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
  <body>
	<?php
	include("navbar.php");
	?>  
	<br/>
    <div class="container">
		<div class="panel panel-primary">
		<div class="panel-heading">
		    <div class="btn-group pull-right">
				<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
			</div>
			<h4 id="nclientes">Reclamos</h4>
		</div>
			<div class="panel-body" >

					<form class="form-horizontal" role="form" id="Reclamos_general">
				
						<div class="form-group row">
							
							<div class="col-md-5">
								<a href="clientesasignados.php" class='btn btn-default' title='Regresar a clientes' id="backclientes" style="display: none" ><img src="img/back_clie.png"/></a>

								<input type="text" class="form-control" id="nombre_clie" placeholder="Nombre del cliente" autocomplete="off" onkeyup='busca_reclamos_vendedores_nombre(1);'>	
							</div>					
							
							<div class="col-md-3">
								<!--<button type="button" class="btn btn-default" onclick='load(1);' id="buscaclientes">
									<span class="glyphicon glyphicon-search" ></span> Buscar</button>
									

									<!--<button type="button" class="btn btn-default" onclick='load(1);' id="backclientes" style="display: none">
										<span class="glyphicon glyphicon-search" ></span> Atras</button>-->

										<button type="button" class="btn btn-default" onclick='nuevo_reclamo(1);' id="nsolicitud">
									<span class="glyphicon glyphicon-plus" ></span> Nuevo reclamo</button>
								<span id="loader"></span>
							</div>
							
						</div>
				
				
				
			</form>

				<?php include("modal/muestra_reclamo.php"); ?>
				
				<div id="resultados"></div><!-- Carga los datos ajax -->
				<div class='outer_div'></div><!-- Carga los datos ajax -->
			</div>
		</div>	
		
	</div>
	
	
	
	<script type="text/javascript" src="./js/reclamos.js"></script>
  </body>
</html>
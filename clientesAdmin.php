<?php	
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	


	$nombre=$_SESSION['user_name'] ; ?>


	<!DOCTYPE html>
<html lang="en">
<head>	<?php include("./head.php"); ?> </head>
	
<script type="text/javascript" src="./js/clientes.js"></script>
 <body onload="loadAuto(1)">

	<input type="hidden" id="nvendedor" value="<?php echo $_SESSION['user_name']  ?>">

	<?php include("navbar.php");?>


<br/>
	<div class="container">
		<div class="panel panel-primary">
		<div class="panel-heading">
		    <div class="btn-group pull-right">
				<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
			</div>
			<h4 id="nclientes">Autorizaciones</h4>
		</div>
			<div class="panel-body" >

					<form class="form-horizontal" role="form" id="clientes_asignados">
				
						<div class="form-group row">

							<div class="col-md-1">
								<a href="clientes.php" class='btn btn-default' title='Regresar a clientes' id="backclientes" style="display: none" >Regresar</a>
							</div>

							<div class="col-md-1">
								<!--<a href="https://goo.gl/forms/3QUmF29GQYa8nwgc2" target="_blank" class="btn btn-default" id="n_cliente">Nuevo</a>-->							</div>
							<!--
							<div class="col-md-5">								
								<input type="text" class="form-control" id="nomclie" placeholder="Nombre del cliente" autocomplete="off">
							</div>					
							
							<div class="col-md-3">
								<button type="button" class="btn btn-default" onclick='load2(1);' id="buscaclientes">
									<span class="glyphicon glyphicon-search" ></span> Buscar</button>
								<span id="loader"></span>
							</div>
						-->
						<span id="loader"></span>
						</div>				
					</form>
				
				<div id="resultados"></div><!-- Carga los datos ajax -->
				<div class='outer_div'></div><!-- Carga los datos ajax -->
			</div>
		</div>	
		
	</div>

<?php include("modal/clientes.php"); ?>

</body>
</html>
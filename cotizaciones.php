<?php	 
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	
	$cve_vend=$_SESSION['user_cvevend']; ?>

	<!DOCTYPE html>
<html lang="en">
<head>	<?php include("./head.php"); ?> </head>
	
 <body onload="busca_cotizaciones(1)">
	<input type="hidden" id="cve_vend" value="<?php echo trim($cve_vend);  ?>">
	<?php include("./navbar.php");?>

<br/>
	<div class="container">
		<div class="panel panel-primary">
		<div class="panel-heading">
		    <div class="btn-group pull-right">
				<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
			</div>
			<h4 id="nombre_formulario">Cotizaciones</h4>
		</div>
			<div class="panel-body" >

					<form class="form-horizontal" role="form" id="pedidos">
				
						<div class="form-group row">						

							<div class="col-md-2">
								<a href="ver_facturas.php?action=cotizaciones" class="btn btn-default" id="n_pedido"><i class="glyphicon glyphicon-plus"> </i> Nueva cotización</a>
							</div>
							
							<!--<div class="col-md-1">
								<a href="#" class="btn btn-default" id="btn_precios" onclick="busca_listas('1')"><i class="glyphicon glyphicon-edit"> </i> Lista de precios</a>
							</div>			

							<div class="col-md-4">								
								<input type="text" class="form-control" id="producto" placeholder="Nombre del producto" autocomplete="off" style="display: none">
							</div>								

							<div class="col-md-1">
								<button type="button" class="btn btn-default" id="btn_buscar" onclick='busca_listas_producto(1);' style="display: none">
									<span class="glyphicon glyphicon-search" ></span> Buscar</button>							
							</div>

							<div class="col-md-4">
								<button type="button" class="btn btn-default" id="backpedidos" onclick="window.location.href='./pedidos.php';" style="display: none">
									<span class="glyphicon glyphicon-chevron-left" ></span> Regresar</button>
									<span id="loader"></span>
							</div>-->

							<div class="col-md-5 pull-right">
								
								<span id="notificacion"></span>
							</div>

						</div>				
					</form>
				
				<div id="resultados"></div><!-- Carga los datos ajax -->
				<div class='outer_div'></div><!-- Carga los datos ajax -->
			</div>
		</div>	
		
	</div>
<script type="text/javascript" src="./js/ordenes.js"></script>

</body>
</html>
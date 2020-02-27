<?php	
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	

	$active_clientes="";
	$active_envios="";
	$active_pedidos="";
	$active_reclamos="";
	$active_sugerencias="";	
	$title="DIACO";

	$nombre=$_SESSION['user_name'] ; ?>


	<!DOCTYPE html>
<html lang="en">
<head>
	<?php include("head.php"); ?>
</head>
	
 <body>

	<input type="hidden" id="nvendedor" value="<?php echo $_SESSION['user_name']  ?>">

	<?php include("navbar.php");?>


<br/>
	 <div class="container">
		<div class="panel panel-primary">
		<div class="panel-heading">
		    <div class="btn-group pull-right">
				<!--<a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
			</div>
			<h4>Catalogo</h4>
		</div>
			<div class="panel-body" >

					<form class="form-horizontal" role="form" >
				
						<!--<div class="form-group row">

							<div class="col-md-5">								
								<input type="text" class="form-control" id="producto" placeholder="Nombre del producto" autocomplete="off">
							</div>					
							
							<div class="col-md-3">
								<button type="button" class="btn btn-default" onclick='busca_producto(1);'>
									<span class="glyphicon glyphicon-search" ></span> Buscar</button>

								<span id="loader"></span>
							</div>
							
						</div>-->				
					</form>
				
				<div id="resultados"></div><!-- Carga los datos ajax 
				<div class='outer_div'></div><!-- Carga los datos ajax -->

				<embed src="archivos/catalogo.pdf" type="application/pdf" width="100%" height="1024px" ></embed>

			</div>
		</div>	

	
</div>
		
		
	</div> -->


<script type="text/javascript" src="./js/catalogo.js"></script>

</body>
</html>
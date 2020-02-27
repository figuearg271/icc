<?php	
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	

	/*$active_clientes="";
	$active_envios="";
	$active_pedidos="";
	$active_reclamos="";
	$active_sugerencias="";	
	$title="DIACO";*/

	$nombre=$_SESSION['user_name'] ; ?>

	<!DOCTYPE html>
<html lang="en">
<head>
	<?php include("head.php"); ?>
</head>	
<body  onload="carga_pedido(1)">

	<?php include("navbar.php");?>

	<br/>
	<div class="container">	
		<div class="panel panel-primary">
			<div class="panel-heading">
			    <div class="btn-group pull-right">
					<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
				</div>
				<h4>Modificacion de pedidos </h4>
			</div>
			
			<div class="panel-body">
				<form class="form-horizontal" role="form" id="datos_factura" name="datos_factura">
					
					<div class="form-group row">
						<label for="nombre_cliente" class="col-md-1 control-label">Nombre</label>
						<div class="col-md-5">
							<input type="text" class="form-control input-sm" id="nsucursal" value="<?php echo base64_decode($_GET['mon']); ?>" readonly>
		                </div>
		                <label for="mail" class="col-md-1 offset-1 control-label">Clave</label>
		                <div class="col-md-1">
		                	<input type="text" class="form-control input-sm" id="cod_cliente" value="<?php echo base64_decode($_GET['eva']); ?>" readonly>
		                	<?php
		                	if ($_SESSION['user_tipo']='V') { ?>
		                		<input type="hidden" class="form-control input-sm" id="cve_doc" value="<?php echo base64_decode($_GET['otm']); ?>">
		                		 <?php
		                	}
		                	elseif ($_SESSION['user_tipo']='M') { ?>
		                		<input type="hidden" class="form-control input-sm" id="cod_matriz" value="<?php echo $_SESSION['user_email']; ?>"> <?php
		                	}
		                	elseif ($_SESSION['user_tipo']='S') {?>  <?php
		                	} ?>
		                </div>
		                <label class="col-md-1 offset-1 control-label">Disponible</label>
		                <div class="col-md-2">
		                	<input type="text" class="form-control input-sm" id="disponible" value="<?php echo number_format(base64_decode($_GET['disponible']),2); ?>" readonly>
		                </div>
		            </div>

		            <div class="form-group row">
		            	<label for="tel2" class="col-md-1 control-label">Fecha</label>
		            	<div class="col-md-2">
		            		<input type="text" class="form-control input-sm" id="fecha_doc" value="<?php echo base64_decode($_GET['ahc']); ?>" readonly>
		            	</div>
		            	<label for="fecha2" class="col-md-1 control-label">F.Entrega</label>
		            	<div class="col-md-2">
		            		<input type="text" class="form-control input-sm" id="fecha_ent" value="<?php echo base64_decode($_GET['ahce']); ?>" onkeypress="actualiza_fecha_e(event,this.value)">
		            	</div>

		            	<label class="col-md-1 control-label">T. Contri.</label>
		            	<div class="col-md-1">
		            		<input type="text" class="form-control input-sm" id="tipo_contribuyente" value="<?php echo base64_decode($_GET['tipo']); ?>" readonly>
		            	</div>                                    
		            </div> 

		            <div class="form-group row">
		            	<label for="comentarios" class="col-md-1 control-label">Comentarios</label>
		            	<div class="col-md-11">
		            		<input type="text" class="form-control input-sm" id="comentarios" value="<?php echo base64_decode($_GET['obs']); ?>" readonly>
		            	</div>
		            </div>
		        	
		        	<div>
		        		<hr>
		        	</div>
				    <div class="pull-right">
				    	<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
				    		<span class="glyphicon glyphicon-plus"></span> Agregar productos
				    	</button>
				    	
				    	<a href="pedidos.php" class='btn btn-default' title='Cancelar Pedido' ><i class="glyphicon glyphicon-inbox"></i> Cancelar</a> 
				    	<br/ >
				    </div>
				</form>
			</div>
			
			<?php  include("modal/agrega_producto.php"); ?>
			
			<div id="loader"></div><!-- Carga los datos ajax -->
			<div class='outer_div'></div><!-- Carga los datos ajax -->

	</div>

	<script type="text/javascript" src="./js/ordenes.js"></script>
</body>
</html>
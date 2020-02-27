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
$title="DIACO"; ?>
<!DOCTYPE html>
<html lang="en">
	<head> <?php include("head.php");?>
	<style>	
	#country-list{float:left;list-style:none;margin-top:-3px;padding:0;width:auto;position: inherit;}
	#country-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px  solid;}
	#country-list li:hover{background:#ecf0f1;cursor: pointer;}
	#search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
	</style>

	</head>
	<body onload="load(1)">
		<?php include("navbar.php"); ?>  
		<br/>
	   <div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
					<div class="btn-group pull-right">
						<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
					</div>
					<h4 id="ncreclamos">Nuevo reclamo</h4>
				</div>
				<div class="panel-body" >
					<form class="form-horizontal" role="form" id="datos_reclamo" name="datosreclamos">
						<div class="form-group">
							<label class="control-label col-sm-2" for="fec">Fecha: </label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="fecha" value="<?php echo date("Y-m-d");?>" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2" for="clie">Cliente: </label>
							<div class="col-sm-9">
								<!--<input type="text" class="form-control" id="cliente">-->
								<?php

								if ($_SESSION['user_tipo']='V') { ?> 
									<input type="text" class="form-control input-sm" id="nombre_cliente" placeholder="Digite el nombre del cliente *" onkeyup='llena_ncliente_vendedor(1);'>
									<input type="hidden" id="vendedor" value="<?php echo trim($_SESSION['user_cvevend']); ?>">
									<input type="hidden" id="id_cliente" >	
									<input type="hidden" id="id_usuario" value="<?php echo $_SESSION['user_id'];?>">

									<div id="suggesstion-box"></div>
								<?php } ?>									
							</div>
						</div>						

						<div class="form-group">
							<label class="control-label col-sm-2" for="rdat">Recibe datos: </label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm" id="r_datos"  placeholder="Digite el nombre de quien procesa reclamo *" required>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2" for="tipo">Tipo devolucion: </label>
							<div class="col-sm-6">
								<div class="radio">
									<label><input type="radio" id="td0" name="tdev" value="devolucion"> Producto</label> &nbsp;
									<label><input type="radio" id="td1" name="tdev" value="diferencia"> Diferencia de precio</label> &nbsp;
									<label><input type="radio" id="td2" name="tdev" value="otros"> Otros</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2" for="rprod">Problema: </label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm" id="problema"  placeholder="Digite el problema del reclamo *">
							</div>
					</div>
					</form>
					
					<div class="form-group">
						<hr>
						<label class="control-label col-sm-6" for="det_prod" style="display: none" id="det_prod">Detalle de productos </label>
						

						<div class="pull-right col-sm-6">
					    	<button type="button" class="btn btn-default col-md-offset-2" data-toggle="modal" data-target="#myModal" style="display: none" id="b_facturas">
					    		<span class="glyphicon glyphicon-search" ></span> Buscar factura
					    	</button>

			    	 

				    	<button type="button" class="btn btn-default col-md-offset-2" id="cancelar_nuevo_reclamo" onclick="cancelar_reclamo(1)" >
				    		<span class="glyphicon glyphicon-remove-sign" ></span> Cancelar
				    	</button>

			    	</div>
			    	
			    	<br/><br>
			    </div>

					</div>
					
					<?php include("modal/buscar_facturas.php"); ?>
					
					
					<div id="resultados"></div><!-- Carga los datos ajax -->
				
				</div>
			</div>	
		</div> 
		
		
		<script type="text/javascript" src="./js/reclamos.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

		
	</body>
</html>
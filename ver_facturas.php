<?php	
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	

	$active_clientes="";
	$active_envios="";
	$active_pedidos="";
	$active_reclamos="";
	$active_sugerencias="";	
	$title="DIACO";

	$nombre=$_SESSION['user_name'] ; 

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	?>


	<!DOCTYPE html>
<html lang="en">
<head>
	<?php include("head.php"); ?>
</head>

<?php
if($action == 'pedidos')
{ ?>
	<body <?php if ($_SESSION['user_tipo']='V') { ?> onload="busca_pendientes_vendedor(1)"; <?php } elseif ($_SESSION['user_tipo']='M') { ?>  <?php } elseif ($_SESSION['user_tipo']='S') { ?>  <?php } ?> >
	<?php include("navbar.php");?>
	<br/>
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="btn-group pull-right">
					<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
				</div> <?php 
				if ($_SESSION['user_tipo']='V') { ?> <h4>Lista de clientes para agregar pedido</h4> <?php }
				elseif ($_SESSION['user_tipo']='M') { ?> <h4>Sucursales para cargar el pedido</h4> <?php }
				elseif ($_SESSION['user_tipo']='S') { ?> <h4>Sucursal para cargar el pedido</h4> <?php } ?>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" role="form" id="datos_pedido" onkeypress="return anular(event)">			
					<div class="form-group row"><?php 					
						
							if ($_SESSION['user_tipo']='V') { ?> 
								<div class="col-md-1">
									<a href="pedidos.php" class='btn btn-default' title='Regresar a pedidos' >Regresar</a>
								</div>
								
								<div class="col-md-4" align="center"> 
									<input type="text" class="form-control" id="nombre" placeholder="Nombre del cliente" autocomplete="off" onkeyup=''>
									<input type="hidden" id="vendedor" value="<?php echo $_SESSION['user_cvevend']; ?>"> 
								</div>
								<div class="col-md-3">
									<button type="button" class="btn btn-default" onclick='cargaclientes_vendedor(1);' id="buscaclientes">
										<span class="glyphicon glyphicon-search" ></span> Buscar</button>								
								</div><?php 
							}
							elseif ($_SESSION['user_tipo']='M') { ?> 

								<div class="col-md-1">
									<a href="pedidos.php" class='btn btn-default' title='Regresar a pedidos' >Regresar</a>
								</div>

								<div class="col-md-4" align="center"> 
									<input type="text" class="form-control" id="nombre" placeholder="Nombre de Sucursales" autocomplete="off" onkeyup='cargamatriz(1);'>
									<input type="hidden" id="cve_sae" value="<?php echo $_SESSION['user_email']; ?>"> 
								</div>
								<div class="col-md-3">
									<button type="button" class="btn btn-default" onclick='load2(1);' id="buscaclientes">
										<span class="glyphicon glyphicon-search" ></span> Buscar</button>
									
								</div><?php 
							}
							elseif ($_SESSION['user_tipo']='S') { ?> 
								<div class="col-md-4" align="center"> 
									<input type="text" class="form-control" id="nombre" placeholder="Nombre de Sucursal" autocomplete="off" onkeyup='cargasucursal(1);'>
									<input type="hidden" id="cve_sae" value="<?php echo $_SESSION['user_email']; ?>"> 
								</div>
								<div class="col-md-3">
									<button type="button" class="btn btn-default" onclick='load2(1);' id="buscaclientes">
										<span class="glyphicon glyphicon-search" ></span> Buscar</button>								
								</div><?php 
							} ?>
					</div>					
					<div class="col-md-3">
						<span id="loader"></span>
					</div>
				</form>					
			</div>			
			<div id="resultados"></div><!-- Carga los datos ajax -->
			<div class='outer_div'></div><!-- Carga los datos ajax -->		
		</div>	
	</div>


<?php
}

if($action == 'cotizaciones')
{ ?>
<body <?php if ($_SESSION['user_tipo']='V') { ?> onload="busca_pendientes_vendedor_cotizaciones(1)"; <?php } elseif ($_SESSION['user_tipo']='M') { ?>  <?php } elseif ($_SESSION['user_tipo']='S') { ?>  <?php } ?> >
	<?php include("navbar.php");?>
	<br/>	
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="btn-group pull-right">
					<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
				</div> <?php 
				if ($_SESSION['user_tipo']='V') { ?> <h4>Lista de clientes para agregar cotización</h4> <?php }
				elseif ($_SESSION['user_tipo']='M') { ?> <h4>Sucursales para cargar la cotización</h4> <?php }
				elseif ($_SESSION['user_tipo']='S') { ?> <h4>Sucursal para cargar la cotización</h4> <?php } ?>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" role="form" id="datos_cotizacion" onkeypress="return anular(event)">			
					<div class="form-group row"><?php 					
						
							if ($_SESSION['user_tipo']='V') { ?> 
								<div class="col-md-1">
									<a href="cotizaciones.php" class='btn btn-default' title='Regresar a pedidos' >Regresar</a>
								</div>
								
								<div class="col-md-4" align="center"> 
									<input type="text" class="form-control" id="nombre" placeholder="Nombre del cliente" autocomplete="off" onkeyup=''>
									<input type="hidden" id="vendedor" value="<?php echo $_SESSION['user_cvevend']; ?>"> 
								</div>
								<div class="col-md-1">
									<button type="button" class="btn btn-default" onclick='cargaclientes_vendedor_cotizacion(1);' id="buscaclientes">
										<span class="glyphicon glyphicon-search" ></span> Buscar</button>								
								</div>
								<!--<div class="col-md-1">
									<button type="button" class="btn btn-default" onclick='cot_vend_varios(1);' id="clientesvarios">
										<span class="glyphicon glyphicon-user" ></span> Cliente varios</button>
									
								</div>--><?php 
							}
							elseif ($_SESSION['user_tipo']='M') { ?> 

								<div class="col-md-1">
									<a href="cotizaciones.php" class='btn btn-default' title='Regresar a pedidos' >Regresar</a>
								</div>

								<div class="col-md-4" align="center"> 
									<input type="text" class="form-control" id="nombre" placeholder="Nombre de Sucursales" autocomplete="off" onkeyup='cargamatriz(1);'>
									<input type="hidden" id="cve_sae" value="<?php echo $_SESSION['user_email']; ?>"> 
								</div>
								<div class="col-md-1">
									<button type="button" class="btn btn-default" onclick='load2(1);' id="buscaclientes">
										<span class="glyphicon glyphicon-search" ></span> Buscar</button>
									
								</div>
								<div class="col-md-1">
									<button type="button" class="btn btn-default" onclick='cot_vend_varios(1);' id="clientesvarios">
										<span class="glyphicon glyphicon-user" ></span> Cliente varios</button>
									
								</div><?php 
							}

							
							elseif ($_SESSION['user_tipo']='S') { ?> 
								<div class="col-md-4" align="center"> 
									<input type="text" class="form-control" id="nombre" placeholder="Nombre de Sucursal" autocomplete="off" onkeyup='cargasucursal(1);'>
									<input type="hidden" id="cve_sae" value="<?php echo $_SESSION['user_email']; ?>"> 
								</div>
								<div class="col-md-3">
									<button type="button" class="btn btn-default" onclick='load2(1);' id="buscaclientes">
										<span class="glyphicon glyphicon-search" ></span> Buscar</button>
									
								</div><?php 
							} ?>
					</div>					
					<div class="col-md-3">
						<span id="loader"></span>
					</div>
				</form>					
			</div>			
			<div id="resultados"></div><!-- Carga los datos ajax -->
			<div class='outer_div'></div><!-- Carga los datos ajax -->
			
		</div>	
	</div>


<?php
}




?>
<script type="text/javascript" src="./js/newfactura.js"></script>



</body>
</html>
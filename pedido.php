<?php	
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	

	$nombre=$_SESSION['user_name'] ; 

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';?>


	<!DOCTYPE html>
<html lang="en">
<head>
	<?php include("head.php"); ?>

	<style>
/* The container */
.container2 {
/*  display: block;*/
  position: relative;
  padding-left: 35px;
  margin-bottom: -3px;
  margin-left: 30px;
  cursor: pointer;
  font-size: 18px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}


/* Hide the browser's default checkbox */
.container2 input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container2:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container2 input:checked ~ .checkmark {
  background-color: #1fce24;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container2 input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container2 .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
</head>	
 <body onload="load(1)">
	<?php include("navbar.php");?>


<br/>
	<?php
if ($action == 'pedido') 
{ ?>
	<div class="container">	
		<div class="panel panel-primary">
			<div class="panel-heading">
			    <div class="btn-group pull-right">
					<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
				</div>
				<h4>Alta de pedidos </h4>
			</div>
			
			<div class="panel-body">
				<form class="form-horizontal" role="form" id="datos_factura" name="datos_factura"  onkeypress="return anular(event)">
					
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
		                		<input type="hidden" class="form-control input-sm" id="cod_matriz" value="<?php echo base64_decode($_GET['matriz']); ?>">
		                		<input type="hidden" class="form-control input-sm" id="vendedor" value="<?php echo $_SESSION['user_cvevend']; ?>"> <?php
		                	}
		                	elseif ($_SESSION['user_tipo']='M') { ?>
		                		<input type="hidden" class="form-control input-sm" id="cod_matriz" value="<?php echo $_SESSION['user_email']; ?>"> <?php
		                	}
		                	elseif ($_SESSION['user_tipo']='S') {?>  <?php
		                	} ?>
		                </div>
		                <label class="col-md-1 offset-1 control-label">Disponible</label>
		                <div class="col-md-2">
		                	<input type="text" class="form-control input-sm" id="disponible" value="<?php echo base64_decode($_GET['disponible']); ?>" readonly>
		                </div>
		            </div>

		            <div class="form-group row">
		            	<label for="tel2" class="col-md-1 control-label">Fecha</label>
		            	<div class="col-md-2">
		            		<input type="text" class="form-control input-sm" id="fecha" value="<?php echo date("Y-m-d");?>" readonly>
		            	</div>
		            	<label for="fecha2" class="col-md-1 control-label">F.Entrega</label>
		            	<div class="col-md-2">
		            		<input type="text" class="form-control input-sm" id="fechaent" value="<?php echo date("Y-m-d");?>" id="f_ent"  maxlength="60"  onkeypress='return validaNumericos(event)'> <!-- echo date('Y-m-d',strtotime($fecha )); extraer fecha en -->
		            	</div>

		            	<label class="col-md-1 control-label">T. Contri.</label>
		            	<div class="col-md-1">
		            		<input type="text" class="form-control input-sm" id="tipo_contribuyente" value="<?php echo base64_decode($_GET['tipo']); ?>" readonly>
		            	</div>                                    
		            </div> 

		            <div class="form-group row">
		            	<label for="comentarios" class="col-md-1 control-label">Comentarios:</label>
		            	<div class="col-md-11">
		            		<input type="text" class="form-control input-sm" id="comentarios" value="<?php echo base64_decode($_GET['comment'])?>">
		            	</div>
		            </div> 
					<?php if($_SESSION['empre_numero']=='14' or $_SESSION['empre_numero']=='11'){
					 $cond = base64_decode($_GET['condi']);
					 $dc = explode('-',$cond);
					 
						?>
					<table width="100%" >
						<tr>
							<th>
								<label class='container2'>
								<p id="tiptext1">CREDITO</p>
									<input type='checkbox' id="tipc"  name='document' <?php if($dc[0] == "CREDITO"){echo "checked";}?> value="<?php if($dc[0]!=""){echo $dc[0];}else{echo "CONTADO";}?>"  >
									<span class='checkmark'></span>
								</label>
							</th>
							<th>
								<label class='container2'>
								<p id="tiptext2">VENDEDOR</p> 
									<input type='checkbox' id="tipc2"  name='document2'  <?php if($dc[1] == "VENDEDOR"){echo "checked";}?> VALUE="<?php if($dc[1]!=""){echo $dc[1];}else{echo "REPARTO";}?>"  >
									<span class='checkmark'></span>
								</label>
							</th>
						</tr>
					</table>
					<?php }?>
		        	<div>
		        		<hr>
		        	</div>
				    <div class="pull-right">
				    	<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
				    		<span class="glyphicon glyphicon-plus"></span> Agregar productos
				    	</button>
				    	<a href="#" class='btn btn-default' title='Cancelar Pedido' onclick="pedidocancelar(1)"><i class="glyphicon glyphicon-inbox"></i> Cancelar</a> 
				    	<br/ >
				    </div>
				</form>
			</div>
			<?php include("modal/buscar_productos.php"); ?>
			
			<div id="resultado"></div><!-- Carga los datos ajax -->
			<div class='outer_div'></div><!-- Carga los datos ajax -->
		</div>
	</div>
<?php }

if ($action == 'cotizaciones') 
{ ?>
	<div class="container">	
		<div class="panel panel-primary">
			<div class="panel-heading">
			    <div class="btn-group pull-right">
					<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
				</div>
				<h4>Alta de cotizaciones </h4>
			</div>
			
			<div class="panel-body">
				<form class="form-horizontal" role="form" id="datos_factura" name="datos_factura"  onkeypress="return anular(event)">
					
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
		                		<input type="hidden" class="form-control input-sm" id="cod_matriz" value="<?php echo base64_decode($_GET['matriz']); ?>">
		                		<input type="hidden" class="form-control input-sm" id="vendedor" value="<?php echo $_SESSION['user_cvevend']; ?>"> <?php
		                	}
		                	elseif ($_SESSION['user_tipo']='M') { ?>
		                		<input type="hidden" class="form-control input-sm" id="cod_matriz" value="<?php echo $_SESSION['user_email']; ?>"> <?php
		                	}
		                	elseif ($_SESSION['user_tipo']='S') {?>  <?php
		                	} ?>
		                </div>
		                <label class="col-md-1 offset-1 control-label">Disponible</label>
		                <div class="col-md-2">
		                	<input type="text" class="form-control input-sm" id="disponible" value="<?php echo base64_decode($_GET['disponible']); ?>" readonly>
		                </div>
		            </div>

		            <div class="form-group row">
		            	<label for="tel2" class="col-md-1 control-label">Fecha</label>
		            	<div class="col-md-2">
		            		<input type="text" class="form-control input-sm" id="fecha" value="<?php echo date("Y-m-d");?>" readonly>
		            	</div>
		            	<label for="fecha2" class="col-md-1 control-label">F.Entrega</label>
		            	<div class="col-md-2">
		            		<input type="text" class="form-control input-sm" id="fechaent" value="<?php echo date("Y-m-d");?>"> <!-- echo date('Y-m-d',strtotime($fecha )); extraer fecha en -->
		            	</div>

		            	<label class="col-md-1 control-label">T. Contri.</label>
		            	<div class="col-md-1">
		            		<input type="text" class="form-control input-sm" id="tipo_contribuyente" value="<?php echo base64_decode($_GET['tipo']); ?>" readonly>
		            	</div>                                    
		            </div> 

		            <div class="form-group row">
		            	<label for="comentarios" class="col-md-1 control-label">Comentarios:</label>
		            	<div class="col-md-11">
		            		<input type="text" class="form-control input-sm" id="comentarios">
		            	</div>
		            </div>
		        	
		        	<div>
		        		<hr>
		        	</div>
				    <div class="pull-right">
				    	<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
				    		<span class="glyphicon glyphicon-plus"></span> Agregar productos
				    	</button>
				    	<a href="#" class='btn btn-default' title='Cancelar Pedido' onclick="cotizacion_cancelar(1)"><i class="glyphicon glyphicon-inbox"></i> Cancelar</a> 
				    	<br/ >
				    </div>
				</form>
			</div>
			<?php include("modal/buscar_productos.php"); ?>
			
			<div id="resultado"></div><!-- Carga los datos ajax -->
		</div>
	</div>
<?php }

if ($action == 'cotizaciones_varios') 
{ ?>
	<div class="container">	
		<div class="panel panel-primary">
			<div class="panel-heading">
			    <div class="btn-group pull-right">
					<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
				</div>
				<h4>Alta de cotizaciones </h4>
			</div>
			
			<div class="panel-body">
				<form class="form-horizontal" role="form" id="datos_factura" name="datos_factura"  onkeypress="return anular(event)">
					
					<div class="form-group row">
						<label for="nombre_cliente" class="col-md-1 control-label">Nombre</label>
						<div class="col-md-5">
							<input type="text" class="form-control input-sm" id="nsucursal" value="<?php echo $_GET['mon']; ?>">
		                </div>
		                <label for="mail" class="col-md-1 offset-1 control-label">Clave</label>
		                <div class="col-md-1">
		                	<input type="text" class="form-control input-sm" id="cod_cliente" value="<?php echo $_GET['eva']; ?>" readonly>
		                	<?php
		                	if ($_SESSION['user_tipo']='V') { ?>
		                		<input type="hidden" class="form-control input-sm" id="cod_matriz" value="<?php echo $_GET['matriz']; ?>">
		                		<input type="hidden" class="form-control input-sm" id="vendedor" value="<?php echo $_SESSION['user_cvevend']; ?>"> <?php
		                	}
		                	elseif ($_SESSION['user_tipo']='M') { ?>
		                		<input type="hidden" class="form-control input-sm" id="cod_matriz" value="<?php echo $_SESSION['user_email']; ?>"> <?php
		                	}
		                	elseif ($_SESSION['user_tipo']='S') {?>  <?php
		                	} ?>
		                </div>
		                <label class="col-md-1 offset-1 control-label">Disponible</label>
		                <div class="col-md-2">
		                	<input type="text" class="form-control input-sm" id="disponible" value="<?php echo $_GET['disponible']; ?>" readonly>
		                </div>
		            </div>

		            <div class="form-group row">
		            	<label for="tel2" class="col-md-1 control-label">Fecha</label>
		            	<div class="col-md-2">
		            		<input type="text" class="form-control input-sm" id="fecha" value="<?php echo date("Y-m-d");?>" readonly>
		            	</div>
		            	<label for="fecha2" class="col-md-1 control-label">F.Entrega</label>
		            	<div class="col-md-2">
		            		<input type="text" class="form-control input-sm" id="fechaent" value="<?php echo date("Y-m-d");?>"> <!-- echo date('Y-m-d',strtotime($fecha )); extraer fecha en -->
		            	</div>

		            	<label class="col-md-1 control-label">T. Contri.</label>
		            	<div class="col-md-1">
		            		<input type="text" class="form-control input-sm" id="tipo_contribuyente" value="<?php echo $_GET['tipo']; ?>" readonly>
		            	</div>                                    
		            </div> 

		            <div class="form-group row">
		            	<label for="comentarios" class="col-md-1 control-label">Comentarios</label>
		            	<div class="col-md-5">
		            		<input type="text" class="form-control input-sm" id="comentarios">
		            	</div>
		            
		            	<label for="comentarios" class="col-md-1 control-label">Dirección</label>
		            	<div class="col-md-5">
		            		<input type="text" class="form-control input-sm" id="direccion">
		            	</div>
		            </div>
		        	
		        	<div>
		        		<hr>
		        	</div>
				    <div class="pull-right">
				    	<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
				    		<span class="glyphicon glyphicon-plus"></span> Agregar productos
				    	</button>
				    	<a href="#" class='btn btn-default' title='Cancelar Pedido' onclick="cotizacion_cancelar(1)"><i class="glyphicon glyphicon-inbox"></i> Cancelar</a> 
				    	<br/ >
				    </div>
				</form>

			</div>
			<?php include("modal/buscar_productos.php"); ?>
			
			<div id="resultado"></div><!-- Carga los datos ajax -->
		</div>
	</div>
<?php }

	?>





<script type="text/javascript" src="./js/newfactura.js"></script>
<?php if(isset($_GET['eva'])) { ?> <script language="javascript">  window.onload = cargaprod(1);  </script>  <?php } ?>


</body>
</html>
<?php
include('is_logged.php');	
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:''; 

if($action == 'add_tmpprod_vendedor') // agrega  producto del tmp reclamos det desde panel vendedores
{
	require_once("../config/conexionweb.php");	
	$prod=$_GET['producto'];
	$cve_art=$_GET['cveart'];
	$cantidad=$_GET['cantidad'];
	$cliente=$_GET['cveclie'];
	$usuario=$_GET['usuario'];
	$nfactura=$_GET['nfactura'];

	$ttmp=$_GET['treclamo'];
	$tfactura=$_GET['tfactura'];
	$prodtmp=$_GET['productotmp'];
	$lote=$_GET['lotetmp'];

	if (($ttmp+$cantidad)>$tfactura && $prod=$prodtmp) {
		
		try
		{
			$usuarios = array();               
	        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

			$query = "select cve_art,producto,sum(cantidad) as cantidad,lote from tmp_det_reclamos where ltrim(cve_clie)='".ltrim($cliente)."' and usuario='".$usuario."' and estado=0 group by cve_art,producto,lote; ";

			
			$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
	        $result = sqlsrv_execute($stmt);
	        $row_count = sqlsrv_num_rows( $stmt );

	        if ($row_count == true)
	        { ?>
				<table class="table">
				<tr>
					<th class='text-center'>Codigo</th>
					<th class='text-center'>Descripcion.</th>
					<th class='text-center'>Cantidad</th>
					<th class='text-center'>Lote</th>
					<th class='text-center'>Accion</th>				
				</tr> <?php

	        	while($row = sqlsrv_fetch_array($stmt))
				{
					$codigo=$row['cve_art'];
					$producto=$row['producto'];
					$cantidad=$row['cantidad'];
					$lote=$row['lote'];

					?>
					<tr>
						<td class='text-left'><?php echo $codigo;?></td>
						<td class='text-left'><?php echo $producto;?></td>
						<td class='text-right'><?php echo $cantidad;?></td>
						<td class='text-right'><?php echo $lote;?></td>
						<td class='text-center'>
							<input type="hidden" value="<?php echo $cantidad; ?>" id="cantidad_tmp<?php echo $codigo;?>">
							<input type="hidden" value="<?php echo $producto; ?>" id="descripcion_tmp<?php echo $codigo;?>">

							<a href="#" onclick="delet_prod_tmp_vendedores('<?php echo $codigo; ?>')"><i class="glyphicon glyphicon-trash"></i></a>
						</td>
					</tr> <?php
				} ?>
				<tr>
					<td class='text-right' colspan="4">
								<a href="#" onclick="graba_reclamo_vendedores(1)"><i class="glyphicon glyphicon-ok"></i>Grabar Reclamo</a>
					</td>
				</tr>
				<tr>
					<td colspan="4" class='text-center'>Error no puedes procesar mas reclamo de lo facturado</td>
				</tr>

			</table> <?php

	        }
		//sqlsrv_free_stmt( $stmt);
		}
		catch (Exception $e)
		{
			echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
		}  
		sqlsrv_close($conn); 
		

	}
	else{

		$prod=$prod." (".$nfactura.")";	
	
			
		$sql = "INSERT INTO tmp_det_reclamos (cve_art,producto,cantidad,cve_clie,usuario,estado,lote) VALUES ('".trim($cve_art)."','".ltrim($prod)."','".$cantidad."','".ltrim($cliente)."','".ltrim($usuario)."','0','".ltrim($lote)."')";
		$stmt = sqlsrv_query( $conn, $sql);

		if($stmt){ $something = "Submission successful."; }
		else{ $something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;
		
		/* Free statement and connection resources. */    
		sqlsrv_free_stmt( $stmt); 
		
		try
		{
			$usuarios = array();               
	        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

			$query = "select cve_art,producto,sum(cantidad) as cantidad,lote from tmp_det_reclamos where ltrim(cve_clie)='".ltrim($cliente)."' and usuario='".$usuario."'  and estado=0  group by cve_art,producto,lote; ";

			
			$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
	        $result = sqlsrv_execute($stmt);
	        $row_count = sqlsrv_num_rows( $stmt );

	        if ($row_count == true)
	        { ?>
				<table class="table">
				<tr>
					<th class='text-center'>Codigo</th>
					<th class='text-center'>Descripcion.</th>
					<th class='text-center'>Cantidad</th>
					<th class='text-center'>Lote</th>
					<th class='text-center'>Accion</th>				
				</tr> <?php

	        	while($row = sqlsrv_fetch_array($stmt))
				{
					$codigo=$row['cve_art'];
					$producto=$row['producto'];
					$cantidad=$row['cantidad'];
					$lote=$row['lote'];

					?>
					<tr>
						<td class='text-left'><?php echo $codigo;?></td>
						<td class='text-left'><?php echo $producto;?></td>
						<td class='text-right'><?php echo $cantidad;?></td>
						<td class='text-right'><?php echo $lote;?></td>
						<td class='text-center'>
							<input type="hidden" value="<?php echo $cantidad; ?>" id="cantidad_tmp<?php echo $codigo;?>">
							<input type="hidden" value="<?php echo $producto; ?>" id="descripcion_tmp<?php echo $codigo;?>">

							<a href="#" onclick="delet_prod_tmp_vendedores('<?php echo $codigo; ?>')"><i class="glyphicon glyphicon-trash"></i></a>
						</td>
					</tr> <?php
				} ?>
				<tr>
					<td class='text-right' colspan="4">
								<a href="#" onclick="graba_reclamo_vendedores(1)"><i class="glyphicon glyphicon-ok"></i>Grabar Reclamo</a>
					</td>
				</tr>

			</table> <?php

	        }
		//sqlsrv_free_stmt( $stmt);
		}
		catch (Exception $e)
		{
			echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
		}  
		sqlsrv_close($conn); 


	}	
}

if($action == 'delet_prod_tmp_vendedores') // elimina producto del tmp reclamos det desde panel vendedores
{
	require_once("../config/conexionweb.php");	
	$codigo=ltrim($_GET['codigo']);
	$cliente=ltrim($_GET['clie']);
	$usuario=trim($_GET['usuario']);			
	
	$sql = "DELETE FROM tmp_det_reclamos WHERE cve_art='".$codigo."' and cve_clie='".$cliente."' and usuario='".$usuario."' AND estado=0;";
	$stmt = sqlsrv_query( $conn, $sql);
	if ( $stmt ) { $something = "Submission successful.";}     
	else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
	$output=$something;
	/* Free statement and connection resources. */    
	sqlsrv_free_stmt( $stmt);  
		
	try
		{
			$usuarios = array();               
	        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

			$query = "select cve_art,producto,sum(cantidad) as cantidad,lote from tmp_det_reclamos where ltrim(cve_clie)='".ltrim($cliente)."' and usuario='".$usuario."'  and estado=0  group by cve_art,producto,lote; ";

			
			$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
	        $result = sqlsrv_execute($stmt);
	        $row_count = sqlsrv_num_rows( $stmt );

	        if ($row_count == true)
	        { ?>
				<table class="table">
				<tr>
					<th class='text-center'>Codigo</th>
					<th class='text-center'>Descripcion.</th>
					<th class='text-center'>Cantidad</th>
					<th class='text-center'>Lote</th>
					<th class='text-center'>Accion</th>				
				</tr> <?php

	        	while($row = sqlsrv_fetch_array($stmt))
				{
					$codigo=$row['cve_art'];
					$producto=$row['producto'];
					$cantidad=$row['cantidad'];
					$lote=$row['lote'];

					?>
					<tr>
						<td class='text-left'><?php echo $codigo;?></td>
						<td class='text-left'><?php echo $producto;?></td>
						<td class='text-right'><?php echo $cantidad;?></td>
						<td class='text-right'><?php echo $lote;?></td>
						<td class='text-center'>
							<input type="hidden" value="<?php echo $cantidad; ?>" id="cantidad_tmp<?php echo $codigo;?>">
							<input type="hidden" value="<?php echo $producto; ?>" id="descripcion_tmp<?php echo $codigo;?>">

							<a href="#" onclick="delet_prod_tmp_vendedores('<?php echo $codigo; ?>')"><i class="glyphicon glyphicon-trash"></i></a>
						</td>
					</tr> <?php
				} ?>
				<tr>
					<td class='text-right' colspan="4">
								<a href="#" onclick="graba_reclamo_vendedores(1)"><i class="glyphicon glyphicon-ok"></i>Grabar Reclamo</a>
					</td>
				</tr>

			</table> <?php

	        }
		//sqlsrv_free_stmt( $stmt);
		}
	catch (Exception $e)
	{
		echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
	}  
	sqlsrv_close($conn); ?>

	</table>
	<?php
}

if($action == 'buscareclamospendientes') // agrega  producto del tmp reclamos det desde panel vendedores
{
	require_once("../config/conexionweb.php");	
	
	$cliente=$_GET['cveclie'];
	$usuario=$_GET['usuario'];
	
	
	try
	{
		$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

		$query = "select cve_art,producto,sum(cantidad) as cantidad,lote from tmp_det_reclamos where ltrim(cve_clie)='".ltrim($cliente)."' and usuario='".$usuario."'  and estado=0  group by cve_art,producto,lote; ";
		
		
		$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );

        if ($row_count == true)
        { ?>
			<table class="table">
			<tr>
				<th class='text-center'>Codigo</th>
				<th class='text-center'>Descripcion.</th>
				<th class='text-center'>Cantidad</th>
				<th class='text-center'>Lote</th>
				<th class='text-center'>Accion</th>
				
				
			</tr> <?php

        	while($row = sqlsrv_fetch_array($stmt))
			{
				$codigo=$row['cve_art'];
				$producto=$row['producto'];
				$cantidad=$row['cantidad'];
				$lote=$row['lote'];

				?>
				<tr>
					<td class='text-left'><?php echo $codigo;?></td>
					<td class='text-left'><?php echo $producto;?></td>
					<td class='text-right'><?php echo $cantidad;?></td>
					<td class='text-right'><?php echo $lote;?></td>
					<td class='text-center'>
						<input type="hidden" value="<?php echo $cantidad; ?>" id="cantidad_tmp<?php echo $codigo;?>">
						<input type="hidden" value="<?php echo $producto; ?>" id="descripcion_tmp<?php echo $codigo;?>">

						<a href="#" onclick="delet_prod_tmp_vendedores('<?php echo $codigo; ?>')"><i class="glyphicon glyphicon-trash"></i></a>
						
					</td>
				</tr> <?php
			} ?>
			<tr>
				<td class='text-right' colspan="4">
							<a href="#" onclick="graba_reclamo_vendedores(1)"><i class="glyphicon glyphicon-ok"></i>Grabar Reclamo</a>
				</td>
			</tr>

		</table> <?php

        }
		
		
	//sqlsrv_free_stmt( $stmt);
	}
	catch (Exception $e)
	{
		echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
	}  
	sqlsrv_close($conn);
}

if($action == 'agrega_reclamo_vendedor') // agrega  producto del tmp reclamos det desde panel vendedores
{
	require_once("../config/conexionweb.php");	
	
	$idcliente=$_GET['idcliente'];
	$idusuario=$_GET['idusuario'];
	$idvendedor=$_GET['idvendedor'];
	$nombre=$_GET['nombre'];
	$rdatos=$_GET['rdatos'];
	$problema=$_GET['problema'];
	$tproblema=$_GET['tproblema'];
		

	$hoy = date("Y-m-d H:i:s");
	
	try
	{
		$sql = "INSERT INTO reclamos (cve_cliente,nombre,recibe_datos,tipo_devolucion,problema,usuario,fecha_ingreso) VALUES ('".trim($idcliente)."','".ltrim($nombre)."','".ltrim($rdatos)."','".ltrim($tproblema)."','".ltrim($problema)."','".ltrim($idusuario)."','".$hoy."')";



		$stmt = sqlsrv_query( $conn, $sql);

		if($stmt){ $something = "Submission successful."; }
		else{ $something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;
		
		/* Free statement and connection resources. */    
		sqlsrv_free_stmt( $stmt); 

		$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

		$query = "select max(id) as id from reclamos where ltrim(cve_cliente)='".ltrim($idcliente)."' and usuario='".ltrim($idusuario)."'; ";

		

		$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );

        if ($row_count == true){
        	while($row = sqlsrv_fetch_array($stmt))
				{
					$id_reclamo=$row['id'];
				}
        }
        else
        {
        	$id_reclamo=1;
        }
        
		$sql = "INSERT INTO det_reclamos (ir_reclamo,cve_art,producto,cantidad,lote) select ".$id_reclamo." as ir_reclamo,cve_art,producto,cantidad,lote from tmp_det_reclamos where ltrim(cve_clie)='".ltrim($idcliente)."' and usuario='".ltrim($idusuario)."' and estado=0; ";

		

		$stmt = sqlsrv_query( $conn, $sql);

		if($stmt){ $something = "Submission successful."; }
		else{ $something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;
		
		/* Free statement and connection resources. */    
		sqlsrv_free_stmt( $stmt); 


		$sql = "update tmp_det_reclamos set estado='1' where ltrim(cve_clie)='".ltrim($idcliente)."' and usuario='".ltrim($idusuario)."' and estado=0; ";

		$stmt = sqlsrv_query( $conn, $sql);

		if($stmt){ $something = "Submission successful."; }
		else{ $something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;
		
		/* Free statement and connection resources. */    
		sqlsrv_free_stmt( $stmt); 

		include ('./mail/phpmailer.php');
		include ('./mail/class.smtp.php');

		try 
		{
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPDebug = 2;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'ssl';
			$mail->SMTPOptions = array
			(
			'ssl' => array
			(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
			)
			);
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465;
			$mail->IsHTML(true);
			$mail->Username = 'diaco@alimentosdiaco.com';
			$mail->Password = 'diaco2018';
			$mail->SetFrom('diaco@alimentosdiaco.com');
			$mail->Subject = "Nuevo reclamo recibido del vendedor ".$_SESSION['user_name']." a la plataforma WEB el dia ".date("Y-m-d")."";
			$mail->Body = "Ha ingresado un nuevo reclamo del cliente ".$nombre." con el problema ".$problema."";
			$mail->AddAddress('calidad@alimentosdiaco.com');

			?><h4 style="color: transparent;"><?php

			if(!$mail->Send()) 
			{
			echo "Mailer Error: " . $mail->ErrorInfo;
			} 
			else 
			{
			echo "Message has been sent";
			}
		} 
		catch (phpmailerException $e) 
		{
		  $errors[] = $e->errorMessage();
		}
		catch (Exception $e) 
		{
		  $errors[] = $e->getMessage(); 
		}
		?></h4> <?php

		echo "Se ha registrado correctamente el reclamo";
		
	//sqlsrv_free_stmt( $stmt);
	}
	catch (Exception $e)
	{
		echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
	}  
	sqlsrv_close($conn);
}

if($action == 'busca_reclamos_vendedores') // carga todos los reclamos ingresados cuando entras a reclamos por producto
{
	require_once("../config/conexionweb.php");		
	$usuario=$_SESSION['user_id'];
	$t_usuario=trim($_SESSION['user_tipo']);
	try
	{
		$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

        if ($t_usuario=="V" or $t_usuario=="M" or $t_usuario=="S") {
        	$query = "select id,cve_cliente,nombre,recibe_datos,tipo_devolucion,isnull(procede,'') as procede,CONVERT(varchar(10),fecha_ingreso,103) as fecha_ingreso from reclamos where usuario='".$usuario."' order by id desc; ";
        }
        elseif ($t_usuario=="C" or $t_usuario=="A" ) {
        	$query = "select id,cve_cliente,nombre,recibe_datos,tipo_devolucion,isnull(procede,'') as procede,CONVERT(varchar(10),fecha_ingreso,103) as fecha_ingreso from reclamos order by id desc; ";
        }

		$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );

        if ($row_count == true)
        { ?>
			<table class="table">
			<tr>
				<th class='text-center'>Cliente</th>
				<th class='text-center'>Tipo reclamo</th>
				<th class='text-center'>Quien recibe datos</th>
				<th class='text-center'>Estado</th>
				<th class='text-center'>Fecha reclamo</th>
				<th class='text-center' colspan="2">Accion</th>				
			</tr> <?php

        	while($row = sqlsrv_fetch_array($stmt))
			{
				$id=$row['id'];
				$idcliente=$row['cve_cliente'];
				$nombre=$row['nombre'];
				$recibe=$row['recibe_datos'];
				$tipo_devolucion=$row['tipo_devolucion'];
				$procede=$row['procede'];
				$fecha=$row['fecha_ingreso'];

				?>
				<tr>
					<td class='text-left'><?php echo $nombre;?></td>
					<td class='text-center'><?php echo $tipo_devolucion;?></td>
					<td class='text-left'><?php echo $recibe;?></td>
					<td class='text-center'><?php
						if ($procede== '0') {?> <span class="label label-danger"> No procesada</span> <?php }
						else if ($procede== '1'){ ?> <span class="label label-success"> Procesada</span> <?php } 	
						else if ($procede== '2'){ ?> <span class="label label-warning"> Procesada</span> <?php } ?>						
					</td>
					<td class='text-center'><?php echo $fecha;?></td>
					<td class='text-center'>
						<input type="hidden" value="<?php echo $id; ?>" id="id_reclamo<?php echo $id;?>">
						<input type="hidden" value="<?php echo $idcliente; ?>" id="cliente_reclamo<?php echo $id;?>">

						<!--<button type="button" class="btn btn-default col-md-offset-1" data-toggle="modal" data-target="#myModal" id="muestra_reclamos" onclick="carga_reclamo(<?php echo $id;?>);">
			    		<span class="glyphicon glyphicon-search" ></span></button>-->

			    		<a href=#" data-toggle="modal" data-target="#myModal" id="muestra_reclamos" onclick="carga_reclamo(<?php echo $id;?>);" title="Muestra reclamos"><i class="glyphicon glyphicon-search"></i></a> 
			    	</td>
			    	<td><?php
			    		if ($t_usuario=="C" or $t_usuario=="A" )  { 
			    			if($procede=='0'){ $idprocesar=base64_encode($id);?> 					    	
					    		<a href="procesa_reclamo.php?id=<?php echo $idprocesar ; ?>" onclick="procesa_reclamo('<?php echo $id;?>');" title='procesar reclamos'><i class="glyphicon glyphicon-certificate"></i></a>
					    		<?php
			    			}
			    		} ?> 

					</td>
				</tr> <?php
			} ?>
		</table> <?php
        }
	}
	catch (Exception $e)
	{
		echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
	}  
	sqlsrv_close($conn);
}

if($action == 'b_reclamos_v_nombre') // carga todos los reclamos ingresados cuando entras a reclamos por producto y digitas el nombre del cliente
{
	require_once("../config/conexionweb.php");		
	$usuario=$_SESSION['user_id'];
	$t_usuario=trim($_SESSION['user_tipo']);
	$nombre=$_GET['nombre'];	

	echo $nombre;
	try
	{
		$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

        if ($t_usuario=="V" or $t_usuario=="M" or $t_usuario=="S") {
        	$query = "select id,cve_cliente,nombre,recibe_datos,tipo_devolucion,isnull(procede,'') as procede,CONVERT(varchar(10),fecha_ingreso,103) as fecha_ingreso from reclamos where usuario='".$usuario."' order by id desc; ";
        }
        elseif ($t_usuario=="C" or $t_usuario=="A" ) {
        	//$query = "select id,cve_cliente,nombre,recibe_datos,tipo_devolucion,isnull(procede,'') as procede,CONVERT(varchar(10),fecha_ingreso,103) as fecha_ingreso from reclamos order by id desc; ";

        	$query = "select id,cve_cliente,nombre,recibe_datos,tipo_devolucion,isnull(procede,'') as procede,CONVERT(varchar(10),fecha_ingreso,103) as fecha_ingreso from reclamos where UPPER(nombre) like upper ('%".$nombre."%') order by id, fecha_ingreso desc; ";
        }

		$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );

        if ($row_count == true)
        { ?>
			<table class="table">
			<tr>
				<th class='text-center'>Cliente</th>
				<th class='text-center'>Tipo reclamo</th>
				<th class='text-center'>Quien recibe datos</th>
				<th class='text-center'>Estado</th>
				<th class='text-center'>Fecha reclamo</th>
				<th class='text-center' colspan="2">Accion</th>				
			</tr> <?php

        	while($row = sqlsrv_fetch_array($stmt))
			{
				$id=$row['id'];
				$idcliente=$row['cve_cliente'];
				$nombre=$row['nombre'];
				$recibe=$row['recibe_datos'];
				$tipo_devolucion=$row['tipo_devolucion'];
				$procede=$row['procede'];
				$fecha=$row['fecha_ingreso'];

				?>
				<tr>
					<td class='text-left'><?php echo $nombre;?></td>
					<td class='text-center'><?php echo $tipo_devolucion;?></td>
					<td class='text-left'><?php echo $recibe;?></td>
					<td class='text-center'><?php
						if ($procede== '0') {?> <span class="label label-danger"> No procesada</span> <?php }
						else if ($procede== '1'){ ?> <span class="label label-success"> Procesada</span> <?php } 	
						else if ($procede== '2'){ ?> <span class="label label-warning"> Procesada</span> <?php } ?>						
					</td>
					<td class='text-center'><?php echo $fecha;?></td>
					<td class='text-center'>
						<input type="hidden" value="<?php echo $id; ?>" id="id_reclamo<?php echo $id;?>">
						<input type="hidden" value="<?php echo $idcliente; ?>" id="cliente_reclamo<?php echo $id;?>">

						<!--<button type="button" class="btn btn-default col-md-offset-1" data-toggle="modal" data-target="#myModal" id="muestra_reclamos" onclick="carga_reclamo(<?php echo $id;?>);">
			    		<span class="glyphicon glyphicon-search" ></span></button>-->

			    		<a href=#" data-toggle="modal" data-target="#myModal" id="muestra_reclamos" onclick="carga_reclamo(<?php echo $id;?>);" title="Muestra reclamos"><i class="glyphicon glyphicon-search"></i></a> 
			    	</td>
			    	<td><?php
			    		if ($t_usuario=="C" or $t_usuario=="A" )  { 
			    			if($procede=='0'){ $idprocesar=base64_encode($id);?> 					    	
					    		<a href="procesa_reclamo.php?id=<?php echo $idprocesar ; ?>" onclick="procesa_reclamo('<?php echo $id;?>');" title='procesar reclamos'><i class="glyphicon glyphicon-certificate"></i></a>
					    		<?php
			    			}
			    		} ?> 

					</td>
				</tr> <?php
			} ?>
		</table> <?php
        }
	}
	catch (Exception $e)
	{
		echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
	}  
	sqlsrv_close($conn);
}

if($action == 'carga_reclamos') // carga el reclamo ingresado desde un vendedor matriz o sucursal
{
	require_once("../config/conexionweb.php");		
	$usuario=$_SESSION['user_id'];
	$idreclamo=$_GET['idreclamo'];	
	try
	{
		$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

        $devolucion="";
		$diferencia="";
		$otro="";

		$query = "select id,cve_cliente,nombre,recibe_datos,tipo_devolucion,isnull(procede,'') as procede,CONVERT(varchar(10),fecha_ingreso,103) as fecha_ingreso,problema,isnull(notas,'') as notas from reclamos where id='".$idreclamo."'; ";		
		
		$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );

        if ($row_count == true)
        { 

        	while($row = sqlsrv_fetch_array($stmt))
			{
				$id=$row['id'];
				$idcliente=$row['cve_cliente'];
				$nombre=$row['nombre'];
				$recibe=$row['recibe_datos'];
				$tipo_devolucion=$row['tipo_devolucion'];
				$procede=$row['procede'];
				$fecha=$row['fecha_ingreso'];
				$problema=$row['problema'];
				$notas=$row['notas'];
			}

			if ($tipo_devolucion=="devolucion") {
				$devolucion="checked";
				$diferencia="";
				$otro="";				
			}
			elseif ($tipo_devolucion=="diferencia") {
				$devolucion="";
				$diferencia="checked";
				$otro="";				
			}
			elseif ($tipo_devolucion=="otros") {
				$devolucion="";
				$diferencia="";
				$otro="checked";				
			}?>

			<div id="contenedor_muestrareclamo">
				<h4>Reclamo ingresado</h4> 
				<hr>

				<form class="form-horizontal" role="form" id="muestra_datos_reclamo" name="muestra_datosreclamos">
					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_fec">Fecha: </label>
						<div class="col-sm-2">
							<input type="text" class="form-control" id="muestra_fecha" value="<?php echo $fecha; ?>" readonly>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_clie">Cliente: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="muestra_nombre_cliente" value="<?php echo $nombre; ?>" readonly size="auto">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_rdat">Recibe datos: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="muestra_r_datos" value="<?php echo $recibe; ?>"  readonly>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="tipo">Tipo devolucion: </label>
						<div class="col-sm-6">
							<div class="radio" readonly>
								<label><input type="radio" id="muestra_td0" name="muestra_tdev" value="devolucion" <?php echo $devolucion;?>  onclick="return false;"> Producto</label> &nbsp;
								<label><input type="radio" id="muestra_td1" name="muestra_tdev" value="diferencia" <?php echo $diferencia;?>  onclick="return false;"> Diferencia de precio</label> &nbsp;
								<label><input type="radio" id="muestra_td2" name="muestra_tdev" value="otros" <?php echo $otro;?>  onclick="return false;"> Otros</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_rprod">Problema: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="muestra_problema"  value="<?php echo $problema;?>" readonly>
						</div>
					</div>
				<hr>
				<h4>Detalle de Articulos</h4> <?php

				$query = "select cve_art,producto,cantidad,lote,destino from det_reclamos where ir_reclamo='".$idreclamo."';";			
			
				$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
		        $result = sqlsrv_execute($stmt);
		        $row_count = sqlsrv_num_rows( $stmt );

		        if ($row_count == true)
		        { ?>
					<table class="table">
					<tr>
						<th class='text-center'>Codigo</th>
						<th class='text-center'>Descripcion.</th>
						<th class='text-center'>Lote</th>
						<th class='text-center'>Destino</th>
						<th class='text-center'>Cantidad</th>							
					</tr> <?php

		        	while($row = sqlsrv_fetch_array($stmt))
					{
						$codigo=$row['cve_art'];
						$producto=$row['producto'];
						$cantidad=$row['cantidad'];
						$lote=$row['lote'];
						$destino=$row['destino']; ?>
						
						<tr>
							<td class='text-left'><?php echo $codigo;?></td>
							<td class='text-left'><?php echo $producto;?></td>
							<td class='text-right'><?php echo $lote;?></td>
							<td class='text-right'><?php echo $destino;?></td>
							<td class='text-right'><?php echo $cantidad;?></td>							
						</tr> <?php
					} ?>					
					
					</table> 
					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_rprod">Notas: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="notas"  value="<?php echo $notas;?>" readonly>
						</div>
					</div>

					<div class="radio" id="radios">
						<label class="control-label col-sm-3" for="muestra_rprod">Procede como devolucion: </label>
						<label><input type="radio" id="si_procede_dev" name="optradio" value="1" <?php if ($procede=="1") { echo "checked='checked'";} ?> onclick="return false;">SI</label>
						<label><input type="radio" id="no_procede_dev" name="optradio" value="2" <?php if ($procede=="2") { echo "checked='checked'";} ?> onclick="return false;">NO</label>
						<br>
						<label >(En caso se proceda como devolucion, se hara como nota de credito unicamente) </label>
						
					</div>
				</form><?php

				}
			?></div> <?php 
	}
  	sqlsrv_close($conn);		
	
	}
	catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }  
}


if($action == 'procesa_reclamos') // carga todos los reclamos ingresados cuando entras a reclamos por producto para procesarlos
{
	require_once("../config/conexionweb.php");		
	$usuario=$_SESSION['user_id'];
	$idreclamo=$_GET['idreclamo'];	
	try
	{
		$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

        $devolucion="";
		$diferencia="";
		$otro="";
		$lote="";
		$destino="";

		$query = "select id,cve_cliente,nombre,recibe_datos,tipo_devolucion,isnull(procede,'') as procede,CONVERT(varchar(10),fecha_ingreso,103) as fecha_ingreso,problema from reclamos where id='".$idreclamo."'; ";		
		
		$stmt= sqlsrv_prepare($conn, $query, $usuarios, $options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );

        if ($row_count == true)
        { 
        	while($row = sqlsrv_fetch_array($stmt))
			{
				$id=$row['id'];
				$idcliente=$row['cve_cliente'];
				$nombre=$row['nombre'];
				$recibe=$row['recibe_datos'];
				$tipo_devolucion=$row['tipo_devolucion'];
				$procede=$row['procede'];
				$fecha=$row['fecha_ingreso'];
				$problema=$row['problema'];
			}

			if ($tipo_devolucion=="devolucion") {
				$devolucion="checked";
				$diferencia="";
				$otro="";
			}
			elseif ($tipo_devolucion=="diferencia") {
				$devolucion="";
				$diferencia="checked";
				$otro="";				
			}
			elseif ($tipo_devolucion=="otros") {
				$devolucion="";
				$diferencia="";
				$otro="checked";				
			}?>

			<div id="contenedor_muestrareclamo">
				<h4>Procesa reclamos ingresado</h4> 
				<hr>

				<form class="form-horizontal" role="form" id="muestra_datos_reclamo" name="muestra_datosreclamos">
					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_fec">Fecha: </label>
						<div class="col-sm-2">
							<input type="text" class="form-control" id="muestra_fecha" value="<?php echo $fecha; ?>" readonly>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_clie">Cliente: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="muestra_nombre_cliente" value="<?php echo $nombre; ?>" readonly size="auto">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_rdat">Recibe datos: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="muestra_r_datos" value="<?php echo $recibe; ?>"  readonly>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="tipo">Tipo devolucion: </label>
						<div class="col-sm-6">
							<div class="radio" readonly>
								<label><input type="radio" id="muestra_td0" name="muestra_procede" value="devolucion" <?php echo $devolucion;?>  onclick="return false;"> Producto</label> &nbsp;
								<label><input type="radio" id="muestra_td1" name="muestra_procede" value="diferencia" <?php echo $diferencia;?>  onclick="return false;"> Diferencia de precio</label> &nbsp;
								<label><input type="radio" id="muestra_td2" name="muestra_tdev" value="otros" <?php echo $otro;?>  onclick="return false;"> Otros</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_rprod">Problema: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="muestra_problema"  value="<?php echo $problema;?>" readonly>
						</div>
					</div>
				<hr>
				<h4>Detalle de Articulos</h4> <?php

				$query = "select id,cve_art,producto,cantidad,isnull(lote,'') as lote,isnull(destino,'') as destino from det_reclamos where ir_reclamo='".$idreclamo."';";				
			
				$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
		        $result = sqlsrv_execute($stmt);
		        $row_count = sqlsrv_num_rows( $stmt );

		        if ($row_count == true)
		        { ?>
					<table class="table">
					<tr>
						<th class='text-center'>Codigo</th>
						<th class='text-center'>Descripcion.</th>
						<th class='text-center'>Cantidad</th>
						<th class='text-left'>Lote</th>
						<th class='text-left'>Destino</th>
						<th class='text-center'>Accion</th>
							
					</tr> <?php

		        	while($row = sqlsrv_fetch_array($stmt))
					{//mailparse_msg_extract_part_file(mimemail, filename)
						$idlinea=trim($row['id']);
						$codigo=$row['cve_art'];
						$producto=$row['producto'];
						$cantidad=$row['cantidad'];
						$lote=$row['lote'];
						$destino=$row['destino']; ?>
						
						<tr>
							<td class='text-left'><?php echo $codigo;?></td>
							<td class='text-left'><?php echo $producto;?></td>
							<td class='text-center'><?php echo $cantidad;?></td>

							<td>
								<input type="text" id="lote42_<?php echo $idlinea;?>" value="<?php echo $lote; ?>" class="col-md-6" > 

							</td>
							<td>
								<input type="text" id="destino42_<?php echo $idlinea;?>" value="<?php echo $destino; ?>" class="col-md-6" >
							</td>
							<td>
								<button type="button" class="btn btn-default col-md-8" onclick="graba_lote_destino('<?php echo $idlinea;?>');">
			    				<span class="glyphicon glyphicon-floppy-saved" ></span></button>

			    				<!--<a href="#" onclick="graba_lote_destino('<?php echo $idlinea;?>');"><i class="glyphicon glyphicon-trash"></i></a>-->

			    				
							</td>						
						</tr> <?php
					} ?>
					
					</table> <hr>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_rprod">Notas: </label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="notas42" >
						</div>
					</div>					

					<div class="radio" id="radios">
						<label class="control-label col-sm-3" for="muestra_rprod">Procede como devolucion: </label>
						<label><input type="radio" id="si_procede_dev" name="optradio" value="1">SI</label>
						<label><input type="radio" id="no_procede_dev" name="optradio" value="2">NO</label>
						<br>
						<label >(En caso se proceda como devolucion, se hara como nota de credito unicamente) </label>
						
					</div>

					<input type="hidden" id="idreclamo_procesar" value="<?php echo $_GET['idreclamo']; ?>">

					</form>
					<?php

				}

			?></div> <?php 
	}
  	sqlsrv_close($conn);
		
		
	//sqlsrv_free_stmt( $stmt);
	}
	catch (Exception $e)
	{
		echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
	}  
}

if($action == 'graba_lote_destino') // carga todos los reclamos ingresados cuando entras a reclamos por producto para procesarlos
{
	require_once("../config/conexionweb.php");		
	$usuario=$_SESSION['user_id'];
	$iditemreclamo=$_GET['iditemreclamo'];	
	$lote=$_GET['lote'];	
	$destino=$_GET['destino'];
	$idreclamo=$_GET['nreclamo'];
	
	$hoy = date("Y-m-d H:i:s");

	try
	{
		$sql = "update det_reclamos set lote='".$lote."', destino='".$destino."' where id='".$iditemreclamo."';";

		$stmt = sqlsrv_query( $conn, $sql);

		if($stmt){ $something = "Submission successful."; }
		else{ $something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;
		
		/* Free statement and connection resources. */    
		sqlsrv_free_stmt( $stmt); 

		$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

        $devolucion="";
		$diferencia="";
		$otro="";
		$lote="";
		$destino="";

		$query = "select id,cve_cliente,nombre,recibe_datos,tipo_devolucion,isnull(procede,'') as procede,CONVERT(varchar(10),fecha_ingreso,103) as fecha_ingreso,problema from reclamos where id='".$idreclamo."'; ";		
		
		$stmt= sqlsrv_prepare($conn, $query, $usuarios, $options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );

        if ($row_count == true)
        { 
        	while($row = sqlsrv_fetch_array($stmt))
			{
				$id=$row['id'];
				$idcliente=$row['cve_cliente'];
				$nombre=$row['nombre'];
				$recibe=$row['recibe_datos'];
				$tipo_devolucion=$row['tipo_devolucion'];
				$procede=$row['procede'];
				$fecha=$row['fecha_ingreso'];
				$problema=$row['problema'];
			}

			if ($tipo_devolucion=="devolucion") {
				$devolucion="checked";
				$diferencia="";
				$otro="";
			}
			elseif ($tipo_devolucion=="diferencia") {
				$devolucion="";
				$diferencia="checked";
				$otro="";				
			}
			elseif ($tipo_devolucion=="otros") {
				$devolucion="";
				$diferencia="";
				$otro="checked";				
			}?>

			<div id="contenedor_muestrareclamo">
				<h4>Procesa reclamos ingresado</h4> 
				<hr>

				<form class="form-horizontal" role="form" id="muestra_datos_reclamo" name="muestra_datosreclamos">
					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_fec">Fecha: </label>
						<div class="col-sm-2">
							<input type="text" class="form-control" id="muestra_fecha" value="<?php echo $fecha; ?>" readonly>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_clie">Cliente: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="muestra_nombre_cliente" value="<?php echo $nombre; ?>" readonly size="auto">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_rdat">Recibe datos: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="muestra_r_datos" value="<?php echo $recibe; ?>"  readonly>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="tipo">Tipo devolucion: </label>
						<div class="col-sm-6">
							<div class="radio" readonly>
								<label><input type="radio" id="muestra_td0" name="muestra_procede" value="devolucion" <?php echo $devolucion;?>  onclick="return false;"> Producto</label> &nbsp;
								<label><input type="radio" id="muestra_td1" name="muestra_procede" value="diferencia" <?php echo $diferencia;?>  onclick="return false;"> Diferencia de precio</label> &nbsp;
								<label><input type="radio" id="muestra_td2" name="muestra_tdev" value="otros" <?php echo $otro;?>  onclick="return false;"> Otros</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_rprod">Problema: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="muestra_problema"  value="<?php echo $problema;?>" readonly>
						</div>
					</div>
				<hr>
				<h4>Detalle de Articulos</h4> <?php

				$query = "select id,cve_art,producto,cantidad,isnull(lote,'') as lote,isnull(destino,'') as destino from det_reclamos where ir_reclamo='".$idreclamo."';";				
			
				$stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
		        $result = sqlsrv_execute($stmt);
		        $row_count = sqlsrv_num_rows( $stmt );

		        if ($row_count == true)
		        { ?>
					<table class="table">
					<tr>
						<th class='text-center'>Codigo</th>
						<th class='text-center'>Descripcion.</th>
						<th class='text-center'>Cantidad</th>
						<th class='text-left'>Lote</th>
						<th class='text-left'>Destino</th>
						<th class='text-center'>Accion</th>
							
					</tr> <?php

		        	while($row = sqlsrv_fetch_array($stmt))
					{//mailparse_msg_extract_part_file(mimemail, filename)
						$idlinea=trim($row['id']);
						$codigo=$row['cve_art'];
						$producto=$row['producto'];
						$cantidad=$row['cantidad'];
						$lote=$row['lote'];
						$destino=$row['destino']; ?>
						
						<tr>
							<td class='text-left'><?php echo $codigo;?></td>
							<td class='text-left'><?php echo $producto;?></td>
							<td class='text-center'><?php echo $cantidad;?></td>
							<td>
								<input type="text" id="lote42_<?php echo $idlinea;?>" value="<?php echo $lote; ?>" class="col-md-6" >
							</td>
							<td>
								<input type="text" id="destino42_<?php echo $idlinea;?>" value="<?php echo $destino; ?>" class="col-md-6" >
							</td>
							<td>
								<button type="button" class="btn btn-default col-md-8" onclick="graba_lote_destino('<?php echo $idlinea;?>');">
			    				<span class="glyphicon glyphicon-floppy-saved" ></span></button>
			    				<!--<a href="#" onclick="graba_lote_destino('<?php //echo $idlinea;?>');"><i class="glyphicon glyphicon-trash"></i></a>-->			    				
							</td>						
						</tr> <?php
					} ?>
					
					</table> <hr>

					<div class="form-group">
						<label class="control-label col-sm-2" for="muestra_rprod">Notas: </label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="notas42" >
						</div>
					</div>					

					<div class="radio" id="radios">
						<label class="control-label col-sm-3" for="muestra_rprod">Procede como devolucion: </label>
						<label><input type="radio" id="si_procede_dev" name="optradio" value="1">SI</label>
						<label><input type="radio" id="no_procede_dev" name="optradio" value="2">NO</label>
						<br>
						<label >(En caso se proceda como devolucion, se hara como nota de credito unicamente) </label>
						
					</div>

					<input type="hidden" id="idreclamo_procesar" value="<?php echo $_GET['idreclamo']; ?>">

					</form>
					<?php

				}

			?></div> <?php 
	}	
  		 sqlsrv_close($conn);
		
		
	//sqlsrv_free_stmt( $stmt);
	}
	catch (Exception $e)
	{
		echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
	}  	
}

if($action == 'graba_reclamo_procesado') // carga todos los reclamos ingresados cuando entras a reclamos por producto para procesarlos
{
	require_once("../config/conexionweb.php");		
	$usuario=$_SESSION['user_id'];	
	$nota=$_GET['nota'];	
	$procede=$_GET['procede'];
	$idreclamo=$_GET['nreclamo'];
	
	$hoy = date("Y-m-d H:i:s");

	try
	{
		$sql = "update reclamos set notas='".$nota."', procede='".$procede."',fecha_resolucion='".$hoy."' where id='".$idreclamo."';";

		$stmt = sqlsrv_query( $conn, $sql);

		if($stmt){ $something = "Submission successful."; }
		else{ $something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;
		
		/* Free statement and connection resources. */    
		sqlsrv_free_stmt( $stmt); 
		sqlsrv_close($conn);

		echo "<h4>Reclamo grabado exitosamente</h4>";
		
		
	//sqlsrv_free_stmt( $stmt);
	}
	catch (Exception $e)
	{
		echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
	}  
	
}


?>


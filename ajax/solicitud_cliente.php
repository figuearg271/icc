<?php

try
{ 
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	$vendedor=trim($_SESSION['user_cvevend']);
	/* Connect To Database*/
	//require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	//Contiene funcion que conecta a la base de datos

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	
	if($action == 'grabasolicitud')
	{ 
		require_once("../config/conexionweb.php");	
		$rfc=$_GET['rfc'];
		$nit=$_GET['nit'];
		$giro=$_GET['giro'];
		$nombre=$_GET['nombre'];
		$dir=$_GET['dir'];
		$tel=$_GET['tel'];
		$correo=$_GET['correo'];
		$matriz=$_GET['matriz'];
		$tipo_clie=$_GET['tipo_clie'];
		$ruta=$_GET['ruta'];
		$req1=$_GET['req1'];
		$req2=$_GET['req2'];
		$tipo_cont=$_GET['tipo_cont'];
		$nsucursal=$_GET['nsucursal'];
		$maneja_cred=$_GET['maneja_cred'];
		$dias_cred=$_GET['dias_cred'];
		$limite_cred=$_GET['limite_cred'];
		$vendedor=$_GET['vendedor'];
		$lista_prec=$_GET['lista_prec'];
		$hoy = date("Y-m-d H:i:s");				

		$sql = "INSERT INTO solicitud_cliente (dui,nit,nombre,direccion,tel,correo,matriz,tip_cliente,ruta,req1,req2,tip_contribuyente,n_cliente,credito,dias,limite,vendedor,l_precio,estado,fecha_solicitud) VALUES ('".ltrim($rfc)."','".ltrim($nit)."','".ltrim($nombre)."','".ltrim($dir)."','".ltrim($tel)."','".$correo."','".$matriz."','".$tipo_clie."','".ltrim($ruta)."','".ltrim($req1)."','".ltrim($req2)."','".ltrim($tipo_cont)."','".ltrim($nsucursal)."','".ltrim($maneja_cred)."','".ltrim($dias_cred)."','".ltrim($limite_cred)."','".ltrim($vendedor)."','".ltrim($lista_prec)."','0','".$hoy."');";
		$stmt = sqlsrv_query( $conn, $sql);
		if($stmt){ $something = "Submission successful."; }
		else{ $something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;
		
		/* Free statement and connection resources. */  

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
			$mail->Subject = "solicitud de nuevo cliente del vendedor ".$vendedor." a la plataforma WEB.";
			$mail->Body = "Ha ingresado solicitud de nuevo cliente";
			$mail->AddAddress('it@alimentosdiaco.com');

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
		sqlsrv_free_stmt( $stmt); 
		sqlsrv_close( $conn);

		echo "<label>Solicitud grabada exitosamente, se ha enviado un correo al administrador</label>";
	}

	if ($action =='valida_registro') 
	{
		require_once("../config/conexionweb.php");	
			$rfc=$_GET['rfc'];
			$usuarios = array();               
	        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );         
	        $query = "select * from solicitud_cliente where dui='".$rfc."';";         
	        $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
	        $result = sqlsrv_execute($stmt);
	        $row_count = sqlsrv_num_rows( $stmt );

	        if ($row_count == true) { ?> <label>Cliente ya registrado</label> <?php }
			sqlsrv_close($conn); 	
	}

	if ($action =='carga_soliciutdes') 
	{
		require_once("../config/conexionweb.php");	
			$vendedor=$_GET['vendedor'];
			$usuarios = array();               
	        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );        
	        $query = "select id,nombre,convert(varchar(10), fecha_solicitud, 103) as fecha_solicitud,estado from solicitud_cliente where vendedor='".$vendedor."' order by id desc;";        
	        $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
	        $result = sqlsrv_execute($stmt);
	        $row_count = sqlsrv_num_rows( $stmt );

	        if ($row_count == true)
	        {?>
				<table class="table">
				<tr>
					<th class='text-center'>Nombre</th>
					<th class='text-center'>Fecha Solicitud</th>
					<th class='text-center'>Estado</th>
					<?php if ($_SESSION['user_tipo']=='A') { ?> <th class='text-center'>Accion</th> <?php  } ?> 
				</tr> <?php

	        	while($row = sqlsrv_fetch_array($stmt))
				{
					$id=$row['id'];
					$nombre=$row['nombre'];
					$fechas=$row['fecha_solicitud'];
					$estado=$row['estado'];

					?>
					<tr>
						<td class='text-left'><?php echo $nombre;?></td>
						<td class='text-center'><?php echo $fechas;?></td>
						<td class='text-center'><?php
							if ($estado== '0') {?> <span class="label label-danger"> No procesada</span> <?php }
							else if ($estado== '1'){ ?> <span class="label label-success"> Procesada</span> <?php } ?>	</td>
						<td class='text-center'>
							<?php if ($_SESSION['user_tipo']=='A') {
								?> <a href="#" onclick="procesa_solicitu('<?php echo $id; ?>')"><i class="glyphicon glyphicon-trash"></i></a> <?php
							} ?>						
						</td>
					</tr> <?php
				} ?>
			</table> <?php
			}

			sqlsrv_close($conn);	
	}
}

catch (Exception $e)
	{
		echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
	}  
?>

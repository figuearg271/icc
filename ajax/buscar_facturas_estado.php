<?php
	//este se ejecuta al mostrar el desgloce de facturas desde la pestaña de clientes

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	//require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexionsae.php");//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	
	if (isset($_GET['id'])){
		$numero_factura=intval($_GET['id']);
		$del1="delete from facturas where numero_factura='".$numero_factura."'";
		$del2="delete from detalle_factura where numero_factura='".$numero_factura."'";
		if ($delete1=mysqli_query($con,$del1) and $delete2=mysqli_query($con,$del2)){
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong> Datos eliminados exitosamente
			</div>
			<?php 
		}else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> No se puedo eliminar los datos
			</div>
			<?php
			
		}
	}

	if($action == 'ajax')
	{
		// escapando, eliminando además todo lo que podría ser (html / javascript-) código  
			try {
			       				       	      	
            	$usuarios = array();               
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

                $query = "SELECT CM.CVE_CLIE,(SELECT NOMBRE FROM CLIE01 WHERE CLAVE=CM.CVE_CLIE) as NOMBRE,CM.NO_FACTURA,convert(varchar(10), CM.FECHA_APLI, 103) as FECHA_APLI,convert(varchar(10), CM.FECHA_VENC, 103) as FECHA_VENC,CAST(CM.IMPORTE*CM.SIGNO AS DECIMAL(10,2)) AS IMPORTE,CM.SIGNO from CUEN_M01 as CM where NO_FACTURA not in (select NO_FACTURA FROM CUEN_DET01 where NO_FACTURA=CM.NO_FACTURA) AND CM.CVE_CLIE='".$_GET['clave_sucursal']."' order by CM.FECHA_APLI DESC, CM.NO_FACTURA";

                $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
                $result = sqlsrv_execute($stmt);
                $row_count = sqlsrv_num_rows( $stmt );

                if ($row_count == true)
                {
                ?>
			<div class="table-responsive"> 
				<table class="table table-hover table-responsive">
				 	<tr  class="info">
						 		<th class='text-center'>Tipo Doc.</th>
						 		<th class='text-center'>Nombre</th>
						 		<th class='text-center'>Factura #</th>
						 		<th class='text-center'>Fecha de creacion</th>
						 		<th class='text-center'>Fecha de vencimiento</th>
						 		<th class='text-center'>Pago</th>
						 		<th class='text-right'>Accion</th>
						 	</tr>
					<?php 

	                    while($row = sqlsrv_fetch_array($stmt))
	                    {
	                    	$cve_clie=$row['CVE_CLIE'];
	                    	$nombre=$row['NOMBRE'];
	                    	$no_factura=$row['NO_FACTURA'];
	                    	$fecha_apli=$row['FECHA_APLI'];
	                    	$fecha_venc=$row['FECHA_VENC'];
	                    	$importe=$row['IMPORTE'];
	                    	$signo=$row['SIGNO'];
	                    	if($signo==1)
	                    	{
	                    		$tdoc="Credito Fiscal";
	                    		$td="F";
	                    	}
	                    	else
	                    	{
	                    		$tdoc="Nota de Credito";
	                    		$td="D";
	                    	}
						?>
						<tr>
							<td><?php echo $tdoc; ?></td>
							<td><?php echo $nombre; ?></td>
							<td class='text-center'><?php echo $no_factura; ?></td>
							<td class='text-center'><?php echo $fecha_apli; ?></td>
							<td class='text-center'><?php echo $fecha_venc; ?></td>
							<td class='text-right'><?php echo $importe; ?></td>
							<td class="text-right">
								<input type="hidden" id="id_factura_<?php echo $no_factura; ?>" value="<?php echo $no_factura; ?>">
								<input type="hidden" id="t_factura_<?php echo $no_factura; ?>" value="<?php echo $td; ?>">

								<a href="#" class='btn btn-default' title='Ver Detalle' onclick="muestrafacturasdetalle('<?php echo $no_factura; ?>')"><i class="glyphicon glyphicon-zoom-in"></i></a>
							</td>
							
						</tr>
						<?php
	                    }

	                    ?>
	                    
	                </table>
	            </div>
			<?php
                }
                else
                {
                    //$this->errors[] = "No se encontraron sucursales";
                }
                ## cerramos la conexion    
                sqlsrv_close($conn);           
            }
            catch (Exception $e)
            {
                echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
            }             


		//loop through fetched data
		?>
		<script type="text/javascript" src="./js/estados.js"></script>
		<?php
	}


if($action == 'vendedor')
{
		
	try {
		$hoy=date("d-m-Y"); 
	       				       	      	
    	//$query = "SELECT CM.CVE_CLIE,(SELECT NOMBRE FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=CM.CVE_CLIE) as NOMBRE,CM.NO_FACTURA AS NO_FACTURA,CAST(CM.FECHA_APLI AS DATE) AS FECHA_APLI,CAST(CM.FECHA_VENC AS DATE) AS FECHA_VENC,CAST(CM.IMPORTE*CM.SIGNO AS DECIMAL(10,2)) AS IMPORTE,CM.SIGNO from CUEN_M".$_SESSION['empre_numero']." as CM where NO_FACTURA not in (select NO_FACTURA FROM CUEN_DET".$_SESSION['empre_numero']." where NO_FACTURA=CM.NO_FACTURA) AND CM.CVE_CLIE='".$_GET['idsucursal']."' and IMPORTE>0 order by CM.FECHA_APLI DESC, CM.NO_FACTURA";

    	$query="SELECT 
			CM.CVE_CLIE, CM.IMPORTE AS MONTOFACT,
			(SELECT NOMBRE FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=CM.CVE_CLIE) as NOMBRE,
			TRIM(CM.NO_FACTURA) AS NO_FACTURA,
			CAST(CM.FECHA_APLI AS DATE) AS FECHA_APLI,
			CAST(CM.FECHA_VENC AS DATE) AS FECHA_VENC,
			COALESCE(CAST(((CM.IMPORTE*CM.SIGNO)-(select SUM(C.IMPORTE) FROM CUEN_DET".$_SESSION['empre_numero']." as C where TRIM(C.NO_FACTURA)=TRIM(CM.NO_FACTURA) GROUP BY TRIM(C.NO_FACTURA))) AS DECIMAL(10,2)),CM.IMPORTE) AS IMPORTE,
			CM.SIGNO 
			from CUEN_M".$_SESSION['empre_numero']." as CM 
			where  
			CM.CVE_CLIE='".$_GET['idsucursal']."' and 
			(TRIM(CM.NO_FACTURA) not in (select TRIM(C.NO_FACTURA) FROM CUEN_DET".$_SESSION['empre_numero']." AS C where TRIM(C.NO_FACTURA)=TRIM(CM.NO_FACTURA) GROUP BY TRIM(C.NO_FACTURA)) OR CM.IMPORTE > (select SUM(C.IMPORTE) FROM CUEN_DET".$_SESSION['empre_numero']." AS C where TRIM(C.NO_FACTURA)=TRIM(CM.NO_FACTURA) GROUP BY TRIM(C.NO_FACTURA)))

			order by CM.NO_FACTURA ASC";

			
        $result = ibase_query($conn, $query); ?>
	<div class="table-responsive"> 
				<table class="table table-hover table-responsive">
			 	<tr  class="info">
				 		<th class='text-center'>Tipo Doc.</th>
				 		<th class='text-center'>Nombre</th>
				 		<th class='text-center'>Factura #</th>
				 		<th class='text-center'>Fecha de creacion</th>
				 		<th class='text-center'>Fecha de vencimiento</th>
				 		<th class='text-center'>Dias de transcurridos</th>
						 <th class='text-center'>Monto Factura</th>
				 		<th class='text-center'>Pendiente</th>
						 
				 		<th class='text-right'>Accion</th>
				 	</tr>
				<?php 
				$totalcliente=0;

                    while($row = ibase_fetch_object($result))
                    {

                    	$cve_clie=$row->CVE_CLIE;
                    	$nombre=$row->NOMBRE;
                    	$no_factura=$row->NO_FACTURA;
                    	$fecha_apli=$row->FECHA_APLI;
                    	$fecha_venc=$row->FECHA_VENC;
                    	$importe=$row->IMPORTE;
						$signo=$row->SIGNO;
						$montofact = $row->MONTOFACT;

                    	if($importe > 0){
                    		if($signo==1)
		                    	{
		                    		$tdoc="Credito Fiscal";
		                    		$td="F";
		                    	}
		                    	else
		                    	{
		                    		$tdoc="Nota de Credito";
		                    		$td="D";
		                    	}

				                    	$totalcliente=$totalcliente+$importe;

				                    	$dias=substr($fecha_venc, 8, 2);
				                    	$meses=substr($fecha_venc, 5, 2);
				                    	$años=substr($fecha_venc, 0, 4);

				                    	$date1 = new DateTime($hoy);
										$date2 = new DateTime($años."-".$meses."-".$dias);
										$diff = $date1->diff($date2);

				                    	
									?>
									<tr>
										<td><?php echo $tdoc; ?></td>
										<td><?php echo $nombre; ?></td>
										<td class='text-center'><?php echo $no_factura; ?></td>
										<td class='text-center'><?php echo $fecha_apli; ?></td>
										<td class='text-center'><?php echo $fecha_venc; ?></td>
										<td class='text-center'><?php echo $diff -> days; ?></td>
										<td class='text-right'><?php echo number_format ($montofact,2); ?></td>
										<td class='text-right'><?php echo number_format ($importe,2); ?></td>
										
										<td class="text-right">
											<input type="hidden" id="id_factura_<?php echo $no_factura; ?>" value="<?php echo $no_factura; ?>">
											<input type="hidden" id="t_factura_<?php echo $no_factura; ?>" value="<?php echo $td; ?>">
											<input type="hidden" id="cveclie_<?php echo $no_factura; ?>" value="<?php echo $_GET['idsucursal']; ?>">

											<?php
											if ($importe==$montofact) {
												
											}
											else
											{?>
												<a href="#" class='btn btn-primary' title='Ver abonos' data-toggle="modal" data-target="#ver_abonos" onclick="ver_abonos('<?php echo $no_factura; ?>')">
													<i class="glyphicon glyphicon-tasks"></i>
												</a> <?php

											}
											?>

											
										</td>
										
									</tr>
									<?php
		                    	}

		                    	
		                    }

		                    ?>
		                    <tr>
		                    	<td class='text-right' colspan="6"><h4 style="font-style: bold">Pendiente de pago</h4></td>
		                    	<td class='text-right' colspan="2"><h4 style="font-style: bold"><?php echo number_format ($totalcliente,2); ?></h4></td>
		                    </tr>
		                    
		            </table>
	            </div>
			<?php
                
                ## cerramos la conexion    
                ibase_close($conn);           
            }
            catch (Exception $e)
            {
                echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
            }             


		//loop through fetched data
		?>
<script type="text/javascript" src="js/clientes.js"></script>
<?php
	}



?>
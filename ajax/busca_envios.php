<?php
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
		 $matriz= $_GET['clave_matriz'];
		 $sucusal= $_GET['clave_sucursal'];	
		 $nsucursal= $_GET['nombre_sucursal'];		

		 
		try
            {
            	

		//main query to fetch the data		
            	$usuarios = array();               
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $query = "SELECT D.CVE_DOC,(SELECT NOMBRE FROM CLIE01 WHERE CLAVE=D.CVE_CLPV) AS SUCURSAL,convert(varchar(10),D.FECHA_DOC,103) as FECHA,CAST(D.IMPORTE AS DECIMAL(10,2)) AS IMPORTE,CAST(ISNULL((SELECT IMPORTE FROM FACTF01 WHERE CVE_DOC=D.DOC_SIG),0) AS DECIMAL(10,2)) as IMPORTE2,ISNULL(D.DOC_SIG,'') AS DOC_SIG,D.FORMAENVIO FROM FACTP01 AS D
where D.STATUS<>'C' AND D.CVE_CLPV='".$sucusal."' AND YEAR(D.FECHA_DOC)>=2017 AND D.SERIE<>'P' AND D.CVE_DOC NOT IN(SELECT CVE_DOC FROM FACTF01 WHERE DOC_ANT=D.CVE_DOC) AND D.IMPORTE>0 ORDER BY D.FECHA_DOC DESC;";
                $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
                $result = sqlsrv_execute($stmt);
                $row_count = sqlsrv_num_rows( $stmt );

                

                if ($row_count == true)
                {
                ?>
				<div class="table-responsive">
					<table class="table table-hover table-responsive" >
					 	<tr  class="info">
							<th colspan="8" align="text-center">
								Sucursal Seleccionada: <?php echo $nsucursal;?>

							</th>
						</th>
					 	<tr  class="info">

					 		<th class='text-center'> N° Pedido </th>
					 		<th class='text-center'> Estado </th>
					 		<th class='text-center'> Sucursal  </th>
					 		<th class='text-center'> Fecha  </th>
					 		<th class='text-right'> Val. Pedido </th>
					 		<th class='text-right'> Val. Envio  </th>
					 		<th class='text-center'>  Pedido  </th>
					 		<th class='text-center'>  Facturado </th>
					 	</tr>
					<?php 

	                    while($row = sqlsrv_fetch_array($stmt))
	                    {
	                    	if ($row['FORMAENVIO'] = 'I' && $row['DOC_SIG']<>'' ){
	                    		$stado='Entregado';
	                    	}
	                    	else
	                    	{
	                    		$stado='En Progreso';
	                    	}

	                    	$cvedoc=$row['CVE_DOC'];
	                    	$sucursal=$row['SUCURSAL'];
	                    	$fecha=$row['FECHA'];
	                    	$importe=$row['IMPORTE'];
	                    	$importe2=$row['IMPORTE2'];
	                    	$docsig=$row['DOC_SIG'];

						?>
						<tr>
							<td><?php echo $cvedoc; ?></td>
							<td class='text-center'><?php echo $stado; ?></td>
							<td><?php echo $sucursal; ?></td>
							<td class='text-center'><?php echo $fecha; ?></td>
							<td class='text-right'><?php echo number_format ($importe,2); ?></td>
							<td class='text-right'><?php echo number_format ($importe2,2); ?></td>
							
							<td class="text-center">
								<input type="hidden" id="id_fac_<?php echo $cvedoc; ?>" value="<?php echo $cvedoc; ?>">
								<input type="hidden" id="id_suc_<?php echo $cvedoc; ?>" value="<?php echo $_GET['clave_sucursal']; 
								?>">
								<input type="hidden" id="n_suc_<?php echo $cvedoc; ?>" value="<?php echo $nsucursal; ?>">

								<a href="#" class='btn btn-default' title='Ver Envio' onclick="muestraenviodetalle('<?php echo $cvedoc; ?>')"><i class="glyphicon glyphicon-th-list"></i></a>
						</td>
						<td class='text-center'>
							<?php
							if($row['DOC_SIG']<>'')
							{
								?>
								<input type="hidden" id="id_fac_<?php echo $docsig; ?>" value="<?php echo $docsig; ?>">
								<input type="hidden" id="id_suc_<?php echo $docsig; ?>" value="<?php echo $_GET['clave_sucursal']; ?>">
								<input type="hidden" id="n_suc_<?php echo $docsig; ?>" value="<?php echo $nsucursal; ?>">

								<a href="#" class='btn btn-default' title='Ver Factura' onclick="muestrafacturadetalle('<?php echo $docsig; ?>')"><i class="glyphicon glyphicon-inbox"></i></a>
								<?php
							}
							?>

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
		
	}
?>
<script type="text/javascript" src="./js/pedidos.js"></script>
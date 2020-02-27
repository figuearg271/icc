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
		// escapando, eliminando además todo lo que podría ser (html / javascript-) código  
			try {
			       				       	      	
            	$usuarios = array();               
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                
                $factura=$_GET['cve_fac'];
                 $sucursal=$_GET['cve_suc'];
                 $nsucursal=$_GET['nombre_suc'];
                
                $query = "select CAST(DF.CANT AS DECIMAL(10,2)) AS CANT,(SELECT DESCR FROM INVE01 WHERE CVE_ART=DF.CVE_ART) AS ARTICULO,CAST(DF.PREC AS DECIMAL(10,2)) AS PRECIO,CAST(DF.TOTIMP3 AS DECIMAL(10,2)) AS RETENCION,CAST(DF.TOTIMP4 AS DECIMAL(10,2)) AS IVA,CAST((DF.DESC1+DF.DESC2) AS DECIMAL(10,2)) AS DESCUENTO,CAST(DF.TOT_PARTIDA AS DECIMAL(10,2)) AS SUBTOTAL,CAST((DF.TOTIMP3+DF.TOTIMP4-(DF.DESC1+DF.DESC2)+DF.TOT_PARTIDA) AS DECIMAL(10,2)) AS TOTAL,(SELECT IMPORTE FROM FACTP01 WHERE CVE_DOC=DF.CVE_DOC) AS IMPORTE from PAR_FACTP01 AS DF where CVE_DOC='".$factura."'";

              
                
                $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
                $result = sqlsrv_execute($stmt);
                $row_count = sqlsrv_num_rows( $stmt );

                if ($row_count == true)
                {
                ?>
				<div class="table-responsive">
					<table class="table table-hover table-responsive" >
					 	<tr  class="info">
							<th colspan="8" align="text-right">

							<input type="hidden" id="id_matriz_<?php echo $sucursal; ?>" value="<?php echo $_SESSION['user_email']; ?>">
								<input type="hidden" id="id_sucursal_<?php echo $sucursal; ?>" value="<?php echo $sucursal; ?>">

								<input type="hidden" id="n_sucursal_<?php echo $sucursal; ?>" value="<?php echo $nsucursal; ?>">

							<a href="#" class='btn btn-default' title='Back' onclick="busca_envios('<?php echo $sucursal; ?>')">◄◄</a>
						</th>

						</tr>
					 	<tr  class="info">
					 		<th class='text-right'>Cantidad</th>
                        	<th class='text-center'>Articulo</th>
                        	<th class='text-right'>Precio</th>
                        	<th class='text-right'>Retencion</th>
                        	<th class='text-right'>Iva</th>
                        	<th class='text-right'>Descuento</th>
                        	<th class='text-right'>Sub total</th>
                        	<th class='text-right'>Total</th>
					 	</tr>
					<?php 

	                    while($row = sqlsrv_fetch_array($stmt))
	                    {
	                    	$can=$row["CANT"];
	                    	$art=$row["ARTICULO"];
	                    	$prec=$row["PRECIO"];
	                    	$ret=$row["RETENCION"];
	                    	$iva=$row["IVA"];
	                    	$des=$row["DESCUENTO"];
	                    	$sub=$row["SUBTOTAL"];
	                    	$tot=$row["TOTAL"];
	                    	 $total=$row["IMPORTE"];
						?>
						<tr>
							<td><?php echo $can; ?></td>
							<td><?php echo $art; ?></td>
							<td class='text-right'><?php echo $prec; ?></td>
							<td class='text-right'><?php echo $ret; ?></td>
							<td class='text-right'><?php echo $iva; ?></td>
							<td class='text-right'><?php echo $des; ?></td>
							<td class='text-right'><?php echo $sub; ?></td>
							<td class='text-right'><?php echo $tot; ?></td>
							
						</tr>
						<?php
	                    }

	                    ?>
	                    <tr>
	                    	<td colspan="8" class="text-right">Total del documento $<?php echo $total; ?></td>
	                    </tr>
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
<script type="text/javascript" src="js/pedidos.js"></script>
<?php

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

if($action == 'b_factura_cliente_vendedor')
	{
		require_once('../config/conexionsae.php');
		
		
		try
		{
			$usuarios = array();               
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            
            $query = "select DF.CVE_ART,(SELECT DESCR FROM INVE01 WHERE CVE_ART=DF.CVE_ART) AS DESCRIPCION,CAST(DF.CANT AS DECIMAL (10,2)) AS CANT,CAST((select CAN_TOT from FACTF01 where CVE_DOC=DF.CVE_DOC) AS DECIMAL(10,2)) as IMPORTE from PAR_FACTF01 as DF WHERE DF.CVE_DOC in (select CVE_DOC from FACTF01 where CVE_DOC='".$_GET['n_factura']."' and CVE_CLPV='".$_GET['id_cliente']."');";

            $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
            $result = sqlsrv_execute($stmt);
            $row_count = sqlsrv_num_rows( $stmt );			

			if ($row_count == true)
			{ ?>
				<div class="table-responsive">
				<table class="table table-hover table-responsive" >
				 	<tr  class="info">
						<th>Código</th>
						<th>Producto</th>
						<th>Cant. Factura</th>
						<th>Cant. Reclamo</th>
						<th>Num. Lote</th>
						<th class='text-right'>Acciones</th>						
					</tr> <?php

				while($row = sqlsrv_fetch_array($stmt))
				{
					$codigo_sae=$row['CVE_ART'];
					$descripcion_sae=utf8_encode($row['DESCRIPCION']);					$cantidad_sae=$row['CANT'];
					$importe_sae=$row['IMPORTE'];
					
					?>
					
					<input type="hidden" value="<?php echo $codigo_sae;?>" id="codigo_producto_<?php echo $codigo_sae;?>">
					<input type="hidden" value="<?php echo $descripcion_sae;?>" id="nombre_producto_<?php echo $codigo_sae;?>">
					
					<input type="hidden" value="<?php echo $cantidad_sae; ?>" id="cantidad_tot<?php echo $codigo_sae;?>">
					<tr>
						
						<td><?php echo $codigo_sae; ?></td>
						<td ><?php echo $descripcion_sae; ?></td>
						<td><input type="text" value="<?php echo $cantidad_sae;?>" id="cantidad_<?php echo $codigo_sae;?>" readonly></td>
						<td><input type="text" value="0" id="reclamo_<?php echo $codigo_sae;?>"></td>
						<td><input type="text" value="0" id="lote_<?php echo $codigo_sae;?>"></td>
						<td ><span class="pull-right">
							<a href="#" class='btn btn-default' title='Agregar producto a reclamo' onclick="agrega_producto_reclamo('<?php echo $codigo_sae;?>');"><i class="glyphicon glyphicon-ok"></i></a> 
						</td>
						
					</tr>
					<?php
				}

				?> </ul> <?php 
				sqlsrv_close( $conn);
			}

			else
			{
				echo "Numero de factura ingresada no existe o no pertenece al cliente";
			}
			

		}
		catch (Exception $e)
		{
			echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
		}  
		


	}

	
		

?>
<script type="text/javascript" src="js/reclamos.js"></script>
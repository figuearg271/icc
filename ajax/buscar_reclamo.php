<?php
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	//require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexionsae.php");//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	
if($action == 'carga_reclamos_vendedores')
	{
		// escapando, eliminando además todo lo que podría ser (html / javascript-) código
         $sTable = "CLIE01";
		 $sWhere = "";
		 $matriz= $_GET['clave_matriz'];
		 $sucusal= $_GET['nombre_sucursal'];
		 $sWhere.="where MATRIZ='".$matriz."' AND STATUS<>'B' and SALDO>0.01";

		if ( $_GET['nombre_sucursal'] != "" and  $_GET['clave_matriz'] != "" )
		{
			$sWhere.= " and NOMBRE like upper('%".$sucusal."%')";
		}
		
		$sWhere.=" order by CLAVE desc";

		//include 'pagination.php'; //include pagination file
		//variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

		$per_page = 10; //cuantos registros quiere mostrar
		$adjacents  = 4; //brecha entre páginas después del número de adjacentes

		$offset = ($page - 1) * $per_page;
		$numrows=0;
		
		//Cuenta el número total de filas de la consulta 
		try
            {
            	$usuarios = array();               
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $query = "SELECT count(*) AS numrows FROM $sTable  $sWhere";
                $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
                $result = sqlsrv_execute($stmt);
                $row_count = sqlsrv_num_rows( $stmt );

                if ($row_count == true)
                {              
                    while($row = sqlsrv_fetch_array($stmt))
                    {
                       $numrows = $row['numrows'];
                    }
                }
                else
                {
                    //$this->errors[] = "No se encontraron sucursales";
                }
                ## cerramos la conexion               
                     


		
		$total_pages = ceil($numrows/$per_page);
		$reload = './estado.php';

		//main query to fetch the data
		


		
            	$usuarios = array();               
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $query = "select CLAVE,NOMBRE,cast(SALDO as decimal (10,2)) as SALDO FROM  ".$sTable." ".$sWhere." ";//LIMIT ".$offset.",".$per_page."";
                $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
                $result = sqlsrv_execute($stmt);
                $row_count = sqlsrv_num_rows( $stmt );

                if ($row_count == true)
                {
                ?>
				<div class="table-responsive">
				<table class="table table-hover table-responsive" >
				 	<tr  class="info">
					 		<th>Clave</th>
					 		<th>Nombre</th>
					 		<th class='text-right'>Saldo</th>
					 		<th class='text-right'>Acciones</th>
					 	</tr>
					<?php 

	                    while($row = sqlsrv_fetch_array($stmt))
	                    {
	                       $clavesucursal=$row['CLAVE'];
							$nombresucursal=utf8_decode($row["NOMBRE"]);
							$saldo=$row['SALDO'];
						?>
						<tr>
							<td><?php echo $clavesucursal; ?></td>
							<td><?php echo $nombresucursal; ?></td>
							<!-- <td>
								<a href="#" data-toggle="tooltip" data-placement="top" title="<i class='glyphicon glyphicon-phone'></i> <?php echo $telefono_cliente;?><br>
									<i class='glyphicon glyphicon-envelope'></i>  <?php echo $email_cliente;?>" ><?php echo $nombre_cliente;?>
								</a></td> --> 
							<td class='text-right'><?php echo number_format ($saldo,2); ?></td>	
							<td class="text-right">
								<input type="hidden" id="id_matriz_<?php echo $clavesucursal; ?>" value="<?php echo $_SESSION['user_email']; ?>">

								<input type="hidden" id="id_sucursal_<?php echo $clavesucursal; ?>" value="<?php echo $clavesucursal; ?>">

								<a href="#" class='btn btn-default' title='Ver Facturas' onclick="muestrafacturas('<?php echo $clavesucursal; ?>')"><i class="glyphicon glyphicon-search"></i></a>
						</td>
							
						</tr>
						<?php
	                    }

	                    ?>
	                   <!-- <tr>
	                    	<td colspan=7><span class="pull-right"><?php echo paginate($reload, $page, $total_pages, $adjacents); 					?></span></td>
	                    </tr>-->
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
<script type="text/javascript" src="js/estados.js"></script>
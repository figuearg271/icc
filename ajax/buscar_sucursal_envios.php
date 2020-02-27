<?php
include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
require_once ("../config/conexionsae.php");//Contiene funcion que conecta a la base de datos

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

if($action == 'ajax')
{  
    $sTable = "CLIE".$_SESSION['empre_numero']."";
	$sWhere = "";
	$matriz= $_GET['clave_matriz'];
	$sucusal= $_GET['nombre_sucursal'];
	$sWhere.="where MATRIZ='".$matriz."' AND STATUS='A'  ";

	if ( $_GET['nombre_sucursal'] != "" and  $_GET['clave_matriz'] != "" ) { $sWhere.= " and NOMBRE like upper('%".$sucusal."%')"; }
	
	$sWhere.=" order by CLAVE desc";
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

	$per_page = 10; //cuantos registros quiere mostrar
	$adjacents  = 4; //brecha entre páginas después del número de adjacentes

	$offset = ($page - 1) * $per_page;
	$numrows=0; //Cuenta el número total de filas de la consulta 
	try
    { 			
    	$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $query = "SELECT CLAVE,NOMBRE,convert(varchar(10), FCH_ULTCOM, 103) as FECHA_ULTCOMP,convert(varchar(10), ULT_PAGOF, 103) as FECHA_ULTPAGO,cast(VENTAS as decimal (10,2)) as VENTAS FROM ".$sTable." ".$sWhere." "; //LIMIT ".$offset.",".$per_page."";
        $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );            

        if ($row_count == true)
        { ?>
			<div class="table-responsive">
				<table class="table table-hover table-striped table-responsive" >
				 	<tr  class="info">
				 		<th>Clave</th>
				 		<th class='text-center'>Nombre</th>
				 		<th class='text-center'>Ultima Compra</th>
				 		<th class='text-center'>Ultimo Pago</th>

				 		<th class='text-center'>Accion</th>
				 	</tr> <?php 
                    while($row = sqlsrv_fetch_array($stmt))
                    {
                       $clavesucursal=$row['CLAVE'];
						$nombresucursal=$row["NOMBRE"];
						$fultv=$row['FECHA_ULTCOMP'];
						$fultp=$row['FECHA_ULTPAGO'];
						$vent=$row['VENTAS']; ?>
						<tr>
							<td><?php echo $clavesucursal; ?></td>
							<td><?php echo utf8_encode($nombresucursal); ?></td>
							<td class='text-center'><?php echo $fultv; ?></td>
							<td class='text-center'><?php echo $fultp; ?></td>
							<!--<td class='text-right'><?php echo number_format ($vent,2); ?></td>-->	
							<td class="text-center">
								<input type="hidden" id="id_matriz_<?php echo $clavesucursal; ?>" value="<?php echo $_SESSION['user_email']; ?>">
								<input type="hidden" id="id_sucursal_<?php echo $clavesucursal; ?>" value="<?php echo $clavesucursal; ?>">
								<input type="hidden" id="n_sucursal_<?php echo $clavesucursal; ?>" value="<?php echo $nombresucursal; ?>">
								<a href="#" class='btn btn-default' title='Ver Envios' onclick="busca_envios('<?php echo $clavesucursal; ?>')"><i class="glyphicon glyphicon-plus"></i></a>
							</td> 						
						</tr> <?php
                    } ?>
                   
                </table>
        	</div> <?php
    	}
        else { /*$this->errors[] = "No se encontraron sucursales";*/ }               
        sqlsrv_close($conn); ## cerramos la conexion        
    }
    catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }
}

if($action == 'bclientes_envios')
{
    $sTable = "CLIE".$_SESSION['empre_numero']."";
	$sWhere = "";
	$vendedor= $_GET['vendedor'];
	$nombre= $_GET['nombre'];
	$sWhere.="where CVE_VEND='".$vendedor."' AND STATUS='A'  ";

	if ( $_GET['nombre'] != "" and  $_GET['vendedor'] != "" ) { $sWhere.= " and NOMBRE like upper('%".$nombre."%')"; }
	
	$sWhere.=" order by CLAVE desc";  //include 'pagination.php'; //include pagination file //variables de paginación
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

	$per_page = 10; //cuantos registros quiere mostrar
	$adjacents  = 4; //brecha entre páginas después del número de adjacentes

	$offset = ($page - 1) * $per_page;
	$numrows=0; //Cuenta el número total de filas de la consulta 
	try
	{ //main query to fetch the data		
    	$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $query = "SELECT CLAVE,NOMBRE,convert(varchar(10), FCH_ULTCOM, 103) as FECHA_ULTCOMP,convert(varchar(10), ULT_PAGOF, 103) as FECHA_ULTPAGO,cast(VENTAS as decimal (10,2)) as VENTAS,MATRIZ FROM ".$sTable." ".$sWhere." ";//LIMIT ".$offset.",".$per_page."";

        $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );        

        if ($row_count == true)
        { ?>
			<div class="table-responsive">
				<table class="table table-striped table-hover">				
					 	<tr class="bg-primary">
				 		<th>Clave</th>
				 		<th class='text-center'>Nombre</th>
				 		<th class='text-center'>Ultima Compra</th>
				 		<th class='text-center'>Ultimo Pago</th>
				 		<!--<th class='text-center'>Total Comprado</th>-->
				 		<th class='text-center'>Accion</th>
				 	</tr> <?php 

	                while($row = sqlsrv_fetch_array($stmt))
	                {
	                   $clavesucursal=$row['CLAVE'];
						$nombresucursal=$row["NOMBRE"];
						$fultv=$row['FECHA_ULTCOMP'];
						$fultp=$row['FECHA_ULTPAGO'];
						$vent=$row['VENTAS'];
						$matrizsae=$row['MATRIZ']; ?>
					<tr>
						<td><?php echo $clavesucursal; ?></td>
						<td><?php echo utf8_encode($nombresucursal); ?></td>
						<td class='text-center'><?php echo $fultv; ?></td>
						<td class='text-center'><?php echo $fultp; ?></td>
						<!--<td class='text-right'><?php echo number_format ($vent,2); ?></td>-->	
						<td class="text-center">
							<input type="hidden" id="id_matriz_<?php echo $clavesucursal; ?>" value="<?php echo $matrizsae; ?>">

							<input type="hidden" id="id_sucursal_<?php echo $clavesucursal; ?>" value="<?php echo $clavesucursal; ?>">
							<input type="hidden" id="n_sucursal_<?php echo $clavesucursal; ?>" value="<?php echo $nombresucursal; ?>">

							<a href="#" class='btn btn-default' title='Ver Envios' onclick="busca_envios_clientes_vendedor('<?php echo $clavesucursal; ?>')"><i class="glyphicon glyphicon-search"></i></a>
					</td>
						
					</tr>
					<?php
	                }

	                ?>
	               
	            </table>
	        </div> <?php
        }
        else { /*$this->errors[] = "No se encontraron sucursales"; */ }           
        sqlsrv_close($conn); ## cerramos la conexion
    }
    catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }             
}

if($action == 'redireccionanuevopedido')
{
    $sTable = "CLIE".$_SESSION['empre_numero']."";
	$sWhere = "";
	$matriz= $_GET['clave_matriz'];
	$sucusal= $_GET['nombre_sucursal'];
	$sWhere.="where MATRIZ='".$matriz."' AND STATUS<>'B'; ";

	if ( $_GET['nombre_sucursal'] != "" and  $_GET['clave_matriz'] != "" ) { $sWhere.= " and NOMBRE like upper('%".$sucusal."%')"; }
	
	$sWhere.=" order by CLAVE desc";
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

	try
    {		
    	$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $query = "SELECT CLAVE,NOMBRE,convert(varchar(10), FCH_ULTCOM, 103) as FECHA_ULTCOMP,convert(varchar(10), ULT_PAGOF, 103) as FECHA_ULTPAGO,cast(VENTAS as decimal (10,2)) as VENTAS FROM ".$sTable." ".$sWhere." ";//LIMIT ".$offset.",".$per_page."";
        $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );

        if ($row_count == true)
        { ?>
			<div class="table-responsive">
				<table class="table">
				 	<tr  class="info">
				 		<th>Clave</th>
				 		<th class='text-center'>Nombre</th>
				 		<th class='text-center'>Ultima Compra</th>
				 		<th class='text-center'>Ultimo Pago</th>
				 		<th class='text-center'>Accion</th>
				 	</tr> <?php 

                    while($row = sqlsrv_fetch_array($stmt))
                    {
                       $clavesucursal=$row['CLAVE'];
						$nombresucursal=$row["NOMBRE"];
						$fultv=$row['FECHA_ULTCOMP'];
						$fultp=$row['FECHA_ULTPAGO'];
						$vent=$row['VENTAS']; ?>
						<tr>
							<td><?php echo $clavesucursal; ?></td>
							<td><?php echo $nombresucursal; ?></td>
							<td class='text-center'><?php echo $fultv; ?></td>
							<td class='text-center'><?php echo $fultp; ?></td>
							<!--<td class='text-right'><?php echo number_format ($vent,2); ?></td>-->	
							<td class="text-center">
								<input type="hidden" id="id_matriz_<?php echo $clavesucursal; ?>" value="<?php echo $_SESSION['user_email']; ?>">
								<input type="hidden" id="id_sucursal_<?php echo $clavesucursal; ?>" value="<?php echo base64_encode($clavesucursal); ?>">
								<input type="hidden" id="n_sucursal_<?php echo $clavesucursal; ?>" value="<?php echo base64_encode($nombresucursal); ?>">
								<a href="#" class='btn btn-default' title='Agregar Pedido' onclick="nuevo_pedido('<?php echo $clavesucursal; ?>')"><i class="glyphicon glyphicon-inbox"></i></a>
						</td>
							
						</tr> <?php
                    } ?>	                   
                </table>
            </div> <?php
        }
        else { /*$this->errors[] = "No se encontraron sucursales"; */}
       
        sqlsrv_close($conn);  ## cerramos la conexion    
    }
    catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; } ?>
	<script type="text/javascript" src="./js/pedidos.js"></script> <?php	
}

if($action == 'clientes_vendedor')
{    
	$vendedor= $_GET['vendedor'];
	$nombre= $_GET['nombre'];
	$limite=0;
	$saldo=0;
	$disponible=0;

	

	//if ( $_GET['nombre'] != "" and  $_GET['vendedor'] != "" ) { $sWhere.= " "; }
	
	
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	
	try
    {     	
        $query = "SELECT 
        	CLAVE,
        	NOMBRE,
        	cast(FCH_ULTCOM as date) as FECHA_ULTCOMP,
        	cast(ULT_PAGOF as date) as FECHA_ULTPAGO,
        	COALESCE((select CAMPLIB4 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE),'') as CONTRIBUYENTE,
        	MATRIZ,
        	cast(SALDO as decimal(10,2)) as SALDO, 
        	cast (LIMCRED as decimal(10,2)) as LIMCRED,
        	cast((LIMCRED - SALDO) as decimal(10,2)) as DISPONIBLE 
        	FROM CLIE".$_SESSION['empre_numero']." 
        	where trim(CVE_VEND)=trim('".$vendedor."') AND STATUS='A' and upper(TRIM(CLAVE)||NOMBRE) like upper('%".$nombre."%')
        	order by SALDO desc;";//LIMIT ".$offset.",".$per_page."";

		//echo $query;
		//die();	 	
       
        $result = ibase_query($conn, $query);
        $count=0;
        while ($row[$count] = ibase_fetch_assoc($result))
			{
			    $count++;
			} 
	    if ($count > 0) 
        {?>
			<div class="table-responsive">
				<table class="table table-striped table-responsive" >
					 	<tr class="info">
			 			<th>Clave</th>
			 			<th class='text-center'>Nombre</th>
				 		<th class='text-center'>Ultima Compra</th>
				 		<th class='text-center'>Ultimo Pago</th>
				 		<th class='text-center'>Saldo</th>
				 		<th class='text-center'>Limite</th>
				 		<th class='text-center'>Disponible</th>
				 		<th class='text-center'>Accion</th>
				 	</tr> <?php 
				 	$result = ibase_query($conn, $query);
	                while($row = ibase_fetch_object($result))
	                {
	                   $clavesae=$row->CLAVE;
						$nombresae=$row->NOMBRE;
						$fultvsae=$row->FECHA_ULTCOMP;
						$fultpsae=$row->FECHA_ULTPAGO;
						$tipsae=$row->CONTRIBUYENTE; 
						$matrizsae=$row->MATRIZ;
						$saldo=$row->SALDO;
						$limite=$row->LIMCRED;
						$disponible=$row->DISPONIBLE; ?>
						<tr>
							<td><?php echo $clavesae; ?></td>
							<td><?php echo utf8_encode($nombresae); ?></td>
							<td class='text-center'><?php echo $fultvsae; ?></td>
							<td class='text-center'><?php echo $fultpsae; ?></td>
							<!--<td class='text-right'><?php echo number_format ($vent,2); ?></td>-->
							<td class='text-center'><?php echo number_format ($saldo,2); ?></td>
							<td class='text-center'><?php echo number_format ($limite,2); ?></td>
							<td class='text-center'><?php echo number_format ($disponible,2); ?></td>	
							<td class="text-center">
								<input type="hidden" id="clave_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($clavesae); ?>">
								<input type="hidden" id="nombre_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($nombresae); ?>">
								<input type="hidden" id="tipo_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($tipsae); ?>">
								<input type="hidden" id="matriz_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($matrizsae); ?>">
								<input type="hidden" id="disponible_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($disponible); ?>">
								<a href="#" class='btn btn-primary' title='Agregar Pedido' onclick="pedido_vendedor('<?php echo $clavesae; ?>')"><i class="glyphicon glyphicon-plus"></i></a>
							</td>							
						</tr> <?php
                	} ?>
                </table>
            </div> <?php
        }
        else { /*$this->errors[] = "No se encontraron sucursales"; */}
        ibase_close($conn); ## cerramos la conexion
    }
    catch (Exception $e)
    {
        echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
    } ?>
	<script type="text/javascript" src="./js/pedidos.js"></script>
	<?php	
}

if($action == 'clientes_vendedor_cotizaciones')
{
    $sTable = "CLIE".$_SESSION['empre_numero']."";
	$sWhere = "";
	$vendedor= $_GET['vendedor'];
	$nombre= $_GET['nombre'];
	$limite=0;
	$saldo=0;
	$disponible=0;

	$sWhere.="where CVE_VEND='".$vendedor."' AND STATUS='A' ";

	if ( $_GET['nombre'] != "" and  $_GET['vendedor'] != "" ) { $sWhere.= " and upper(NOMBRE) like upper('%".$nombre."%')"; }
	
	$sWhere.=" order by CLAVE desc";
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	
	try
    { 
    	$usuarios = array();               
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $query = "SELECT CLAVE,NOMBRE,convert(varchar(10), FCH_ULTCOM, 103) as FECHA_ULTCOMP,convert(varchar(10), ULT_PAGOF, 103) as FECHA_ULTPAGO,isnull((select CAMPLIB8 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE),'') as CONTRIBUYENTE,MATRIZ,cast(SALDO as decimal(10,2)) as SALDO, cast (LIMCRED as decimal(10,2)) as LIMCRED,cast((LIMCRED - SALDO) as decimal(10,2)) as DISPONIBLE FROM ".$sTable." ".$sWhere." ";//LIMIT ".$offset.",".$per_page."";
	
	   $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
        $result = sqlsrv_execute($stmt);
        $row_count = sqlsrv_num_rows( $stmt );    


        if ($row_count == true)
        {?>
			<div class="table-responsive">
				<table class="table table-hover table-striped table-responsive">
			 		<tr class="bg-primary table-responsive">
			 			<th>Clave</th>
			 			<th class='text-center'>Nombre</th>
				 		<th class='text-center'>Ultima Compra</th>
				 		<th class='text-center'>Ultimo Pago</th>
				 		<th class='text-center'>Saldo</th>
				 		<th class='text-center'>Limite</th>
				 		<th class='text-center'>Disponible</th>
				 		<th class='text-center'>Accion</th>
				 	</tr> <?php 
	                while($row = sqlsrv_fetch_array($stmt))
	                {
	                   $clavesae=$row['CLAVE'];
						$nombresae=$row["NOMBRE"];
						$fultvsae=$row['FECHA_ULTCOMP'];
						$fultpsae=$row['FECHA_ULTPAGO'];
						$tipsae=$row['CONTRIBUYENTE']; 
						$matrizsae=$row['MATRIZ'];
						$saldo=$row['SALDO'];
						$limite=$row['LIMCRED'];
						$disponible=$row['DISPONIBLE']; ?>
						<tr>
							<td><?php echo $clavesae; ?></td>
							<td><?php echo utf8_encode($nombresae); ?></td>
							<td class='text-center'><?php echo $fultvsae; ?></td>
							<td class='text-center'><?php echo $fultpsae; ?></td>
							<!--<td class='text-right'><?php echo number_format ($vent,2); ?></td>-->
							<td class='text-center'><?php echo $saldo; ?></td>
							<td class='text-center'><?php echo $limite; ?></td>
							<td class='text-center'><?php echo $disponible; ?></td>	
							<td class="text-center">
								<input type="hidden" id="clave_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($clavesae); ?>">
								<input type="hidden" id="nombre_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($nombresae); ?>">
								<input type="hidden" id="tipo_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($tipsae); ?>">
								<input type="hidden" id="matriz_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($matrizsae); ?>">
								<input type="hidden" id="disponible_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($disponible); ?>">
								<a href="#" class='btn btn-primary' title='Agregar Pedido' onclick="pedido_vendedor_cotizacion('<?php echo $clavesae; ?>')"><i class="glyphicon glyphicon-plus"></i></a>
							</td>							
						</tr> <?php
                	} ?>
                </table>
            </div> <?php
        }
        else { /*$this->errors[] = "No se encontraron sucursales"; */}
        sqlsrv_close($conn); ## cerramos la conexion
    }
    catch (Exception $e)
    {
        echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
    } ?>
	<script type="text/javascript" src="./js/pedidos.js"></script>
	<?php	
} ?>


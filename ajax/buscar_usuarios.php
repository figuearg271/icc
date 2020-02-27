<?php
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	//require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	
	if($action == 'nuevo')
	{
		?>                      
			<div class="table-responsive">
				<table class="table table-hover table-responsive" >
				 	<tr  class="info">
				 		<th class='text-center'>Usuario</th>
                    	<th class='text-center'>Nombre</th>
                    	<th class='text-center'>CVE SAE</th>
                    	<th class='text-center'>CVE VEND</th>
                    	<th class='text-center'>Tipo</th>
                    	<th class='text-center'>Estado</th>
                    	<th class='text-center'>Accion</th>
				 	</tr>
				
					<tr>
						<td><?php echo $id; ?></td>
						<td class='text-left'><?php echo $usuario; ?></td>
						<td class='text-left'><?php echo $nombre; ?></td>


						<td class='text-left'><?php echo $cve_sae; ?></td>
						<td class='text-right'><?php echo $cve_vend; ?></td>
						<td class='text-center'><?php echo $tipo; ?></td>
						<td class='text-center'><?php echo $estado; ?></td>

						<td class="text-center">
							<input type="hidden" name="id" value="<?php echo $id; ?>">
							<input type="hidden" name="nsucursal" value="<?php echo $nsuc; ?>">
							
							<!--<input type="submit" name="" value="Ver Pedido">-->

							<a href="#" class='btn btn-default' title='Ver Detalle'" onclick='modifica(<?php echo $id; ?>);'><img src="img/mod_user.png"/></a>
						</td>
						
					</tr>
					
                   
                </table>
           
            </div>
            <?php




	 }
	
	if($action == 'ajax')
	{
		require_once ("../config/conexionweb.php");
		// escapando, eliminando además todo lo que podría ser (html / javascript-) código  
		try 
		{
			$usuarios = array();               
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            
            $query = "select * from usuarios;";

            $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
            $result = sqlsrv_execute($stmt);
            $row_count = sqlsrv_num_rows( $stmt );

            if ($row_count == true)
            {
            ?>
                      
			<div class="table-responsive">
				<table class="table table-hover table-responsive" >
				 	<tr  class="info">
				 		<th class='text-left'>ID</th>
				 		<th class='text-center'>Usuario</th>
                    	<th class='text-center'>Nombre</th>
                    	<th class='text-center'>CVE SAE</th>
                    	<th class='text-center'>CVE VEND</th>
                    	<th class='text-center'>Tipo</th>
                    	<th class='text-center'>Estado</th>
                    	<th class='text-center'>Accion</th>
				 	</tr>
				<?php 

                    while($row = sqlsrv_fetch_array($stmt))
                    {
                    	$id=$row["id"];
                    	$usuario=$row["usuario"];
                    	$nombre=$row["nombre"];
                    	$cve_sae=$row["clave_sae"];
                    	$cve_vend=$row["cve_vend"];
                    	$tipo=$row["tipo"];
                    	$estado=$row["status"];

                    	if ($estado='A') {$estado='Activo';}
                    	else {$estado='Inactivo';}

					?>
					<tr>
						<td><?php echo $id; ?></td>
						<td class='text-left'><?php echo $usuario; ?></td>
						<td class='text-left'><?php echo $nombre; ?></td>


						<td class='text-left'><?php echo $cve_sae; ?></td>
						<td class='text-right'><?php echo $cve_vend; ?></td>
						<td class='text-center'><?php echo $tipo; ?></td>
						<td class='text-center'><?php echo $estado; ?></td>

						<td class="text-center">
							<input type="hidden" name="id" value="<?php echo $id; ?>">
							<input type="hidden" name="nsucursal" value="<?php echo $nsuc; ?>">
							
							<!--<input type="submit" name="" value="Ver Pedido">-->

							<a href="#" class='btn btn-default' title='Ver Detalle'" onclick='modifica(<?php echo $id; ?>);'><img src="img/mod_user.png"/></a>
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
<script type="text/javascript" src="js/pedidos.js"></script>

<?php
	//include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	//require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexionweb.php");//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	
	
	if($action == 'buscando_empresas')
	{				 		 
		try
            {
            	$query = "SELECT NOMBRE from EMPRESAS; ";

                $result = ibase_query($conn, $query);
                ?>
                <div class="form-group">
					<label for="Empresa">Seleccione una empresa:</label>
					<select class="form-control" name="nombre_emp"><?php

	                    while($row = ibase_fetch_object($result))
	                    {
	                    	echo "<option>".$row->NOMBRE."</option>";

						}
					?>
					</select>
				</div><?php
							
			
                
                ## cerramos la conexion    
                ibase_close($conn);           
            }
            catch (Exception $e)
            {
                echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
            }             


		//loop through fetched data
		
	}
?>

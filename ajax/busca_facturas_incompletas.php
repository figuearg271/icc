<?php
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	//require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexionweb.php");//Contiene funcion que conecta a la base de datos
	
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
                $matriz=$_GET['clave_matriz'];
				$query = "select ltrim(CVE_CLIE) as CVE_CLIE,ltrim(N_CLIE) as N_CLIE,
				SUM(CANTIDAD) AS ARTICULOS,CAST(SUM(PRECIO*CANTIDAD) AS DECIMAL(10,2)) AS MONTO from 
				TMP_FACTURAS where ltrim(CVE_MAT)='".ltrim($matriz)."' and ESTADO=0 group by ltrim(CVE_CLIE),ltrim(N_CLIE)";             
				
                $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
                $result = sqlsrv_execute($stmt);
                $row_count = sqlsrv_num_rows( $stmt );

                if ($row_count == true)
                {
                ?>
                <hr style="color: blue" size="3">

                <label style="text-align:center;">Pedidos no guardados</label>
                <br/>
				
					<form action="pedido.php" method="POST">
					<div class="table-responsive"> 
				<table class="table table-hover table-responsive">
							 	<tr  class="info">
							 		<th class='text-center'>Sucursal</th>
		                        	<th class='text-right'>Cant. Articulos</th>
		                        	<th class='text-right'>Monto</th>
		                        	<th class='text-center'>Accion</th>
							 	</tr>
						<?php 

	                    while($row = sqlsrv_fetch_array($stmt))
	                    {
	                    	$suc=$row["CVE_CLIE"];
	                    	$nsuc=$row["N_CLIE"];
	                    	$art=$row["ARTICULOS"];
	                    	$mon=$row["MONTO"];
						?>
						<tr>
							<td><?php echo $nsuc; ?></td>
							<td class='text-right'><?php echo $art; ?></td>
							<td class='text-right'><?php echo $mon; ?></td>
							<td class="text-center">
								<input type="hidden" name="sucursal" value="<?php echo $suc; ?>">
								<input type="hidden" name="nsucursal" value="<?php echo $nsuc; ?>">
								
								<!--<input type="submit" name="" value="Ver Pedido">-->

								<a href="pedido.php?cus=<?php echo base64_encode($suc);?>&mon=<?php echo base64_encode($nsuc);?>" class='btn btn-default' title='Ver Detalle'" onclick='cargaprod(1);'><i class="glyphicon glyphicon-zoom-in"></i></a>
							</td>
							
						</tr>
						<?php
	                    }

	                    ?>
	                   
	                </table>
	            </form>
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

/*if($action == 'busca_pendientes_vendedor')
{
	require_once ("../config/conexionweb.php");
	try 
	{				       
        $vendedor=$_GET['vendedor'];        
        $query = "select 
        	TRIM(CVE_CLIE) as CVE_CLIE,
        	TRIM(N_CLIE) as N_CLIE,
        	SUM(CANTIDAD) AS ARTICULOS,
        	CAST(SUM(PRECIO*CANTIDAD) AS DECIMAL(10,2)) AS MONTO,
        	CONTRIBUYENTE 
        	from TMP_FACTURAS 
        	where 
        	TRIM(CVE_VEND)='".ltrim($vendedor)."' and 
        	ESTADO=0 AND N_EMPRESA='".$_SESSION['empre_numero']."'
        	group by TRIM(CVE_CLIE),TRIM(N_CLIE),CONTRIBUYENTE";  
		//echo $query;
		//die();
        $result = ibase_query($conn, $query);       

        $count = 0;
		while ($row[$count] = ibase_fetch_assoc($result)) 
			{ 
				$count++;
			}

		if($count > 0)
		{ ?>

        	<hr style="color: blue" size="3">
	        <label style="text-align:center;">Pedidos no guardados</label> <br/>
			
				<form action="pedido.php" method="POST">
					<div class="table-responsive">
						<table class="table table-striped table-responsive" >
						 	<tr  class="info">
						 		<th class='text-center'>Sucursal</th>
			                	<th class='text-right'>Cant. Articulos</th>
			                	<th class='text-right'>Monto</th>
			                	<th class='text-center'>Accion</th>
						 	</tr> <?php 

						 $result = ibase_query($conn, $query);						 	

		                while($row = ibase_fetch_object($result))
		                {
		                	$suc=$row->CVE_CLIE;
		             	   	$nsuc=$row->N_CLIE;
		                	$art=$row->ARTICULOS;
		                	$mon=$row->MONTO;
		                	$cont=$row->CONTRIBUYENTE; ?>
							<tr>
								<td><?php echo utf8_encode($nsuc); ?></td>
								<td class='text-right'><?php echo $art; ?></td>
								<td class='text-right'><?php echo $mon; ?></td>
								<td class="text-center">
									<input type="hidden" name="sucursal" value="<?php echo $suc; ?>">
									<input type="hidden" name="nsucursal" value="<?php echo $nsuc; ?>">
									<a href="pedido.php?action=pedido&eva=<?php echo base64_encode($suc);?>&mon=<?php echo base64_encode($nsuc);?>&tipo=<?php echo base64_encode($cont);?>&disponible=<?php echo base64_encode("0");?>" class='btn btn-default' title='Ver Detalle'" onclick='cargaprod(1);'><i class="glyphicon glyphicon-zoom-in"></i></a>
								</td>								
							</tr> <?php
		         		} ?>               
		            </table>
	        	</form>
	        </div> <?php
			    ## cerramos la conexion
			ibase_close($conn); 
		}
		else
        {        	
        	require_once ("../config/conexionsae.php");
	        $query = "SELECT 
	        	CLAVE,
	        	NOMBRE,
	        	CAST(FCH_ULTCOM AS DATE) as FECHA_ULTCOMP,
	        	CAST(ULT_PAGOF AS DATE) as FECHA_ULTPAGO,
	        	COALESCE((select CAMPLIB7 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE),'') as CONTRIBUYENTE,
	        	MATRIZ,
	        	cast(SALDO as decimal(10,2)) as SALDO, 
	        	cast (LIMCRED as decimal(10,2)) as LIMCRED,
	        	cast((LIMCRED - SALDO) as decimal(10,2)) as DISPONIBLE 
	        	FROM CLIE".$_SESSION['empre_numero']." 
	        	where 
	        	TRIM(CVE_VEND)='".$_GET['vendedor']."' AND STATUS<>'B' and COALESCE(VENTAS,0)>=0 
	        	order by SALDO desc";//LIMIT ".$offset.",".$per_page."";

	        $result2 = ibase_query($conn, $query);  

	        $count2 = 0;
			while ($row[$count2] = ibase_fetch_assoc($result2))
			{
			    $count2++;
			}	
			if ($count2 > 0)
			{ ?>
				<div class="table-responsive">
					<table class="table table-hover table-striped table-responsive">
					 	<tr  class="info">
				 			<th>Clave</th>
				 			<th class='text-center'>Nombre</th>
					 		<th class='text-center'>Ultima Compra</th>
					 		<th class='text-center'>Ultimo Pago</th>
					 		<th class='text-center'>Saldo</th>
					 		<th class='text-center'>Limite</th>
					 		<th class='text-center'>Disponible</th>
					 		<th class='text-center'>Accion</th>
					 	</tr> <?php 
					 	$result3 = ibase_query($conn, $query);  
		                while($row = ibase_fetch_object($result3))
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
								<!--<td class='text-right'><?php //echo number_format ($vent,2); ?></td>-->	
								<td class='text-center'><?php echo $saldo; ?></td>
								<td class='text-center'><?php echo $limite; ?></td>
								<td class='text-center'><?php echo $disponible; ?></td>	
								<td class="text-center">
									<input type="hidden" id="clave_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($clavesae); ?>">
									<input type="hidden" id="nombre_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($nombresae); ?>">
									<input type="hidden" id="tipo_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($tipsae); ?>">
									<input type="hidden" id="matriz_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($matrizsae); ?>">
									<input type="hidden" id="disponible_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($disponible); ?>">

									<button class='btn btn-primary' title='Agregar Pedido' onclick="pedido_vendedor('<?php echo $clavesae; ?>')">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</td>
								
							</tr> <?php
	                	} ?>
	                </table>
	            </div> <?php
	        }
	        ibase_close($conn); 	        
	   	}
    	                
    }	

   

    catch (Exception $e)
    {
    	echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
    }

     
}*/

if($action == 'busca_pendientes_vendedor')
{
	require_once ("../config/conexionsaweb.php");
	require_once ("../config/conexionsae.php");
	try 
	{				       
        $vendedor=$_GET['vendedor'];        
        $query = "select 
        	TRIM(CVE_CLIE) as CVE_CLIE,
        	TRIM(N_CLIE) as N_CLIE,
        	SUM(CANTIDAD) AS ARTICULOS,
        	CAST(SUM(PRECIO*CANTIDAD) AS DECIMAL(10,2)) AS MONTO,
        	CONTRIBUYENTE, COMENTARIO, CONDICION
        	from TMP_FACTURAS 
        	where 
        	TRIM(CVE_VEND)='".ltrim($vendedor)."' and 
        	ESTADO=0 AND N_EMPRESA='".$_SESSION['empre_numero']."'
        	group by TRIM(CVE_CLIE),TRIM(N_CLIE),CONTRIBUYENTE, COMENTARIO, CONDICION";  
  
        $result = ibase_query($conn2, $query);       

        $count = 0;
		while ($row[$count] = ibase_fetch_assoc($result)) 
			{ 
				$count++;
			}

		if($count > 0)
		{ ?>

        	<hr style="color: blue" size="3">
	        <label style="text-align:center;">Pedidos no guardados</label> <br/>
			
				<form action="pedido.php" method="POST">
				<div class="table-responsive"> 
				<table class="table table-hover table-responsive">
						 	<tr  class="info">
						 		<th class='text-center'>Sucursal</th>
			                	<th class='text-right'>Cant. Articulos</th>
			                	<th class='text-right'>Monto</th>
			                	<th class='text-right'>Disponible</th>
			                	<th class='text-center'>Accion</th>
						 	</tr> <?php 

						 $result = ibase_query($conn2, $query);						 	

		                while($row = ibase_fetch_object($result))
		                {
		                	$suc=$row->CVE_CLIE;
		                	$disponible=0;

		                	 $query2 = "select 
					        	cast((LIMCRED - SALDO) as decimal(10,2)) as DISPONIBLE 
					        	from CLIE".$_SESSION['empre_numero']." 
					        	where 
					        	trim(CLAVE)=trim('".$suc."');";  

					        	


					  
					        $result2 = ibase_query($conn, $query2);       

					        $count2 = 0;

					        
							while ($row2[$count2] = ibase_fetch_assoc($result2)) 
								{ 
									$count2++;
								}

								

							if($count2 > 0)
							{
								$result2 = ibase_query($conn, $query2); 
								 while($row2 = ibase_fetch_object($result2))
					            {
					            	$disponible=$row2->DISPONIBLE;
					            	
					            }
								
							}

		                	
		             	   	$nsuc=$row->N_CLIE;
		                	$art=$row->ARTICULOS;
		                	$mon=$row->MONTO;
							$cont=$row->CONTRIBUYENTE;
							//?NUEVOS CAMPOS
							$condi = $row->CONDICION;
							$comment = $row->COMENTARIO;



							?>
							<tr>
								<td><?php echo utf8_encode($nsuc); ?></td>
								<td class='text-right'><?php echo $art; ?></td>
								<td class='text-right'><?php echo $mon; ?></td>
								<td class='text-right'><?php echo $disponible; ?></td>
								<td class="text-center">
									<input type="hidden" name="sucursal" value="<?php echo $suc; ?>">
									<input type="hidden" name="nsucursal" value="<?php echo $nsuc; ?>">
									<a href="pedido.php?action=pedido&eva=<?php echo base64_encode($suc);?>&mon=<?php echo base64_encode($nsuc);?>&tipo=<?php echo base64_encode($cont);?>&disponible=<?php echo base64_encode($disponible);?>&condi=<?php echo base64_encode($condi);?>&comment=<?php echo base64_encode($comment);?>" class='btn btn-default' title='Ver Detalle'" onclick='cargaprod(1);'><i class="glyphicon glyphicon-zoom-in"></i></a>
								</td>								
							</tr> <?php
		         		} ?>               
		            </table>
	        	</form>
	        </div> <?php
			    ## cerramos la conexion
			ibase_close($conn); 
			ibase_close($conn2); 
		}
		else
        {        	
        	require_once ("../config/conexionsae.php");
	        $query = "SELECT 
	        	CLAVE,
	        	NOMBRE,
	        	CAST(FCH_ULTCOM AS DATE) as FECHA_ULTCOMP,
	        	CAST(ULT_PAGOF AS DATE) as FECHA_ULTPAGO,
	        	COALESCE((select CAMPLIB7 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE),'') as CONTRIBUYENTE,
	        	MATRIZ,
	        	cast(SALDO as decimal(10,2)) as SALDO, 
	        	cast (LIMCRED as decimal(10,2)) as LIMCRED,
	        	cast((LIMCRED - SALDO) as decimal(10,2)) as DISPONIBLE 
	        	FROM CLIE".$_SESSION['empre_numero']." 
	        	where 
	        	TRIM(CVE_VEND)='".$_GET['vendedor']."' AND STATUS<>'B' and COALESCE(VENTAS,0)>=0 
	        	order by SALDO desc";//LIMIT ".$offset.",".$per_page."";

	        $result2 = ibase_query($conn, $query);  

	        $count2 = 0;
			while ($row[$count2] = ibase_fetch_assoc($result2))
			{
			    $count2++;
			}	
			if ($count2 > 0)
			{ ?>
			<div class="table-responsive"> 
				<table class="table table-hover table-responsive">
					 	<tr  class="info">
				 			<th>Clave</th>
				 			<th class='text-center'>Nombre</th>
					 		<th class='text-center'>Ultima Compra</th>
					 		<th class='text-center'>Ultimo Pago</th>
					 		<th class='text-center'>Saldo</th>
					 		<th class='text-center'>Limite</th>
					 		<th class='text-center'>Disponible</th>
					 		<th class='text-center'>Accion</th>
					 	</tr> <?php 
					 	$result3 = ibase_query($conn, $query);  
		                while($row = ibase_fetch_object($result3))
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
								<!--<td class='text-right'><?php //echo number_format ($vent,2); ?></td>-->	
								<td class='text-center'><?php echo $saldo; ?></td>
								<td class='text-center'><?php echo $limite; ?></td>
								<td class='text-center'><?php echo $disponible; ?></td>	
								<td class="text-center">
									<input type="hidden" id="clave_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($clavesae); ?>">
									<input type="hidden" id="nombre_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($nombresae); ?>">
									<input type="hidden" id="tipo_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($tipsae); ?>">
									<input type="hidden" id="matriz_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($matrizsae); ?>">
									<input type="hidden" id="disponible_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($disponible); ?>">

									<button class='btn btn-primary' title='Agregar Pedido' onclick="pedido_vendedor('<?php echo $clavesae; ?>')">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</td>
								
							</tr> <?php
	                	} ?>
	                </table>
	            </div> <?php
	        }
	        ibase_close($conn); 	        
	   	}
    	                
    }	

   

    catch (Exception $e)
    {
    	echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
    }

     
}



if($action == 'busca_pendientes_vendedor_cotizacion')
{
	try 
	{        
        	require_once ("../config/conexionsae.php");
        	
			$vendedor= $_GET['vendedor'];
	        //LIMIT ".$offset.",".$per_page."";

	        $query = "SELECT 
	        	CLAVE,
	        	NOMBRE,
	        	CAST(FCH_ULTCOM AS DATE) as FECHA_ULTCOMP,
	        	CAST(ULT_PAGOF AS DATE) as FECHA_ULTPAGO,
	        	COALESCE((select CAMPLIB7 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE),'') as CONTRIBUYENTE,
	        	MATRIZ,cast(SALDO as decimal(10,2)) as SALDO, 
	        	cast (LIMCRED as decimal(10,2)) as LIMCRED,
	        	cast((LIMCRED - SALDO) as decimal(10,2)) as DISPONIBLE 
	        	FROM CLIE".$_SESSION['empre_numero']."
	        	where TRIM(CVE_VEND)='".$vendedor."' AND STATUS<>'B' and COALESCE(VENTAS,0)>=0
	        	order by CLAVE desc";

	        	
	       
	        $result = ibase_query($conn, $query);
	       	$count = 0;
			while ($row[$count] = ibase_fetch_assoc($result))
			{
			    $count++;
			} 
	        if ($count > 0)  

	        { ?>
			<div class="table-responsive"> 
				<table class="table table-hover table-responsive">
					 	<tr  class="info">
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
		                while($row=ibase_fetch_object($result))
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
								<td class='text-center'><?php echo $saldo; ?></td>
								<td class='text-center'><?php echo $limite; ?></td>
								<td class='text-center'><?php echo $disponible; ?></td>	
								<td class="text-center">
									<input type="hidden" id="clave_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($clavesae); ?>">
									<input type="hidden" id="nombre_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($nombresae); ?>">
									<input type="hidden" id="tipo_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($tipsae); ?>">
									<input type="hidden" id="matriz_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($matrizsae); ?>">
									<input type="hidden" id="disponible_sae<?php echo $clavesae; ?>" value="<?php echo base64_encode($disponible); ?>">

									<button class='btn btn-primary' title='Agregar cotización' onclick="cot_vend('<?php echo $clavesae; ?>')"><i class="glyphicon glyphicon-plus"></i></button>
								</td>
								
							</tr> <?php
	                	}
	                	ibase_close($conn) ?>
	                </table>
	            </div> <?php
	        }
	        else
	        {
	            //$this->errors[] = "No se encontraron sucursales";
	        }
	                      
    }
    catch (Exception $e)
    {
    	echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
    }
}

?> <script type="text/javascript" src="js/pedidos.js"></script>

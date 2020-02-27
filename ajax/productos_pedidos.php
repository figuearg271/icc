<?php
include('is_logged.php');
require_once ("../config/conexionsae.php");

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

if($action == 'ajax') 
{	
	try{
		echo "<br/><br/>";
		$query = "select I.CVE_ART,I.DESCR,CAST((SELECT PRECIO FROM PRECIO_X_PROD01 WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO IN (SELECT LISTA_PREC FROM CLIE01 WHERE MATRIZ=trim('".$_GET['w']."') GROUP BY LISTA_PREC) ) AS DECIMAL(10,2)) AS PRECIO FROM INVE01 AS I WHERE I.DESCR LIKE upper('%".$_GET['q']."%') and I.STATUS='A' ;";
		$stmt = sqlsrv_prepare($conn, $query, array(&$myID));
		$result = sqlsrv_execute($stmt); ?>
		<div class="table-responsive">            	
			<table class="table table-hover table-striped table-responsive">
				<tr  class="warning">
					<th>Código</th>
					<th align="text-center">Producto</th>
					<th align="text-center"><span class="pull-right">Cant.</span></th>
					<th align="text-center"><span class="pull-right">Precio</span></th>
					<th class='text-center' style="width: 36px;">Agregar</th>
				</tr> <?php
				$id_producto='';
				$codigo_producto='';
				$nombre_producto='';
				$precio_venta='';
				$precio_venta='';
				while($row = sqlsrv_fetch_array($stmt))
				{
					if($row['PRECIO']>0) {
						$id_producto=$row['CVE_ART'];
						$codigo_producto=$row['CVE_ART'];
						$nombre_producto=$row['DESCR'];
						$precio_venta=$row['PRECIO'];
						$precio_venta=number_format($precio_venta,2,'.','');
						?>
						<tr>
							<td><?php echo $codigo_producto; ?></td>
							<td><?php echo utf8_encode($nombre_producto); ?></td>
							<td class='col-xs-1'>
								<div class="pull-right">
									<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $codigo_producto; ?>"  value="1" >
								</div>
							</td>
							<td class='col-xs-2'>
								<div class="pull-right">
									<input type="text" class="form-control" style="text-align:right" id="precio_venta_<?php echo $codigo_producto; ?>"  value="<?php echo $precio_venta;?>" readonly="readonly" >
									<input type="hidden" class="form-control" id="descripcion_<?php echo $codigo_producto; ?>" value="<?php echo utf8_encode($row['DESCR']); ?>" >
									<input type="hidden" class="form-control" id="clie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['c'];?>" >
									<input type="hidden" class="form-control" id="producto_<?php echo $codigo_producto; ?>"  value="<?php echo $codigo_producto; ?>" >
									<input type="hidden" class="form-control" id="mat_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['w']; ?>" >
									<input type="hidden" class="form-control" id="nclie_<?php echo $codigo_producto; ?>"  value="<?php echo utf8_encode($_GET['n']); ?>" >
								</div>
							</td>							
							<td class='text-center'>
								<a href="javascript:;" class='btn btn-info' onclick="addproductos('<?php echo $codigo_producto; ?>')">
								<i class="glyphicon glyphicon-plus"></i></a> 	
                            </td>
						</tr> <?php
					}
				} ?>
				<tr>
					<td colspan=5>
						<span class="pull-right"><?php 
						//echo paginate($reload, $page, $total_pages, $adjacents);?>
						</span>
					</td>
				</tr>
			</table>             
		</div> <?php
		sqlsrv_close($conn);		
	}
	catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }	
}

//Busca cuando escribe el nombre del pructo y hace click en buscar
/*if($action == 'bproductos_vendedor') 
{			
	try{
		echo "<br/>";
	 $query="select 
		I.CVE_ART,
		I.DESCR,
		CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE UPPER(trim(CLAVE))=upper(trim('".$_GET['c']."')))) AS DECIMAL(10,2)) AS PRECIO,
		
		COALESCE((select VAL from POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select max(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),0) AS DESCUENTO,
		
		COALESCE((select PRC_MON FROM POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select MAX(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),'') AS PRC_MONTO,

		(SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_PRECIO=2 AND CVE_ART=I.CVE_ART) AS PRECIOMINIMO 
		FROM INVE".$_SESSION['empre_numero']." AS I 
		WHERE UPPER(I.DESCR) LIKE upper('%".$_GET['q']."%') and I.STATUS='A' and I.TIPO_ELE<>'S'"; 
	//die();
		$p_sql=ibase_prepare($query);
		$result = ibase_execute($p_sql); ?>
		<div class="table-responsive col-md-12">
					<table class="table table-striped table-responsive" >
					 	<tr class="warning">
					<th class="text-center col-md-2">Código</th>
					<th class="text-center col-md-4">Producto</th>
					<th class="text-center col-md-2"><span >Cant.</span></th>
					<th class="text-center col-md-1"><span >Precio</span></th>
					<th class="text-center col-md-1"><span >% desc</span></th>
					<th class='text-center col-md-1' style="width: 30px;">Agregar</th>
				</tr> <?php

				while($row = ibase_fetch_object($result))
				{
					$preciocondescuento=0;
					$desc_part=0;
					if($row->PRECIO>0)
					{
						$id_producto=$row->CVE_ART;
						$codigo_producto=$row->CVE_ART;
						$nombre_producto=$row->DESCR;
						$precio_venta=$row->PRECIO;
						$descuento=$row->DESCUENTO;
						$tipodescuento=$row->PRC_MONTO;
						$precio_venta=number_format($precio_venta,2,'.','');
						$preciominimo=$row->PRECIOMINIMO;

						if ($tipodescuento=='M' && $descuento>0) 
						{ 
							$preciocondescuento=$precio_venta-$descuento;
							if ($preciocondescuento<$preciominimo) { $desc_part=0; }
							else { $desc_part=(($descuento/$precio_venta)*100); }							
						}
						elseif($descuento>0)
						{
							$preciocondescuento=($precio_venta-(($descuento/100)*$precio_venta));							
							if ($preciocondescuento<$preciominimo) { $desc_part=0; }
							else { $desc_part=$descuento; }
						} ?>
						<tr>
							<td><?php echo $codigo_producto; ?></td>
							<td class="text"><?php echo utf8_encode($nombre_producto); ?></td>
							<td class='text-center'>
								<div class="pull-right">
									<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $codigo_producto; ?>"  value="1" >
								</div>
							</td>
							<td class='text-center'>
								

									<label><?php echo $precio_venta;?></label>
									<input type="hidden" class="form-control" style="text-align:right" id="precio_venta_<?php echo $codigo_producto; ?>"  value="<?php echo $precio_venta;?>" readonly="readonly" >

									<input type="hidden" class="form-control" id="descripcion_<?php echo $codigo_producto; ?>" value="<?php echo str_replace("&","{",utf8_encode($row->DESCR)); ?>" >
									<input type="hidden" class="form-control" id="clie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['c'];?>" >
									<input type="hidden" class="form-control" id="producto_<?php echo $codigo_producto; ?>"  value="<?php echo $codigo_producto; ?>" >
									<input type="hidden" class="form-control" id="mat_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['w']; ?>" >
									<input type="hidden" class="form-control" id="nclie_<?php echo $codigo_producto; ?>"  value="<?php echo utf8_encode($_GET['n']); ?>" >
									<input type="hidden" class="form-control" id="vend_<?php echo $codigo_producto; ?>"  value="<?php echo $_SESSION['user_cvevend']; ?>" >
								
							</td>
							<td class='text-center'>
								<label><?php echo $desc_part;?></label>
								<input type="hidden" class="form-control" style="text-align:right" id="desc_<?php echo $codigo_producto; ?>"  value="<?php echo $desc_part;?>" readonly="readonly" >								
							</td>							
							<td class='text-center'> <?php
								if ($_SESSION['user_tipo']=='M'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductosss('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='V'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductos_vendedor('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='S'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductos('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}?>									
                            </td>
						</tr> <?php
					}
				} ?>
						<tr>
							<td colspan=5>
								<span class="pull-right">
								</span>
							</td>
						</tr>
			</table>
		</div> <?php
		ibase_close($conn);		
	}
	catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; 	}	
}*/

if($action == 'bproductos_vendedor') 
{			
	try{
		echo "<br/>";
	/*	$query="select 
		I.CVE_ART,
		I.DESCR,
		CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE UPPER(trim(CLAVE))=upper(trim('".$_GET['c']."')))) AS DECIMAL(10,2)) AS PRECIO,
		
		COALESCE((select VAL from POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select max(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),0) AS DESCUENTO,
		
		COALESCE((select PRC_MON FROM POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select MAX(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),'') AS PRC_MONTO,

		(SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_PRECIO=2 AND CVE_ART=I.CVE_ART) AS PRECIOMINIMO 
		FROM INVE".$_SESSION['empre_numero']." AS I 
		WHERE UPPER(I.DESCR) LIKE upper('%".$_GET['q']."%') and I.STATUS='A' and I.TIPO_ELE<>'S'"; 
*/

// CONDICION CON M
		$query = "select 
		I.CVE_ART,
		I.DESCR,ROUND(I.EXIST,2)AS EXISTENCIA,
		CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE UPPER(trim(CLAVE))=upper(trim('".$_GET['c']."')))) AS DECIMAL(10,2)) AS PRECIO,
		
		COALESCE((select VAL from POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select max(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),0) AS DESCUENTO,
		
		COALESCE((select PRC_MON FROM POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select MAX(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),'') AS PRC_MONTO,

		(SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_PRECIO=2 AND CVE_ART=I.CVE_ART) AS PRECIOMINIMO, 
		  CAMPLIB7 as t FROM (SELECT INVE".$_SESSION['empre_numero'].".*,CAMPLIB7 FROM INVE".$_SESSION['empre_numero'].",INVE_CLIB".$_SESSION['empre_numero']." WHERE CVE_PROD = CVE_ART AND CAMPLIB7='M') AS I
		WHERE UPPER(TRIM(I.CVE_ART)||I.DESCR) LIKE upper('%".$_GET['q']."%') and I.STATUS='A' and I.TIPO_ELE<>'S'";
		
	/*	$query = "select 
		I.CVE_ART,
		I.DESCR,
		CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE UPPER(trim(CLAVE))=upper(trim('".$_GET['c']."')))) AS DECIMAL(10,2)) AS PRECIO,
		
		COALESCE((select VAL from POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select max(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),0) AS DESCUENTO,
		
		COALESCE((select PRC_MON FROM POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select MAX(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),'') AS PRC_MONTO,

		(SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_PRECIO=2 AND CVE_ART=I.CVE_ART) AS PRECIOMINIMO 
		FROM INVE".$_SESSION['empre_numero']." AS I 
		WHERE UPPER(I.DESCR) LIKE upper('%".$_GET['q']."%') and I.STATUS='A' and I.TIPO_ELE<>'S'";*/
		//echo $query;
//die();
		$p_sql=ibase_prepare($query);
		$result = ibase_execute($p_sql); ?>
		<div style="overflow-x:auto;"> 
				<table class=" table-hover table-responsive">
					 	<tr >
					<th class="text-center col-md-2">Código</th>
					<th class="text-center col-md-4">Producto</th>
					<th class="text-center col-md-2"><span >Cant.</span></th>
					<th class="text-center col-md-1"><span >Precio</span></th>
					<th class="text-center col-md-1"><span >Existencia</span></th>
					<!--<th class="text-center col-md-1"><span >% desc</span></th>-->
					<th class='text-center col-md-1' style="width: 30px;">Acciones</th> 
				</tr> <?php

				while($row = ibase_fetch_object($result))
				{
					$preciocondescuento=0;
					$desc_part=0;
					$existen = 0;
					if($row->PRECIO>0)
					{
						$id_producto=$row->CVE_ART;
						$codigo_producto=$row->CVE_ART;
						$nombre_producto=$row->DESCR;
						$precio_venta=$row->PRECIO;
						$descuento=$row->DESCUENTO;
						$tipodescuento=$row->PRC_MONTO;
						$precio_venta=number_format($precio_venta,2,'.','');
						$preciominimo=$row->PRECIOMINIMO;
						
						$existen=$row->EXISTENCIA;

						if ($tipodescuento=='M' && $descuento>0) 
						{ 
							$preciocondescuento=$precio_venta-$descuento;
							if ($preciocondescuento<$preciominimo) { $desc_part=0; }
							else { $desc_part=(($descuento/$precio_venta)*100); }							
						}
						elseif($descuento>0)
						{
							$preciocondescuento=($precio_venta-(($descuento/100)*$precio_venta));							
							if ($preciocondescuento<$preciominimo) { $desc_part=0; }
							else { $desc_part=$descuento; }
						} ?>
						<tr>
							<td><?php echo $codigo_producto; ?></td>
							<td class="text"><?php echo utf8_encode($nombre_producto); ?></td>
							<td class='text-center'>
								 
									<div >
										<input type="text" class="form-control"  style="text-align:right" id="cantidad_<?php echo $codigo_producto; ?>"  value="1" onkeyup="setPrecios(<?php echo $codigo_producto; ?>)"  >
									</div>
								  
							</td>
							<td class='text-center'>
								

									<label id="pvent">$<?php echo $precio_venta;?></label>
									<input type="hidden" class="form-control" style="text-align:right" id="precio_venta_<?php echo $codigo_producto; ?>"  value="<?php echo $precio_venta;?>" readonly="readonly" >

									<input type="hidden" class="form-control" id="descripcion_<?php echo $codigo_producto; ?>" value="<?php echo str_replace("&","{",utf8_encode($row->DESCR)); ?>" >
									<input type="hidden" class="form-control" id="clie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['c'];?>" >
									<input type="hidden" class="form-control" id="producto_<?php echo $codigo_producto; ?>"  value="<?php echo $codigo_producto; ?>" >
									<input type="hidden" class="form-control" id="mat_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['w']; ?>" >
									<input type="hidden" class="form-control" id="nclie_<?php echo $codigo_producto; ?>"  value="<?php echo utf8_encode($_GET['n']); ?>" >
									<input type="hidden" class="form-control" id="vend_<?php echo $codigo_producto; ?>"  value="<?php echo $_SESSION['user_cvevend']; ?>" >
								
							</td>
							 
							 <td class='text-center'>
								<label><?php echo $existen;?></label>
								<!--<input type="hidden" class="form-control" style="text-align:right" id="desc_<?php echo $codigo_producto; ?>"  value="<?php echo $desc_part;?>" readonly="readonly" >-->
							</td>	 
												
							<td class='text-center'> 
							<?php
								if ($_SESSION['user_tipo']=='M'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductosss('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='V'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductos_vendedor('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='S'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductos('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
							if($_SESSION['bonif']==1){ 
							?>
								<a href="#" class='btn btn-success' onclick="addbonificacion('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-gift"></i></a>
							<?php }?> 
                            </td> 
						</tr> <?php
					}
				} ?>
						<tr>
							<td colspan=6>
								<span class="pull-right">
								</span>
							</td>
						</tr>
			</table>
		</div> <?php
		ibase_close($conn);		
	}
	catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; 	}	
}

//busca los productos cuando el cliente a utilizar es varios
if($action == 'bproductos_vendedor_varios') 
{			
	try{
		echo "<br/>";
		 $query="select 
		I.CVE_ART,
		I.DESCR,
		CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE UPPER(CLAVE)=upper(trim('".$_GET['c']."')))) AS DECIMAL(10,2)) AS PRECIO,
		
		NULLIF((select VAL from POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select max(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(CLIE_D) AND UPPER(CLIE_H) AND ST='A' AND NULLIF(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=upper(trim('".$_GET['c']."'))))),0) AS DESCUENTO,
		
		NULLIF((select PRC_MON FROM POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select MAX(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(CLIE_D) AND UPPER(CLIE_H) AND ST='A' AND NULLIF(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=upper(trim('".$_GET['c']."'))))),'') AS PRC_MONTO,

		(SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_PRECIO=2 AND CVE_ART=I.CVE_ART) AS PRECIOMINIMO FROM INVE".$_SESSION['empre_numero']." AS I WHERE UPPER(I.DESCR) LIKE upper('%".$_GET['q']."%') and I.STATUS='A'"; 

		$stmt = sqlsrv_prepare($conn, $query, array(&$myID));
		$result = sqlsrv_execute($stmt); ?>
		<div style="overflow-x:auto;"> 
				<table class=" table-hover table-responsive">
					 	<tr class="warning">
					<th class="text-center col-md-2">Código</th>
					<th class="text-center col-md-4">Producto</th>
					<th class="text-center col-md-2"><span >Cant.</span></th>
					<th class="text-center col-md-1"><span >Precio</span></th>
					<th class="text-center col-md-1"><span >% desc</span></th>
					<th class='text-center col-md-1' style="width: 30px;">Agregar</th>
				</tr> <?php

				while($row = sqlsrv_fetch_array($stmt))
				{
					$preciocondescuento=0;
					if($row['PRECIO']>0)
					{
						$id_producto=$row['CVE_ART'];
						$codigo_producto=$row['CVE_ART'];
						$nombre_producto=$row['DESCR'];
						$precio_venta=$row['PRECIO'];
						$descuento=$row['DESCUENTO'];
						$tipodescuento=$row['PRC_MONTO'];
						$precio_venta=number_format($precio_venta,2,'.','');
						$preciominimo=$row['PRECIOMINIMO'];
						if ($tipodescuento=='M') 
						{ 
							$preciocondescuento=$precio_venta-$descuento;
							if ($preciocondescuento<$preciominimo) { $desc_part=0; }
							else { $desc_part=(($descuento/$precio_venta)*100); }							
						}
						else
						{
							$preciocondescuento=($precio_venta-(($descuento/100)*$precio_venta));							
							if ($preciocondescuento<$preciominimo) { $desc_part=0; }
							else { $desc_part=$descuento; }
						} ?>
						<tr>
							<td><?php echo $codigo_producto; ?></td>
							<td class="text"><?php echo utf8_encode($nombre_producto); ?></td>
							<td class='text-center'>
								<div class="pull-right">
									<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $codigo_producto; ?>"  value="1" >
								</div>
							</td>
							<td class='text-center'>
								

									<label><?php echo $precio_venta;?></label>
									<input type="hidden" class="form-control" style="text-align:right" id="precio_venta_<?php echo $codigo_producto; ?>"  value="<?php echo $precio_venta;?>" readonly="readonly" >

									<input type="hidden" class="form-control" id="descripcion_<?php echo $codigo_producto; ?>" value="<?php echo str_replace("&","{",utf8_encode($row['DESCR'])); ?>" >
									<input type="hidden" class="form-control" id="clie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['c'];?>" >
									<input type="hidden" class="form-control" id="producto_<?php echo $codigo_producto; ?>"  value="<?php echo $codigo_producto; ?>" >
									<input type="hidden" class="form-control" id="mat_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['w']; ?>" >
									<input type="hidden" class="form-control" id="nclie_<?php echo $codigo_producto; ?>"  value="<?php echo utf8_encode($_GET['n']); ?>" >
									<input type="hidden" class="form-control" id="vend_<?php echo $codigo_producto; ?>"  value="<?php echo $_SESSION['user_cvevend']; ?>" >
								
							</td>
							
							<td class='text-center'>
								<label><?php echo $desc_part;?></label>
								<input type="hidden" class="form-control" style="text-align:right" id="desc_<?php echo $codigo_producto; ?>"  value="<?php echo $desc_part;?>" readonly="readonly" >								
							</td>							
							<td class='text-center'> <?php
								if ($_SESSION['user_tipo']=='M'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductosss('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='V'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductos_vendedor_varios('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='S'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductos('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}?>									
                            </td>
						</tr> <?php
					}
				} ?>
						<tr>
							<td colspan=5>
								<span class="pull-right">
								</span>
							</td>
						</tr>
			</table>
		</div> <?php
		sqlsrv_close($conn);		
	}
	catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; 	}	
}

if($action == 'top10')
{			
	try{
		echo "<br/><br/>";

		$query = "select top 10 sum(DF.CANT) as CANT, DF.CVE_ART, (select DESCR from INVE".$_SESSION['empre_numero']." where CVE_ART=DF.CVE_ART) AS DESCR, CAST(max(DF.PREC) AS DECIMAL (10,2)) AS PRECIO from PAR_FACTF".$_SESSION['empre_numero']." as DF where DF.CVE_DOC in (select CVE_DOC from FACTP".$_SESSION['empre_numero']." where trim(CVE_CLPV)='".trim($_GET['c'])."') AND DF.CVE_ART<>'S00NA007' group by DF.CVE_ART order by CANT desc";

		echo $query;


		$stmt = sqlsrv_prepare($conn, $query, array(&$myID));
		$result = sqlsrv_execute($stmt);

		$row_count = sqlsrv_num_rows( $stmt );	
				echo $row_count;

		if( $stmt === false ) { echo "El cliente no posee facturas registradas"; }
		else
		{ ?>
			 <div style="overflow-x:auto;"> 
				<table class=" table-hover table-responsive">
					 	<tr  class="info">
						<th>Código</th>
						<th align="text-center">Producto</th>
						<th align="text-center"><span class="pull-right">Cant.</span></th>
						<th align="text-center"><span class="pull-right">Precio</span></th>
						<th class='text-center' style="width: 36px;">Agregar</th>
					</tr> <?php
					$id_producto='';
					$codigo_producto='';
					$nombre_producto='';
					$precio_venta='';
					$precio_venta='';
					while($row = sqlsrv_fetch_array($stmt))
					{
						if($row['PRECIO']>0)
						{
							$id_producto=$row['CVE_ART'];
							$codigo_producto=$row['CVE_ART'];
							$nombre_producto=$row['DESCR'];
							$precio_venta=$row['PRECIO'];
							$precio_venta=number_format($precio_venta,2,'.',''); ?>
							<tr>
								<td><?php echo $codigo_producto; ?></td>
								<td><?php echo utf8_encode($nombre_producto); ?></td>
								<td class='col-xs-1'>
									<div class="pull-right">
										<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $codigo_producto; ?>"  value="1" >
									</div>
								</td>
								<td class='col-xs-2'>
									<div class="pull-right">
										<input type="text" class="form-control" style="text-align:right" id="precio_venta_<?php echo $codigo_producto; ?>"  value="<?php echo $precio_venta;?>" readonly="readonly" >
										<input type="hidden" class="form-control" id="descripcion_<?php echo $codigo_producto; ?>" value="<?php echo $row['DESCR']; ?>" >
										<input type="hidden" class="form-control" id="clie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['c'];?>" >
										<input type="hidden" class="form-control" id="producto_<?php echo $codigo_producto; ?>"  value="<?php echo $codigo_producto; ?>" >
										<input type="hidden" class="form-control" id="mat_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['w']; ?>" >
										<input type="hidden" class="form-control" id="nclie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['n']; ?>" >
									</div>
								</td>
								
								<td class='text-center'>
									<a href="javascript:;" class='btn btn-info' onclick="addproductos('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> 	
	                            </td>
							</tr> <?php
						}
					} ?>
					<tr>
						<td colspan=5>
							<span class="pull-right"><?php 
							//echo paginate($reload, $page, $total_pages, $adjacents);?>
							</span>
						</td>
					</tr>
				</table>             
			</div> <?php
		}
		sqlsrv_close($conn);		
	}
	catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }	
}

//Busca en el boton top10 cuando esta logueado de vendedores
/*if($action == 'top10v') {
	try{
		echo "<br/>";
		$query = "select 
			FIRST 20 sum(DF.CANT) as CANT, 
			DF.CVE_ART, 	
			(select DESCR from INVE".$_SESSION['empre_numero']." where CVE_ART=DF.CVE_ART) AS DESCR,
			CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=DF.CVE_ART AND CVE_PRECIO IN 
				(SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE UPPER(trim(CLAVE))=upper(trim('".$_GET['c']."')))) AS DECIMAL(10,2)) AS PRECIO,
			
			COALESCE((SELECT VAL FROM POLI".$_SESSION['empre_numero']." WHERE CVE_POLIT=
				(select MAX(CVE_POLIT) AS CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=DF.CVE_ART AND CVE_FIN=DF.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),0) AS DESCUENTO,

			COALESCE((SELECT PRC_MON FROM POLI".$_SESSION['empre_numero']." WHERE CVE_POLIT=(select MAX(CVE_POLIT) AS CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=DF.CVE_ART AND CVE_FIN=DF.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),'') AS PRC_MONTO,
		

			(SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_PRECIO=2 AND CVE_ART=DF.CVE_ART) AS PRECIOMINIMO 
			from PAR_FACTF".$_SESSION['empre_numero']." as DF 
			where DF.CVE_DOC in (select CVE_DOC from FACTF".$_SESSION['empre_numero']." where upper(trim(CVE_CLPV))=upper('".trim($_GET['c'])."'))  
			group by DF.CVE_ART order by CANT desc";
		
		$result = ibase_query($conn, $query);
		$count = 0;
			while ($row[$count] = ibase_fetch_assoc($result))
			{
			    $count++;
			} 
	        if ($count = 0)   { echo "El cliente no posee facturas registradas"; }
		else { ?>
			<div class="table-responsive col-md-12">
					<table class="table table-striped table-responsive" >
					 	<tr class="warning">
					<th class="text-center col-md-2">Código</th>
					<th class="text-center col-md-4">Producto</th>
					<th class="text-center col-md-2"><span >Cant.</span></th>
					<th class="text-center col-md-1"><span >Precio</span></th>
					<th class="text-center col-md-1"><span >% desc</span></th>
					<th class='text-center col-md-1' style="width: 30px;">Agregar</th>
				</tr> <?php
				$result = ibase_query($conn, $query);
				
				while($row = ibase_fetch_object($result))
				{				
					$preciocondescuento=0;
					$desc_part=0;
					$precio_venta=$row->PRECIO;

					if($precio_venta>0)
					{
						$id_producto=$row->CVE_ART;
						$codigo_producto=$row->CVE_ART;
						$nombre_producto=$row->DESCR;

						$descuento=$row->DESCUENTO;
						$tipodescuento=$row->PRC_MONTO;
						$precio_venta=number_format($precio_venta,2,'.','');
						$preciominimo=$row->PRECIOMINIMO;
						
						if ($tipodescuento=='M' and $descuento>0) 
						{ 
							$preciocondescuento=$precio_venta-$descuento;
							if ($preciocondescuento<$preciominimo) { $desc_part=0; }
							else { $desc_part=(($descuento/$precio_venta)*100); }							
						}
						elseif($descuento>0)
						{
							$preciocondescuento=($precio_venta-(($descuento/100)*$precio_venta));							
							if ($preciocondescuento<$preciominimo) { $desc_part=0; }
							else { $desc_part=$descuento; }
						} ?>
						<tr>
							<td><?php echo $codigo_producto; ?></td>
							<td class="text"><?php echo utf8_encode($nombre_producto); ?></td>
							<td class='text-center'>
								<div class="pull-right">
									<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $codigo_producto; ?>"  value="1" >
								</div>
							</td>
							<td class='text-center'>
								

									<label><?php echo $precio_venta;?></label>
									<input type="hidden" class="form-control" style="text-align:right" id="precio_venta_<?php echo $codigo_producto; ?>"  value="<?php echo $precio_venta;?>" readonly="readonly" >

									<input type="hidden" class="form-control" id="descripcion_<?php echo $codigo_producto; ?>" value="<?php echo str_replace("&","{",utf8_encode($row->DESCR)); ?>" >
									<input type="hidden" class="form-control" id="clie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['c'];?>" >
									<input type="hidden" class="form-control" id="producto_<?php echo $codigo_producto; ?>"  value="<?php echo $codigo_producto; ?>" >
									<input type="hidden" class="form-control" id="mat_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['w']; ?>" >
									<input type="hidden" class="form-control" id="nclie_<?php echo $codigo_producto; ?>"  value="<?php echo utf8_encode($_GET['n']); ?>" >
									<input type="hidden" class="form-control" id="vend_<?php echo $codigo_producto; ?>"  value="<?php echo $_SESSION['user_cvevend']; ?>" >
								
							</td>
							<td class='text-center'>
								<label><?php echo $desc_part;?></label>
								<input type="hidden" class="form-control" style="text-align:right" id="desc_<?php echo $codigo_producto; ?>"  value="<?php echo $desc_part;?>" readonly="readonly" >								
							</td>							
							<td class='text-center'> <?php
								if ($_SESSION['user_tipo']=='M'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductosss('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='V'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductos_vendedor('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='S'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductos('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}?>									
                            </td>
						</tr> <?php
						}
					} ?>
					<tr>
						<td colspan=5>
							<span class="pull-right"><?php  //echo paginate($reload, $page, $total_pages, $adjacents);?></span>
						</td>
					</tr>
				</table>             
			</div> <?php
		}
		ibase_close($conn);
	}
	catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }	
}*/

if($action == 'top10v') {
	try{
		echo "<br/>";
		$query = "select 
			FIRST 20 sum(DF.CANT) as CANT, 
			DF.CVE_ART, 	
			(select DESCR from INVE".$_SESSION['empre_numero']." where CVE_ART=DF.CVE_ART) AS DESCR,
			CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=DF.CVE_ART AND CVE_PRECIO IN 
				(SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE UPPER(trim(CLAVE))=upper(trim('".$_GET['c']."')))) AS DECIMAL(10,2)) AS PRECIO,
			
			COALESCE((SELECT VAL FROM POLI".$_SESSION['empre_numero']." WHERE CVE_POLIT=
				(select MAX(CVE_POLIT) AS CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=DF.CVE_ART AND CVE_FIN=DF.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),0) AS DESCUENTO,

			COALESCE((SELECT PRC_MON FROM POLI".$_SESSION['empre_numero']." WHERE CVE_POLIT=(select MAX(CVE_POLIT) AS CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=DF.CVE_ART AND CVE_FIN=DF.CVE_ART AND upper(trim('".$_GET['c']."')) BETWEEN UPPER(trim(CLIE_D)) AND UPPER(trim(CLIE_H)) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE upper(trim(CLAVE))=upper(trim('".$_GET['c']."'))))),'') AS PRC_MONTO,
		

			(SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_PRECIO=2 AND CVE_ART=DF.CVE_ART) AS PRECIOMINIMO 
			from PAR_FACTF".$_SESSION['empre_numero']." as DF 
			where DF.CVE_DOC in (select CVE_DOC from FACTF".$_SESSION['empre_numero']." where upper(trim(CVE_CLPV))=upper('".trim($_GET['c'])."'))  
			group by DF.CVE_ART order by CANT desc";


		
		$result = ibase_query($conn, $query);
		$count = 0;
			while ($row[$count] = ibase_fetch_assoc($result))
			{
			    $count++;
			} 
	        if ($count = 0)   { echo "El cliente no posee facturas registradas"; }
		else { ?>
			<div style="overflow-x:auto;"> 
				<table class=" table-hover table-responsive">
					 	<tr class="warning">
					<th class="text-center col-md-2">Código</th>
					<th class="text-center col-md-4">Producto</th>
					<th class="text-center col-md-2"><span >Cant.</span></th>
					<th class="text-center col-md-1"><span >Precio</span></th>
					<th class="text-center col-md-1"><span >% desc</span></th>
					<th class='text-center col-md-1' style="width: 30px;">Agregar</th>
				</tr> <?php
				$result = ibase_query($conn, $query);
				
				while($row = ibase_fetch_object($result))
				{				
					$preciocondescuento=0;
					$desc_part=0;
					$precio_venta=$row->PRECIO;

					if($precio_venta>0)
					{
						$id_producto=$row->CVE_ART;
						$codigo_producto=$row->CVE_ART;
						$nombre_producto=$row->DESCR;

						$descuento=$row->DESCUENTO;
						$tipodescuento=$row->PRC_MONTO;
						$precio_venta=number_format($precio_venta,2,'.','');
						$preciominimo=$row->PRECIOMINIMO;
						
						if ($tipodescuento=='M' and $descuento>0) 
						{ 
							$preciocondescuento=$precio_venta-$descuento;
							if ($preciocondescuento<$preciominimo) { $desc_part=0; }
							else { $desc_part=(($descuento/$precio_venta)*100); }							
						}
						elseif($descuento>0)
						{
							$preciocondescuento=($precio_venta-(($descuento/100)*$precio_venta));							
							if ($preciocondescuento<$preciominimo) { $desc_part=0; }
							else { $desc_part=$descuento; }
						} ?>
						<tr>
							<td><?php echo $codigo_producto; ?></td>
							<td class="text"><?php echo utf8_encode($nombre_producto); ?></td>
							<td class='text-center'>
								<div class="pull-right">
									<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $codigo_producto; ?>"  value="1" >
								</div>
							</td>
							<td class='text-center'>
								

									<label><?php echo $precio_venta;?></label>
									<input type="hidden" class="form-control" style="text-align:right" id="precio_venta_<?php echo $codigo_producto; ?>"  value="<?php echo $precio_venta;?>" readonly="readonly" >

									<input type="hidden" class="form-control" id="descripcion_<?php echo $codigo_producto; ?>" value="<?php echo str_replace("&","{",utf8_encode($row->DESCR)); ?>" >
									<input type="hidden" class="form-control" id="clie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['c'];?>" >
									<input type="hidden" class="form-control" id="producto_<?php echo $codigo_producto; ?>"  value="<?php echo $codigo_producto; ?>" >
									<input type="hidden" class="form-control" id="mat_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['w']; ?>" >
									<input type="hidden" class="form-control" id="nclie_<?php echo $codigo_producto; ?>"  value="<?php echo utf8_encode($_GET['n']); ?>" >
									<input type="hidden" class="form-control" id="vend_<?php echo $codigo_producto; ?>"  value="<?php echo $_SESSION['user_cvevend']; ?>" >
								
							</td>
							<td class='text-center'>
								<label><?php echo $desc_part;?></label>
								<input type="hidden" class="form-control" style="text-align:right" id="desc_<?php echo $codigo_producto; ?>"  value="<?php echo $desc_part;?>" readonly="readonly" >								
							</td>							
							<td class='text-center'> <?php
								if ($_SESSION['user_tipo']=='M'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductosss('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='V'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductos_vendedor('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='S'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductos('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}?>									
                            </td>
						</tr> <?php
						}
					} ?>
					<tr>
						<td colspan=5>
							<span class="pull-right"><?php  //echo paginate($reload, $page, $total_pages, $adjacents);?></span>
						</td>
					</tr>
				</table>             
			</div> <?php
		}
		ibase_close($conn);
	}
	catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }	
}

if($action == 'vendedores')
{		
	
	try{
		echo "<br/><br/>";
		$query = "select I.CVE_ART,I.DESCR,CAST((SELECT PRECIO FROM PRECIO_X_PROD01 WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO IN (SELECT LISTA_PREC FROM CLIE01 WHERE CLAVE=trim('".$_GET['c']."') GROUP BY LISTA_PREC) ) AS DECIMAL(10,2)) AS PRECIO FROM INVE01 AS I WHERE I.DESCR LIKE upper('%".$_GET['q']."%') and I.STATUS='A' and I.CVE_ART IN (SELECT CVE_ART FROM MULT01 WHERE CVE_ALM=1);";

		$stmt = sqlsrv_prepare($conn, $query, array(&$myID));
		$result = sqlsrv_execute($stmt);					
		?>
		<div style="overflow-x:auto;"> 
				<table class=" table-hover table-responsive">
					 	<tr  class="info">
					<th>Código</th>
					<th align="text-center">Producto</th>
					<th align="text-center"><span class="pull-right">Cant.</span></th>
					<th align="text-center"><span class="pull-right">Precio</span></th>
					<th class='text-center' style="width: 36px;">Agregar</th>
				</tr>
				<?php
				$id_producto='';
				$codigo_producto='';
				$nombre_producto='';
				$precio_venta='';
				$precio_venta='';
				while($row = sqlsrv_fetch_array($stmt))
				{
					if($row['PRECIO']>0)
					{
						$id_producto=$row['CVE_ART'];
						$codigo_producto=$row['CVE_ART'];
						$nombre_producto=$row['DESCR'];
						$precio_venta=$row['PRECIO'];
						$precio_venta=number_format($precio_venta,2,'.','');
						?>
						<tr>
							<td><?php echo $codigo_producto; ?></td>
							<td><?php echo utf8_encode($nombre_producto); ?></td>
							<td class='col-xs-1'>
								<div class="pull-right">
									<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $codigo_producto; ?>"  value="1" >
								</div>
							</td>
							<td class='col-xs-2'>
								<div class="pull-right">

									<input type="text" class="form-control" style="text-align:right" id="precio_venta_<?php echo $codigo_producto; ?>"  value="<?php echo $precio_venta;?>" readonly="readonly" >

									<input type="hidden" class="form-control" id="descripcion_<?php echo $codigo_producto; ?>" value="<?php echo utf8_encode($row['DESCR']); ?>" >

							<input type="hidden" class="form-control" id="clie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['c'];?>" >
							<input type="hidden" class="form-control" id="producto_<?php echo $codigo_producto; ?>"  value="<?php echo $codigo_producto; ?>" >

							<input type="hidden" class="form-control" id="nclie_<?php echo $codigo_producto; ?>"  value="<?php echo utf8_encode($_GET['n']); ?>" >

								</div>
							</td>
							
							<td class='text-center'>




								<a href="javascript:;" class='btn btn-info' onclick="addproductosvendedor('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> 	
                            </td>
						</tr>
						<?php
					}
				}
				?>
				<tr>
					<td colspan=5>
						<span class="pull-right"><?php 
						//echo paginate($reload, $page, $total_pages, $adjacents);?>
						</span>
					</td>
				</tr>
			</table>
             
			</div>
			<?php		
	}
	catch (Exception $e)
	{
		echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
	}
	## cerramos la conexion
	//sqlsrv_free_stmt($stmt);
	sqlsrv_close($conn);
}
?>
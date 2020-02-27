<style>
/* The container */
.container2 {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: -3px;
  margin-left: 30px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}


/* Hide the browser's default checkbox */
.container2 input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container2:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container2 input:checked ~ .checkmark {
  background-color: #1fce24;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container2 input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container2 .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
<?php

include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

require_once ("../config/conexionsae.php");
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

if($action == 'busca_pedidos2') //pedidos
{ 

	$cve_vend=trim($_GET['cve_vend']);

    $query="select 
			FIRST 120 P.CVE_DOC,
			(SELECT NOMBRE FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=P.CVE_CLPV) AS CLIENTE,
			CAST(P.FECHA_DOC AS DATE) AS FECHA_DOC,
			CAST(P.FECHA_ENT AS DATE) AS FECHA_ENT,
			COALESCE(P.FORMAENVIO,'') as FORMAENVIO,
			COALESCE(P.TIP_DOC_SIG,'') as TIP_DOC_SIG,
			P.IMPORTE,
			P.CVE_CLPV,
			(SELECT (LIMCRED-SALDO) FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=P.CVE_CLPV) AS DISPONIBLE, 
			COALESCE((select CAMPLIB5 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=P.CVE_CLPV),'') as T_CONT, 
			COALESCE((SELECT STR_OBS FROM OBS_DOCF".$_SESSION['empre_numero']." WHERE CVE_OBS=P.CVE_OBS),'') as OBSERVACION,
			P.STATUS 
			from FACTP".$_SESSION['empre_numero']." as P 
			where TRIM(P.CVE_VEND)='".$cve_vend."' 
			order by P.CVE_DOC desc";

    //echo $query;
    $result = ibase_query($conn, $query); ?>
	<div style="overflow-x:auto;"> 
				<table class=" table-hover table-responsive">
		 	<tr  class="info">
	 			
	 			<th class='text-center'>Documento</th>
		 		<th class='text-center'>Cliente</th>
		 		<th class='text-center'>Fecha Doc.</th>
		 		<th class='text-center'>Fecha Ent.</th>
		 		<th class='text-center'>Estado</th>
		 		<th class='text-center'>Importe</th>
		 		<th class='text-center'>Accion</th>
		 	</tr> <?php 
            while($row = ibase_fetch_object($result))
            {
               	$documento=$row->CVE_DOC;
				$cliente=$row->CLIENTE;
				$fecha_doc=$row->FECHA_DOC;
				$fecha_ent=$row->FECHA_ENT;
				$formaenvio=$row->FORMAENVIO; 
				$tip_doc_sig=$row->TIP_DOC_SIG; 
				$importe=$row->IMPORTE;
				$disponible=$row->DISPONIBLE; 
				$c_cliente=$row->CVE_CLPV; 
				$t_cliente=$row->T_CONT;
				$observacion=$row->OBSERVACION;
				$status=$row->STATUS; ?>

				<tr>
					<td><?php echo $documento; ?></td>
					<td><?php echo utf8_encode($cliente); ?></td>
					<td class='text-center'><?php echo $fecha_doc; ?></td>
					<td class='text-center'><?php echo $fecha_ent; ?></td>
					<td><?php


					if ($formaenvio== '' && $status<>"C") {?> <span class="label label-warning"> Ingresada</span> <?php }
					else if ($formaenvio== 'I' && $tip_doc_sig=='' && $status<>"C"){ ?> <span class="label label-info"> Impresa</span> <?php } 	
					else if ($formaenvio== 'I' && $tip_doc_sig=='F' && $status<>"C"){ ?> <span class="label label-success"> Facturada</span> <?php }
					else if ($status=='C') { ?> <span class="label label-danger"> Cancelada</span> <?php } ?>

					</td>

					<td class='text-right'><?php echo number_format ($importe,2); ?></td>
					
					<td class="text-center">
						<input type="hidden" id="formaenvio_<?php echo $documento; ?>" value="<?php echo base64_encode($formaenvio); ?>">
						<input type="hidden" id="tipo_doc_<?php echo $documento; ?>" value="<?php echo base64_encode($tip_doc_sig); ?>"> <?php


						if ($formaenvio== ''  && $status<>"C") {?> 
							<a href="#" class='btn btn-danger btn-xs' title='cancelar Pedido' onclick="cancelar_pedido('<?php echo $documento; ?>')"><i class="glyphicon glyphicon-remove"></i></a>
							<a href="#" class='btn btn-info btn-xs' title='Modificar Pedido' onclick='location.href ="modifica_pedido.php?otm=<?php echo base64_encode($documento); ?>
							?&mon=<?php echo base64_encode($cliente); ?>
							?&eva=<?php echo base64_encode($c_cliente); ?>
							?&tipo=<?php echo base64_encode($t_cliente); ?>
							?&disponible=<?php echo base64_encode($disponible); ?>
							?&obs=<?php echo base64_encode($observacion); ?>
							?&ahc=<?php echo base64_encode($fecha_doc); ?>
							?&ahce=<?php echo base64_encode($fecha_ent); ?>";'><i class="glyphicon glyphicon-refresh"></i></a> <?php }
						else if ($formaenvio== 'I' && $tip_doc_sig==''  && $status<>"C"){ ?> 
							<a href="#" class='btn btn-danger btn-xs' title='cancelar Pedido' onclick="cancelar_pedido('<?php echo $documento; ?>')"><i class="glyphicon glyphicon-remove"></i></a>
							<a href="#" class='btn btn-info btn-xs' title='Modificar Pedido' onclick='location.href ="modifica_pedido.php?otm=<?php echo base64_encode($documento); ?>
							?&mon=<?php echo base64_encode($cliente); ?>
							?&eva=<?php echo base64_encode($c_cliente); ?>
							?&tipo=<?php echo base64_encode($t_cliente); ?>
							?&disponible=<?php echo base64_encode($disponible); ?>
							?&obs=<?php echo base64_encode($observacion); ?>
							?&ahc=<?php echo base64_encode($fecha_doc); ?>
							?&ahce=<?php echo base64_encode($fecha_ent); ?>";'><i class="glyphicon glyphicon-refresh"></i></a> <?php } 	
						else if ($formaenvio== 'I' && $tip_doc_sig=='F'  && $status<>"C"){ ?>  <?php } ?>


						

						
					</td>
					
				</tr> <?php
        	} ?>
        </table>
    </div> <?php
    ibase_close($conn);
		
	
	
}

if($action == 'busca_pedidos') //cotizaciones
{ 
	$cve_vend=trim($_GET['cve_vend']);

	
    $query = "select 
    		 P.CVE_DOC,
    		(SELECT trim(clave)||' '|| NOMBRE as nombre FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=P.CVE_CLPV) AS CLIENTE,
    		CAST(P.FECHA_DOC as DATE) AS FECHA_DOC,
    		CAST(P.FECHA_ENT as DATE) AS FECHA_ENT,
    		COALESCE(P.FORMAENVIO,'') as FORMAENVIO,
    		COALESCE(P.TIP_DOC_SIG,'') as TIP_DOC_SIG,
    		P.IMPORTE,P.CVE_CLPV,
    		(SELECT (LIMCRED-SALDO) FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=P.CVE_CLPV) AS DISPONIBLE, 
    		COALESCE((select CAMPLIB5 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=P.CVE_CLPV),'') as T_CONT, 
    		COALESCE((SELECT STR_OBS FROM OBS_DOCF".$_SESSION['empre_numero']." WHERE CVE_OBS=P.CVE_OBS),'') as OBSERVACION,
    		P.STATUS,
    		P.TIP_DOC_E,
			P.ENLAZADO,
			P.BLOQ,
			P.CONDICION
			from FACTC".$_SESSION['empre_numero']." as P";

			if($_SESSION['user_tipo'] == "A"){	
				$query .=" where p.FECHA_DOC BETWEEN cast(dateadd(day, -7, current_timestamp)as date) and cast('today' as date) and P.STATUS <> 'C' order by P.FECHA_DOC desc";
				//$query .=" where  P.STATUS <> 'C' order by P.FECHA_DOC desc";
			}
			else{
				
			$query .=" where p.FECHA_DOC BETWEEN cast(dateadd(day, -7, current_timestamp)as date) and cast('today' as date) and TRIM(P.CVE_VEND)='".$cve_vend."' and P.STATUS <> 'C' order by P.FECHA_DOC desc";

			}  
			//echo $query;


	//die();
    $result = ibase_query($conn, $query);
     ?>
		<div class="table-responsive"> 
				<table class="table table-hover table-responsive">
			 	<tr  >		 			
		 			<th class='text-center'>Documento</th>
			 		<th class='text-center'>Cliente</th>
			 		<th class='text-center'>Fecha Doc.</th>
			 		<th class='text-center'>Fecha Ent.</th>
			 		<th class='text-center'>Estado</th> 
					 
					 <?php if($_SESSION['user_tipo'] == "A"){?> 
						<th class="text-center">Autorizado</th>
					 <?php } ?>
					<th class ="text-center">Condici&oacute;n</th>	 
			 		<th class='text-center'>Importe</th>
					
			 		<th class='text-center'>Acción</th>
			 	</tr> <?php 
                while($row = ibase_fetch_object($result))
                {
                   	$documento=$row->CVE_DOC;
					$cliente=$row->CLIENTE;
					$fecha_doc=$row->FECHA_DOC;
					$fecha_ent=$row->FECHA_ENT;
					$formaenvio=$row->FORMAENVIO; 
					$tip_doc_sig=$row->TIP_DOC_SIG; 
					$importe=$row->IMPORTE;
					$disponible=$row->DISPONIBLE; 
					$c_cliente=$row->CVE_CLPV; 
					$t_cliente=$row->T_CONT;
					$observacion=$row->OBSERVACION;
					$status=$row->STATUS;
					$doc_e=$row->TIP_DOC_E;
					$enlazado=$row->ENLAZADO; 
					$auto = $row->BLOQ;

					//AGREGACION DE CONDICION
					$cond = $row->CONDICION;
					$dcond = explode('-',$cond);
					

					?>

					<tr>
						<td><?php echo $documento; ?></td>
						<td><?php echo utf8_encode($cliente); ?></td>
						<td class='text-center'><?php echo $fecha_doc; ?></td>
						<td class='text-center'><?php echo $fecha_ent; ?></td>
						<td class="text-center"><?php

							if ($formaenvio== '' && $status<>"C" && $doc_e<>"F") {?> 
								<span class="label label-warning"> Ingresada</span> <?php 
							}
							else if ($formaenvio== 'I' && $tip_doc_sig=='' && $status<>"C"){ ?> 
								<span class="label label-info"> Impresa</span> <?php 
							} 	
							else if ($tip_doc_sig=='P' && $status<>"C"){ ?> 
								<span class="label label-primary"> Pedida</span> <?php 
							}
							else if ($tip_doc_sig=='F' && $status<>"C"){ ?> 
								<span class="label label-success"> Facturada</span> <?php 
							}
							else if ($tip_doc_sig=='F' && $status<>"C"){ ?> 
								<span class="label label-success"> Facturada</span> <?php 
							}
							else if ($status=='C') { ?> 
								<span class="label label-danger"> Cancelada</span> <?php 
							} ?>

						</td>
						<?php if($_SESSION['user_tipo'] == "A"){?>
							<td class="text-center"> 
								 
									<!--<input type="checkbox" id="autor" <?php if($auto=='N'){echo "checked disabled";}else{echo "";}?> value="<?php echo $documento; ?>"/>-->
									<label class='container2'>
										<input type='checkbox' class='docsaut_<?php echo $documento; ?>' <?php if($auto=='N'){echo "checked disabled";}else{echo "";}?> name='document' value='<?php echo $documento; ?>' onchange="autCotizacion('<?php echo $documento; ?>')">
										<span class='checkmark'></span>
									</label>
								 
							</td>
						
						<?php }?> 
						<td class='text-right'><?php echo $dcond[0]; ?></td>
						<td class='text-right'><?php echo number_format ($importe,2); ?></td>
						
						<td class="text-center">
							<input type="hidden" id="formaenvio_<?php echo $documento; ?>" value="<?php echo base64_encode($formaenvio); ?>">
							<input type="hidden" id="tipo_doc_<?php echo $documento; ?>" value="<?php echo base64_encode($tip_doc_sig); ?>"> <?php


							if ($formaenvio== ''  && $status<>"C") {?> 
								
								<a href="#" class='btn btn-secondary btn-xs' title='Ver cotización' onclick='location.href ="../vendedores/pdf/imprime_cotizacion.php?documento=<?php echo $documento; ?>"'>
									<i class="glyphicon glyphicon-search"></i>
								</a>
								<a href="#" class='btn btn-secondary btn-xs' title='Imprimir cotización' onclick='location.href ="../vendedores/pdf/pdf_exportar.php?documento=<?php echo $documento; ?>"'>
									<i class="glyphicon glyphicon-print"></i>
								</a> <?php }
							else if ($formaenvio== 'I' && $tip_doc_sig==''  && $status<>"C"){ ?> 
								
								<a href="#" class='btn btn-secondary btn-xs' title='Ver cotización'onclick='location.href ="../vendedores/pdf/imprime_cotizacion.php?documento=<?php echo $documento; ?>"'><i class="glyphicon glyphicon-search"></i></a>
								<a href="#" class='btn btn-secondary btn-xs' title='Imprimir cotización' onclick='location.href ="../vendedores/pdf/pdf_exportar.php?documento=<?php echo $documento; ?>"'><i class="glyphicon glyphicon-print"></i></a> <?php } 	
							else if ($formaenvio== 'I' && $tip_doc_sig=='F' or $tip_doc_sig=='P' && $status<>"C"){ ?>  
								<a href="#" class='btn btn-secondary btn-xs' title='Imprimir cotización' onclick='location.href ="../vendedores/pdf/pdf_exportar.php?documento=<?php echo $documento; ?>"'><i class="glyphicon glyphicon-print"></i></a>
								<?php } ?>							
						</td>
						
					</tr> <?php
            	} ?>
            </table>
        </div> <?php
	ibase_close($conn);	
	
	
}

if($action == 'busca_cotizaciones') //cotizaciones
{ 
	$cve_vend=trim($_GET['cve_vend']);

    $query = "select 
    		FIRST 60 P.CVE_DOC,
    		(SELECT NOMBRE FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=P.CVE_CLPV) AS CLIENTE,
    		CAST(P.FECHA_DOC as DATE) AS FECHA_DOC,
    		CAST(P.FECHA_ENT as DATE) AS FECHA_ENT,
    		COALESCE(P.FORMAENVIO,'') as FORMAENVIO,
    		COALESCE(P.TIP_DOC_SIG,'') as TIP_DOC_SIG,
    		P.IMPORTE,P.CVE_CLPV,
    		(SELECT (LIMCRED-SALDO) FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=P.CVE_CLPV) AS DISPONIBLE, 
    		COALESCE((select CAMPLIB5 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=P.CVE_CLPV),'') as T_CONT, 
    		COALESCE((SELECT STR_OBS FROM OBS_DOCF".$_SESSION['empre_numero']." WHERE CVE_OBS=P.CVE_OBS),'') as OBSERVACION,
    		P.STATUS,
    		P.TIP_DOC_E,
    		P.ENLAZADO
    		from FACTC".$_SESSION['empre_numero']." as P 
    		where TRIM(P.CVE_VEND)='".$cve_vend."' order by P.FECHA_DOC desc";
	//die();
    $result = ibase_query($conn, $query);
     ?>
		<div style="overflow-x:auto;"> 
				<table class=" table-hover table-responsive">
			 	<tr class="info">		 			
		 			<th class='text-center'>Documento</th>
			 		<th class='text-center'>Cliente</th>
			 		<th class='text-center'>Fecha Doc.</th>
			 		<th class='text-center'>Fecha Ent.</th>
			 		<th class='text-center'>Estado</th>
			 		<th class='text-center'>Importe</th>
			 		<th class='text-center'>Acción</th>
			 	</tr> <?php 
                while($row = ibase_fetch_object($result))
                {
                   	$documento=$row->CVE_DOC;
					$cliente=$row->CLIENTE;
					$fecha_doc=$row->FECHA_DOC;
					$fecha_ent=$row->FECHA_ENT;
					$formaenvio=$row->FORMAENVIO; 
					$tip_doc_sig=$row->TIP_DOC_SIG; 
					$importe=$row->IMPORTE;
					$disponible=$row->DISPONIBLE; 
					$c_cliente=$row->CVE_CLPV; 
					$t_cliente=$row->T_CONT;
					$observacion=$row->OBSERVACION;
					$status=$row->STATUS;
					$doc_e=$row->TIP_DOC_E;
					$enlazado=$row->ENLAZADO;  ?>

					<tr>
						<td><?php echo $documento; ?></td>
						<td><?php echo utf8_encode($cliente); ?></td>
						<td class='text-center'><?php echo $fecha_doc; ?></td>
						<td class='text-center'><?php echo $fecha_ent; ?></td>
						<td class="text-center"><?php

							if ($formaenvio== '' && $status<>"C" && $doc_e<>"F") {?> 
								<span class="label label-warning"> Ingresada</span> <?php 
							}
							else if ($formaenvio== 'I' && $tip_doc_sig=='' && $status<>"C"){ ?> 
								<span class="label label-info"> Impresa</span> <?php 
							} 	
							else if ($tip_doc_sig=='P' && $status<>"C"){ ?> 
								<span class="label label-primary"> Pedida</span> <?php 
							}
							else if ($tip_doc_sig=='F' && $status<>"C"){ ?> 
								<span class="label label-success"> Facturada</span> <?php 
							}
							else if ($tip_doc_sig=='F' && $status<>"C"){ ?> 
								<span class="label label-success"> Facturada</span> <?php 
							}
							else if ($status=='C') { ?> 
								<span class="label label-danger"> Cancelada</span> <?php 
							} ?>

						</td>
						<td class='text-right'><?php echo number_format ($importe,2); ?></td>
						
						<td class="text-center">
							<input type="hidden" id="formaenvio_<?php echo $documento; ?>" value="<?php echo base64_encode($formaenvio); ?>">
							<input type="hidden" id="tipo_doc_<?php echo $documento; ?>" value="<?php echo base64_encode($tip_doc_sig); ?>"> <?php


							if ($formaenvio== ''  && $status<>"C") {?> 
								
								<a href="#" class='btn btn-secondary btn-xs' title='Ver cotización' onclick='location.href ="../vendedores/pdf/imprime_cotizacion.php?documento=<?php echo $documento; ?>"'>
									<i class="glyphicon glyphicon-search"></i>
								</a>
								<a href="#" class='btn btn-secondary btn-xs' title='Imprimir cotización' onclick='location.href ="../vendedores/pdf/pdf_exportar.php?documento=<?php echo $documento; ?>"'>
									<i class="glyphicon glyphicon-print"></i>
								</a> <?php }
							else if ($formaenvio== 'I' && $tip_doc_sig==''  && $status<>"C"){ ?> 
								
								<a href="#" class='btn btn-secondary btn-xs' title='Ver cotización'onclick='location.href ="../vendedores/pdf/imprime_cotizacion.php?documento=<?php echo $documento; ?>"'><i class="glyphicon glyphicon-search"></i></a>
								<a href="#" class='btn btn-secondary btn-xs' title='Imprimir cotización' onclick='location.href ="../vendedores/pdf/pdf_exportar.php?documento=<?php echo $documento; ?>"'><i class="glyphicon glyphicon-print"></i></a> <?php } 	
							else if ($formaenvio== 'I' && $tip_doc_sig=='F' or $tip_doc_sig=='P' && $status<>"C"){ ?>  
								<a href="#" class='btn btn-secondary btn-xs' title='Imprimir cotización' onclick='location.href ="../vendedores/pdf/pdf_exportar.php?documento=<?php echo $documento; ?>"'><i class="glyphicon glyphicon-print"></i></a>
								<?php } ?>							
						</td>
						
					</tr> <?php
            	} ?>
            </table>
        </div> <?php
	ibase_close($conn);	
	
	
}

if($action == 'cancelar_pedido')
{ 

	$cve_doc=trim($_GET['cve_doc']);

	require_once("../config/conexionsae.php");	
		
	$sql = "update FACTP".$_SESSION['empre_numero']." set STATUS='C' where CVE_DOC='".$cve_doc."' and TIP_DOC_SIG is null and DOC_SIG is null ";
	//echo $sql;
	//die();
	$stmt = ibase_query( $conn, $sql);
	if ( $stmt ) { $something = "Submission successful.";}     
	else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
	$output=$something;
	/* Free statement and connection resources. */    
	sqlsrv_free_stmt( $stmt);  
	
}

if($action == 'busca_listas')
{ 	
    $query = "select 
    	FIRST 60 I.CVE_ART,
    	I.DESCR,
    	COALESCE(CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO=1) AS DECIMAL(10,2)),0) AS PUBLICO,
    	COALESCE(CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO=2) AS DECIMAL(10,2)),0) AS MINIMO,
    	COALESCE(CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO=3) AS DECIMAL(10,2)),0) AS LIQUIDACION 
    	from INVE".$_SESSION['empre_numero']." as I where STATUS='A' and I.EXIST>0 AND I.TIPO_ELE<>'S';";

    $result = ibase_query($conn, $query);
    	

    $count = 0;
	while ($row[$count] = ibase_fetch_assoc($result)) 
		{ 
			$count++;
		}

	if($count > 0)
    {?>
		<div style="overflow-x:auto;"> 
				<table class=" table-hover table-responsive">
			 	<tr  class="info">
		 			
		 			<th class='text-center'>Clave</th>
			 		<th class='text-center'>Descripcion</th>
			 		<th class='text-center'>Publico</th>
			 		<th class='text-center'>Minimo</th>
			 		<th class='text-center'>Liquidacion</th>
			 	</tr> <?php 
			 	$result = ibase_query($conn, $query);
                while($row = ibase_fetch_object($result))
                {
                   	$cve_art=$row->CVE_ART;
					$descr=$row->DESCR;
					$publico=$row->PUBLICO;
					$ia=$row->MINIMO;
					$ib=$row->LIQUIDACION; ?>

					<tr>
						<td class='text-left'><?php echo $cve_art; ?></td>
						<td class='text-left'><?php echo utf8_encode($descr); ?></td>
						<td class='text-right'><?php echo $publico; ?></td>
						<td class='text-right'><?php echo $ia; ?></td>
						<td class='text-right'><?php echo $ib; ?></td>
						
						
					</tr> <?php
            	} ?>
            </table>
        </div> <?php
		
	}
	else {

		
	} 
	ibase_close($conn);
	
}


if($action == 'busca_listas_producto')
{ 

	$producto=$_GET['producto'];

    $query = "select 
    	FIRST 60 I.CVE_ART,
    	I.DESCR,
    	COALESCE(CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO=1) AS DECIMAL(10,2)),0) AS PUBLICO,
    	COALESCE(CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO=2) AS DECIMAL(10,2)),0) AS MINIMO,
    	COALESCE(CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO=3) AS DECIMAL(10,2)),0) AS LIQUIDACION 
    	from INVE".$_SESSION['empre_numero']." as I 
    	where STATUS='A' and upper(I.DESCR) like upper('%".$producto."%') and I.EXIST>0 AND I.TIPO_ELE<>'S';";

    $result = ibase_query($conn, $query);
    $count = 0;
	while ($row[$count] = ibase_fetch_assoc($result)) 
		{ 
			$count++;
		}

	if($count > 0)
    {?>
		<div class="table-responsive"> 
				<table class="table table-hover table-responsive">
			 	<tr  class="info">
		 			
		 			<th class='text-center'>Clave</th>
			 		<th class='text-center'>Descripcion</th>
			 		<th class='text-center'>Publico</th>
			 		<th class='text-center'>Minimo</th>
			 		<th class='text-center'>Liquidacion</th>
			 	</tr> <?php 
			 	$result = ibase_query($conn, $query);
                while($row = ibase_fetch_object($result))
                {
                   	$cve_art=$row->CVE_ART;
					$descr=$row->DESCR;
					$publico=$row->PUBLICO;
					$ia=$row->MINIMO;
					$ib=$row->LIQUIDACION; ?>

					<tr>
						<td class='text-left'><?php echo $cve_art; ?></td>
						<td class='text-left'><?php echo utf8_encode($descr); ?></td>
						<td class='text-right'><?php echo $publico; ?></td>
						<td class='text-right'><?php echo $ia; ?></td>
						<td class='text-right'><?php echo $ib; ?></td>
						
					</tr> <?php
            	} ?>
            </table>
        </div> <?php
		
	}
	else {

		
	} 
	
}
?>

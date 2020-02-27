<?php
require_once('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

require_once("../config/conexionsae.php");	
require_once("../config/conexionsaweb.php");

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

if($action == 'carga_pedido') // agrega producto a la orden desde panel vendedores
{
	detalle_fatura();	
}

if($action == 'elimina_item') //elimina item de partirda
{ 
	$cve_doc=trim($_GET['cve_doc']);
	$cve_art=trim($_GET['art']);
	$nl=trim($_GET['nl']);	
		
	$sql = "delete from PAR_FACTP".$_SESSION['empre_numero']."  
		where CVE_DOC='".$cve_doc."' and CVE_ART='".$cve_art."' and NUM_PAR='".$nl."'; ";

	$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg()); 

	$sql = "delete from PAR_FACTP_CLIB".$_SESSION['empre_numero']." 
		 where CLAVE_DOC='".$cve_doc."' and NUM_PART='".$nl."'; ";
	
	$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg()); 

	$sql = "update FACTP".$_SESSION['empre_numero']." 
		set CAN_TOT=(select SUM(CANT*PREC)from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'), 
		IMP_TOT3=(select SUM(TOTIMP3) from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'), 
		IMP_TOT4=(select SUM(TOTIMP4) from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'), 
		DES_TOT=(select SUM((CANT*PREC)*(DESC1/100)) 
		from PAR_FACTP".$_SESSION['empre_numero']." 
		where CVE_DOC='".$cve_doc."'), IMPORTE=(CAN_TOT+IMP_TOT3+IMP_TOT4-DES_TOT) where CVE_DOC='".$cve_doc."'; ";

	$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg()); 

	$TOTAL_BRUTO=0;
	$T_CLIE='';
    $query = "select 
    	(CAN_TOT - DES_TOT) as CAN_TOT,
    	(SELECT CAMPLIB7 FROM CLIE_CLIB".$_SESSION['empre_numero']." WHERE CVE_CLIE=CVE_CLPV) AS T_CLIE 
    	from FACTP".$_SESSION['empre_numero']." as P 
    	WHERE CVE_DOC='".$cve_doc."'; ";
	
    $result = ibase_query($conn, $query);

    while($row = ibase_fetch_object($result))
		{
			$TOTAL_BRUTO=$row->CAN_TOT;
			$T_CLIE=$row->T_CLIE;
		}
    
    

	if($TOTAL_BRUTO > 99 && $_SESSION['tipo_empresa']=='M' && T_CLIE=='G')
    {
		$sql = "UPDATE PAR_FACTP".$_SESSION['empre_numero']." 
		SET TOTIMP3=(case WHEN DESC1>0 THEN (((CANT*PREC-((CANT*PREC)*(DESC1/100)))*0.01)*-1) ELSE (((CANT*PREC)*0.01)*-1)	END ),
		IMPU3=-1 
		WHERE CVE_DOC='".$cve_doc."'; 

		update FACTP".$_SESSION['empre_numero']." 
		set IMP_TOT3=(select SUM(TOTIMP3) from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."')
		where CVE_DOC='".$cve_doc."';

		";
				
		$gestor_sent = ibase_que($conn, $sql) or die (ibase_errmsg());
    	
				
	}
	elseif ($TOTAL_BRUTO>99 && $_SESSION['tipo_empresa']=='G' && T_CLIE=='M')
	{
		$sql = "UPDATE PAR_FACTP".$_SESSION['empre_numero']." 
		SET TOTIMP3=(case WHEN DESC1>0 THEN (((CANT*PREC-((CANT*PREC)*(DESC1/100)))*0.01)*-1) ELSE ((CANT*PREC)*0.01)	END ),
		IMPU3=1 
		WHERE CVE_DOC='".$cve_doc."'; 

		update FACTP".$_SESSION['empre_numero']." 
		set IMP_TOT3=(select SUM(TOTIMP3) from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."')
		where CVE_DOC='".$cve_doc."';

		";
				
		$gestor_sent = ibase_que($conn, $sql) or die (ibase_errmsg());	
	}

	ibase_close( $conn);
	
	detalle_fatura();	
}	

if($action == 'modifica_item') //modifica item en partida
{ 
	$cve_doc=trim($_GET['cve_doc']);
	$cve_art=trim($_GET['art']);
	$tcont=trim($_GET['tcont']);
	$nl=trim($_GET['nl']);

	$cantidad=trim($_GET['cantidad']);

	$imp3 = (isset($_REQUEST['imp3'])&& $_REQUEST['imp3'] !=NULL)?$_REQUEST['imp3']:'';	
	$timp3 = (isset($_REQUEST['timp3'])&& $_REQUEST['timp3'] !=NULL)?$_REQUEST['timp3']:'';
	
	if ($imp3=='') { $imp3=0; }
	if ($timp3=='') { $timp3=0; }

	$sql = "update PAR_FACTP".$_SESSION['empre_numero']." 
		set CANT='".$cantidad."',
		IMPU3='".$imp3."',
		TOTIMP3='".$timp3."',
		TOTIMP4=((".$cantidad."*PREC)*.13),
		TOT_PARTIDA=CANT*PREC 
		where CVE_DOC='".$cve_doc."' and CVE_ART='".$cve_art."' and NUM_PAR='".$nl."'; ";	
	//echo $sql;
	$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

	$sql3 = "update FACTP".$_SESSION['empre_numero']." 
		set CAN_TOT=(select SUM(CANT*PREC) from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'),
		IMP_TOT3=(select SUM(TOTIMP3) from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'),
		IMP_TOT4=(select SUM(TOTIMP4) from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'),
		DES_TOT=(select SUM((CANT*PREC)*(DESC1/100))from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'), 
		IMPORTE=(CAN_TOT+IMP_TOT3+IMP_TOT4-DES_TOT) 
		where CVE_DOC='".$cve_doc."'; ";

	$gestor_sent = ibase_query($conn, $sql3) or die(ibase_errmsg());

    $TOTAL_BRUTO=0;
	$T_CLIE='';
    $query = "select 
    	(CAN_TOT - DES_TOT) as CAN_TOT,
    	(SELECT CAMPLIB7 FROM CLIE_CLIB".$_SESSION['empre_numero']." WHERE CVE_CLIE=CVE_CLPV) AS T_CLIENT 
    	from FACTP".$_SESSION['empre_numero']." as P 
    	WHERE CVE_DOC='".$cve_doc."'; ";
	
    $result = ibase_query($conn, $query);

    while($row = ibase_fetch_object($result))
		{
			$TOTAL_BRUTO=$row->CAN_TOT;
			$T_CLIE=$row->T_CLIENT;
		}
    
    

	if($TOTAL_BRUTO > 100 && $T_CLIE=='M' && $T_CLIE=='G')
    {
		$sql = "UPDATE PAR_FACTP".$_SESSION['empre_numero']." 
		SET TOTIMP3=(case WHEN DESC1>0 THEN (((CANT*PREC-((CANT*PREC)*(DESC1/100)))*0.01)*-1) ELSE (((CANT*PREC)*0.01)*-1)	END ),
		IMPU3=-1 
		WHERE CVE_DOC='".$cve_doc."';";
				
		$gestor_sent = ibase_query($conn, $sql) or die (ibase_errmsg());

		$sql="update FACTP".$_SESSION['empre_numero']." 
		set IMP_TOT3=(select SUM(TOTIMP3) from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."')
		where CVE_DOC='".$cve_doc."';

		";
				
		$gestor_sent = ibase_query($conn, $sql) or die (ibase_errmsg());
    	
				
	}
	elseif ($TOTAL_BRUTO>100 && $_SESSION['tipo_empresa']=='G' && $T_CLIE=='M')
	{
		$sql = "UPDATE PAR_FACTP".$_SESSION['empre_numero']." 
		SET TOTIMP3=(case WHEN DESC1>0 THEN (((CANT*PREC-((CANT*PREC)*(DESC1/100)))*0.01)*-1) ELSE ((CANT*PREC)*0.01)	END ),
		IMPU3=1 
		WHERE CVE_DOC='".$cve_doc."';";

			$gestor_sent = ibase_query($conn, $sql) or die (ibase_errmsg());


		$sql="update FACTP".$_SESSION['empre_numero']." 
		set IMP_TOT3=(select SUM(TOTIMP3) from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."')
		where CVE_DOC='".$cve_doc."';";
				
		$gestor_sent = ibase_query($conn, $sql) or die (ibase_errmsg());	
	}


	ibase_close( $conn);

	detalle_fatura();
	
}

if($action == 'mod_fecha') //modifica la fecha de entrega del pedido
{ 
	
	$cve_doc=trim($_GET['cve_doc']);
	
	

	$fecha=trim($_GET['fecha']);
		
	$sql3 = "update FACTP".$_SESSION['empre_numero']." set FECHA_ENT='".$fecha."' where CVE_DOC='".$cve_doc."'; ";

	echo $sql3;

	$stmt3 = sqlsrv_query( $conn, $sql3);
	if ( $stmt3 ) { $something = "Submission successful.";}     
	else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
	$output=$something;   
	sqlsrv_free_stmt( $stmt3);

	sqlsrv_close( $conn);

	detalle_fatura();
	
}


function detalle_fatura() // actualiza detalle de factura
{
	include("../config/conexionsae.php");

	try
		{
			$cve_doc=trim($_GET['cve_doc']);
			$tcont=trim($_GET['tcont']);

		    $query2 = "select 
		    	P.NUM_PAR,
		    	P.CVE_DOC,
		    	P.CVE_ART,
		    	P.CANT,
		    	(SELECT DESCR FROM INVE".$_SESSION['empre_numero']." WHERE CVE_ART=P.CVE_ART) AS DESCR,
		    	P.PREC,
		    	(P.CANT*PREC) AS SUB_TOT,
		    	CAST(((P.CANT*(P.PREC-(P.PREC*(P.DESC1/100))))*(P.IMPU4/100)) AS DECIMAL(10,4)) AS IVA,
		    	CAST(((P.CANT*(P.PREC-(P.PREC*(P.DESC1/100))))*(P.IMPU3/100)) AS DECIMAL(10,4)) AS RETENCION,
		    	P.DESC1,
		    	(select CAN_TOT from FACTP".$_SESSION['empre_numero']." WHERE CVE_DOC=P.CVE_DOC) as CAN_TOT 
		    	from PAR_FACTP".$_SESSION['empre_numero']." as P 
		    	WHERE CVE_DOC='".$cve_doc."';";
			
			$result2 = ibase_query($conn, $query2);

		    $count = 0;
		    while ($row[$count] = ibase_fetch_assoc($result2))
			{
			    $count++;
			}

			if ($count > 0)
			{?>			
				<div class="table-responsive">
				<table class="table table-hover table-responsive" >
				 	<tr  class="info">
						<th class='text-center'>Codigo</th>
						<th class='text-center'>Cant.</th>
						<th class='text-center'>Descripcion</th>
						<th class='text-right'>Precio Unit.</th>
						<th class='text-right'>Sub total</th>
						<th class='text-center'>Accion</th>
					</tr> <?php	

											
					$sumador_total=0;
					$sumador_total_p=0;
					$iva_total=0;
					$retencion_total=0;
					$lineas=0;
					$tot_descuento=0;
					$pdescuento=0;
					$alimento='';
					$lalimentos='';
					$subtotal=0;

					$result2 = ibase_query($conn, $query2);

					while($row = ibase_fetch_object($result2))
					{
						$cantidad_total=0;
						$cantidad_total=0.00;
						$retencion=0;

						$doc=$row->CVE_DOC;
						$nlin=$row->NUM_PAR;
						$pro=$row->CVE_ART;
						$des=$row->DESCR;
						$pre=$row->PREC;
						$can=$row->CANT;
						$iv=$row->IVA;
						$cantidad_total=$row->CAN_TOT;
						$pdescuento=$row->DESC1;
						$retencion_total=$retencion_total+$row->RETENCION;

						$sumador_total=$sumador_total+($pre*$can);
						$sumador_total_p=($pre*$can);

						$iva_total=$iva_total+$iv;
						$lineas=$lineas+1;

						$tot_descuento=$tot_descuento+(($pdescuento /100)*($pre*$can));

						$alimento= substr($pro,0,1 );
						if ($alimento=='A') { $lalimentos='A'; } 
						
						if ($cantidad_total>100 && $tcont=='G') {

							$retencion=1;

							/*$imp3=-1;
							$timp3=((($sumador_total_p-(($pdescuento /100)*($pre*$can)))*0.01)*-1);*/
						} ?>
						
					<tr>
						<td class='text-center'><?php echo $pro;?></td>					
						<td class='text-center'>
							
							<input type="text" name="canti_<?php echo $nlin; ?>" id="cant_<?php echo $nlin; ?>" size="6" onkeypress="modifica_item(event,<?php echo $nlin; ?>,this.value)" value="<?php echo number_format($can,2);?>"> </td>

						<td><?php echo utf8_encode($des);?></td>
						<td class='text-right'><?php echo number_format($pre,4);?></td>
						<td class='text-right'>
							<?php echo number_format($sumador_total_p,2);?>
						</td>
						<input type="hidden" class="form-control" id="art_<?php echo $nlin; ?>"  value="<?php echo $pro;?>" >
						<input type="hidden" class="form-control" id="doc_<?php echo $nlin; ?>"  value="<?php echo $doc;?>" >
						<td class='text-center'>
							<a href="#" class='btn btn-danger btn-xs' title='Elimina item' onclick="elimina_item('<?php echo $nlin; ?>')"><i class="glyphicon glyphicon-remove"></i></a>
							<!--<a href="#" class='btn btn-success btn-xs' title='Modifica item' onclick="modifica_item('<?php echo $nlin; ?>')"><i class="glyphicon glyphicon-refresh"></i></a>-->
							
						</td>
					</tr> <?php

					}

					ibase_close( $conn);
			}
		}
			
		catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }  
		
		
		$subtotal=number_format($sumador_total,2,'.','');
		//$total_iva=($subtotal * $impuesto )/100;
		$total_iva=number_format($iva_total,2,'.','');
		$total_factura=$subtotal+$total_iva+$retencion_total;
		?>
		<tr>
			<td class='text-right' colspan=4>SUB-TOTAL $</td>
			<td class='text-right'><?php echo number_format($subtotal,2);?>
				<input type="hidden" class="form-control" id="cantot"  value="<?php echo $subtotal;?>" >
			</td>
			<td></td>
		</tr>
		<tr>
			<td class='text-right' colspan=4>DESCUENTO $</td>
			<td class='text-right'><?php echo number_format($tot_descuento,2);?>
				<input type="hidden" class="form-control" id="desc_tot"  value="<?php echo $tot_descuento;?>" >
			</td>
			<td></td>
		</tr>
		<tr>
			<td class='text-right' colspan=4>IVA (13)% <?php //echo $simbolo_moneda;?></td>
			<td class='text-right'><?php echo number_format($total_iva,2);?>
				<input type="hidden" class="form-control" id="ivatot"  value="<?php echo $total_iva;?>" >
			</td>
			<td></td>
		</tr>
		<tr>
			<td class='text-right' colspan=4>Retencion (-1)% <?php //echo $simbolo_moneda;?></td>
			<td class='text-right'><?php echo number_format($retencion_total,2);?>
				<input type="hidden" class="form-control" id="ivatot"  value="<?php echo $total_iva;?>" >
			</td>
			<td></td>
		</tr>
		<tr>
			<td class='text-right' colspan=4>TOTAL $ <?php //echo $simbolo_moneda;?></td>
			<td class='text-right'><?php echo number_format(($total_factura-$tot_descuento),2);?>
				<input type="hidden" class="form-control" id="importe"  value="<?php echo $total_factura;?>" >
				<input type="hidden" class="form-control" id="importe"  value="<?php echo $total_factura;?>" >
				<input type="hidden" class="form-control" id="nlin"  value="<?php echo $lineas;?>" >
			</td>
			<td></td>
		</tr>

		

		</table>
	</div>
	
		<input type="hidden" class="form-control" id="alimento"  value="<?php echo $lalimentos; ?>" >

		<script type="text/javascript" src="./js/ordenes.js"></script>

		<?php
}



if($action == 'b_productos') 
{			
	try{

		echo "<br/>";

		$tb=$_GET['tb'];

		if ($tb=='1') {
			
			$query="select 
				I.CVE_ART,
				I.DESCR,I.COSTO_PROM,I.TIPO_ELE,I.UNI_ALT,
				CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=I.CVE_ART AND CVE_PRECIO IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE UPPER(trim(CLAVE))=upper(trim('".$_GET['cod_cliente']."')))) AS DECIMAL(10,2)) AS PRECIO,
		
		COALESCE((select VAL from POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select max(CVE_POLIT) as CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['cod_cliente']."')) BETWEEN UPPER(CLIE_D) AND UPPER(CLIE_H) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=upper(trim('".$_GET['cod_cliente']."'))))),0) AS DESCUENTO,
		
		COALESCE((select PRC_MON FROM POLI".$_SESSION['empre_numero']." where CVE_POLIT=(select MAX(CVE_POLIT) as CVE_POLIT from POLI01 where CVE_INI=I.CVE_ART AND CVE_FIN=I.CVE_ART AND upper(trim('".$_GET['cod_cliente']."')) BETWEEN UPPER(CLIE_D) AND UPPER(CLIE_H) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=upper(trim('".$_GET['cod_cliente']."'))))),'') AS PRC_MONTO,

		(SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_PRECIO=2 AND CVE_ART=I.CVE_ART) AS PRECIOMINIMO FROM INVE".$_SESSION['empre_numero']." AS I WHERE UPPER(I.DESCR) LIKE upper('%".$_GET['nom_art']."%') and I.STATUS='A'";

		}
		else
		{
			$query = "select FIRST 20 sum(DF.CANT) as CANT, 
				DF.CVE_ART, 
				(select DESCR from INVE".$_SESSION['empre_numero']." where CVE_ART=DF.CVE_ART) AS DESCR,
				(select COSTO_PROM from INVE".$_SESSION['empre_numero']." where CVE_ART=DF.CVE_ART) AS COSTO_PROM,
				(select TIPO_ELE from INVE".$_SESSION['empre_numero']." where CVE_ART=DF.CVE_ART) AS TIPO_ELE,
				(select UNI_ALT from INVE".$_SESSION['empre_numero']." where CVE_ART=DF.CVE_ART) AS UNI_ALT,
				CAST((SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_ART=DF.CVE_ART AND CVE_PRECIO IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE UPPER(trim(CLAVE))=upper(trim('".$_GET['cod_cliente']."')))) AS DECIMAL(10,2)) AS PRECIO,
		
		COALESCE((SELECT VAL FROM POLI".$_SESSION['empre_numero']." WHERE CVE_POLIT=(select MAX(CVE_POLIT) AS CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=DF.CVE_ART AND CVE_FIN=DF.CVE_ART AND upper(trim('".$_GET['cod_cliente']."')) BETWEEN UPPER(CLIE_D) AND UPPER(CLIE_H) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=upper(trim('".$_GET['cod_cliente']."'))))),0) AS DESCUENTO,

		COALESCE((SELECT PRC_MON FROM POLI".$_SESSION['empre_numero']." WHERE CVE_POLIT=(select MAX(CVE_POLIT) AS CVE_POLIT from POLI".$_SESSION['empre_numero']." where CVE_INI=DF.CVE_ART AND CVE_FIN=DF.CVE_ART AND upper(trim('".$_GET['cod_cliente']."')) BETWEEN UPPER(CLIE_D) AND UPPER(CLIE_H) AND ST='A' AND COALESCE(V_HFECH,'".date("Y-m-d")."')>='".date("Y-m-d")."' AND LISTA_PREC IN (SELECT LISTA_PREC FROM CLIE".$_SESSION['empre_numero']." WHERE CLAVE=upper(trim('".$_GET['cod_cliente']."'))))),0) AS PRC_MONTO,
		

		(SELECT PRECIO FROM PRECIO_X_PROD".$_SESSION['empre_numero']." WHERE CVE_PRECIO=2 AND CVE_ART=DF.CVE_ART) AS PRECIOMINIMO from PAR_FACTF".$_SESSION['empre_numero']." as DF where DF.CVE_DOC in (select CVE_DOC from FACTF".$_SESSION['empre_numero']." where trim(CVE_CLPV)='".trim($_GET['cod_cliente'])."') AND DF.CVE_ART<>'S00NA007' group by DF.CVE_ART order by CANT desc";

		}

		$result = ibase_query($conn, $query); ?>

		<div class="table-responsive">
				<table class="table table-hover table-responsive" >
				 	<tr  class="info">
					<th align="text-center">Código</th>
					<th align="text-center">Producto</th>
					<th align="text-center"><span >Cant.</span></th>
					<th align="text-center"><span >Precio</span></th>
					<th align="text-center"><span >% desc</span></th>
					<th class='text-center' style="width: 30px;">Agregar</th>
				</tr> <?php

				while($row = ibase_fetch_object($result))
				{
					$preciocondescuento=0;
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

						$costo=$row->COSTO_PROM;
						$uni_alt=$row->UNI_ALT;
						$tipo_ele=$row->TIPO_ELE;

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
							<td><?php echo $nombre_producto; ?></td>
							<td class='col-xs-1'>
								<div class="pull-right">
									<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $codigo_producto; ?>"  value="1" >
								</div>
							</td>
							<td class='col-xs-2'>
								<div class="pull-right">
									<input type="text" class="form-control" style="text-align:right" id="precio_venta_<?php echo $codigo_producto; ?>"  value="<?php echo $precio_venta;?>" readonly="readonly" >
									<input type="hidden" class="form-control" id="descripcion_<?php echo $codigo_producto; ?>" value="<?php echo utf8_encode($nombre_producto); ?>" >
									<!--<input type="hidden" class="form-control" id="clie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['cod_cliente'];?>" >-->
									<input type="hidden" class="form-control" id="producto_<?php echo $codigo_producto; ?>"  value="<?php echo $codigo_producto; ?>" >
									<!--<input type="hidden" class="form-control" id="mat_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['w']; ?>" >
									<!--<input type="hidden" class="form-control" id="nclie_<?php echo $codigo_producto; ?>"  value="<?php echo $_GET['nom_']; ?>" >-->
									<input type="hidden" class="form-control" id="vend_<?php echo $codigo_producto; ?>"  value="<?php echo $_SESSION['user_cvevend']; ?>" >


									<input type="hidden" class="form-control" id="costo_<?php echo $codigo_producto; ?>"  value="<?php echo $costo; ?>" >
									<input type="hidden" class="form-control" id="uni_alt_<?php echo $codigo_producto; ?>"  value="<?php echo $uni_alt; ?>" >
									<input type="hidden" class="form-control" id="tipo_ele_<?php echo $codigo_producto; ?>"  value="<?php echo $tipo_ele; ?>" >


								</div>
							</td>
							<td class='col-xs-2'>
								<input type="text" class="form-control" style="text-align:right" id="desc_<?php echo $codigo_producto; ?>"  value="<?php echo $desc_part;?>" readonly="readonly" >								
							</td>		

							<td class='text-center'> <?php
								if ($_SESSION['user_tipo']=='M'){ ?>
									<a href="#" class='btn btn-primary' onclick="addproductosss('<?php echo $codigo_producto; ?>')">
									<i class="glyphicon glyphicon-plus"></i></a> <?php
								}
								elseif ($_SESSION['user_tipo']=='V'){ ?>
									<a href="#" class='btn btn-primary' onclick="a_producto('<?php echo $codigo_producto; ?>')">
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
		ibase_close ($conn);		
	}
	catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; 	}	
}

if($action == 'agrega_producto') //modifica item en partida
{ 

	$cve_doc=trim($_GET['cve_doc']);
	$tcont=trim($_GET['tcont']);

	$cve_art=trim($_GET['art']);
	$cantidad=trim($_GET['cantidad']);
	$precio=trim($_GET['precio']);
	$descuento=utf8_encode(trim($_GET['desc']));
	$costo=trim($_GET['costo']);
	$uni_alt=trim($_GET['uni']);
	$tipo_ele=trim($_GET['tipo']);

	$hoy = date("Y-m-d");	
	$hora = date("H:i:s");

	$iva=(($cantidad*$precio)-(($cantidad*$precio)*($descuento/100)))*0.13;

	$tot_par=($cantidad*$precio);

	$n_par=0;

    $query = "select max(NUM_PAR) as NUM_PAR 
    	from PAR_FACTP".$_SESSION['empre_numero']." 
    	WHERE CVE_DOC='".$cve_doc."' ;";	

    $result= ibase_query($conn, $query);
    
	while($row = ibase_fetch_object($result)) { $n_par=$row->NUM_PAR; }
	$n_par=$n_par+1;
	

	 $sql = "insert into PAR_FACTP".$_SESSION['empre_numero']." (CVE_DOC,NUM_PAR,CVE_ART,CANT,PXS,PREC,COST,IMPU1,IMPU2,IMPU3,IMPU4,IMP1APLA,IMP2APLA,IMP3APLA,IMP4APLA,TOTIMP1,TOTIMP2,TOTIMP3,TOTIMP4,DESC1,DESC2,DESC3,COMI,APAR,ACT_INV,NUM_ALM,POLIT_APLI,TIP_CAM,UNI_VENTA,TIPO_PROD,CVE_OBS,REG_SERIE,E_LTPD,TIPO_ELEM,NUM_MOV,TOT_PARTIDA,IMPRIMIR) values('".$cve_doc."','".$n_par."','".$cve_art."','".$cantidad."','".$cantidad."','".$precio."','".$costo."','0','0','0','13','0','0','0','1','0','0','0','".$iva."','".$descuento."','0','0','0.000','0','N','1',null,'1','".$uni_alt."','".$tipo_ele."','0','0','0','N','0','".$tot_par."','S');";

	$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg()); 

	$sql = "insert into PAR_FACTP_CLIB".$_SESSION['empre_numero']." (CLAVE_DOC,NUM_PART) values('".$cve_doc."','".$n_par."')"; //este es 1 por cada linea del documento
	
	$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg()); 

	$sql = "insert into bitacora (usuario,fecha,accion,hora) values('".$_SESSION['user_id']."','".$hoy."','Agrega item a sae en la orden ".$cve_doc."','".$hora."')";

	$gestor_sent = ibase_query($conn2, $sql) or die(ibase_errmsg()); 

	$sql = "update FACTP".$_SESSION['empre_numero']." 
		set CAN_TOT=(select SUM(CANT*PREC)from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'), 
		IMP_TOT3=(select SUM(TOTIMP3)from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'),
		IMP_TOT4=(select SUM(TOTIMP4)from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'),
		DES_TOT=(select SUM((CANT*PREC)*(DESC1/100))from PAR_FACTP".$_SESSION['empre_numero']." where CVE_DOC='".$cve_doc."'), 
		IMPORTE=(CAN_TOT+IMP_TOT3+IMP_TOT4-DES_TOT) 
		where CVE_DOC='".$cve_doc."'; ";

	$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg()); 

    $query = "select 
    	CAN_TOT 
    	from FACTP".$_SESSION['empre_numero']." as P 
    	WHERE CVE_DOC='".$cve_doc."' and (CAN_TOT-DES_TOT)>100";

    $result= ibase_query($conn, $query);
	
    $count = 0;
	while ($row[$count] = ibase_fetch_assoc($result))
	{
	    $count++;
	}

	if ($count > 0 && $tcont=='G'){
		$sql = "UPDATE PAR_FACTP".$_SESSION['empre_numero']." SET 
			TOTIMP3=(case WHEN DESC1>0 THEN (((CANT*PREC-((CANT*PREC)*(DESC1/100)))*0.01)*-1) ELSE (((CANT*PREC)*0.01)*-1)	END ),IMPU3=-1 WHERE CVE_DOC='".$cve_doc."'; ";	

		$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());  		
	}
	else
	{
		$sql = "UPDATE PAR_FACTP".$_SESSION['empre_numero']." SET 
			TOTIMP3=0,IMPU3=0 WHERE CVE_DOC='".$cve_doc."'; ";	
		
		$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());   
	}

	ibase_close( $conn);
	ibase_close( $conn2);

	detalle_fatura();
	
}

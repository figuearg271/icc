 <?php
include('is_logged.php');
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
try
{
	if($action == 'elimina_item') // elimina articulo a la orden
	{
		require_once("../config/conexionweb.php");
		$sql = "DELETE 
			FROM TMP_FACTURAS 
			WHERE trim(CVE_ART)='".trim($_GET['id'])."' AND trim(CVE_CLIE)='".trim($_GET['clie'])."' and ESTADO=0 and PRECIO = '".$_GET['pre']."' and N_EMPRESA='".$_SESSION['empre_numero']."'";

		$stmt = ibase_query( $conn, $sql);

		if ( $stmt ) { $something = "Submission successful.";}     
		else {$something = "Submission unsuccessful."; die( print_r( ibase_errors(), true)); }
		$output=$something;
		/* Free statement and connection resources. */    
		//sqlsrv_free_stmt( $stmt);  ?>

		<table class="table table-hover table-striped table-responsive">
			 	<tr  class="info">
				<th class='text-center'>CODIGO</th>
				<th class='text-center'>CANT.</th>
				<th class='text-center'>DESCRIPCION</th>
				<th class='text-right'>PRECIO UNIT.</th>
				<th class='text-right'>PRECIO TOTAL</th>
				<th></th>
			</tr> <?php
		try
		{
			$query2 = "select 
				trim(CVE_ART) as CVE_ART,
				trim(CVE_CLIE) as CVE_CLIE,
				DESCRIPCION,
				PRECIO,
				SUM(CANTIDAD) AS CANTIDAD, 
				SUM(IVA) AS IVA,max(DESCUENTO) AS DESCUENTO 
				FROM TMP_FACTURAS 
				where trim(CVE_CLIE)='".trim($_GET['clie'])."' and ESTADO=0 
				GROUP BY trim(CVE_ART),trim(CVE_CLIE),DESCRIPCION,PRECIO";

			
			$result2 = ibase_query($conn, $query2);
			#$row = sqlsrv_fetch_array($result);	
			$sumador_total=0;
			$sumador_total_p=0;
			$iva_total=0;
			$lineas=0;
			$tot_descuento=0;
			$pdescuento=0;
			$alimento='';
			$lalimentos='';
			while($row = ibase_fetch_object($result2))
			{
				$pro=$row->CVE_ART;
				$cli=$row->CVE_CLIE;
				$des=utf8_encode($row->DESCRIPCION);
				$pre=$row->PRECIO;
				$can=$row->CANTIDAD;
				$iv=$row->IVA;
				$pdescuento=$row->DESCUENTO;
				$sumador_total=$sumador_total+($pre*$can);
				$sumador_total_p=($pre*$can);
				$iva_total=$iva_total+$iv;
				$lineas=$lineas+1;

				$tot_descuento=$tot_descuento+(($pdescuento /100)*($pre*$can));

				$alimento= substr($pro,0,1 );
				if ($alimento=='A') { $lalimentos='A'; } ?>
			<tr>
				<td class='text-center'><?php echo $pro;?></td>
				<td class='text-center'><?php echo $can;?></td>
				<td><?php echo $des;?></td>
				<td class='text-right'><?php echo $pre;?></td>
				<td class='text-right'>
					<?php echo $sumador_total_p;?>
				</td>
				<input type="hidden" class="form-control" id="clie_<?php echo $pro; ?>"  value="<?php echo $cli;?>" >
				<td class='text-center'>
				<a href="#" onclick="elimina_item('<?php echo $pro ?>',<?=$pre ?>)"><i class="glyphicon glyphicon-trash"></i></a>
				</td>
			</tr>		
			<?php
			}
		//sqlsrv_free_stmt( $stmt);
		}
		catch (Exception $e)
		{
			echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
		}  
		ibase_close( $conn);
		$subtotal=number_format($sumador_total,2,'.','');
		//$total_iva=($subtotal * $impuesto )/100;
		$total_iva=number_format($iva_total,2,'.','');
		$total_factura=$subtotal+$total_iva;
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
				<td class='text-right' colspan=4>TOTAL $ <?php //echo $simbolo_moneda;?></td>
				<td class='text-right'><?php echo number_format(($total_factura-$tot_descuento),2);?>
					<input type="hidden" class="form-control" id="importe"  value="<?php echo $total_factura;?>" >
					<input type="hidden" class="form-control" id="nlin"  value="<?php echo $lineas;?>" >
				</td>
				<td></td>
			</tr>

			<tr> <?php
				if ($total_factura>0.01) { ?>
					<td class='text-right' colspan='4'><a href="#;" onclick="grabarpedido('<?php echo $pro ?>')"><i class="glyphicon glyphicon-ok"></i> Grabar Pedido</a></td>
					<td class='text-right' colspan='2'><a href="#;" onclick="elimina_pedido('<?php echo $pro ?>')"><i class="glyphicon glyphicon-remove"></i> Eliminar Pedido
					<td></td> <?php
				} ?>			
			</tr>
		</table>
		<input type="hidden" class="form-control" id="alimento"  value="<?php echo $lalimentos; ?>" >

		<?php  
	}

	if($action == 'elimina_orden') //elimina toda la orden
	{
		require_once("../config/conexionweb.php");
		$sql = "DELETE FROM TMP_FACTURAS 
		WHERE trim(CVE_CLIE)='".trim($_GET['clie'])."' and ESTADO=0 and N_EMPRESA='".$_SESSION['empre_numero']."'";

		$stmt = ibase_query( $conn, $sql);
		
		/* Free statement and connection resources. */    
		//sqlsrv_free_stmt( $stmt);  
		?>

		
		<table class="table">
		 	<tr  class="info">
				<th class='text-center'>CODIGO</th>
				<th class='text-center'>CANT.</th>
				<th class='text-center'>DESCRIPCION</th>
				<th class='text-right'>PRECIO UNIT.</th>
				<th class='text-right'>PRECIO TOTAL</th>
				<th></th>
			</tr>
		<?php
		try
		{
			$query2 = "select 
				trim(CVE_ART) as CVE_ART,
				trim(CVE_CLIE) as CVE_CLIE,
				DESCRIPCION,PRECIO,
				SUM(CANTIDAD) AS CANTIDAD, 
				SUM(IVA) AS IVA,max(COALESCE(DESCUENTO,0)) as DESCUENTO 
				FROM TMP_FACTURAS 
				where trim(CVE_CLIE)='".ltrim($_GET['clie'])."' and ESTADO=0 and N_EMPRESA='".$_SESSION['empre_numero']."' 
				GROUP BY trim(CVE_ART),trim(CVE_CLIE),DESCRIPCION,PRECIO";

			$result2 = ibase_query($conn, $query2);
			//$result2 = ibase_execute($stmt2);
			#$row = sqlsrv_fetch_array($result);	
			$sumador_total=0;
			$sumador_total_p=0;
			$iva_total=0;
			$lineas=0;
			$tot_descuento=0;
			$pdescuento=0;
			$alimento='';
			$lalimentos='';

			while($row = ibase_fetch_object($result2))
			{
				$pro=$row->CVE_ART;
				$cli=$row->CVE_CLIE;
				$des=utf8_encode($row->DESCRIPCION);
				$pre=$row->PRECIO;
				$can=$row->CANTIDAD;
				$iv=$row->IVA;
				$pdescuento=$row->DESCUENTO;
				$sumador_total=$sumador_total+($pre*$can);
				$sumador_total_p=($pre*$can);				
				$iva_total=$iva_total+$iv;
				$lineas=$lineas+1;

				$tot_descuento=$tot_descuento+(($pdescuento /100)*($pre*$can));

				$alimento= substr($pro,0,1 );
				
				if ($alimento=='A') { $lalimentos='A'; } ?>
			<tr>
				<td class='text-center'><?php echo $pro;?></td>
				<td class='text-center'><?php echo $can;?></td>
				<td><?php echo $des;?></td>
				<td class='text-right'><?php echo $pre;?></td>
				<td class='text-right'>
					<?php echo $sumador_total_p;?>
				</td>
				<input type="hidden" class="form-control" id="clie_<?php echo $pro; ?>"  value="<?php echo $cli;?>" >
				<td class='text-center'>
					<a href="#" onclick="delet('<?php echo $pro ?>')"><i class="glyphicon glyphicon-trash"></i></a>
				</td>
			</tr>		
			<?php
			}
		}
		catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }  
		ibase_close( $conn);
		$subtotal=number_format($sumador_total,2,'.','');
		//$total_iva=($subtotal * $impuesto )/100;
		$total_iva=number_format($iva_total,2,'.','');
		$total_factura=$subtotal+$total_iva;
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
			<td class='text-right' colspan=4>TOTAL $ <?php //echo $simbolo_moneda;?></td>
			<td class='text-right'><?php echo number_format(($total_factura-$tot_descuento),2);?>
				<input type="hidden" class="form-control" id="importe"  value="<?php echo $total_factura;?>" >
				<input type="hidden" class="form-control" id="nlin"  value="<?php echo $lineas;?>" >
			</td>
			<td></td>
		</tr>

		<tr>
			<td class='text-right' colspan='4'><a href="#;" onclick="grabarpedido('<?php echo $pro ?>')"><i class="glyphicon glyphicon-ok"></i> Grabar Pedido</a></td>
			<td class='text-right' colspan='2'><a href="#;" onclick="borrapedido('<?php echo $pro ?>')"><i class="glyphicon glyphicon-remove"></i> Eliminar Pedido
			<td></td>
		</tr>

		</table>
		<input type="hidden" class="form-control" id="alimento"  value="<?php echo $lalimentos; ?>" >
		<?php  
	}

	if($action == 'add_pedido') //agregando orden al SAE
	{		
		require_once("../config/conexionsae.php");
		require_once("../config/conexionsaweb.php");

		$tdoc=$_GET['tdoc'];

		if ($tdoc==1) { $folio="ZA"; }
		else{ $folio="PWEB";}

		$cve_clie=ltrim($_GET['cli']);
		$fecha=ltrim($_GET['fecha']);
		$fechaentrega=ltrim($_GET['fechaent']);
		$descuento_tot=ltrim($_GET['descuento']);
		$lalimentos=ltrim($_GET['lalimen']);
		$iva=$_GET['ivat'];
		$can_tot=$_GET['tot'];
		$importe=$_GET['importe'];
		$observaciones=$_GET['com'];		
		$cve_vend='';
	    $su_refer = 'WEB';
	    $femision=$fecha;
	    $fentrega=$fechaentrega;
	    $npart=$_GET['nlin'];
	    $ret_tot=0;
	    $lin='0';
	    $com_tot=$can_tot*.005;
	    $bita='';
	    $datmost='';
	    $referdir='';
	    $cve_obs='is null';
	    $ncve_obs=0;
	    $nuevafecha = strtotime ( '+2 day' , strtotime ( $fecha ) ) ;
	    $nuevafecha = date ( 'Y-m-j' , $nuevafecha ); //echo $observaciones;
	    $alimento='';
	    $percepcion=0;

	    
	    if ($observaciones=='') {	    	
	    }
	    else
	    {
	    	$query2 = "select ULT_CVE from TBLCONTROL".$_SESSION['empre_numero']." where ID_TABLA=56";//selecciono el ultimo observacion de facturas
			
			$result2 = ibase_query($conn, $query2);
			
			while($row = ibase_fetch_object($result2))
			{
				$cve_obs=$row->ULT_CVE; //extraigo observacion ultimo correlativo
		    }

		    $ncve_obs=$cve_obs+1;
		    $sql = "update TBLCONTROL".$_SESSION['empre_numero']." set ULT_CVE='".$ncve_obs."' where ID_TABLA='56'"; //actualizo el correlativo de la ultima observacion

		    $gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

			$sql = "INSERT INTO OBS_DOCF".$_SESSION['empre_numero']." (STR_OBS,CVE_OBS) values ('".$observaciones."','".$ncve_obs."')"; //inserto la observacion del pedido

			//echo $sql;

			$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

	    }

	    $query2 = "select ULT_CVE from TBLCONTROL".$_SESSION['empre_numero']." where ID_TABLA=62 or ID_TABLA=58 order by ID_TABLA DESC";//selecciono el ultimo bita y datos de mostrador
		

		$result2 = ibase_query($conn, $query2);
		
		
		while($row = ibase_fetch_object($result2))
		{
			if ($lin==0) { $bita=$row->ULT_CVE; } //extraigo bita en la primera corrida
			if ($lin==1)  { $datmost=$row->ULT_CVE; } //extraigo datos del mostrador en la segunda corrida			
			$lin=$lin+1;
	    }

	    $lin=0;
	    $bita = $bita + 1;
	    $datmost = $datmost + 1;

	    $sql = "update TBLCONTROL".$_SESSION['empre_numero']." set ULT_CVE='".$bita."' where ID_TABLA='62'"; //actualizo el correlativo del bita

	    $gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());
		
		if ($cve_clie=="MOSTR") {
			$sql = "update TBLCONTROL".$_SESSION['empre_numero']." set ULT_CVE='".$datmost."' where ID_TABLA='58'"; //actualizo el correlativo de datos de mostrador

			 $gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

			}
			else
			{
				$datmost=0;
			}
		

		$query2 = "select ULT_DOC from FOLIOSF".$_SESSION['empre_numero']." where SERIE='".$folio."' and TIP_DOC='P';";//extraigo el ultimo correlativo ocupado de documento del folio PWEB
		
		$result2 = ibase_query($conn, $query2);
		
		$cvedoc='';
		
		while($row = ibase_fetch_object($result2)) { $cvedoc=$row->ULT_DOC; }

	    $cvedoc=$cvedoc+1;
	    
	    $sql = "update FOLIOSF".$_SESSION['empre_numero']." set ULT_DOC='".$cvedoc."' where SERIE='".$folio."' and TIP_DOC='P';"; // actualizo folio utilizado al control de folios

	    $gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

		$longitud=strlen($cvedoc);

		if ($longitud==1) { $cvedoc="000000".$cvedoc; }
	    elseif ($longitud==2) { $cvedoc="00000".$cvedoc; }
	    elseif ($longitud==3) { $cvedoc="0000".$cvedoc; }
	    elseif ($longitud==4) { $cvedoc="000".$cvedoc; }
	    elseif ($longitud==5) { $cvedoc="00".$cvedoc; }
	    elseif ($longitud==6) { $cvedoc="0".$cvedoc; }


		$query2 = "select 
			CVE_VEND,
			DIASCRED,
			NOMBRE,
			CALLE,
			LOCALIDAD,
			CURP,
			REFERDIR,
			ESTADO,
			PAIS,
			MUNICIPIO,
			RFC,
			COLONIA,
			CLAVE,
			(select CAMPLIB5 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE) as TIP_CLIE
			from CLIE".$_SESSION['empre_numero']." 
			where trim(CLAVE)=trim('".$cve_clie."')"; // extraigo la informacion del cliente de la tabla
		
		$result2 = ibase_query($conn, $query2);
		#$row = sqlsrv_fetch_array($result);	
		
		//echo $query2;
		while($row = ibase_fetch_object($result2))
		{
			$cve_vend=$row->CVE_VEND;
			$diascred=$row->DIASCRED;
			$nombre=$row->NOMBRE;
			$calle=$row->CALLE;
			$localidad=$row->LOCALIDAD;
			$curp=$row->CURP;
			$referdir=$row->REFERDIR;
			$estado=$row->ESTADO;
			$pais=$row->PAIS;
			$municipio=$row->MUNICIPIO;
			$rfc=$row->RFC;
			$colonia=$row->COLONIA;
			$tipclie=$row->TIP_CLIE;
			$cve_clie=$row->CLAVE;
			
	    }

	    if ($tipclie=='G' && $_SESSION['tipo_empresa']=='M' && $can_tot>100) {
	    	
	    	$ret_tot=(($can_tot*.01)*-1);
	    		
		}
		elseif ($tipclie=='M' && $_SESSION['tipo_empresa']=='G' && $can_tot>100){
			
			$ret_tot=($can_tot*.01);   	
	    	
		}
		else { 
	    		$ret_tot=0; 
	    	}

	    $fvenci=strtotime ( '+'.$diascred.' day' , strtotime ( $femision ) ) ;
	    $fvenci = date ( 'Y-m-j' , $fvenci );

	    $sql = "insert into FACTP".$_SESSION['empre_numero']." (TIP_DOC,CVE_DOC,CVE_CLPV,STATUS,DAT_MOSTR,CVE_VEND,CVE_PEDI,FECHA_DOC,FECHA_ENT,FECHA_VEN,CAN_TOT,IMP_TOT1,IMP_TOT2,IMP_TOT3,IMP_TOT4,DES_TOT,DES_FIN,COM_TOT,CVE_OBS,NUM_ALMA,ACT_CXC,ACT_COI,ENLAZADO,TIP_DOC_E,NUM_MONED,TIPCAMB,NUM_PAGOS,FECHAELAB,PRIMERPAGO,RFC,CTLPOL,ESCFD,AUTORIZA,SERIE,FOLIO,AUTOANIO,DAT_ENVIO,CONTADO,CVE_BITA,BLOQ,FORMAENVIO,DES_FIN_PORC,DES_TOT_PORC,IMPORTE,COM_TOT_PORC,CONDICION) VALUES('P','".$folio."".$cvedoc. "','".$cve_clie."','O','".$datmost."','".$cve_vend."','".$su_refer."','".$femision."','".$fentrega."','".$fvenci."','".$can_tot."','0','0','".$ret_tot."','".$iva."','".$descuento_tot."','0','".$com_tot."','".$ncve_obs."','1','S','N','O','O','1','1','1','".$femision."','0','".$rfc."','0','N','0','".$folio."','".$cvedoc."',null,'0','N','".$bita."','N',null,'0','0','".$importe."','0.000','".$su_refer."');";
		$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

		$hoy = date("Y-m-d");	
		$hora= date("H:i:s");	

		$sql = "insert into bitacora (usuario,fecha,accion,hora) values('".$_SESSION['user_id']."','".$hoy."','Agrega pedido a sae ".$folio."".$cvedoc."','".$hora."')";
		
		$gestor_sent = ibase_query($conn2, $sql) or die(ibase_errmsg());

		$sql = "insert into PAR_FACTP_CLIB".$_SESSION['empre_numero']." (CLAVE_DOC,NUM_PART) values('".$folio."".$cvedoc."','".$npart."')"; //este es 1 por cada linea del documento
		
		$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

		$sql = "insert into BITA".$_SESSION['empre_numero']." (CVE_BITA,CVE_CLIE,CVE_CAMPANIA,CVE_ACTIVIDAD,FECHAHORA,CVE_USUARIO,OBSERVACIONES,STATUS,NOM_USUARIO) values('".$bita."','".$cve_clie."','_SAE_','    4','".date("Y-m-d H:i:s")."','0','No. [ ".$folio."".$cvedoc." ]  ".$importe."','F','ADMINISTRADOR');";
		
		$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

		/*$sql = "insert into FACTP_CLIB".$_SESSION['empre_numero']." (CLAVE_DOC,CAMPLIB1,CAMPLIB2) values('".$folio."".$cvedoc."','".$lalimentos."',NULL);";
		
		$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());*/

		if ($cve_clie=="MOSTR") {
			
			$sql = "insert into INFCLI".$_SESSION['empre_numero']." (CVE_INFO,NOMBRE,CALLE,COLONIA,POB,CURP,CVE_ZONA,CVE_OBS,REFERDIR,CODIGO,ESTADO,PAIS,MUNICIPIO,RFC) values('".$datmost."','".$nombre."','".$calle."','".$colonia."','".$localidad."','".$curp."',null,null,'".$referdir."',null,'".$estado."','".$pais."','".$municipio."','".$rfc."');";
			
			$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

		}
		$query = "select 
			trim(CVE_ART) as CVE_ART,
			sum(CANTIDAD) as CANTIDAD,
			sum(IVA) as IVA,
			max(DESCUENTO) as DESCUENTO 
			from TMP_FACTURAS 
			where trim(CVE_CLIE)=trim('".$cve_clie."') and ESTADO=0 and REFERENCIA is null 
			group by CVE_ART;";

		$result = ibase_query($conn2, $query);
		
		$nlin=1;
		$reten=0;
		$ivas=($_SESSION['impu']*100);
		$ret=0;

		while($row = ibase_fetch_object($result))
		{
				$cve_art=$row->CVE_ART;
				$ca=$row->CANTIDAD;
				$iva=$row->IVA;
				//$lineatmp=$row['ID'];
				$descuento=$row->DESCUENTO;

				$query2 = "SELECT 
					(select PRECIO from PRECIO_X_PROD".$_SESSION['empre_numero']." where CVE_ART=I.CVE_ART and CVE_PRECIO in (SELECT LISTA_PREC from CLIE".$_SESSION['empre_numero']." where trim(CLAVE)=trim('".$cve_clie."'))) as PRECIO,
					I.COSTO_PROM,
					I.UNI_ALT,
					I.TIPO_ELE 
					from INVE".$_SESSION['empre_numero']." as I 
					WHERE I.CVE_ART='".$cve_art."'";
				
				$result2 = ibase_query($conn, $query2);
							
				while($row = ibase_fetch_object($result2))
				{
					$pre=$row->PRECIO;
					$cos=$row->COSTO_PROM;
					$uni_alt=$row->UNI_ALT;
					$tip_ele=$row->TIPO_ELE;

					$top= ($ca*$pre);			
			    }

			    if ($tipclie=='G' && $_SESSION['tipo_empresa']=='M' && $can_tot>100) {
					
					$reten=-1;
			    	$ret=(($top*.01)*-1);			    	
			    	
			    }
			    elseif ($tipclie=='M' && $_SESSION['tipo_empresa']=='G' && $can_tot>100) {
			    	$reten=1;
			    	$ret=($top*.01);
			    }
			    else
			    	{
			    		$reten=0;
			    		$ret=0;

			    	}

			    $sql = "insert into PAR_FACTP".$_SESSION['empre_numero']." (CVE_DOC,NUM_PAR,CVE_ART,CANT,PXS,PREC,COST,IMPU1,IMPU2,IMPU3,IMPU4,IMP1APLA,IMP2APLA,IMP3APLA,IMP4APLA,TOTIMP1,TOTIMP2,TOTIMP3,TOTIMP4,DESC1,DESC2,DESC3,COMI,APAR,ACT_INV,NUM_ALM,POLIT_APLI,TIP_CAM,UNI_VENTA,TIPO_PROD,CVE_OBS,REG_SERIE,E_LTPD,TIPO_ELEM,NUM_MOV,TOT_PARTIDA,IMPRIMIR) values('".$folio."".$cvedoc."','".$nlin."','".$cve_art."','".$ca."','".$ca."','".$pre."','".$cos."','0','0','".$reten."','".$ivas."','0','0','0','1','0','0','".$ret."','".$iva."','".$descuento."','0','0','0.000','0','N','1',null,'1','".$uni_alt."','".$tip_ele."','0','0','0','N','0','".$top."','S');";

			    $gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

				$nlin=$nlin+1;


				$sql = "update TMP_FACTURAS set ESTADO='1', REFERENCIA='".$folio."".$cvedoc."' where CVE_ART='".$cve_art."' and trim(CVE_CLIE)=trim('".$cve_clie."') and ESTADO=0 ";

				$gestor_sent = ibase_query($conn2, $sql) or die(ibase_errmsg());

				$hoy = date("Y-m-d");	
				$hora=date("H:i:s");	

				$sql = "insert into bitacora (usuario,fecha,accion,hora) values('".$_SESSION['user_id']."','".$hoy."','Agrega articulo a sae ".$cve_art." del pedido ".$folio."".$cvedoc."','".$hora."')";

				$gestor_sent = ibase_query($conn2, $sql) or die(ibase_errmsg());

	    	}

		    ibase_close($conn);
		    ibase_close($conn2);

		  	echo "<h4 align='center'>Se registo correctamente la orden ".$folio."".$cvedoc."</h4>";


	}

	if($action == 'add_cotizacion') //agregando orden al SAE
	{		
		require_once("../config/conexionsae.php");
		require_once("../config/conexionsaweb.php");

		$tdoc=$_GET['tdoc'];

		if ($tdoc==1) { $folio="ZA"; }
		else{ $folio="CWEB";}

		$cve_clie=ltrim($_GET['cli']);
		$fecha=ltrim($_GET['fecha']);
		$fechaentrega=ltrim($_GET['fechaent']);
		$descuento_tot=ltrim($_GET['descuento']);
		$lalimentos=ltrim($_GET['lalimen']);
		$iva=$_GET['ivat'];
		$can_tot=$_GET['tot'];
		$importe=$_GET['importe'];
		$observaciones=$_GET['com'];		
		$cve_vend='';

		//este campo seria de agregar los datos contado o credito (vendedor o reparto)
		$su_refer = 'WEB';
		$su_referN = $_GET["nref"];
		//-------------------------------
	    $femision=$fecha;
	    $fentrega=$fechaentrega;
	    $npart=$_GET['nlin'];
	    $ret_tot=0;
	    $lin='0';
	    $com_tot=$can_tot*.005;
	    $bita='';
	    $datmost='';
	    $referdir='';
	    $cve_obs='is null';
	    $ncve_obs=0;
	    $nuevafecha = strtotime ( '+2 day' , strtotime ( $fecha ) ) ;
	    $nuevafecha = date ( 'Y-m-j' , $nuevafecha ); //echo $observaciones;
	    $alimento='';
	    $percepcion=0;

	    
	    if ($observaciones=='') {	    	
	    }
	    else
	    {
	    	$query2 = "select ULT_CVE from TBLCONTROL".$_SESSION['empre_numero']." where ID_TABLA=56";//selecciono el ultimo observacion de facturas
			
			$result2 = ibase_query($conn, $query2);
			
			while($row = ibase_fetch_object($result2))
			{
				$cve_obs=$row->ULT_CVE; //extraigo observacion ultimo correlativo
		    }

		    $ncve_obs=$cve_obs+1;
		    $sql = "update TBLCONTROL".$_SESSION['empre_numero']." set ULT_CVE='".$ncve_obs."' where ID_TABLA='56'"; //actualizo el correlativo de la ultima observacion

		    $gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

			$sql = "INSERT INTO OBS_DOCF".$_SESSION['empre_numero']." (STR_OBS,CVE_OBS) values ('".$observaciones."','".$ncve_obs."')"; //inserto la observacion del pedido

			//echo $sql;

			$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

	    }

	    $query2 = "select ULT_CVE from TBLCONTROL".$_SESSION['empre_numero']." where ID_TABLA=62 or ID_TABLA=58 order by ID_TABLA DESC";//selecciono el ultimo bita y datos de mostrador
		

		$result2 = ibase_query($conn, $query2);
		
		
		while($row = ibase_fetch_object($result2))
		{
			if ($lin==0) { $bita=$row->ULT_CVE; } //extraigo bita en la primera corrida
			if ($lin==1)  { $datmost=$row->ULT_CVE; } //extraigo datos del mostrador en la segunda corrida			
			$lin=$lin+1;
	    }

	    $lin=0;
	    $bita = $bita + 1;
	    $datmost = $datmost + 1;

	    $sql = "update TBLCONTROL".$_SESSION['empre_numero']." set ULT_CVE='".$bita."' where ID_TABLA='62'"; //actualizo el correlativo del bita

	    $gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());
		
		if ($cve_clie=="MOSTR") {
			$sql = "update TBLCONTROL".$_SESSION['empre_numero']." set ULT_CVE='".$datmost."' where ID_TABLA='58'"; //actualizo el correlativo de datos de mostrador

			 $gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

			}
			else
			{
				$datmost=0;
			}
		

		$query2 = "select ULT_DOC from FOLIOSF".$_SESSION['empre_numero']." where SERIE='".$folio."' and TIP_DOC='C'; ";//extraigo el ultimo correlativo ocupado de documento del folio PWEB
		
		$result2 = ibase_query($conn, $query2);
		
		$cvedoc='';
		
		while($row = ibase_fetch_object($result2)) { $cvedoc=$row->ULT_DOC; }

	    $cvedoc=$cvedoc+1;
	    
	    $sql = "update FOLIOSF".$_SESSION['empre_numero']." set ULT_DOC='".$cvedoc."' where SERIE='".$folio."' and TIP_DOC='C';"; // actualizo folio utilizado al control de folios

	    $gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

		$longitud=strlen($cvedoc);

		if ($longitud==1) { $cvedoc="000000".$cvedoc; }
	    elseif ($longitud==2) { $cvedoc="00000".$cvedoc; }
	    elseif ($longitud==3) { $cvedoc="0000".$cvedoc; }
	    elseif ($longitud==4) { $cvedoc="000".$cvedoc; }
	    elseif ($longitud==5) { $cvedoc="00".$cvedoc; }
	    elseif ($longitud==6) { $cvedoc="0".$cvedoc; }


		$query2 = "select 
			CVE_VEND,
			DIASCRED,
			NOMBRE,
			CALLE,
			LOCALIDAD,
			CURP,
			REFERDIR,
			ESTADO,
			PAIS,
			MUNICIPIO,
			RFC,
			COLONIA,
			CLAVE,
			(select CAMPLIB5 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE) as TIP_CLIE
			from CLIE".$_SESSION['empre_numero']." 
			where trim(CLAVE)=trim('".$cve_clie."')"; // extraigo la informacion del cliente de la tabla
		
		$result2 = ibase_query($conn, $query2);
		#$row = sqlsrv_fetch_array($result);	
		
		//echo $query2;
		while($row = ibase_fetch_object($result2))
		{
			$cve_vend=$row->CVE_VEND;
			$diascred=$row->DIASCRED;
			$nombre=$row->NOMBRE;
			$calle=$row->CALLE;
			$localidad=$row->LOCALIDAD;
			$curp=$row->CURP;
			$referdir=$row->REFERDIR;
			$estado=$row->ESTADO;
			$pais=$row->PAIS;
			$municipio=$row->MUNICIPIO;
			$rfc=$row->RFC;
			$colonia=$row->COLONIA;
			$tipclie=$row->TIP_CLIE;
			$cve_clie=$row->CLAVE;
			
	    }

	    if ($tipclie=='G' && $_SESSION['tipo_empresa']=='M' && $can_tot>100) {
	    	
	    	$ret_tot=(($can_tot*.01)*-1);
	    		
		}
		elseif ($tipclie=='M' && $_SESSION['tipo_empresa']=='G' && $can_tot>100){
			
			$ret_tot=($can_tot*.01);   	
	    	
		}
		else { 
	    		$ret_tot=0; 
	    	}

	    $fvenci=strtotime ( '+'.$diascred.' day' , strtotime ( $femision ) ) ;
	    $fvenci = date ( 'Y-m-j' , $fvenci );

	    $sql = "insert into FACTC".$_SESSION['empre_numero']." (TIP_DOC,CVE_DOC,CVE_CLPV,STATUS,DAT_MOSTR,CVE_VEND,CVE_PEDI,FECHA_DOC,FECHA_ENT,FECHA_VEN,CAN_TOT,IMP_TOT1,IMP_TOT2,IMP_TOT3,IMP_TOT4,DES_TOT,DES_FIN,COM_TOT,CVE_OBS,NUM_ALMA,ACT_CXC,ACT_COI,ENLAZADO,TIP_DOC_E,NUM_MONED,TIPCAMB,NUM_PAGOS,FECHAELAB,PRIMERPAGO,RFC,CTLPOL,ESCFD,AUTORIZA,SERIE,FOLIO,AUTOANIO,DAT_ENVIO,CONTADO,CVE_BITA,BLOQ,FORMAENVIO,DES_FIN_PORC,DES_TOT_PORC,IMPORTE,COM_TOT_PORC,CONDICION) VALUES('C','".$folio."".$cvedoc. "','".$cve_clie."','O','".$datmost."','".$cve_vend."','".$su_refer."','".$femision."','".$fentrega."','".$fvenci."','".$can_tot."','0','0','".$ret_tot."','".$iva."','".$descuento_tot."','0','".$com_tot."','".$ncve_obs."','1','S','N','O','O','1','1','1','".$femision."','0','".$rfc."','0','N','0','".$folio."','".$cvedoc."',null,'0','N','".$bita."','n',null,'0','0','".$importe."','0.000','".$su_referN."');";
		$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

		$hoy = date("Y-m-d");	
		$hora= date("H:i:s");	

		$sql = "insert into bitacora (usuario,fecha,accion,hora) values('".$_SESSION['user_id']."','".$hoy."','Agrega pedido a sae ".$folio."".$cvedoc."','".$hora."')";
		
		$gestor_sent = ibase_query($conn2, $sql) or die(ibase_errmsg());

		$sql = "insert into PAR_FACTC_CLIB".$_SESSION['empre_numero']." (CLAVE_DOC,NUM_PART) values('".$folio."".$cvedoc."','".$npart."')"; //este es 1 por cada linea del documento
		
		$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

		$sql = "insert into BITA".$_SESSION['empre_numero']." (CVE_BITA,CVE_CLIE,CVE_CAMPANIA,CVE_ACTIVIDAD,FECHAHORA,CVE_USUARIO,OBSERVACIONES,STATUS,NOM_USUARIO) values('".$bita."','".$cve_clie."','_SAE_','    4','".date("Y-m-d H:i:s")."','0','No. [ ".$folio."".$cvedoc." ]  ".$importe."','F','ADMINISTRADOR');";
		
		$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

		/*$sql = "insert into FACTP_CLIB".$_SESSION['empre_numero']." (CLAVE_DOC,CAMPLIB1,CAMPLIB2) values('".$folio."".$cvedoc."','".$lalimentos."',NULL);";
		
		$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());*/

		if ($cve_clie=="MOSTR") {
			
			$sql = "insert into INFCLI".$_SESSION['empre_numero']." (CVE_INFO,NOMBRE,CALLE,COLONIA,POB,CURP,CVE_ZONA,CVE_OBS,REFERDIR,CODIGO,ESTADO,PAIS,MUNICIPIO,RFC) values('".$datmost."','".$nombre."','".$calle."','".$colonia."','".$localidad."','".$curp."',null,null,'".$referdir."',null,'".$estado."','".$pais."','".$municipio."','".$rfc."');";
			
			$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

		}
		$query = "select 
			trim(CVE_ART) as CVE_ART,
			sum(CANTIDAD) as CANTIDAD,
			sum(IVA) as IVA,
			max(DESCUENTO) as DESCUENTO 
			from TMP_FACTURAS 
			where trim(CVE_CLIE)=trim('".$cve_clie."') and ESTADO=0 and REFERENCIA is null 
			group by CVE_ART;";

		$result = ibase_query($conn2, $query);
		
		$nlin=1;
		$reten=0;
		$ivas=($_SESSION['impu']*100);
		$ret=0;

		while($row = ibase_fetch_object($result))
		{
				$cve_art=$row->CVE_ART;
				$ca=$row->CANTIDAD;
				$iva=$row->IVA;
				//$lineatmp=$row['ID'];
				$descuento=$row->DESCUENTO;

				$query2 = "SELECT 
					(select PRECIO from PRECIO_X_PROD".$_SESSION['empre_numero']." where CVE_ART=I.CVE_ART and CVE_PRECIO in (SELECT LISTA_PREC from CLIE".$_SESSION['empre_numero']." where trim(CLAVE)=trim('".$cve_clie."'))) as PRECIO,
					I.COSTO_PROM,
					I.UNI_ALT,
					I.TIPO_ELE 
					from INVE".$_SESSION['empre_numero']." as I 
					WHERE I.CVE_ART='".$cve_art."'";
				
				$result2 = ibase_query($conn, $query2);
							
				while($row = ibase_fetch_object($result2))
				{
					$pre=$row->PRECIO;
					$cos=$row->COSTO_PROM;
					$uni_alt=$row->UNI_ALT;
					$tip_ele=$row->TIPO_ELE;

					$top= ($ca*$pre);			
			    }

			    if ($tipclie=='G' && $_SESSION['tipo_empresa']=='M' && $can_tot>100) {
					
					$reten=-1;
			    	$ret=(($top*.01)*-1);			    	
			    	
			    }
			    elseif ($tipclie=='M' && $_SESSION['tipo_empresa']=='G' && $can_tot>100) {
			    	$reten=1;
			    	$ret=($top*.01);
			    }
			    else
			    	{
			    		$reten=0;
			    		$ret=0;

			    	}

			    $sql = "insert into PAR_FACTC".$_SESSION['empre_numero']." (CVE_DOC,NUM_PAR,CVE_ART,CANT,PXS,PREC,COST,IMPU1,IMPU2,IMPU3,IMPU4,IMP1APLA,IMP2APLA,IMP3APLA,IMP4APLA,TOTIMP1,TOTIMP2,TOTIMP3,TOTIMP4,DESC1,DESC2,DESC3,COMI,APAR,ACT_INV,NUM_ALM,POLIT_APLI,TIP_CAM,UNI_VENTA,TIPO_PROD,CVE_OBS,REG_SERIE,E_LTPD,TIPO_ELEM,NUM_MOV,TOT_PARTIDA,IMPRIMIR) values('".$folio."".$cvedoc."','".$nlin."','".$cve_art."','".$ca."','".$ca."','".$pre."','".$cos."','0','0','".$reten."','".$ivas."','0','0','0','1','0','0','".$ret."','".$iva."','".$descuento."','0','0','0.000','0','N','1',null,'1','".$uni_alt."','".$tip_ele."','0','0','0','N','0','".$top."','S');";

			    $gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

				$nlin=$nlin+1;


				$sql = "update TMP_FACTURAS set ESTADO='1', REFERENCIA='".$folio."".$cvedoc."' where CVE_ART='".$cve_art."' and trim(CVE_CLIE)=trim('".$cve_clie."') and ESTADO=0 ";

				$gestor_sent = ibase_query($conn2, $sql) or die(ibase_errmsg());

				$hoy = date("Y-m-d");	
				$hora=date("H:i:s");	

				$sql = "insert into bitacora (usuario,fecha,accion,hora) values('".$_SESSION['user_id']."','".$hoy."','Agrega articulo a sae ".$cve_art." en la cotización ".$folio."".$cvedoc."','".$hora."')";

				$gestor_sent = ibase_query($conn2, $sql) or die(ibase_errmsg());

	    	}

		    ibase_close($conn);
		    ibase_close($conn2);

		  	echo "<h4 align='center'>Se registo correctamente la cotización ".$folio."".$cvedoc."</h4>";


	}

	if($action == 'addorden_varios') //agregando cotizacion al SAE
	{		
		require_once("../config/conexionsae.php");
		require_once("../config/conexionsaweb.php");

		$tdoc=$_GET['tdoc'];

		$folio="CWEB";

		$cve_clie=ltrim($_GET['cli']);
		$fecha=ltrim($_GET['fecha']);
		$fechaentrega=ltrim($_GET['fechaent']);
		$descuento_tot=ltrim($_GET['descuento']);
		$lalimentos=ltrim($_GET['lalimen']);
		$iva=$_GET['ivat'];
		$can_tot=$_GET['tot'];
		$importe=$_GET['importe'];
		$observaciones=$_GET['com'];		
		$cve_vend='';
	    $su_refer = 'WEB';
	    $femision=$fecha;
	    $fentrega=$fechaentrega;
	    $npart=$_GET['nlin'];
	    $ret_tot='0';
	    $lin='0';
	    $com_tot=$can_tot*.005;
	    $bita='';
	    $datmost='';
	    $referdir='';
	    $cve_obs='is null';
	    $ncve_obs='';
	    $nuevafecha = strtotime ( '+2 day' , strtotime ( $fecha ) ) ;
	    $nuevafecha = date ( 'Y-m-j' , $nuevafecha ); //echo $observaciones;
	    $alimento='';
	    $nombre=$_GET['n_clie'];
	    $calle=$_GET['direccion'];
	    $cve_vend=$_GET['cve_vendedor'];

	    if ($observaciones=='') {	    	
	    }
	    else
	    {
	    	$query2 = "select ULT_CVE from TBLCONTROL01 where ID_TABLA=56";//selecciono el ultimo observacion de facturas
			$stmt2 = sqlsrv_prepare($conn, $query2);
			$result2 = sqlsrv_execute($stmt2);
			#$row = sqlsrv_fetch_array($result);	
			
			while($row = sqlsrv_fetch_array($stmt2))
			{
				$cve_obs=$row['ULT_CVE']; //extraigo observacion ultimo correlativo
		    }
		    $ncve_obs=$cve_obs+1;
		    $sql = "update TBLCONTROL01 set ULT_CVE='".$ncve_obs."' where ID_TABLA='56'"; //actualizo el correlativo de la ultima observacion
			$stmt = sqlsrv_query( $conn, $sql);
			if ( $stmt ) { $something = "Submission successful.";}     
			else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
			$output=$something;

			$sql = "INSERT INTO OBS_DOCF01 (STR_OBS,CVE_OBS) values ('".$observaciones."','".$ncve_obs."')"; //inserto la observacion del pedido

			//echo $sql;
			$stmt = sqlsrv_query( $conn, $sql);
			if ( $stmt ) { $something = "Submission successful.";}     
			else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
			$output=$something;
	    }

	    $query2 = "select ULT_CVE from TBLCONTROL01 where ID_TABLA=62 or ID_TABLA=58 order by ID_TABLA DESC";//selecciono el ultimo bita y datos de mostrador
		$stmt2 = sqlsrv_prepare($conn, $query2);
		$result2 = sqlsrv_execute($stmt2);
		#$row = sqlsrv_fetch_array($result);	
		
		while($row = sqlsrv_fetch_array($stmt2))
		{
			if ($lin==0) { $bita=$row['ULT_CVE']; } //extraigo bita en la primera corrida
			if ($lin==1)  { $datmost=$row['ULT_CVE']; } //extraigo datos del mostrador en la segunda corrida			
			$lin=$lin+1;
	    }

	    $lin=0;
	    $bita = $bita + 1;
	    $datmost = $datmost + 1;

	    $sql = "update TBLCONTROL01 set ULT_CVE='".$bita."' where ID_TABLA='62'"; //actualizo el correlativo del bita
		$stmt = sqlsrv_query( $conn, $sql);
		if ( $stmt ) { $something = "Submission successful.";}     
		else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;

		$sql = "update TBLCONTROL01 set ULT_CVE='".$datmost."' where ID_TABLA='58'"; //actualizo el correlativo de datos de mostrador
		$stmt = sqlsrv_query( $conn, $sql);
		if ( $stmt ) { $something = "Submission successful.";}     
		else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;

		$query2 = "select ULT_DOC from FOLIOSF01 where SERIE='".$folio."' AND TIP_DOC='C'";//extraigo el ultimo correlativo ocupado de documento del folio PWEB
		$stmt2 = sqlsrv_prepare($conn, $query2);
		$result2 = sqlsrv_execute($stmt2);
		#$row = sqlsrv_fetch_array($result);	
		$cvedoc='';
		
		while($row = sqlsrv_fetch_array($stmt2)) { $cvedoc=$row['ULT_DOC']; }

	    $cvedoc=$cvedoc+1;
	    
	    $sql = "update FOLIOSF01 set ULT_DOC='".$cvedoc."' where SERIE='".$folio."'"; // actualizo folio utilizado al control de folios
	    $stmt = sqlsrv_query( $conn, $sql);
		if ( $stmt ) { $something = "Submission successful.";}     
		else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;
		$longitud=strlen($cvedoc);
		

		if ($longitud==1) { $cvedoc="000000".$cvedoc; }
	    elseif ($longitud==2) { $cvedoc="00000".$cvedoc; }
	    elseif ($longitud==3) { $cvedoc="0000".$cvedoc; }
	    elseif ($longitud==4) { $cvedoc="000".$cvedoc; }
	    elseif ($longitud==5) { $cvedoc="00".$cvedoc; }
	    elseif ($longitud==6) { $cvedoc="0".$cvedoc; }


		$query2 = "select CVE_VEND,DIASCRED,NOMBRE,CALLE,LOCALIDAD,CURP,REFERDIR,ESTADO,PAIS,MUNICIPIO,RFC,COLONIA,(select CAMPLIB8 from CLIE_CLIB01 where CVE_CLIE=CLAVE) as TIPCLIE from CLIE01 where CLAVE='".$cve_clie."'"; // extraigo la informacion del cliente de la tabla
		$stmt2 = sqlsrv_prepare($conn, $query2);
		$result2 = sqlsrv_execute($stmt2);
		#$row = sqlsrv_fetch_array($result);	
		
		while($row = sqlsrv_fetch_array($stmt2))
		{
			//$cve_vend=$row['CVE_VEND'];
			$diascred=$row['DIASCRED'];
			//$nombre=$row['NOMBRE'];
			//$calle=$row['CALLE'];
			$localidad=$row['LOCALIDAD'];
			$curp=$row['CURP'];
			$referdir=$row['REFERDIR'];
			$estado=$row['ESTADO'];
			$pais=$row['PAIS'];
			$municipio=$row['MUNICIPIO'];
			$rfc=$row['RFC'];
			$colonia=$row['COLONIA'];
			$tipclie=ltrim($row['TIPCLIE']);
			
	    }

	    if ($tipclie=='G' && $_SESSION['tipo_empresa']=='M' && $can_tot>100) {
	    	$ret_tot=($can_tot*.01);	
		}
		elseif ($tipclie=='M' && $_SESSION['tipo_empresa']=='G' && $can_tot>100){
			$ret_tot=($can_tot*.01);
		}
		else { 
	    		$ret_tot=0; 
	    	}

	    $fvenci=strtotime ( '+'.$diascred.' day' , strtotime ( $femision ) ) ;
	    $fvenci = date ( 'Y-m-j' , $fvenci );

	    $sql = "insert into FACTC".$_SESSION['empre_numero']." (TIP_DOC,CVE_DOC,CVE_CLPV,STATUS,DAT_MOSTR,CVE_VEND,CVE_PEDI,FECHA_DOC,FECHA_ENT,FECHA_VEN,CAN_TOT,IMP_TOT1,IMP_TOT2,IMP_TOT3,IMP_TOT4,DES_TOT,DES_FIN,COM_TOT,CVE_OBS,NUM_ALMA,ACT_CXC,ACT_COI,ENLAZADO,TIP_DOC_E,NUM_MONED,TIPCAMB,NUM_PAGOS,FECHAELAB,PRIMERPAGO,RFC,CTLPOL,ESCFD,AUTORIZA,SERIE,FOLIO,AUTOANIO,DAT_ENVIO,CONTADO,CVE_BITA,BLOQ,FORMAENVIO,DES_FIN_PORC,DES_TOT_PORC,IMPORTE,COM_TOT_PORC,CONDICION) VALUES('P','".$folio."".$cvedoc. "','".$cve_clie."','O','".$datmost."','".$cve_vend."','".$su_refer."','".$femision."','".$fentrega."','".$fvenci."','".$can_tot."','0','0','".$ret_tot."','".$iva."','".$descuento_tot."','0','".$com_tot."','".$ncve_obs."','1','S','N','O','O','1','1','1','".$femision."','0','".$rfc."','0','N','0','".$folio."','".$cvedoc."','','0','N','".$bita."','n','','0','0','".$importe."','0.0005','".$su_refer."');";
		$stmt = sqlsrv_query( $conn, $sql);

		if ( $stmt ) { $something = "Submission successful.";}     
		else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;

		$hoy = date("Y-m-d H:i:s");		

		$sql = "insert into bitacora (usuario,fecha,accion) values('".$_SESSION['user_id']."','".$hoy."','Agrega pedido a sae ".$folio."".$cvedoc."')";
		$stmt = sqlsrv_query( $conn2, $sql);
		if ( $stmt ) { $something = "Submission successful.";}     
		else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;


		$sql = "insert into PAR_FACTC_CLIB".$_SESSION['empre_numero']." (CLAVE_DOC,NUM_PART) values('".$folio."".$cvedoc."','".$npart."')"; //este es 1 por cada linea del documento
		$stmt = sqlsrv_query( $conn, $sql);
		if ( $stmt ) { $something = "Submission successful.";}     
		else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;


		$sql = "insert into BITA".$_SESSION['empre_numero']." (CVE_BITA,CVE_CLIE,CVE_CAMPANIA,CVE_ACTIVIDAD,FECHAHORA,CVE_USUARIO,OBSERVACIONES,STATUS,NOM_USUARIO) values('".$bita."','".$cve_clie."','_SAE_','    4','".date("Y-m-d H:i:s")."','0','No. [ ".$folio."".$cvedoc." ]  ".$importe."','F','ADMINISTRADOR');";
		$stmt = sqlsrv_query( $conn, $sql);
		if ( $stmt ) { $something = "Submission successful.";}     
		else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;


		$sql = "insert into FACTC_CLIB".$_SESSION['empre_numero']." (CLAVE_DOC,CAMPLIB1,CAMPLIB2) values('".$folio."".$cvedoc."','".$lalimentos."','');";
		$stmt = sqlsrv_query( $conn, $sql);
		if ( $stmt ) { $something = "Submission successful.";}     
		else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;

		$sql = "insert into INFCLI".$_SESSION['empre_numero']." (CVE_INFO,NOMBRE,CALLE,COLONIA,POB,CURP,CVE_ZONA,CVE_OBS,REFERDIR,CODIGO,ESTADO,PAIS,MUNICIPIO,RFC) values('".$datmost."','".$nombre."','".$calle."','".$colonia."','".$localidad."','".$curp."','','','".$referdir."','','".$estado."','".$pais."','".$municipio."','".$rfc."');";
		$stmt = sqlsrv_query( $conn, $sql);
		if ( $stmt ) { $something = "Submission successful.";}     
		else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
		$output=$something;


		$query = "select ltrim(CVE_ART) as CVE_ART,sum(CANTIDAD) as CANTIDAD,sum(IVA) as IVA,max(DESCUENTO) as DESCUENTO from TMP_FACTURAS where CVE_CLIE='".$cve_clie."' and ESTADO=0 and REFERENCIA is null group by CVE_ART;";
		$stmt3 = sqlsrv_prepare($conn2, $query);
		$result = sqlsrv_execute($stmt3);
		#$row = sqlsrv_fetch_array($result);	
		$nlin=1;
		$reten=0;
		$ivas=13;
		$ret=0;

		while($row = sqlsrv_fetch_array($stmt3))
		{
				$cve_art=$row['CVE_ART'];
				$ca=$row['CANTIDAD'];
				$iva=$row['IVA'];
				//$lineatmp=$row['ID'];
				$descuento=$row['DESCUENTO'];

				$query2 = "SELECT (select PRECIO from PRECIO_X_PROD01 where CVE_ART=I.CVE_ART and CVE_PRECIO in (SELECT LISTA_PREC from CLIE01 where CLAVE='".$cve_clie."')) as PRECIO,I.COSTO_PROM,I.UNI_ALT,I.TIPO_ELE from INVE01 as I WHERE I.CVE_ART='".$cve_art."'";
				$stmt2 = sqlsrv_prepare($conn, $query2);
				$result2 = sqlsrv_execute($stmt2);
				#$row = sqlsrv_fetch_array($result);	
			
				while($row = sqlsrv_fetch_array($stmt2))
				{
					$pre=$row['PRECIO'];
					$cos=$row['COSTO_PROM'];
					$uni_alt=$row['UNI_ALT'];
					$tip_ele=$row['TIPO_ELE'];

					$top= ($ca*$pre);			
			    }

			    if ($tipclie=='G' && $_SESSION['tipo_empresa']=='M' && $can_tot>100) {
					
					$reten=-1;
			    	$ret=(($top*.01)*-1);			    	
			    	
			    }
			    elseif ($tipclie=='M' && $_SESSION['tipo_empresa']=='G' && $can_tot>100) {
			    	$reten=1;
			    	$ret=($top*.01);
			    }
			    else
			    	{
			    		$reten=0;
			    		$ret=0;

			    	}

			    $sql = "insert into PAR_FACTC01 (CVE_DOC,NUM_PAR,CVE_ART,CANT,PXS,PREC,COST,IMPU1,IMPU2,IMPU3,IMPU4,IMP1APLA,IMP2APLA,IMP3APLA,IMP4APLA,TOTIMP1,TOTIMP2,TOTIMP3,TOTIMP4,DESC1,DESC2,DESC3,COMI,APAR,ACT_INV,NUM_ALM,POLIT_APLI,TIP_CAM,UNI_VENTA,TIPO_PROD,CVE_OBS,REG_SERIE,E_LTPD,TIPO_ELEM,NUM_MOV,TOT_PARTIDA,IMPRIMIR) values('".$folio."".$cvedoc."','".$nlin."','".$cve_art."','".$ca."','".$ca."','".$pre."','".$cos."','0','0','".$reten."','".$ivas."','0','0','0','1','0','0','".$ret."','".$iva."','".$descuento."','0','0','0.0005','0','N','1','','1','".$uni_alt."','".$tip_ele."','0','0','0','N','0','".$top."','S');";

				$stmt = sqlsrv_query( $conn, $sql);
				if ( $stmt ) { $something = "Submission successful.";}     
				else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
				$output=$something;

				$nlin=$nlin+1;


				$sql = "update TMP_FACTURAS set ESTADO='1', REFERENCIA='".$folio."".$cvedoc."' where CVE_ART='".$cve_art."' and CVE_CLIE='".$cve_clie."' and ESTADO=0 ";
				$stmt = sqlsrv_query( $conn2, $sql);
				if ( $stmt ) { $something = "Submission successful.";}     
				else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
				$output=$something;

				$hoy = date("Y-m-d H:i:s");		

				$sql = "insert into bitacora (usuario,fecha,accion) values('".$_SESSION['user_id']."','".$hoy."','Agrega articulo a sae ".$cve_art." del pedido ".$folio."".$cvedoc."')";
				$stmt = sqlsrv_query( $conn2, $sql);
				if ( $stmt ) { $something = "Submission successful.";}     
				else {$something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
				$output=$something;
			
	    	}

		    sqlsrv_close( $conn);
		    sqlsrv_close( $conn2);

		  	echo "<h4 align='center'>Se registo correctamente la orden ".$folio."".$cvedoc."</h4>";


		  	/*require_once("../libraries/class.phpmailer.php");
			/*$mail = new PHPMailer();

			//Luego tenemos que iniciar la validación por SMTP:
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->Host = "smtp.gmail.com"; // A RELLENAR. Aquí pondremos el SMTP a utilizar. Por ej. mail.midominio.com
			$mail->Username = "diaco@alimentosdiaco.com"; // A RELLENAR. Email de la cuenta de correo. ej.info@midominio.com La cuenta de correo debe ser creada previamente. 
			$mail->Password = "Di@co2015"; // A RELLENAR. Aqui pondremos la contraseña de la cuenta de correo
			$mail->Port = 587; // Puerto de conexión al servidor de envio. 
			$mail->From = "diaco@alimentosdiaco.com"; // A RELLENARDesde donde enviamos (Para mostrar). Puede ser el mismo que el email creado previamente.
			$mail->FromName = "ALIMENTOS DIACO"; //A RELLENAR Nombre a mostrar del remitente. 
			$mail->AddAddress("it@alimentosdiaco.com"); // Esta es la dirección a donde enviamos 
			$mail->IsHTML(true); // El correo se envía como HTML 
			$mail->Subject = "Nuevo Pedido DIACO"; // Este es el titulo del email. 
			$body = "Hemos recibido el pedid0 PWEB".$cvedoc." en nuestro sistema uno de nuestro assesores le estara brindando seguimiento"; 
			$body .= "Gracias por preferirnos"; 
			$mail->Body = $body; // Mensaje a enviar. 
			$exito = $mail->Send(); // Envía el correo.
			if($exito){ echo "El correo fue enviado correctamente"; }else{ echo "Hubo un problema. Contacta a un administrador"; }*/


			/*
			$mail = new PHPMailer(true); //New instance, with exceptions enabled
			$body = "Hemos recibido el pedid0 PWEB".$cvedoc." en nuestro sistema uno de nuestro assesores le estara brindando seguimiento"; 
			//$body             = preg_replace('/\\\\/','', $body); //Strip backslashes
			$mail->IsSMTP();                           // tell the class to use SMTP
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->Port       = 587;                    // set the SMTP server port
			$mail->Host       = "smtp.gmail.com"; // SMTP server
			$mail->Username   = "diaco@alimentosdiaco.com";     // SMTP server username
			$mail->Password   = "Di@co2015";            // SMTP server password
			$mail->IsSendmail();  // tell the class to use Sendmail
			$mail->AddReplyTo("");
			$mail->From       = "diaco@alimentosdiaco.com";
			$mail->FromName   = "ALIMENTOS DIACO";
			$to = "it@alimentosdiaco.com";
			$mail->AddAddress($to);
			$mail->Subject  = "First PHPMailer Message";
			$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
			$mail->WordWrap   = 80; // set word wrap
			$mail->MsgHTML($body);
			$mail->IsHTML(true); // send as HTML
			$mail->Send();
			echo 'Message has been sent.'; 
			*/


		  	/*$destinatario = "it@alimentosdiaco.com"; 
			$asunto = "Este mensaje es de prueba"; 
			$cuerpo ="Hemos recibido el pedid0 PWEB".$cvedoc." en nuestro sistema uno de nuestro assesores le estara brindando seguimiento";

			//para el envío en formato HTML 
			$headers = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

			//dirección del remitente 
			$headers .= "From: Miguel Angel Alvarez <diaco@alimentosdiaco.com>\r\n"; 

			//dirección de respuesta, si queremos que sea distinta que la del remitente 
			$headers .= "Reply-To: diaco@alimentosdiaco.com\r\n"; 

			//ruta del mensaje desde origen a destino 
			$headers .= "Return-path: diaco@alimentosdiaco.com\r\n"; 

			//direcciones que recibián copia 
			$headers .= "Cc: cesarv.222@gmail.com\r\n"; 

			//direcciones que recibirán copia oculta 
			//$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; 

			mail($destinatario,$asunto,$cuerpo,$headers); */
	}
	

	if($action == 'add_propducto_vendedor') // agrega producto a la orden desde panel vendedores
	{
		try
		{
			require_once("../config/conexionweb.php");	
			$impuestoIVA = $_SESSION['impu'];
			$producto=$_GET['cve_producto'];
			$cantidad=$_GET['cantidad'];
			$precio=$_GET['precio_venta'];
			$cliente=$_GET['cve_cliente'];
			$descripcion=$_GET['descripcion'];
			
			$matriz=$_GET['cve_matriz'];
			$nombre_cliente=$_GET['nombre_cliente'];
			
			$contribuyente=$_GET['contribuyente'];
			$descuento=$_GET['desc'];			

			$vendedor=$_GET['vendedor'];

			$bonif = $_GET['bono'];

			///
			$comentario = $_GET['comment'];
			$condicion = $_GET['condi'];

			
			$IVA=(($cantidad*$precio)-(($descuento/100)*($cantidad*$precio)))*$impuestoIVA;	

		//verificacion si es de bonificacion o no
		if(isset($bonif)==1){
			//modificacion de validacion de ingreso de articulo 
			$sqlval = "SELECT count(*)as conteo, cantidad, precio  FROM tmp_facturas where cve_art ='".ltrim($producto)."' and estado = 0 and precio = 0.00 and cve_clie = '".ltrim($cliente)."' group by cantidad";
			
			$valqry = ibase_query($conn,$sqlval);
			$rowDval = ibase_fetch_assoc($valqry);
		   
			//validacion de cantidad de productos a ingresar.
			if($rowDval['CONTEO'] >=1 && $rowDval['PRECIO']==0.00){
				$cant = $cantidad + $rowDval['CANTIDAD'];
				//$IVA=(($cant*$precio)-(($descuento/100)*($cant*$precio)))*$impuestoIVA;	

				$sql = "UPDATE TMP_FACTURAS SET CANTIDAD = $cant, IVA = 0 where cve_art ='".ltrim($producto)."' and estado = 0 and precio = 0.00 and cve_clie = '".ltrim($cliente)."' ";

			}else{
				$sql = "INSERT INTO TMP_FACTURAS 
				(CVE_ART,CVE_CLIE,N_CLIE,CVE_MAT,DESCRIPCION,PRECIO,CANTIDAD,IVA,ESTADO,CVE_VEND,CONTRIBUYENTE,DESCUENTO,N_EMPRESA,COMENTARIO,CONDICION) VALUES 
				('".ltrim($producto)."','".ltrim($cliente)."','".utf8_decode(ltrim($nombre_cliente))."','".ltrim($matriz)."','".str_replace("{","&",utf8_decode(ltrim($descripcion)))."','0','".$cantidad."','".$IVA."','0.00','".ltrim($vendedor)."','".ltrim($contribuyente)."','".ltrim($descuento)."','".$_SESSION['empre_numero']."','".$comentario."','".$condicion."');";
			}
		}
		else{
			//modificacion de validacion de ingreso de articulo 
			$sqlval = "SELECT count(*)as conteo, cantidad  FROM tmp_facturas where cve_art ='".ltrim($producto)."' and estado = 0 and precio <> 0 and cve_clie = '".ltrim($cliente)."' group by cantidad";
			
			$valqry = ibase_query($conn,$sqlval);
			$rowDval = ibase_fetch_assoc($valqry);
		   
			//validacion de cantidad de productos a ingresar.
			if($rowDval['CONTEO'] >=1){
				$cant = $cantidad + $rowDval['CANTIDAD'];
				$IVA=(($cant*$precio)-(($descuento/100)*($cant*$precio)))*$impuestoIVA;	

				$sql = "UPDATE TMP_FACTURAS SET CANTIDAD = $cant, IVA = $IVA where cve_art ='".ltrim($producto)."' and estado = 0 and precio <> 0 and cve_clie = '".ltrim($cliente)."' ";

			}else{
				$sql = "INSERT INTO TMP_FACTURAS 
				(CVE_ART,CVE_CLIE,N_CLIE,CVE_MAT,DESCRIPCION,PRECIO,CANTIDAD,IVA,ESTADO,CVE_VEND,CONTRIBUYENTE,DESCUENTO,N_EMPRESA,COMENTARIO,CONDICION) VALUES 
				('".ltrim($producto)."','".ltrim($cliente)."','".utf8_decode(ltrim($nombre_cliente))."','".ltrim($matriz)."','".str_replace("{","&",utf8_decode(ltrim($descripcion)))."','".$precio."','".$cantidad."','".$IVA."','0','".ltrim($vendedor)."','".ltrim($contribuyente)."','".ltrim($descuento)."','".$_SESSION['empre_numero']."','".$comentario."','".$condicion."');";
			}
		}
			
			
			//$stmt = ibase_execute( $conn, $sql);

			$gestor_sent = ibase_query($conn, $sql) or die(ibase_errmsg());

			/*if($stmt){ $something = "Submission successful."; }
			else{ $something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
			$output=$something;*/
			
			/* Free statement and connection resources. */    
			//ibase_free_result( $gestor_sent);
			//ibase_free_result ( resource $gestor_sent ) : bool;	
			//echo $descripcion; 
			//echo $_GET['descripcion'];
			?>			
			<table class="table table-hover table-striped table-responsive">
			 	<tr  class="info">
				<th class='text-center'>CODIGO</th>
				<th class='text-center'>CANT.</th>
				<th class='text-center'>DESCRIPCION</th>
				<th class='text-right'>PRECIO UNIT.</th>
				<th class='text-right'>PRECIO TOTAL</th>
				<th></th>
			</tr> <?php
		
			$query2 = "select 
				trim(CVE_ART) as CVE_ART,
				trim(CVE_CLIE) as CVE_CLIE,
				DESCRIPCION,
				PRECIO,
				SUM(CANTIDAD) AS CANTIDAD, 
				SUM(IVA) AS IVA,
				max(COALESCE(DESCUENTO,0)) as DESCUENTO 
				FROM TMP_FACTURAS where 
				upper(trim(CVE_CLIE))=upper('".trim($cliente)."') and trim(CVE_VEND)=trim('".$vendedor."') and ESTADO=0 
				GROUP BY trim(CVE_ART),trim(CVE_CLIE),DESCRIPCION,PRECIO;";

			
			$result2 = ibase_query($conn, $query2);
			#$row = sqlsrv_fetch_array($result);	
			$sumador_total=0;
			$sumador_total_p=0;
			$iva_total=0;
			$lineas=0;
			$tot_descuento=0;
			$pdescuento=0;
			$alimento='';
			$lalimentos='';
			while($row = ibase_fetch_object($result2))
			{
				$pro=$row->CVE_ART;
				$des=$row->DESCRIPCION;
				$pre=$row->PRECIO;
				$can=$row->CANTIDAD;
				$iv=$row->IVA;
				$pdescuento=$row->DESCUENTO;
				$sumador_total=$sumador_total+($pre*$can);
				$sumador_total_p=($pre*$can);
				$iva_total=$iva_total+$iv;
				$lineas=$lineas+1;
				$tot_descuento=$tot_descuento+(($pdescuento /100)*($pre*$can));

				$alimento= substr($pro,0,1 );
				if ($alimento=='A') { $lalimentos='A'; } ?>
				
			<tr>
				<td class='text-center'><?php echo $pro;?></td>
				<td class='text-center'><?php echo $can;?></td>
				<td><?php echo utf8_encode($des);?></td>
				<td class='text-right'><?php echo $pre;?></td>
				<td class='text-right'><?php echo $sumador_total_p;?></td>
				<input type="hidden" class="form-control" id="clie_<?php echo $pro; ?>"  value="<?php echo $cli;?>" >
				<td class='text-center'> 
					<a href="#" onclick="elimina_item('<?php echo $pro ?>',<?=$pre ?>)"><i class="glyphicon glyphicon-trash"></i></a>
				</td>
			</tr> <?php
			}
		ibase_close( $conn);
		}
		catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }  
				
		$subtotal=number_format($sumador_total,2,'.','');
		//$total_iva=($subtotal * $impuesto )/100;
		$total_iva=number_format($iva_total,2,'.','');
		$total_factura=$subtotal+$total_iva;
		?>
		<tr>
			<td class='text-right' colspan=4>SUB-TOTAL $</td>
			<td class='text-right'><?php echo number_format($subtotal,2);?>
				<input type="hidden" class="form-control" id="cantot"  value="<?php echo $subtotal;?>" >
			</td>
			<td></td>
		</tr><tr>
			<td class='text-right' colspan=4>DESCUENTO $</td>
			<td class='text-right'><?php echo number_format($tot_descuento,2);?>
				<input type="hidden" class="form-control" id="desc_tot"  value="<?php echo $tot_descuento;?>" >
			</td>
			<td></td>
		</tr><tr>
			<td class='text-right' colspan=4>IVA (<?php echo $_SESSION['impu'] *100; ?>)% <?php //echo $simbolo_moneda;?></td>
			<td class='text-right'><?php echo number_format($total_iva,2);?>
				<input type="hidden" class="form-control" id="ivatot"  value="<?php echo $total_iva;?>" >
			</td>
			<td></td>
		</tr><tr>
			<td class='text-right' colspan=4>TOTAL $ <?php //echo $simbolo_moneda;?></td>
			<td class='text-right'><?php echo number_format(($total_factura-$tot_descuento),2);?>
				<input type="hidden" class="form-control" id="importe"  value="<?php echo $total_factura;?>" >
				<input type="hidden" class="form-control" id="importe"  value="<?php echo $total_factura;?>" >
				<input type="hidden" class="form-control" id="nlin"  value="<?php echo $lineas;?>" >
			</td>
			<td></td>
		</tr><tr>
			<td></td>
			<td></td>
			<td class='text-right' ><!--<a href="#;" onclick="grabarpedido('<?php echo $pro ?>',1)"><i class="glyphicon glyphicon-ok"></i> Grabar Agregado</a>--></td>
			<td class='text-right'><a href="#;" onclick="grabarpedido('<?php echo $pro ?>',2)"><i class="glyphicon glyphicon-ok"></i> Grabar Pedido</a></td>
			<td class='text-right' ><a href="#;" onclick="elimina_pedido('<?php echo $pro ?>')"><i class="glyphicon glyphicon-remove"></i> Eliminar Pedido</a></td>
		</tr>

		</table>
		<input type="hidden" class="form-control" id="alimento"  value="<?php echo $lalimentos; ?>" >
		<?php
	}

	if($action == 'add_propducto_vendedor_varios') // agrega producto a la orden desde panel vendedores
	{
		try
		{
			require_once("../config/conexionweb.php");	
			$producto=$_GET['cve_producto'];
			$cantidad=$_GET['cantidad'];
			$precio=$_GET['precio_venta'];
			$cliente=$_GET['cve_cliente'];
			$descripcion=$_GET['descripcion'];
			
			$matriz=$_GET['cve_matriz'];
			$nombre_cliente=$_GET['nombre_cliente'];
			
			$contribuyente=$_GET['contribuyente'];
			$descuento=$_GET['desc'];			

			$vendedor=$_GET['vendedor'];
			
			$IVA=(($cantidad*$precio)-(($descuento/100)*($cantidad*$precio)))*$impuestoIVA;	

			$sql = "INSERT INTO TMP_FACTURAS (CVE_ART,CVE_CLIE,N_CLIE,CVE_MAT,DESCRIPCION,PRECIO,CANTIDAD,IVA,ESTADO,CVE_VEND,CONTRIBUYENTE,DESCUENTO) VALUES ('".ltrim($producto)."','".ltrim($cliente)."','".utf8_decode(ltrim($nombre_cliente))."','".ltrim($matriz)."','".str_replace("{","&",utf8_decode(ltrim($descripcion)))."','".$precio."','".$cantidad."','".$IVA."','0','".ltrim($vendedor)."','".ltrim($contribuyente)."','".ltrim($descuento)."');";
			$stmt = sqlsrv_query( $conn, $sql);

			if($stmt){ $something = "Submission successful."; }
			else{ $something = "Submission unsuccessful."; die( print_r( sqlsrv_errors(), true)); }
			$output=$something;
			
			/* Free statement and connection resources. */    
			sqlsrv_free_stmt( $stmt);
			//echo $descripcion; 
			//echo $_GET['descripcion'];
			?>			
			<table class="table table-hover table-striped table-responsive">
			 	<tr  class="info">
				<th class='text-center'>CODIGO</th>
				<th class='text-center'>CANT.</th>
				<th class='text-center'>DESCRIPCION</th>
				<th class='text-right'>PRECIO UNIT.</th>
				<th class='text-right'>PRECIO TOTAL</th>
				<th></th>
			</tr> <?php
		
			$query2 = "select ltrim(CVE_ART) as CVE_ART,ltrim(CVE_CLIE) as CVE_CLIE,DESCRIPCION,PRECIO,SUM(CANTIDAD) AS CANTIDAD, SUM(IVA) AS IVA,max(isnull(DESCUENTO,0)) as DESCUENTO FROM TMP_FACTURAS where ltrim(CVE_CLIE)='".ltrim($cliente)."' and CVE_VEND='".$vendedor."' and ESTADO=0 GROUP BY ltrim(CVE_ART),ltrim(CVE_CLIE),DESCRIPCION,PRECIO;";

			$stmt2 = sqlsrv_prepare($conn, $query2);
			$result2 = sqlsrv_execute($stmt2);
			#$row = sqlsrv_fetch_array($result);	
			$sumador_total=0;
			$sumador_total_p=0;
			$iva_total=0;
			$lineas=0;
			$tot_descuento=0;
			$pdescuento=0;
			$alimento='';
			$lalimentos='';
			while($row = sqlsrv_fetch_array($stmt2))
			{
				$pro=$row['CVE_ART'];
				$cli=$row['CVE_CLIE'];
				$des=$row['DESCRIPCION'];
				$pre=$row['PRECIO'];
				$can=$row['CANTIDAD'];
				$iv=$row['IVA'];
				$pdescuento=$row['DESCUENTO'];
				$sumador_total=$sumador_total+($pre*$can);
				$sumador_total_p=($pre*$can);
				$iva_total=$iva_total+$iv;
				$lineas=$lineas+1;
				$tot_descuento=$tot_descuento+(($pdescuento /100)*($pre*$can));

				$alimento= substr($pro,0,1 );
				if ($alimento=='A') { $lalimentos='A'; } ?>
				
			<tr>
				<td class='text-center'><?php echo $pro;?></td>
				<td class='text-center'><?php echo $can;?></td>
				<td><?php echo utf8_encode($des);?></td>
				<td class='text-right'><?php echo $pre;?></td>
				<td class='text-right'><?php echo $sumador_total_p;?></td>
				<input type="hidden" class="form-control" id="clie_<?php echo $pro; ?>"  value="<?php echo $cli;?>" >
				<td class='text-center'>
				<a href="#" onclick="elimina_item('<?php echo $pro ?>',<?=$pre ?>)"><i class="glyphicon glyphicon-trash"></i></a>
				</td>
			</tr> <?php
			}
		sqlsrv_close( $conn);
		}
		catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }  
				
		$subtotal=number_format($sumador_total,2,'.','');
		//$total_iva=($subtotal * $impuesto )/100;
		$total_iva=number_format($iva_total,2,'.','');
		$total_factura=$subtotal+$total_iva;
		?>
		<tr>
			<td class='text-right' colspan=4>SUB-TOTAL $</td>
			<td class='text-right'><?php echo number_format($subtotal,2);?>
				<input type="hidden" class="form-control" id="cantot"  value="<?php echo $subtotal;?>" >
			</td>
			<td></td>
		</tr><tr>
			<td class='text-right' colspan=4>DESCUENTO $</td>
			<td class='text-right'><?php echo number_format($tot_descuento,2);?>
				<input type="hidden" class="form-control" id="desc_tot"  value="<?php echo $tot_descuento;?>" >
			</td>
			<td></td>
		</tr><tr>
			<td class='text-right' colspan=4>IVA (13)% <?php //echo $simbolo_moneda;?></td>
			<td class='text-right'><?php echo number_format($total_iva,2);?>
				<input type="hidden" class="form-control" id="ivatot"  value="<?php echo $total_iva;?>" >
			</td>
			<td></td>
		</tr><tr>
			<td class='text-right' colspan=4>TOTAL $ <?php //echo $simbolo_moneda;?></td>
			<td class='text-right'><?php echo number_format(($total_factura-$tot_descuento),2);?>
				<input type="hidden" class="form-control" id="importe"  value="<?php echo $total_factura;?>" >
				<input type="hidden" class="form-control" id="importe"  value="<?php echo $total_factura;?>" >
				<input type="hidden" class="form-control" id="nlin"  value="<?php echo $lineas;?>" >
			</td>
			<td></td>
		</tr><tr>
			<td></td>
			<td></td>
			<td class='text-right' ></td>
			<td class='text-right'><a href="#;" onclick="grabarpedido_varios('<?php echo $pro ?>',2)"><i class="glyphicon glyphicon-ok"></i> Grabar Cotización</a></td>
			<td class='text-right' ><a href="#;" onclick="elimina_cotizacion('<?php echo $pro ?>')"><i class="glyphicon glyphicon-remove"></i> Eliminar Cotización</a></td>
		</tr>

		</table>
		<input type="hidden" class="form-control" id="alimento"  value="<?php echo $lalimentos; ?>" >
		<?php
	}

	if($action == 'carga_articulos') //carga al inicio del pedido
	{
		require_once("../config/conexionweb.php"); 
		try
		{			
			$query2 = "select 
			TRIM(CVE_ART) as CVE_ART,
			TRIM(CVE_CLIE) as CVE_CLIE,
			DESCRIPCION,
			PRECIO,
			SUM(CANTIDAD) AS CANTIDAD, 
			SUM(IVA) AS IVA,
			max(COALESCE(DESCUENTO,0)) as DESCUENTO 
			FROM TMP_FACTURAS 
			where TRIM(CVE_CLIE)='".ltrim($_GET['codc'])."' and ESTADO=0 
			GROUP BY TRIM(CVE_ART),TRIM(CVE_CLIE),DESCRIPCION,PRECIO";
			
			$result2 = ibase_query($conn, $query2);
			
			$sumador_total=0;
			$sumador_total_p=0;
			$iva_total=0;
			$lineas=0;
			$tot_descuento=0;
			$pdescuento=0;
			$alimento='';
			$lalimentos='';

			$count = 0;
			while ($row[$count] = ibase_fetch_assoc($result2))
			{
			    $count++;
			}

			if ($count > 0)
			{	?>		
				<table class="table table-hover table-striped table-responsive">
				 	<tr  class="info">
						<th class='text-center'>CODIGO</th>
						<th class='text-center'>CANT.</th>
						<th class='text-center'>DESCRIPCION</th>
						<th class='text-right'>PRECIO UNIT.</th>
						<th class='text-right'>PRECIO TOTAL</th>
						<th></th>
					</tr> <?php

					$result2 = ibase_query($conn, $query2);

					while($row = ibase_fetch_object($result2))
					{
						$pro=$row->CVE_ART;
						$cli=$row->CVE_CLIE;
						$des=$row->DESCRIPCION;
						$pre=$row->PRECIO;
						$can=$row->CANTIDAD;
						$iv=$row->IVA;
						$pdescuento=$row->DESCUENTO;
						$sumador_total=$sumador_total+($pre*$can);
						$sumador_total_p=($pre*$can);
						$iva_total=$iva_total+$iv;
						$lineas=$lineas+1;

						$tot_descuento=$tot_descuento+(($pdescuento /100)*($pre*$can));

						$alimento= substr($pro,0,1 );				
						if ($alimento=='A') { $lalimentos='A'; } ?>
						
						<tr>
							<td class='text-center'><?php echo $pro;?></td>
							<td class='text-center'><?php echo $can;?></td>
							<td><?php echo utf8_encode($des);?></td>
							<td class='text-right'><?php echo $pre;?></td>
							<td class='text-right'><?php echo $sumador_total_p;?></td>
							<input type="hidden" class="form-control" id="clie_<?php echo $pro; ?>"  value="<?php echo $cli;?>" >					
							<td class='text-center'>
							<a href="#" onclick="elimina_item('<?php echo $pro ?>',<?=$pre ?>)"><i class="glyphicon glyphicon-trash"></i></a>
							</td>
						</tr> <?php
					}
					ibase_close($conn);
			}					
		}
		catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }  
			
			$subtotal=number_format($sumador_total,2,'.','');
			//$total_iva=($subtotal * $impuesto )/100;
			$total_iva=number_format($iva_total,2,'.','');
			$total_factura=$subtotal+$total_iva;
			?>
			<tr>
				<td class='text-right table-primary' colspan=4 >SUB-TOTAL $</td>
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
				<td class='text-right' colspan=4>IVA (<?php echo $_SESSION['impu'] *100; ?>)% <?php //echo $simbolo_moneda;?></td>
				<td class='text-right'><?php echo number_format($total_iva,2);?>
					<input type="hidden" class="form-control" id="ivatot"  value="<?php echo $total_iva;?>" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td class='text-right' colspan=4>TOTAL $ <?php //echo $simbolo_moneda;?></td>
				<td class='text-right'><?php echo number_format(($total_factura-$tot_descuento),2);?>
					<input type="hidden" class="form-control" id="importe"  value="<?php echo $total_factura;?>" >
					<input type="hidden" class="form-control" id="nlin"  value="<?php echo $lineas;?>" >
				</td>
				<td></td>
			</tr>

			<tr> <?php
				if ($total_factura>0.01) { ?>
					<td></td>
					<td></td>
					<td class='text-right' ><!--<a href="#;" onclick="grabarpedido('<?php echo $pro ?>',1)"><i class="glyphicon glyphicon-ok"></i> Grabar Agregado</a>--></td>
					<td class='text-right' ><a href="#;" onclick="grabarpedido('<?php echo $pro ?>',2)"><i class="glyphicon glyphicon-ok"></i> Grabar Pedido</a></td>
					<td class='text-right' ><a href="#;" onclick="elimina_pedido('<?php echo $pro ?>')"><i class="glyphicon glyphicon-remove"></i> Eliminar Pedido</a></td> <?php
				} ?>
			</tr>

		</table>
		<input type="hidden" class="form-control" id="alimento"  value="<?php echo $lalimentos; ?>" > <?php  
	}
 }
	catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }   
?>


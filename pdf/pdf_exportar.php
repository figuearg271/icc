<?php ob_start(); 
session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	
	 ?>


<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<style type="text/css">
		#tabla2 {
		    border-collapse:separate;
		    border:solid black 1px;
		    border-radius:6px;
		    -moz-border-radius:6px;
		}
	</style>
</head>
<body>


	<?php //include("navbar.php");
	$documento=$_GET['documento'];

	require_once ("../config/conexionsae.php");
	

    $query = "select 
    		(SELECT NOMBRE FROM VEND".$_SESSION['empre_numero']." WHERE CVE_VEND=F.CVE_VEND) AS VENDEDOR, 
    		CAST(F.FECHA_DOC AS DATE) AS FECHA, 
    		(select NOMBRE from INFCLI".$_SESSION['empre_numero']." where CVE_INFO=F.DAT_MOSTR) AS CLIENTE, 
    		(SELECT CALLE FROM INFCLI".$_SESSION['empre_numero']." WHERE CVE_INFO=F.DAT_MOSTR) AS DIRECCION 
    		from FACTC".$_SESSION['empre_numero']." as F 
    		where F.CVE_DOC='".$documento."'; ";

    //echo $query;

    $result = ibase_query($conn, $query);

    	while($row = ibase_fetch_object($result))
        { ?>
        	<table width="100%">
        		<tr>
        			<td width="23%"><img src="..\img\logo.png" ></td>
        			<td width="54%" style="text-align: center;">
        				<h5> <b> <u><?php echo $_SESSION['empre_nombre']; ?> </b></u><br>
        				<small><?php echo $_SESSION['empre_direccion']; ?></small><br>
        				<b> WWW.xxxxxxxxx.COM </b></h5>
        			</td>
        			<td width="23%">
        				<table style="font-size:10px; text-align: center;" id="tabla2">
							<tr>
								<td>COTIZACI&#211N #</td>
							</tr>
							<tr>
								<td><b><u><?php echo $documento; ?></u></b></td>
							</tr>
							<tr>
								<td><b>NIT: </b><?php echo $_SESSION['nit_direccion']; ?></td>
							</tr>
							<tr>
								<td><b>NRC: </b><?php echo $_SESSION['nrc_direccion']; ?></td>
							</tr>					
						</table>        				
        			</td>
        		</tr>

        	</table>
        	<table width="100%" style="font-size:13px;">
        		<tr>
        			<td width="80%"><b>Vendedor: </b><?php echo $row->VENDEDOR; ?></td>
        			<td width="20%"><b>Fecha: </b><?php echo $row->FECHA; ?></td>
        		</tr>
        		<tr>
        			<td width="100%"><b>Nombre: </b><?php echo $row->CLIENTE; ?></td>
        		</tr>
        		<tr>
        			<td width="100%"><b>Direccion: </b><?php echo $row->DIRECCION; ?></td>
        		</tr>
        	</table>
        	<hr width="100%">
        	 <?php 
		}?>			
			<p><h5 style="text-align: center;"><strong>Estimado cliente Esta cotizaci&#243n solo tiene v&#225lidez de 15 D&#237as</strong></h5></p>
			<hr width="100%">

			

			
				<table style="font-size:13px;" width="100%">
				 	<tr style="border-top: 1px;">
						<td width="10%"><u>C&#211DIGO</u></td>
						<td width="10%" style="text-align: center;"><u>CANT.</u></td>
						<td width="60%" style="text-align: center;"><u>DESCRIPCI&#211N</u></td>
						<td width="20%" style="text-align: center;"><u>PRECIO UNIT.</u></td>
						<td width="20%" style="text-align: center;"><u>PRECIO TOTAL</u></td>
					</tr> <?php
		
					$query2 = "SELECT 
						D.CVE_ART,
						(SELECT DESCR FROM INVE".$_SESSION['empre_numero']." WHERE CVE_ART=D.CVE_ART) AS DESCRIPCION,
						CAST(D.PREC AS DECIMAL(10,2)) AS PRECIO,
						CAST(D.CANT AS DECIMAL(10,2)) AS CANTIDAD,
						CAST(D.TOTIMP4 AS DECIMAL(10,2)) AS IVA,
						CAST(D.DESC1 AS DECIMAL(10,2)) AS DESCUENTO 
						FROM PAR_FACTC".$_SESSION['empre_numero']." AS D 
						where CVE_DOC='".$documento."';";

					
					$result2 = ibase_query($conn, $query2);
					
					$sumador_total=0;
					$sumador_total_p=0;
					$iva_total=0;
					$lineas=0;
					$tot_descuento=0;
					$pdescuento=0;
					
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
						$tot_descuento=$tot_descuento+(($pdescuento /100)*($pre*$can)); ?>
						<tr>
							<td ><?php echo $pro;?></td>
							<td style="text-align: center;"><?php echo $can;?></td>
							<td ><?php echo $des;?></td>
							<td style="text-align: center;"><?php echo $pre;?></td>
							<td style="text-align: center;"><?php echo number_format($sumador_total_p,2);?></td>							
						</tr> <?php

						$subtotal=number_format($sumador_total,2,'.','');
						//$total_iva=($subtotal * $impuesto )/100;
						$total_iva=number_format($iva_total,2,'.','');
						$total_factura=$subtotal+$total_iva;
					} ?>
					<tr>
						<td colspan="4" style="text-align: right;"><b>SUB-TOTAL $</b></td>
						<td style="text-align: center;"><b><?php echo number_format($subtotal,2);?></b></td>
					</tr><tr>
						<td colspan="4" style="text-align: right;"><b>DESCUENTO $</b></td>
						<td style="text-align: center;"><b><?php echo number_format($tot_descuento,2);?></b></td>						
					</tr><tr>
						<td colspan="4" style="text-align: right;"><b>IVA (13)% </b><?php //echo $simbolo_moneda;?></td>
						<td style="text-align: center;"><b><?php echo number_format($total_iva,2);?></b></td>						
					</tr><tr>
						<td colspan="4" style="text-align: right;"><b>TOTAL $ </b><?php //echo $simbolo_moneda;?></td>
						<td style="text-align: center;"><b><?php echo number_format(($total_factura-$tot_descuento),2);?></b></td>
					</tr>
				</table>			
					<hr width="100%">
					<p><h5 style="text-align: center;"><strong>Estamos comprometidos con la calidad de nuestros productos y servicio</strong></h5></p>
					<hr class="hr-primary" />
				


</body>
</html>

<?php

require_once("../pdf/dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
$dompdf->load_html(ob_get_clean());
$dompdf->render();
$pdf=$dompdf->output();
$filename = ''.$documento.'.pdf';
$dompdf->stream($filename, array("Attachment" => 0));
?>

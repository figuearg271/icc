<?php	 
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	
	 ?>


<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<style type="text/css">
		table {
    border-collapse:separate;
    border:solid black 1px;
    border-radius:6px;
    -moz-border-radius:6px;
}

	</style>

</head>
<body>
<br/>
<div class="container" name="muestra_cotizacion">
	<?php //include("navbar.php");
	$documento=$_GET['documento'];

	//include('is_logged.php');

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
				<div class="row">		    
			    	<div class="col-sm-2"><br/><img src="..\img\logo.png" width="105%" height="15%"></div>
			    	<div class="col-sm-7">
			    		<p>
			    			<h5 class="text text-center">
			    				<strong> <?php echo $_SESSION['empre_nombre']; ?></strong>
			    			</h5>
			    		</p>
			    		<p>
			    			<h6 class="text text-center"> <?php echo $_SESSION['empre_direccion'] ?></h6>
			    		</p>
			    		
					</div>
					<div class="col-sm-3" style="text-align: center;">
						<table width="100%" class="text-center">
							<th>COTIZACI&#211N #</th>
							<tr>
								<td><u><?php echo $documento; ?></u></td>
							</tr>
							<tr>
								<td>NIT: <?php echo $_SESSION['nit_direccion']; ?> </td>
							</tr>
							<tr>
								<td>NRC: <?php echo $_SESSION['nrc_direccion']; ?></td>
							</tr>					
						</table>				
					</div>			
				</div>
				<br>
				<form >
					<div class="row">
						<div class="form-row align-items-center col-sm-12  mx-auto">						    
						    <div class="col-sm-8">
						    	<label class="sr-only" for="inlineFormInput">Vendedor</label>						      
						    	<div class="input-group mb-2">
						        	<div class="input-group-prepend">
							        	<div class="input-group-text">Vendedor</div>
							        </div>
							    	<input type="text" class="form-control mb-0.5" id="Vendedor" value='<?php echo $row->VENDEDOR; ?>' >
							    </div>
							</div>						    
						    <div class="col-sm-3">
						      <label class="sr-only" for="inlineFormInputGroup">Fecha</label>
						      <div class="input-group mb-2">
						        <div class="input-group-prepend">
						          <div class="input-group-text">Fecha:</div>
						        </div>
						        <input type="text" class="form-control" id="fecha" value='<?php echo $row->FECHA; ?>' >
						      </div>
						    </div>
						</div>
						<div class="form-row align-items-center col-sm-12  mx-auto">						    
						    <div class="col-sm-8">
						    	<label class="sr-only" for="inlineFormInput">Nombre</label>						      
						    	<div class="input-group mb-2">
						        	<div class="input-group-prepend">
							        	<div class="input-group-text"> Nombre</div>
							        </div>
							    	<input type="text" class="form-control mb-0.5" id="nombre_cliente" value='<?php echo $row->CLIENTE; ?>' >
							    </div>
							</div>
						</div>

						<div class="form-row align-items-center col-sm-12  mx-auto">
						    
						    <div class="col-sm-11">
						    	<label class="sr-only" for="inlineFormInput">Direccion</label>
						      
						    	<div class="input-group mb-2">
						        	<div class="input-group-prepend">
							        	<div class="input-group-text"> Direccion</div>
							        </div>

							    	<input type="text" class="form-control mb-0.5" id="direccion" value='<?php echo $row->DIRECCION; ?>' >
							    </div>
							</div>
						    
						    
						</div>
					</div> 
				</form>
		<?php 


		}

		 ?>

			
			<div class="row col-sm-12 ">
				<div class="col-sm-12">
					<p><h5 class="text text-center"><strong>Estimado cliente Esta cotizaci&#243n solo tiene v&#225lidez de 15 D&#237as</strong></h5></p>
				</div>

			</div>

			<div class="table-responsive col-mx-auto">
				<table class="table table-striped table-responsive ">
				 	<tr class="info ">
						<th class='text-center col-sm-2'>C&#211DIGO</th>
						<th class='text-center col-sm-2'>CANT.</th>
						<th class='text-center col-sm-6'>DESCRIPCI&#211N</th>
						<th class='text-right col-sm-1'>PRECIO UNIT.</th>
						<th class='text-right col-sm-1'>PRECIO TOTAL</th>
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
							<td class='text-center'><?php echo $pro;?></td>
							<td class='text-center'><?php echo $can;?></td>
							<td class='text'><?php echo $des;?></td>
							<td class='text-right'><?php echo $pre;?></td>
							<td class='text-right'><?php echo number_format($sumador_total_p,2);?></td>							
						</tr> <?php

						$subtotal=number_format($sumador_total,2,'.','');
						//$total_iva=($subtotal * $impuesto )/100;
						$total_iva=number_format($iva_total,2,'.','');
						$total_factura=$subtotal+$total_iva;
					} ?>
					<tr>
						<td class='text-right' colspan=4><strong>SUB-TOTAL $</strong></td>
						<td class='text-right'><?php echo number_format($subtotal,2);?></td>
					</tr><tr>
						<td class='text-right' colspan=4><strong>DESCUENTO $</strong></td>
						<td class='text-right'><?php echo number_format($tot_descuento,2);?></td>						
					</tr><tr>
						<td class='text-right' colspan=4><strong>IVA (13)% </strong><?php //echo $simbolo_moneda;?></td>
						<td class='text-right'><?php echo number_format($total_iva,2);?></td>						
					</tr><tr>
						<td class='text-right' colspan=4><strong>TOTAL $ </strong><?php //echo $simbolo_moneda;?></td>
						<td class='text-right'><?php echo number_format(($total_factura-$tot_descuento),2);?></td>
					</tr>
				</table>
			</div>

			<div class="row col-sm-12 ">
				<div class="col-sm-12">
					<hr class="hr-primary" />
					<p><h5 class="text text-center"><strong>Estamos comprometidos con la calidad de nuestros productos y servicio</strong></h5></p>
					<hr class="hr-primary" />
				</div>

			</div>

</div>
<button type="button" class="btn btn-default" onclick="printDiv('muestra_cotizacion')">Imprimir </button>

<script type="text/javascript" src="./js/ordenes.js"></script>

</body>
</html>
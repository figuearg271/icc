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
// este escrip se ejecuta al hacer clic en clientes y buscar cliente por nombre

include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
$vendedor=trim($_SESSION['user_cvevend']);
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

if($action == 'pornombre')
{
	require_once ("../config/conexionsae.php");
	try
	{
		if ($_SESSION['user_tipo'] == 'V') 
		{
			 $query = "select
			CLAVE,
			NOMBRE,
			COALESCE(cast(SALDO as decimal (10,2)),0) as SALDO,
			COALESCE(TELEFONO,'') as TELEFONO,
			(select CAMPLIB5 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE) as RUTA
			from CLIE".$_SESSION['empre_numero']."
			where trim(CVE_VEND)='".$vendedor."' and upper(TRIM(CLAVE)||NOMBRE) like upper('%".$_GET['clie']."%')
			order by SALDO desc;";
		}
		else
		{
			$query = "select
			CLAVE,
			NOMBRE,
			COALESCE(cast(SALDO as decimal (10,2)),0) as SALDO,
			COALESCE(TELEFONO,'') as TELEFONO,
			(select CAMPLIB5 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE) as RUTA
			from CLIE".$_SESSION['empre_numero']."
			where upper(TRIM(CLAVE)||NOMBRE) like upper('%".$_GET['clie']."%')
			order by SALDO desc;";
		}
		


        $result = ibase_query($conn, $query);
        ?>
			<div class="table-responsive"> 
				<table class="table table-hover table-responsive">
				 	<tr  class="info">
				 		<th class='text-left'>Codigo</th>
				 		<th class='text-center'>Nombre</th>
                    	<th class='text-center'>Telefono</th>
                    	<th class='text-center'>Saldo</th>
                    	<th class='text-center'>Ruta</th>
                    	<th class='text-center'>Accion</th>
				 	</tr> <?php

                    while($row = ibase_fetch_object($result))
                    {
                    	$codigo=$row->CLAVE;
	                	$nombre=utf8_decode($row->NOMBRE);
	                	$saldo=$row->SALDO;
	                	$tel=$row->TELEFONO;
	                	$ruta=$row->RUTA; ?>
						<tr>
							<td class='text-left'><?php echo $codigo; ?></td>
							<td class='text-left'><?php echo $nombre; ?></td>
							<td class='text-center'><?php echo $tel; ?></td>
							<td class='text-right'><?php echo number_format ($saldo,2); ?></td>
							<td class='text-center'><?php echo $ruta; ?></td>
							<td class="text-center">
								<input type="hidden" id="saldosuc_<?php echo $codigo; ?>" value="<?php echo $saldo; ?>">
								<a href="#" class='btn btn-primary' title='Muestra desgloce de facturas' onclick="viewfacturas('<?php echo $codigo; ?>')"><i class="glyphicon glyphicon-search"></i></a>
							</td>
						</tr> <?php
                    } ?>
                </table>
            </div> <?php

        ibase_close($conn); ## cerramos la conexion
    }
        catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }
}

if($action == 'ajax')
{
	require_once ("../config/conexionsae.php");
	try
	{
		if ($_SESSION['user_tipo'] == 'V') 
		{
			 $query = "select
			CLAVE,
			trim(clave)||' '|| NOMBRE as nombre,
			COALESCE(cast(SALDO as decimal (10,2)),0) as SALDO,COALESCE(TELEFONO,'') as TELEFONO,
			(select CAMPLIB5 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE) as RUTA
			from CLIE".$_SESSION['empre_numero']."
			where TRIM(CVE_VEND)='".$vendedor."'
			order by SALDO desc;";

		}
		else
		{
			 $query = "select
			CLAVE,
			trim(clave)||' '|| NOMBRE as nombre,
			COALESCE(cast(SALDO as decimal (10,2)),0) as SALDO,COALESCE(TELEFONO,'') as TELEFONO,
			(select CAMPLIB5 from CLIE_CLIB".$_SESSION['empre_numero']." where CVE_CLIE=CLAVE) as RUTA
			from CLIE".$_SESSION['empre_numero']."
			order by SALDO desc;";

		}
		

			//echo $query;
			//die();
        $result = ibase_query($conn, $query);
        ?>
        <div class="table-responsive"> 
				<table class="table table-hover table-responsive">
			 	<tr  class="info">
			 		<th class='text-left'>Codigo</th>
			 		<th class='text-center'>Nombre</th>
	            	<th class='text-center'>Telefono</th>
	            	<th class='text-center'>Saldo</th>
	            	<th class='text-center'>Ruta</th>
	            	<th class='text-center'>Accion</th>
		 		</tr>
		 	 	</thead> <?php

	            while($row = ibase_fetch_object($result))
	            {
	            	$codigo=$row->CLAVE;
	            	$nombre=utf8_decode($row->NOMBRE);
	            	$saldo=$row->SALDO;
	            	$tel=$row->TELEFONO;
	            	$ruta=$row->RUTA; ?>

					<tr>
						<td class='text-left'><?php echo $codigo; ?></td>
						<td class='text-left'><?php echo $nombre; ?></td>
						<td class='text-center'><?php echo $tel; ?></td>
						<td class='text-right'><?php echo number_format ($saldo,2); ?></td>
						<td class='text-center'><?php echo $ruta; ?></td>
						<td class="text-center">
							<input type="hidden" id="saldosuc_<?php echo $codigo; ?>" value="<?php echo $saldo; ?>">
							<a href="#" class='btn btn-primary' title='Muestra desgloce de facturas' onclick="viewfacturas('<?php echo $codigo; ?>')"><i class="glyphicon glyphicon-search"></i></a>
						</td>
					</tr> <?php
	            } ?>
            </table>
	    </div> <?php
	    ibase_close($conn); ## cerramos la conexion
    }
    catch (Exception $e)  { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }

}
//nueva accion 
if($action == 'autorizaciones')
{
	require_once ("../config/conexionsae.php");
	try
	{
		
			 $query = "SELECT TRIM(C.CLAVE) AS CLAVE, F.CVE_DOC, C.NOMBRE, C.SALDO, F.FECHA_DOC, F.FECHA_VEN,
			 C.LIMCRED, SUBSTRING(F.CONDICION FROM 1 FOR 7) as CONDICION, F.BLOQ AS AUTORIZAR
			 FROM FACTC".$_SESSION['empre_numero']." F 
			 INNER JOIN CLIE".$_SESSION['empre_numero']." C ON C.CLAVE = F.CVE_CLPV WHERE F.BLOQ <> 'N'
			 AND F.FECHA_DOC BETWEEN cast(dateadd(day, -7, current_timestamp)as date) and cast('today' as date) and F.STATUS <> 'C' 
			 order by F.CVE_DOC DESC"; 
		

			//echo $query;
			//die();
        $result = ibase_query($conn, $query);
        ?>
        <div class="table-responsive"> 
				<table id="detailsCL" class="table table-hover table-responsive">
			 	<tr  class="info">
			 		<th class='text-left'>Codigo</th>
			 		<th class='text-center'>Nombre</th> 
	            	<th class='text-center'>Saldo</th>
	            	<th class='text-center'>Limite</th>
					<th class='text-center'>Dias</th>
					<th class='text-center'>Condicion</th>
	            	<th class='text-center'>Autorizado</th>
		 		</tr>
		 	 	</thead> <?php

	            while($row = ibase_fetch_object($result))
	            {
					$codigo= $row->CLAVE ;
					$codigo2=trim("'".$row->CLAVE."'");
	            	$nombre=utf8_decode($row->NOMBRE);
	            	$saldo=$row->SALDO;
					$documento = $row->CVE_DOC;
					$fdoc = $row->FECHA_DOC;
					$fecha_venc = $row->FECHA_VEN;
					$limite = $row->LIMCRED;
					$cond = $row->CONDICION;
					$auto = $row->AUTORIZAR; 
					
					$dias=substr($fecha_venc, 8, 2);
					$meses=substr($fecha_venc, 5, 2);
					$años=substr($fecha_venc, 0, 4);

					$date1 = new DateTime($hoy);
					$date2 = new DateTime($años."-".$meses."-".$dias);
					$diff = $date1->diff($date2);
					
					
					?>

					<tr>
						<td class='text-left'><?php echo $codigo; ?></td>
						<td class='text-left'><?php echo $nombre; ?></td> 
						<td class='text-right'>
							<?php 
							if($saldo!=0){
								echo '<a href="#" title="Ver Cliente" data-toggle="modal" data-target="#ver_detclientes" onclick="ver_detCliente('.trim($codigo2).')" >'.number_format ($saldo,2).'</a>'; 
								//echo '<a href="#"  class="details-control" >'.number_format ($saldo,2).'</a>'; 
							}

							?>
						</td>
						<td class='text-center'><?php echo $limite; ?> </td>
						<td class='text-center'><?php echo $diff->days; ?> </td>
						<td class='text-center'> <?php echo $cond; ?></td> 
						<td class="text-center">  
						<input type="hidden" id="cveclie_<?php echo trim($codigo); ?>" value="<?php echo trim($codigo) ?>">
								 <!--<input type="checkbox" id="autor" <?php if($auto=='N'){echo "checked disabled";}else{echo "";}?> value="<?php echo $documento; ?>"/>-->
								 <label class='container2'>
									 <input type='checkbox' class='docsaut_<?php echo $documento; ?>' <?php if($auto=='N'){echo "checked disabled";}else{echo "";}?> name='document' value='<?php echo $documento; ?>' onchange="autCotizacion('<?php echo $documento; ?>')">
									 <span class='checkmark'></span>
								 </label> 
						 </td>
					</tr> <?php
	            } ?>
            </table>
	    </div> <?php
	    ibase_close($conn); ## cerramos la conexion
    }
    catch (Exception $e)  { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }

}
?>

<script type="text/javascript" src="./js/clientes.js"></script>

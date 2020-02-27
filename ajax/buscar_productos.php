<?php

include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
//require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
//include("../funciones.php"); //Archivo de funciones PHP
require_once ("../config/conexionsae.php");
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';


if($action == 'catalogo'){ 

	$producto=$_GET['producto'];

	$usuarios = array();               
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $query = "select CVE_ART,DESCR,(select CAMPLIB28 FROM INVE_CLIB01 WHERE CVE_PROD=CVE_ART) as DESCR_WEB,(select CAMPLIB29 FROM INVE_CLIB01 WHERE CVE_PROD=CVE_ART) as ENTREGA from INVE01 WHERE UPPER(DESCR) LIKE UPPER('%".$producto."%') AND STATUS='A';";

    

    $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
    $result = sqlsrv_execute($stmt);
    $row_count = sqlsrv_num_rows( $stmt );

    

    if ($row_count == true)
    {
    	 ?>
		<section class="listings">
			<br/>
			<h2 align="center">Catalogo de productos</h2>
			<br/>

			<div class="wrapper">
				<ul class="properties_list"><?php

				while($row = sqlsrv_fetch_array($stmt))
	            {
					$CVE_ART=$row['CVE_ART'];
					$DESCR=utf8_encode($row['DESCR']);
					$DESCR_WEB=$row['DESCR_WEB'];
					$ENTREGA=$row['ENTREGA']; ?>

					<li>
						<a href="#">
							<img src="img/productos/<?php echo $CVE_ART; ?>.jpg" alt="" title="" class="property_img"/>
						</a>
						<!--<span class="price">$2.25</span>-->
						<div class="property_details">
							<h1>
								<a href="#"><?php echo $DESCR; ?></a>
							</h1>
							<h2><?php echo $DESCR_WEB; ?></h2>
							<h2><span class="property_size">(Tiempo de entrega: <?php echo $ENTREGA; ?>)</span></h2>
						</div>
					</li> <?php
				}

					?>
				</div>
			</section>

				<?php
		
	}
	else {

		
	} 
	
}

?>


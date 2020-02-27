<?php

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

if($action == 'busca_cliente_vendedor')
	{
		require_once('../config/conexionsae.php');
		
		
		try
		{
			$usuarios = array();               
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            
            $query = "select top 3 CLAVE,NOMBRE FROM CLIE01 where UPPER(NOMBRE) LIKE UPPER('%".$_GET['nombre']."%') and CVE_VEND='".$_GET['vendedor']."' ORDER BY CLAVE;";

            $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
            $result = sqlsrv_execute($stmt);
            $row_count = sqlsrv_num_rows( $stmt );			

			if ($row_count == true)
			{
				?> <ul id="country-list"> <?php
				while($row = sqlsrv_fetch_array($stmt))
				{
					$clave=$row['CLAVE'];
					$nombre=utf8_decode($row["NOMBRE"]);
					

					?> <li onClick="selectCountry('<?php echo $nombre; ?>');"><?php echo $nombre; ?>
						<input id="id_cliente_<?php echo $nombre; ?>" value="<?php echo $clave; ?>" type='hidden'>
						</li> 	<?php  

				}

				?> </ul> <?php 
			}
			sqlsrv_close( $conn);

		}
		catch (Exception $e)
		{
			echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
		}  
	}

if($action == 'busca_facturas_cliente_vendedor')
	{
		require_once('../config/conexionsae.php');		
		try
		{
			$usuarios = array();               
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            
            $query = "select top 5 CVE_DOC FROM FACTF01 where UPPER(CVE_DOC) LIKE UPPER('%".ltrim($_GET['nfactura'])."%') and CVE_VEND='".ltrim($_GET['vendedor'])."' and CVE_CLPV='".ltrim($_GET['idcliente'])."' ORDER BY CVE_DOC;";

            $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
            $result = sqlsrv_execute($stmt);
            $row_count = sqlsrv_num_rows( $stmt );			

			if ($row_count == true)
			{
				?> <ul id="cvedoc-list"> <?php
				while($row = sqlsrv_fetch_array($stmt))
				{
					$clave=$row['CVE_DOC'];
					
					

					?> <li onClick="selectclave('<?php echo $clave; ?>');"><?php echo $clave; ?>
						
						</li> 	<?php  

				}

				?> </ul> <?php 
			}
			sqlsrv_close( $conn);

		}
		catch (Exception $e)
		{
			echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
		}  
		


	}

	
		

?>
<script type="text/javascript" src="js/reclamos.js"></script>
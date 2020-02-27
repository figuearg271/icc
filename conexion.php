<?php
function monto($table,$vendedor,$mes,$periodo)
{
		global $con;
		if($mes==1)	{ $mess="Enero"; }
		else if($mes==2) { $mess="Febrero"; }
		else if($mes==3) { $mess="Marzo"; }
		else if($mes==4) { $mess="Abril"; }
		else if($mes==5) { $mess="Mayo"; }
		else if($mes==6) { $mess="Junio"; }
		else if($mes==7) { $mess="Julio"; }
		else if($mes==8) { $mess="Agosto"; }
		else if($mes==9) { $mess="Septiembre"; } 
		else if($mes==10) { $mess="Octubre"; }
		else if($mes==11) { $mess="Noviembre"; }
		else if($mes==12) { $mess="Diciembre"; }
		
		try 
		{
			$serverName = "127.0.0.1";
			$connectionInfo = array( "Database"=>"SAE50Empre01","UID"=>"sa", "PWD"=>"Mmsql2013");
			$con = sqlsrv_connect( $serverName, $connectionInfo );
			if( $con === false ) { die( print_r( sqlsrv_errors(), true)); }
			
			$sql = "select isnull(sum(FACTURADO),0) as IMPORTE from $table where MES='$mess' AND YEARS='$periodo' and ASSESOR='$vendedor';";
			$stmt = sqlsrv_query( $con, $sql );
			if( $stmt === false) { die( print_r( sqlsrv_errors(), true) ); }			
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) { $monto=floatval($row["IMPORTE"]); }			
			sqlsrv_free_stmt( $stmt);			
			sqlsrv_close($con);		
		} 
		
		catch (Exception $e) {  echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";	}
		return $monto;
}
		
		
function meta($table,$vendedor,$mes,$periodo)
{
		global $con;
		
		if($mes==1) { $mess="enero"; }
		else if($mes==2) { $mess="febrero"; }
		else if($mes==3) { $mess="marzo"; }
		else if($mes==4) { $mess="abril"; }
		else if($mes==5) { $mess="mayo"; }
		else if($mes==6) { $mess="junio"; }
		else if($mes==7) { $mess="julio"; }	
		else if($mes==8) { $mess="agosto"; }
		else if($mes==9) { $mess="septiembre"; }
		else if($mes==10) { $mess="octubre"; }
		else if($mes==11) { $mess="noviembre"; }
		else if($mes==12) { $mess="diciembre"; }
		
		try 
		{
			$serverName = "127.0.0.1";
			$connectionInfo = array( "Database"=>"SAE50Empre01","UID"=>"sa", "PWD"=>"Mmsql2013");
			$con = sqlsrv_connect( $serverName, $connectionInfo );
			if( $con === false ) { die( print_r( sqlsrv_errors(), true)); }			
			$sql = "select meta from $table where mes='$mess' and years='$periodo' and vendedor='$vendedor'";
			$stmt = sqlsrv_query( $con, $sql );
			if( $stmt === false) { die( print_r( sqlsrv_errors(), true) ); }
			
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) { $meta=floatval($row["meta"]); }
			
			sqlsrv_free_stmt( $stmt);			
			sqlsrv_close($con);
		} 
		
		catch (Exception $e) { echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n"; }		
		return $meta;
}


function monto_categoria($table,$vendedor,$categoria,$mes,$periodo)
	{
		global $con;
			
		try 
		{
			$serverName = "127.0.0.1";
			$connectionInfo = array( "Database"=>"SAE50Empre01","UID"=>"sa", "PWD"=>"Mmsql2013");
			$con = sqlsrv_connect( $serverName, $connectionInfo );
			if( $con === false ) { die( print_r( sqlsrv_errors(), true)); }
			
			$sql = "select sum(FACTURADO) as IMPORTE from scorediaco where MES='$mes' AND YEARS='$periodo' and ASSESOR='$vendedor' and CATEGORIA='$categoria';";
			$stmt = sqlsrv_query( $con, $sql );
			if( $stmt === false) { die( print_r( sqlsrv_errors(), true) ); }
			
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) { $monto_categoria=floatval($row["IMPORTE"]); }			
			sqlsrv_free_stmt( $stmt);			
			sqlsrv_close($con);
		} 
		
		catch (Exception $e) {  echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";	}
		return $monto_categoria;
	}

function b_categoria($table,$vendedor,$categoria,$mes,$periodo)
	{
		global $con;

		if($mes==1)	{ $mess="Enero"; }
		else if($mes==2) { $mess="Febrero"; }
		else if($mes==3) { $mess="Marzo"; }
		else if($mes==4) { $mess="Abril"; }
		else if($mes==5) { $mess="Mayo"; }
		else if($mes==6) { $mess="Junio"; }
		else if($mes==7) { $mess="Julio"; }
		else if($mes==8) { $mess="Agosto"; }
		else if($mes==9) { $mess="Septiembre"; } 
		else if($mes==10) { $mess="Octubre"; }
		else if($mes==11) { $mess="Noviembre"; }
		else if($mes==12) { $mess="Diciembre"; }
			
		try 
		{
			$serverName = "127.0.0.1";
			$connectionInfo = array( "Database"=>"SAE50Empre01","UID"=>"sa", "PWD"=>"Mmsql2013");
			$con = sqlsrv_connect( $serverName, $connectionInfo );
			if( $con === false ) { die( print_r( sqlsrv_errors(), true)); }
			
			$sql = "select isnull(sum(FACTURADO),0) as IMPORTE from scorediaco where MES='$mess' AND YEARS='$periodo' and ASSESOR='$vendedor' and CATEGORIA='$categoria';";

			
			$stmt = sqlsrv_query( $con, $sql );
			if( $stmt === false) { die( print_r( sqlsrv_errors(), true) ); }
			
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) { $b_categoria=floatval($row["IMPORTE"]); }
			
			sqlsrv_free_stmt( $stmt);			
			sqlsrv_close($con);		

		} 
		
		catch (Exception $e) {  echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";	}
		return $b_categoria;
	}

		
		
		
	
?>
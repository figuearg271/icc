<?php	
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	

	$active_clientes="";
	$active_envios="";
	$active_pedidos="";
	$active_reclamos="";
	$active_sugerencias="";	
	$title="DIACO";
	//print_r($_SESSION);
	$nombre=$_SESSION['user_name'] ; ?>



	<!DOCTYPE html>
<html lang="en">
<head>
	<?php include("head.php"); ?>
</head>	
<body >
 
	<input type="hidden" id="nvendedor" value="<?php echo $_SESSION['user_name']  ?>">

	<?php include("navbar.php");?>

	
	
</body>
</html>
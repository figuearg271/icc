<?php	
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	

	$active_clientes="";
	$active_envios="";
	$active_pedidos="";
	$active_reclamos="";
	$active_sugerencias="";	
	$title="DIACO";

	$nombre=$_SESSION['user_name'] ; ?>


	<!DOCTYPE html>
<html lang="en">
<head>
	<?php include("head.php"); ?>
</head>	
<body>
<?php include("navbar.php");?>
<br/>
	<div class="container">
		<div class="panel panel-primary">
		<div class="panel-heading">
		    <div class="btn-group pull-right">
				<!-- <a  href="nueva_factura.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a> -->
			</div>
			<h4 id="nclientes">Metas</h4>
		</div>

	<input type="hidden" id="nvendedor" value="<?php echo $_SESSION['user_name']  ?>">

	<?php 

	$usertipo=trim($_SESSION['user_tipo']);
if($usertipo =='V'){?>

	<div class="container" style=" height:350px; width:75%" >
	    <!-- Main component for a primary marketing message or call to action -->
	    <div class="jumbotron2">
	    	<h5 align="center">VENTAS vrs META </h5>
	    	<div class='row'>
	        	
	        	<div class='col-md-3' class="text-center">
	            	
	                <select id="periodo" onchange="drawVisualization();" class="form-control">
	                	<option value='2019' selected>Período 2019</option>
						<option value='2018'>Período 2018</option>
	                    <option value='2017'>Período 2017</option>
					</select>
	               
				</div>
			</div>
	        <hr>
	        <div id="chart_div" style="height: 345px"></div>
	        <div id="chart_div3" style="height: 345px"></div>

		</div>
	</div> <!-- /container --><br/>

<div class="container" style="margin-top: 175px;height:480px; width:75%" >
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron2"  style=" height: auto;">
    	<h5 align="center">VENTAS X CATEGORIA</h5>
    	<div class='row'>
        	
        	<div class='col-md-3' class="text-center">
            	
                <select id="periodo2" onchange="drawVisualization2();" class="form-control">
                	<option value='2019' selected>Período 2019</option>
					<option value='2018'>Período 2018</option>
					<option value='2017'>Período 2017</option>
				</select>
               
			</div>
		</div>
        <hr>
        <div id="chart_div2" style="height: 470px" ></div>
	</div>
</div> <!-- /container --><br/>	<?php
} 
else
{ ?>
	 <div class="container" align="center"> <br /> <br />
		<div class="panel panel-info">
			<img src="./img/logo.jpg" class="user-image img-responsive"/ align="center">			
		</div>	
	
	</div>

<?php }

	?>
	<br/>
	<h5 align="center">VENTAS X CATEGORIA</h5>
	<br/><br/>
</div></div>
	<?php

	/*include("mas_comprados.php"); 

	 include("footer.php"); */

	 
	 echo "<script type='text/javascript' src='js/graficos.js'></script>";
	?>
	
	
</body>
</html>
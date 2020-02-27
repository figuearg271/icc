<?php 
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){

include("conexion.php");//Contiene los datos de conexion a la base de datos
$periodo=intval($_REQUEST['periodo']);
$nvendedor=$_REQUEST['nvend'];
$txt_mes=array( "1"=>"Ene","2"=>"Feb","3"=>"Mar","4"=>"Abr","5"=>"May","6"=>"Jun",
				"7"=>"Jul",	"8"=>"Ago","9"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Dic"
			 );//Arreglo que contiene las abreviaturas de los meses del año		
 
$ventaanual []= array('Mes',"Ventas $periodo", "Metas $periodo ");//Nombre de la primer fila del grafico
for ($inicio = 1; $inicio <= 12; $inicio++) {
    $mes=$txt_mes[$inicio];//Obtengo la abreviatura del mes
	$ingresos=monto('scorediaco',$nvendedor,$inicio,$periodo);//Obtengo el  monto de las Ventas
	$metas=meta('metas_vendedores',$nvendedor,$inicio,$periodo);//Obtengo las metas
	$ventaanual []= array($mes,$ingresos,$metas);//Agrego elementos al arreglo	
	
}
echo json_encode( ($ventaanual) );//Convierto el arreglo a formato json 
}

if($action == 'ajax2'){

include("conexion.php");//Contiene los datos de conexion a la base de datos
$periodo=intval($_REQUEST['periodo']);
$nvendedor=$_REQUEST['nvend'];
//$mes_seleccionado=$_REQUEST['mes'];

$txt_mes=array( "1"=>"Ene","2"=>"Feb","3"=>"Mar","4"=>"Abr","5"=>"May","6"=>"Jun","7"=>"Jul","8"=>"Ago","9"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Dic");//Arreglo que contiene las abreviaturas de los meses del año		
 
$categorias []= array('Mes',"Bebidas","B. frutas","Carnes","C. Marinadas","C. Procion.","Descuentos","Embutidos","Lácteos","L. Suceda.","Mariscos","M.P.","P.P.","Servicios");//Nombre de la primer fila del grafico

for ($inicio = 1; $inicio <= 12; $inicio++) {
     $mes=$txt_mes[$inicio];//Obtengo la abreviatura del mes

	$bebidas=b_categoria('scorediaco',$nvendedor,"Bebidas",$inicio,$periodo);//Obtengo el  monto de las Ventas
	$b_frutas=b_categoria('scorediaco',$nvendedor,"Bebidas de frutas",$inicio,$periodo);//Obtengo el  monto de las Ventas

	$carnes=b_categoria('scorediaco',$nvendedor,"Carnes",$inicio,$periodo);//Obtengo el  monto de las Ventas
	$c_marinadas=b_categoria('scorediaco',$nvendedor,"Carnes marinadas",$inicio,$periodo);//Obtengo el  monto de las Ventas
	$c_procionadas=b_categoria('scorediaco',$nvendedor,"Carnes porcionadas",$inicio,$periodo);//Obtengo el  monto de las Ventas

	$descuentos=b_categoria('scorediaco',$nvendedor,"Descuentos",$inicio,$periodo);//Obtengo el  monto de las Ventas

	$embutidos=b_categoria('scorediaco',$nvendedor,"Embutidos",$inicio,$periodo);//Obtengo el  monto de las Ventas
	$lacteos=b_categoria('scorediaco',$nvendedor,"Lácteos",$inicio,$periodo);//Obtengo el  monto de las Ventas
	$l_sucedaneos=b_categoria('scorediaco',$nvendedor,"Lácteos sucedáneos",$inicio,$periodo);//Obtengo el  monto de las Ventas

	$mariscos=b_categoria('scorediaco',$nvendedor,"Mariscos",$inicio,$periodo);//Obtengo el  monto de las Ventas
	$mp=b_categoria('scorediaco',$nvendedor,"Materia prima",$inicio,$periodo);//Obtengo el  monto de las Ventas

	$pp=b_categoria('scorediaco',$nvendedor,"Producto en proceso",$inicio,$periodo);//Obtengo el  monto de las Ventas
	$s=b_categoria('scorediaco',$nvendedor,"Servicio",$inicio,$periodo);//Obtengo el  monto de las Ventas

	//$metas=meta('metas_vendedores',$nvendedor,$inicio,$periodo,$mes);//Obtengo las metas

	$categorias []= array($mes,$bebidas,$b_frutas,$carnes,$c_marinadas,$c_procionadas,$descuentos,$embutidos,$lacteos,$l_sucedaneos,$mariscos,$mp,$pp,$s);//Agrego elementos al arreglo
	
	
}
echo json_encode( ($categorias) );//Convierto el arreglo a formato json
}


?>
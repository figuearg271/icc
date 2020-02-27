 <?php
$host = '192.168.6.8:E:\\DesarrolloV\\WEB.FDB';

//$host = 'C:\\BD_FBP_HH\\WEB.FDB';

$conn2 = ibase_connect($host, "SYSDBA", "masterkey");
 
if (!$conn2) 
{
  echo "Acceso Denegado!";
  exit; 
}

?>
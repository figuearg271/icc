 <?php
//$host = "C:\\Program Files (x86)\\Common Files\\Aspel\\Sistemas Aspel\\SAE6.00\\Empresa".$_SESSION['empre_numero']."\\Datos\\SAE".$_SESSION['empre_version']."EMPRE".$_SESSION['empre_numero'].".FDB";

$host = "192.168.6.8:E:\\aspeldac\\Sistemas Aspel\\SAE6.00\\Empresa".$_SESSION['empre_numero']."\\Datos\\SAE".$_SESSION['empre_version']."EMPRE".$_SESSION['empre_numero'].".FDB";

$conn = ibase_connect($host, "SYSDBA", "masterkey");
 
if (!$conn) 
{
  echo "Acceso Denegado!";
  exit; 
}	
?>
<?php 
include('is_logged.php');
require_once ("../config/conexionsae.php");

extract($_POST);

$sqlcoti = "UPDATE FACTC$emp SET BLOQ = 'N' WHERE CVE_DOC = '$documento'";
$d = ibase_query($conn,$sqlcoti);
if (!$d) {
    die('Consulta no válida: ' . mysqli_connect_error());
}else{
    echo 1;
}



?>
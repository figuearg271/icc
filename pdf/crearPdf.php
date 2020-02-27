<?php
// Cargamos la librería dompdf que hemos instalado en la carpeta dompdf
session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) { header("location: inicial.php"); exit; }	
	

require_once '../pdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Introducimos HTML de prueba


$url="http://plataforma.alimentosdiaco.com/pdf/descarga_cotizacion.php?documento=".$_GET['documento']."";

//echo $url;


 $html=file_get_contents_curl($url);


 
// Instanciamos un objeto de la clase DOMPDF.
$pdf = new DOMPDF();
 
// Definimos el tamaño y orientación del papel que queremos.
$pdf->set_paper("letter", "portrait");
//$pdf->set_paper(array(0,0,104,250));
 
// Cargamos el contenido HTML.
$pdf->load_html(utf8_decode($html));
 
// Renderizamos el documento PDF.
$pdf->render();
 
// Enviamos el fichero PDF al navegador.
$pdf->stream('reportePdf.pdf');


function file_get_contents_curl($url) {
	$crl = curl_init();
	$timeout = 5;
	curl_setopt($crl, CURLOPT_URL, $url);
	curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	$ret = curl_exec($crl);
	curl_close($crl);
	return $ret;
}

?>
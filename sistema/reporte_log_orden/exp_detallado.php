<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
//$fchdsd = $_GET["fchdsd"];
//$fchhst = $_GET["fchhst"];
$fchdsd = $_GET["fchdsd"] != '' ? "'".formato_fecha($_GET["fchdsd"], false, true)."'" : 'NULL';
$fchhst = $_GET["fchhst"] != '' ? "'".formato_fecha($_GET["fchhst"], false, true)."'" : 'NULL';
$epago = $_GET["epago"];
$certificado = $_GET["certificado"];
$cerradas = $_GET["cerradas"];
$diferencias = $_GET["diferencias"];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=produccionfecha_".date('d-m-Y').".xls");

$contenido='<html>';
$contenido.='<style>';
$contenido.='td{';
$contenido.='font-family:Arial, Helvetica, Sans-Serif;';
$contenido.='font-size:11px;';
$contenido.='font-weight: normal;';
$contenido.='}';
$contenido.='th{';
$contenido.='font-family:Arial, Helvetica, Sans-Serif;';
$contenido.='font-size:11px;';
$contenido.='}';
$contenido.='</style>';
$contenido.='<body>';
$contenido.='<table border="0" width="2025px" cellpadding="0" cellspacing="1">';
$contenido.='<tr>';
$contenido.='<th >N&deg;</th>';
$contenido.='<th align="left">&nbsp;Movil</th>';
$contenido.='<th align="left">&nbsp;Localidad</th>';
$contenido.='<th align="left">&nbsp;Inspector</th>';
$contenido.='<th >N&deg; ODT</th>';
$contenido.='<th >Fecha</th>';
$contenido.='<th >&Iacute;tem</th>';
$contenido.='<th align="left">&nbsp;Descripci&oacute;n</th>';
$contenido.='<th align="right">Cantidad I.&nbsp;</th>';
$contenido.='<th align="right">Cantidad P.&nbsp;</th>';
$contenido.='<th >Unidad</th>';
$contenido.='<th align="right">Precio&nbsp;</th>';
$contenido.='<th align="right">Total I.&nbsp;</th>';
$contenido.='<th align="right">Total P.&nbsp;</th>';
$contenido.='<th align="left">&nbsp;Estado</th>';
$contenido.='<th align="left">&nbsp;Direcci&oacute;n</th>';
$contenido.='<th >50% en&nbsp;</th>';
$contenido.='<th >E.Pago</th>';
$contenido.='<th >Cerrada</th>';
$contenido.='</tr>';
echo $contenido;

$stmt = mssql_query("EXEC Orden..sp_getProduccionMoviles 0, '$contrato', $fchdsd, $fchhst, '$epago', '$certificado', '$cerradas', '$diferencias'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido='<tr>';
	$contenido.='<td align="center">'.$cont.'</td>';
	$contenido.='<td >'.htmlentities(trim($rst["strMovil"])).'</td>';
	$contenido.='<td >'.htmlentities(trim($rst["Localidad"])).'</td>';
	$contenido.='<td align="left">'.htmlentities(trim($rst["Inspector"])).'</td>';
	$contenido.='<td align="center">'.$rst["ODT"].'</td>';
	$contenido.='<td align="center">'.$rst["Fecha"].'</td>';
	$contenido.='<td align="center">'.$rst["Item"].'</td>';
	$contenido.='<td align="left">'.htmlentities(trim($rst["Detalle"])).'</td>';
	$contenido.='<td align="right">'.number_format($rst["Cantidad_I"], 2, '.', '').'</td>';
	$contenido.='<td align="right">'.number_format($rst["Cantidad_P"], 2, '.', '').'</td>';
	$contenido.='<td align="center">'.$rst["Unidad"].'</td>';
	$contenido.='<td align="right">'.number_format($rst["Precio"], 0, '', '').'</td>';
	$contenido.='<td align="right">'.number_format($rst["Total_I"], 0, '', '').'</td>';
	$contenido.='<td align="right">'.number_format($rst["Total_P"], 0, '', '').'</td>';
	$contenido.='<td align="left">'.htmlentities(trim($rst["Estado"])).'</td>';
	$contenido.='<td >'.htmlentities(trim($rst["Direccion"])).'</td>';
	$contenido.='<td align="center">'.$rst["dblCertificado"].'</td>';	$contenido.='<td align="center">'.$rst["dblEP"].'</td>';
	$contenido.='<td align="center">'.$rst["intCerrada"].'</td>';
	$contenido.='</tr>';
	echo $contenido;
}
mssql_free_result($stmt);

$contenido='</table>';
$contenido.='</body>';
$contenido.='</html>';
echo $contenido;

mssql_close($cnx);
?>
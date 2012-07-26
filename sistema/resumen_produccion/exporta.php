<?php
include '../conexion.inc.php';

//$fchdsd = $_GET["fchdsd"];
//$fchhst = $_GET["fchhst"];
$fchdsd = formato_fecha($_GET["fchdsd"], false, true);
$fchhst = formato_fecha($_GET["fchhst"], false, true);
$contrato = $_GET["contrato"];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=resumenproduccion_".date('d-m-Y').".xls");

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
$contenido.='font-weight:bold';
$contenido.='}';
$contenido.='</style>';
$contenido.='<body>';
$contenido.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$contenido.='<tr>';
$contenido.='<th ><b>N&deg;</b></th>';
$contenido.='<th align="left"><b>&nbsp;Comuna</b></th>';
$contenido.='<th align="right"><b>Recibidas&nbsp;</b></th>';
$contenido.='<th align="right"><b>Informadas&nbsp;</b></th>';
$contenido.='<th align="right"><b>No Informadas&nbsp;</b></th>';
$contenido.='<th align="right"><b>Producci&oacute;n&nbsp;</b></th>';
$contenido.='</tr>';
echo $contenido;

$stmt = mssql_query("EXEC Orden..sp_getResumenProduccion 0, '$fchdsd', '$fchhst', '$contrato'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido='<tr>';
	$contenido.='<td align="center">'.$cont.'</td>';
	$contenido.='<td >'.htmlentities(trim($rst["strDescComuna"])).'</td>';
	$contenido.='<td align="right">'.number_format($rst["dblRecibidas"], 0, '', '').'</td>';
	$contenido.='<td align="right">'.number_format($rst["dblInformadas"], 0, '', '').'</td>';
	$contenido.='<td align="right">'.number_format($rst["dblRecibidas"] - $rst["dblInformadas"], 0, '', '').'</td>';
	$contenido.='<td align="right">'.number_format($rst["dblProduccion"], 0, '', '').'</td>';
	$contenido.='</tr>';
	echo $contenido;
	$recibidas+=$rst["dblRecibidas"];
	$informadas+=$rst["dblInformadas"];
	$noinformadas+=($rst["dblRecibidas"] - $rst["dblInformadas"]);
	$produccion+=$rst["dblProduccion"];
}
mssql_free_result($stmt);
$contenido='<tr>';
$contenido.='<td colspan="2" align="right"><b>TOTALES&nbsp;</b></td>';
$contenido.='<td align="right"><b>'.number_format($recibidas, 0, '', '').'</b></td>';
$contenido.='<td align="right"><b>'.number_format($informadas, 0, '', '').'</b></td>';
$contenido.='<td align="right"><b>'.number_format($noinformadas, 0, '', '').'</b></td>';
$contenido.='<td align="right"><b>'.number_format($produccion, 0, '', '').'</b></td>';
$contenido.='</tr>';
$contenido.='</table>';
$contenido.='</body>';
$contenido.='</html>';
echo $contenido;

mssql_close($cnx);
?>
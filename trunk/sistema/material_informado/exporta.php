<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
$fchdsd = formato_fecha($_GET["fchdsd"], false, true);
$fchhst = formato_fecha($_GET["fchhst"], false, true);

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=materialinformado_".date('d-m-Y').".xls");

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
$contenido.='font-weight:bold;';
$contenido.='}';
$contenido.='</style>';
$contenido.='<body>';
$contenido.='<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$contenido.='<tr>';
$contenido.='<th width="3%">N&deg;</th>';
$contenido.='<th width="10%" align="left">&nbsp;Localizacion</th>';
$contenido.='<th width="10%" align="left">&nbsp;ITO</th>';
$contenido.='<th width="9%">N&deg;ODT</th>';
$contenido.='<th width="15%" align="left">&nbsp;Trabajo</th>';
$contenido.='<th width="7%">Fecha</th>';
$contenido.='<th width="7%">C&oacute;digo</th>';
$contenido.='<th width="15%" align="left">&nbsp;Descripci&oacute;n</th>';
$contenido.='<th width="5%">Unidad</th>';
$contenido.='<th width="7%">Cantidad</th>';
$contenido.='<th width="5%">Movil</th>';
$contenido.='<th width="2%">&nbsp;</th>';
$contenido.='<th width="5%">Precio</th>';
$contenido.='</tr>';
echo $contenido;

$stmt = mssql_query("EXEC Orden..sp_getMaterialInformado '$contrato', '$fchdsd', '$fchhst'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido='<tr>';
	$contenido.='<td align="center">'.$cont.'</td>';
	$contenido.='<td align="left">'.htmlentities(trim($rst["strLocalidad"])).'</td>';
	$contenido.='<td align="left">'.htmlentities(trim($rst["strITO"])).'</td>';
	$contenido.='<td align="center">'.$rst["strODT"].'</td>';
	$contenido.='<td align="left">'.htmlentities(trim($rst["strMotivo"])).'</td>';
	$contenido.='<td align="center">';
	$contenido.=substr(htmlentities(trim($rst["dtmInforme"])), 0, 10).'';
	$contenido.='</td>';
	$contenido.='<td align="center">'.$rst["strCodigo"].'</td>';
	$contenido.='<td align="left">'.htmlentities(trim($rst["strDescripcion"])).'</td>';
	$contenido.='<td align="center">'.$rst["strUnidad"].'</td>';
	$contenido.='<td align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'</td>';
	$contenido.='<td align="center">'.$rst["strMovil"].'</td>';
	$contenido.='<td align="right">'.number_format($rst["dblPrecio"], 0, ',', '.').'</td>';
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
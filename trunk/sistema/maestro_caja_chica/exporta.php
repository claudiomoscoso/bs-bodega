<?php
include '../conexion.inc.php';
$bodega=$_GET["bodega"];
$usuario=$_GET["usuario"];
$perfil=$_GET["perfil"];
$nivel=$_GET["nivel"];

$responsable=$_GET["responsable"];
$estado=$_GET["estado"];
$mes=$_GET["mes"];
$ano=$_GET["ano"];
$periodo=$_GET["periodo"];

$nombarch = 'caja_chica'.date('d-m-Y').'.xls';
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=".$nombarch);

$contenido='<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido.='<body >';
$contenido.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$contenido.='<tr>';
$contenido.='<td width="3%" align="center"><b>N&deg;</b></td>';
$contenido.='<td width="8%" align="center"><b>Fecha</b></td>';
$contenido.='<td width="8%" align="center"><b>N&deg;C.Chica</b></td>';
$contenido.='<td width="19%" align="left"><b>&nbsp;Bodega</b></td>';
$contenido.='<td width="15%" align="left"><b>&nbsp;Responsable</b></td>';
$contenido.='<td width="20%" align="left"><b>&nbsp;Nota</b></td>';
$contenido.='<td width="15%" align="center"><b>Estado</b></td>';
$contenido.='<td width="10%" align="right"><b>Total&nbsp;</b></td>';
$contenido.='</tr>';
$stmt = mssql_query("EXEC Bodega..sp_getCajaChica 1, NULL, NULL, '$responsable', '$estado', '$mes', $ano, $periodo", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
	$contenido.='<tr >';
	$contenido.='<td width="3%" align="center">'.$cont.'</td>';
	$contenido.='<td width="8%" align="center">'.$rst["dtmFch"].'</td>';
	$contenido.='<td width="8%" align="center">'.$rst["dblNum"].'</td>';
	$contenido.='<td width="19%" align="left">&nbsp;'.$rst["strDescBodega"].'</td>';
	$contenido.='<td width="15%" align="left">'.htmlentities(trim($rst["strNombre"])).'</td>';
	$contenido.='<td width="20%" align="left">'.htmlentities(trim($rst["strNota"])).'</td>';
	$contenido.='<td width="15%" align="center">'.$rst["strDescEstado"].'</td>';
	$contenido.='<td width="10%" align="right">'.number_format($rst["dblTotal"], 0, '', '').'&nbsp;</td>';
	$contenido.='</tr>';
}
mssql_free_result($stmt);
$contenido.='</table>';
$contenido.='</body>';
$contenido.='</html>';
print($contenido);

mssql_close($cnx);
?>
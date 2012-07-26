<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$mes = $_GET["mes"];
$ano = $_GET["ano"];
$bodega = $_GET["bodega"];
$movil = $_GET["movil"];
$ccosto = $_GET["ccosto"];

$contenido ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$contenido.='<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido.='<body marginheight="0" marginwidth="0">';
$contenido.='<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$contenido.='	<tr>';
$contenido.='		<th width="3%">N&deg;</th>';
$contenido.='		<th width="10%" align="center">&nbsp;Movil</th>';
$contenido.='		<th width="10%">N&uacute;mero</th>';
$contenido.='		<th width="8%">Fecha</th>';
$contenido.='		<th width="10%">C&oacute;digo</th>';
$contenido.='		<th width="20%" align="left">&nbsp;Descripci&oacute;n</th>';
$contenido.='		<th width="7%">Unidad</th>';
$contenido.='		<th width="10%" align="right">Cantidad&nbsp;</th>';
$contenido.='		<th width="10%" align="right">Precio&nbsp;</th>';
$contenido.='		<th width="10%" align="right">Total&nbsp;</th>';
$contenido.='	</tr>';
$stmt = mssql_query("EXEC Bodega..sp_getMaterialCentroCosto $modulo, '$mes', '$ano', '$bodega', '$movil', '$ccosto'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
$contenido.='	<tr bgcolor="'.($cont%2==0 ? '#EBF1FF' : '#FFFFFF').'">';
$contenido.='		<td width="3%" align="center">'.$cont.'</td>';
$contenido.='		<td width="10%" align="center">'.($rst["strMovil"] != '' ? $rst["strMovil"] : 'N/A').'</td>';
$contenido.='		<td width="10%" align="center">'.$rst["dblNumero"].'</td>';
$contenido.='		<td width="8%" align="center">'.$rst["dtmFch"].'</td>';
$contenido.='		<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
$contenido.='		<td width="20%" align="left">'.htmlentities(trim($rst["strDescripcion"])).'</td>';
$contenido.='		<td width="7%" align="center">'.$rst["strUnidad"].'</td>';
$contenido.='		<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, '.', '').'</td>';
$contenido.='		<td width="10%" align="right">'.number_format($rst["dblPrecio"], 0, '', '').'</td>';
$contenido.='		<td width="10%" align="right">'.number_format($rst["dblTotal"], 0, '', '').'</td>';
$contenido.='	</tr>';
}
mssql_free_result($stmt);
$contenido.='</table>';
$contenido.='</body>';
$contenido.='</html>';

$nombarch = 'material_ccosto'.date('d-m-Y').'.xls';
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=".$nombarch."");
print($contenido);

mssql_close($cnx);
?>
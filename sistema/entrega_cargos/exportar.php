<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$finicio = $_GET["finicio"] != '' ? "'".formato_fecha($_GET["finicio"], false, true)."'" : 'NULL';
$ftermino = $_GET["ftermino"] != '' ? "'".formato_fecha($_GET["ftermino"], false, true)."'" : 'NULL';

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=entrega_cargos_".date('d-m-Y').".xls");

$contenido = '<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido .= '<body>';
$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$contenido .= '<tr>';
$contenido .= '<th width="3%">N&deg;</th>';
$contenido .= '<th width="10%">C&oacute;digo</th>';
$contenido .= '<th width="35%" align="left">&nbsp;Descripci&oacute;n</th>';
$contenido .= '<th width="10%">Unidad</th>';
$contenido .= '<th width="10%" align="right">Cantidad I.&nbsp;</th>';
$contenido .= '<th width="10%" align="right">Cantidad S.&nbsp;</th>';
$contenido .= '<th width="10%" align="right">Precio&nbsp;</th>';
$contenido .= '<th width="10%">Fecha</th>';
$contenido .= '<th width="10%">Nombre</th>';
$contenido .= '</tr>';

$stmt = mssql_query("EXEC Bodega..sp_getEntregaCargos 0, '$bodega', $finicio, $ftermino", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido .= '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	$contenido .= '<td width="3%" align="center">'.$cont.'</td>';
	$contenido .= '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
	$contenido .= '<td width="35%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
	$contenido .= '<td width="10%" align="center">'.$rst["strUnidad"].'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblIngreso"], 2, ',', '.').'&nbsp;</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblSalida"], 2, ',', '.').'&nbsp;</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblValor"], 0, '', '.').'&nbsp;</td>';
	$contenido .= '<td width="10%" align="center">'.$rst["dtmFch"].'</td>';
	$contenido .= '<td width="10%" align="left">'.$rst["strNombre"].'</td>';
	$contenido .= '</tr>';
}
mssql_free_result($stmt);

$contenido .= '</table>';
$contenido .= '</body>';
echo $contenido .= '</html>';
?>

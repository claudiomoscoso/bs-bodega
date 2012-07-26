<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];

$nombarch = 'envio_otrabajo'.date('d-m-Y').'.xls';
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=".$nombarch);

$contenido .= '<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido .= '<body>';
$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$contenido .= '<th width="3%">N&deg;</th>';
$contenido .= '<th width="9%">Orden</th>';
$contenido .= '<th width="15%">Fecha</th>';
$contenido .= '<th width="20%" align="left">&nbsp;Direcci&oacute;n</th>';
$contenido .= '<th width="20%" align="left">&nbsp;Comuna</th>';
$contenido .= '<th width="20%" align="left">&nbsp;Inspector</th>';
$contenido .= '<th width="9%" align="right">Total&nbsp;</th>';
$contenido .= '<th width="9%" align="center">E.Pago</th>';
$contenido .= '<th width="9%" align="center">N&deg;Envio</th>';
$chkall = 0;
$stmt = mssql_query("EXEC Orden..sp_getTMPEnvioOrdenTrabajo 1, '$usuario'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido .= '<tr style="background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	$contenido .= '<td width="3%" align="center">'.$cont.'</td>';
	$contenido .= '<td width="9%" align="center">'.trim($rst["strOrden"]).'</td>';
	$contenido .= '<td width="15%" align="center">'.formato_fecha($rst["dtmOrden"], true, false).'</td>';
	$contenido .= '<td width="20%" align="left">'.htmlentities(trim($rst["strDireccion"])).'</td>';
	$contenido .= '<td width="20%" align="left">'.htmlentities(trim($rst["strComuna"])).'</td>';
	$contenido .= '<td width="20%" align="left">'.htmlentities(trim($rst["strInspector"])).'</td>';
	$contenido .= '<td width="9%" align="right">'.number_format($rst["dblTotal"], 0, '', '').'&nbsp;</td>';
	$contenido .= '<td width="9%" align="right">'.$rst["dblEPago"].'&nbsp;</td>';
	$contenido .= '<td width="9%" align="right">'.$rst["dblNEnvio"].'&nbsp;</td>';
	$contenido .= '</tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
$contenido .= '</table>';
$contenido .= '</body>';
echo $contenido .= '</html>';
?>
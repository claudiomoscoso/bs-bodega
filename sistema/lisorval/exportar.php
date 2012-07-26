<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
$finicio = $_GET["finicio"] != '' ? "'".formato_fecha($_GET["finicio"], false, true)."'" : 'NULL';
$ftermino = $_GET["ftermino"] != '' ? "'".formato_fecha($_GET["ftermino"], false, true)."'" : 'NULL';
$epago = $_GET["epago"] != '' ? $_GET["epago"] : 'NULL';
$certificado = $_GET["certificado"] != '' ? $_GET["certificado"] : 'NULL';
$movil = $_GET["movil"] != '' ? "'".$_GET["movil"]."'" : 'NULL';
$cerradas = $_GET["cerradas"];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=lisorval_".date('d-m-Y').".xls");

$contenido .= '<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido .= '<body >';
$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$contenido .= '<tr>';
$contenido .= '<th width="10%">N&deg;Orden</th>';
$contenido .= '<th width="10%">Fax/ODS</th>';
$contenido .= '<th width="15%">Fecha</th>';
$contenido .= '<th width="43%" align="left" colspan="2">&nbsp;Direcci&oacute;n</th>';
$contenido .= '<th width="20%" align="left" colspan="2">&nbsp;Sector/Localidad</th>';
$contenido .= '<th width="2%">&nbsp;</th>';
$contenido .= '</tr>';
$contenido .= '</table>';
$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$contenido .= '<tr>';
$contenido .= '<th width="10%" style="border-bottom:solid 1px">Item</th>';
$contenido .= '<th width="38%" align="left" style="border-bottom:solid 1px">&nbsp;Descripci&oacute;n</th>';
$contenido .= '<th width="10%" style="border-bottom:solid 1px">Unidad</th>';
$contenido .= '<th width="10%" align="right" style="border-bottom:solid 1px">Precio&nbsp;</th>';
$contenido .= '<th width="10%" align="right" style="border-bottom:solid 1px">C.Informada&nbsp;</th>';
$contenido .= '<th width="10%" align="right" style="border-bottom:solid 1px">C.Pagada&nbsp;</th>';
$contenido .= '<th width="10%" align="right" style="border-bottom:solid 1px">T.Informado&nbsp;</th>';
$contenido .= '<th width="10%" align="right" style="border-bottom:solid 1px">T.Pagado&nbsp;</th>';
$contenido .= '<th width="10%" style="border-bottom:solid 1px" >Movil</th>';
$contenido .= '<th width="2%">&nbsp;</th>';
$contenido .= '</tr>';
$contenido .= '</table>';
$stmt = mssql_query("EXEC Orden..sp_getOrdenTrabajo 6, '', '$contrato', $movil, NULL, NULL, NULL, NULL, NULL, $epago, $finicio, $ftermino, $certificado, $cerradas", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	if($orden != $rst["strOrden"]){
		if($orden != ''){
			$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
			$contenido .= '<tr>';
			$contenido .= '<td width="70%" align="right" style="border-top:solid 1px" colspan="6"><b>TOTAL:</b></td>';
			$contenido .= '<td width="10%" align="right" style="border-top:solid 1px"><b>'.number_format($tinformada, 0, '', '').'&nbsp;</b></td>';
			$contenido .= '<td width="10%" align="right" style="border-top:solid 1px"><b>'.number_format($tpagada, 0, '', '').'&nbsp;</b></td>';
			$contenido .= '<td width="10%" style="border-top:solid 1px">&nbsp;</td>';
			$contenido .= '</tr>';
			$contenido .= '</table>';
			$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>';
		}
		$tinformada = 0;
		$tpagada = 0;
		$orden = $rst["strOrden"];
		$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
		$contenido .= '<tr >';
		$contenido .= '<td width="10%" align="center"><b>'.$rst["strOrden"].'</b></td>';
		$contenido .= '<td width="10%" align="center"><b>'.$rst["strODS"].'</b></td>';
		$contenido .= '<td width="15%" align="center"><b>'.formato_fecha($rst["dtmOrden"], true, false).'</b></td>';
		$contenido .= '<td width="43%" align="left" colspan="2"><b>&nbsp;'.$rst["strDireccion"].'</b></td>';
		$contenido .= '<td width="20%" align="left" colspan="2"><b>&nbsp;'.$rst["strSector"].'</b></td>';
		$contenido .= '</tr>';
		$contenido .= '</table>';
	}
	$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
	$contenido .= '<tr>';
	$contenido .= '<td width="10%" align="center">'.$rst["strItem"].'</td>';
	$contenido .= '<td width="38%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
	$contenido .= '<td width="10%" align="center">'.$rst["strUnidad"].'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblPrecio"], 0, '', '.').'&nbsp;</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, '.', '').'&nbsp;</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblCantidadEmos"], 2, '.', '').'&nbsp;</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblTInformada"], 0, '', '').'&nbsp;</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblTPagada"], 0, '', '').'&nbsp;</td>';
	$contenido .= '<td width="10%" align="center">'.$rst["strMovil"].'</td>';
	$contenido .= '</tr>';
	$contenido .= '</table>';
	$tinformada += $rst["dblTInformada"];
	$tpagada += $rst["dblTPagada"];
}
mssql_free_result($stmt);
$ln++;
$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$contenido .= '<tr>';
$contenido .= '<td width="70%" align="right" style="border-top:solid 1px" colspan="6"><b>TOTAL</b></td>';
$contenido .= '<td width="10%" align="right" style="border-top:solid 1px"><b>'.number_format($tinformada, 0, '', '').'&nbsp;</b></td>';
$contenido .= '<td width="10%" align="right" style="border-top:solid 1px"><b>'.number_format($tpagada, 0, '', '').'&nbsp;</b></td>';
$contenido .= '<td width="10%" style="border-top:solid 1px">&nbsp;</td>';
$contenido .= '</tr>';
$contenido .= '</table>';
$contenido .= '</body>';
$contenido .= '</html>';
print($contenido);

mssql_close($cnx);
?>
<?php
include '../conexion.inc.php';

$contrato = $_POST["cmbContratos"];
$finicio = $_POST["txtFInicio"] != '' ? "'".formato_fecha($_POST["txtFInicio"], false, true)."'" : 'NULL';
$ftermino = $_POST["txtFTermino"] != '' ? "'".formato_fecha($_POST["txtFTermino"], false, true)."'" : 'NULL';
$estado = $_POST["cmbEstado"];
$zona = $_POST["cmbZona"];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=volumen_obra".date('d-m-Y').".xls");

$contenido .= '<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido .= '<body>';
if($zona=='0') {
	$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
	$contenido .= '<tr bgcolor=#ffff99>';
	$contenido .= '<th width="10%">Cantidad ODT</th>';
	$contenido .= '<th width="10%">ODT Informadas</th>';
	$contenido .= '<th width="10%">Nulas</th>';
	$contenido .= '<th width="10%">Sin Informar</th>';
	$contenido .= '<th width="10%">Cobradas</th>';
	$contenido .= '<th width="10%">Anexos Pendientes</th>';
	$contenido .= '<th width="10%">ODT c/C.Atencion</th>';
	$contenido .= '<th width="10%">Total Informado</th>';
	$contenido .= '<th width="10%">Total Cobrado</th>';
	$contenido .= '<th width="10%">Total Cerrado</th>';
	$contenido .= '</tr>';
	$stmt = mssql_query("EXEC Orden..sp_getResumenObra '$contrato', $finicio, $ftermino, '$estado'", $cnx);
	while($rst = mssql_fetch_array($stmt)){
		$contenido .= '<tr bgcolor=#eeee00>';
		$contenido .= '<td width="10%" align="center">'.number_format($rst["dblN_ODT"], 0, '', '').'</td>';
		$contenido .= '<td width="10%" align="center">'.number_format($rst["dblN_Info"], 0, '', '').'</td>';
		$contenido .= '<td width="10%" align="center">'.number_format($rst["dblN_Nula"], 0, '', '').'</td>';
		$contenido .= '<td width="10%" align="center">'.number_format($rst["dblN_SinInf"], 0, '', '').'</td>';
		$contenido .= '<td width="10%" align="center">'.number_format($rst["dblN_Cobro"], 0, '', '').'</td>';
		$contenido .= '<td width="10%" align="center">'.number_format($rst["dblN_AnxPen"], 0, '', '').'</td>';
		$contenido .= '<td width="10%" align="center">'.number_format($rst["dblN_CierreAt"], 0, '', '').'</td>';
		$contenido .= '<td width="10%" align="center">'.number_format($rst["dblT_Info"], 0, '', '').'</td>';
		$contenido .= '<td width="10%" align="center">'.number_format($rst["dblT_Cobro"], 0, '', '').'</td>';
		$contenido .= '<td width="10%" align="center">'.number_format($rst["dblT_Cerrado"], 0, '', '').'</td>';
		$contenido .= '</tr>';
		$contenido .= '<tr bgcolor=#FFFFFF><td>&nbsp;</td></tr>';
	}
	mssql_free_result($stmt);

	$contenido .= '</table>';
}
$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$contenido .= '<tr>';
if($zona=='0'){
	$contenido .= '<th width="3%">N&deg;</th>';
	$contenido .= '<th width="10%">Item</th>';
	$contenido .= '<th width="35%" align="left">&nbsp;Descripci&oacute;n</th>';
	$contenido .= '<th width="10%">Unidad</th>';
	$contenido .= '<th width="10%" align="right">Cantidad I.</th>';
	$contenido .= '<th width="10%" align="right">Cantidad P.</th>';
	$contenido .= '<th width="10%" align="right">Total I.</th>';
	$contenido .= '<th width="10%" align="right">Total P.</th>';
}
else {
	$contenido .= '<th width="2%">N&deg;</th>';
	$contenido .= '<th width="3%">Item</th>';
	$contenido .= '<th width="12%" align="left">&nbsp;Descripci&oacute;n</th>';
	$contenido .= '<th width="3%">Unidad</th>';
	$contenido .= '<th width="5%" align="right">Informado&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Infor.Z1&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Infor.Z2&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Infor.Z3&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Cobrado&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Cobrado Z1&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Cobrado Z2&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Cobrado Z3&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Total Inf.&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Total I.Z1&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Total I.Z2&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Total I.Z3&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Total Cobrado.&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Total C.Z1&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Total C.Z2&nbsp;</th>';
	$contenido .= '<th width="5%" align="right">Total C.Z3&nbsp;</th>';
}
$contenido .= '<th width="2%">&nbsp;</th>';
$contenido .= '</tr>';

if($zona=='0') {
	$stmt = mssql_query("EXEC Orden..sp_getDetalleInformeOrden 2, '$contrato', NULL, $finicio, $ftermino, '$estado'", $cnx);
	while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido .= '<tr bgcolor='.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'>';
	$contenido .= '<td width="3%" align="center">'.$cont.'</td>';
	$contenido .= '<td width="10%" align="center">'.trim($rst["strItem"]).'</td>';
	$contenido .= '<td width="35%" align="left">'.trim($rst["strDescripcion"]).'</td>';
	$contenido .= '<td width="10%" align="center">'.trim($rst["strUnidad"]).'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblCantidadEmos"], 2, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblTInformado"], 0, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["dblTPagado"], 0, ',', '').'</td>';
	$contenido .= '</tr>';
	}
} else {
	$stmt = mssql_query("EXEC Orden..sp_getDetalleInformeOrden 3, '$contrato', NULL, $finicio, $ftermino, '$estado'", $cnx);
	while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido .= '<tr bgcolor='.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'>';
	$contenido .= '<td width="3%" align="center">'.$cont.'</td>';
	$contenido .= '<td width="10%" align="center">'.trim($rst["strItem"]).'</td>';
	$contenido .= '<td width="35%" align="left">'.trim($rst["strDescripcion"]).'</td>';
	$contenido .= '<td width="10%" align="center">'.trim($rst["strUnidad"]).'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["INFO"], 2, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["INFOZ1"], 2, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["INFOZ2"], 2, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["INFOZ3"], 2, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["COBRO"], 2, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["COBROZ1"], 2, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["COBROZ2"], 2, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["COBROZ3"], 2, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["TINFO"], 0, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["TINFOZ1"], 0, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["TINFOZ2"], 0, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["TINFOZ3"], 0, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["TCOBRO"], 0, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["TCOBROZ1"], 0, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["TCOBROZ2"], 0, ',', '').'</td>';
	$contenido .= '<td width="10%" align="right">'.number_format($rst["TCOBROZ3"], 0, ',', '').'</td>';
	$contenido .= '</tr>';
	}
}
mssql_free_result($stmt);

$contenido .= '</table>';
$contenido .= '</body>';
echo $contenido .= '</html>';

mssql_close($cnx);
?>

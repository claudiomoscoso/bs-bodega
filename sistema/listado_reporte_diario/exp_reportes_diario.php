<?php
include '../conexion.inc.php';

$obra = $_POST["hdnObra"];
$equipo = $_POST["hdnEquipo"];
$estado = $_POST["cmbEstado"];
$operador = $_POST["hdnOperador"];
$mes = $_POST["cmbMes"];
$ano = $_POST["cmbAno"];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=reporte_diario_".date('d-m-Y').".xls");

$contenido = '<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido .= '<body>';
$contenido .= '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$contenido .= '<tr>';
$contenido .= '<th width="3%">N&deg;</th>';
$contenido .= '<th width="7%">C.Costo</th>';
$contenido .= '<th width="15%" align="left">&nbsp;Equipo</th>';
$contenido .= '<th width="10%">Estado</th>';
$contenido .= '<th width="10%">Kms.Inicial</th>';
$contenido .= '<th width="10%">Kms.Final</th>';
$contenido .= '<th width="9%">Kms.Total</th>';
$contenido .= '<th width="7%">N&deg;Report</th>';
$contenido .= '<th width="6%">Litros</th>';
$contenido .= '<th width="15%" align="left">&nbsp;Operador</th>';
$contenido .= '<th width="6%">O.T.</th>';
$contenido .= '</tr>';
$stmt = mssql_query("EXEC Operaciones..sp_getReporteDiario 2, NULL, '$obra', '$equipo', '$estado', '$operador', '$mes', '$ano'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido .= '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	$contenido .= '<td width="3%" align="center">'.$cont.'</td>';
	$contenido .= '<td width="7%" align="center">'.$rst["strCCosto"].'</td>';
	$contenido .= '<td width="15%" >'.htmlentities($rst["strEquipo"]).'</td>';
	$contenido .= '<td width="10%" >'.htmlentities($rst["strEstado"]).'</td>';
	$contenido .= '<td width="10%" align="center">'.number_format($rst["dblOdometroInicial"], 2, '.', '').'</td>';
	$contenido .= '<td width="10%" align="center">'.number_format($rst["dblOdometroFinal"], 2, '.', '').'</td>';
	$contenido .= '<td width="9%" align="center">'.number_format($rst["dblOdometroFinal"] - $rst["dblOdometroInicial"], 2, '.', '').'</td>';
	$contenido .= '<td width="7%" align="center">'.$rst["dblNumero"].'</td>';
	$contenido .= '<td width="6%" align="center">'.number_format($rst["dblCombustible"], 2, '.', '').'</td>';
	$contenido .= '<td width="15%" align="left">'.htmlentities($rst["strNombre"]).'</td>';
	$contenido .= '<td width="6%" align="center">'.$rst["strOTrabajo"].'</td>';
	$contenido .= '</tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
$contenido .= '</table>';
$contenido .= '</body>';
echo $contenido .= '</html>';
?>
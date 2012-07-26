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
header("Content-Disposition: attachment; filename=ctrl_combustible_".date('d-m-Y').".xls");

$contenido = '<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido .= '<body >';
$contenido .= '<table border="0" cellpadding="0" cellspacing="0">';
$contenido .= '<tr>';
$contenido .= '<th width="25px">N&deg;</th>';
$contenido .= '<th width="65px">Fecha</th>';
$contenido .= '<th width="150px" align="left">&nbsp;Obra</th>';
$contenido .= '<th width="50px">C.Costo</th>';
$contenido .= '<th width="55px">Patente</th>';
$contenido .= '<th width="150px" align="left">&nbsp;Equipo</th>';
$contenido .= '<th width="150px" align="left">&nbsp;Marca</th>';
$contenido .= '<th width="150px" align="left">&nbsp;Modelo</th>';
$contenido .= '<th width="40px">A&ntilde;o</th>';
$contenido .= '<th width="150px" align="left">&nbsp;Operador</th>';
$contenido .= '<th width="90px">T.Combustible</th>';
$contenido .= '<th width="50px">Litros</th>';
$contenido .= '<th width="90px">Kms./Hrs. Inicial</th>';
$contenido .= '<th width="90px">Kms./Hrs. Final</th>';
$contenido .= '<th width="90px">Kms./Hrs. Total</th>';
$contenido .= '<th width="90px">Rend.(Kms./Lts.)</th>';
$contenido .= '</tr>';

$stmt = mssql_query("EXEC Operaciones..sp_getReporteDiario 3, NULL, '$obra', '$equipo', '$estado', '$operador', '$mes', '$ano'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido .= '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	$contenido .= '<td align="center">'.$cont.'</td>';
	$contenido .= '<td align="center">'.$rst["dtmFecha"].'</td>';
	$contenido .= '<td >'.htmlentities($rst["strObra"]).'</td>';
	$contenido .= '<td align="center">'.$rst["strCCosto"].'</td>';
	$contenido .= '<td align="center">'.$rst["strPatente"].'</td>';
	$contenido .= '<td >'.htmlentities($rst["strEquipo"]).'</td>';
	$contenido .= '<td >&nbsp;'.$rst["strMarca"].'</td>';
	$contenido .= '<td >&nbsp;'.$rst["strModelo"].'</td>';
	$contenido .= '<td align="center">'.$rst["dblAno"].'</td>';
	$contenido .= '<td >'.htmlentities($rst["strNombre"]).'</td>';
	$contenido .= '<td align="center">'.$rst["strTCombustible"].'</td>';
	$contenido .= '<td align="center">'.number_format($rst["dblCombustible"], 2, '.', '').'</td>';
	$contenido .= '<td align="center">'.number_format($rst["dblOdometroInicial"], 2, '.', '').'</td>';
	$contenido .= '<td align="center">'.number_format($rst["dblOdometroFinal"], 2, '.', '').'</td>';
	$contenido .= '<td align="center">'.number_format($rst["dblOdometroFinal"] - $rst["dblOdometroInicial"], 2, '.', '').'</td>';
	$contenido .= '<td align="center">'.@number_format(($rst["dblOdometroFinal"] - $rst["dblOdometroInicial"]) / $rst["dblCombustible"], 2, '.', '').'</td>';
	$contenido .= '</tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
$contenido .= '</table>';
$contenido .= '</body>';
echo $contenido .= '</html>';
?>

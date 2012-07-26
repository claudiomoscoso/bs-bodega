<?php
include '../conexion.inc.php';

$contrato = $_POST["cmbContratos"];
$finicio = $_POST["txtFInicio"] != '' ? "'".formato_fecha($_POST["txtFInicio"], false, true)."'" : 'NULL';
$ftermino = $_POST["txtFTermino"] != '' ? "'".formato_fecha($_POST["txtFTermino"], false, true)."'" : 'NULL';

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=Listado".date('d-m-Y').".xls");

$Total = 0;

$contenido .= '<html><body><table border=1>';
$contenido .= '<tr>';
$contenido .= '<th>N&deg;</th>';
$contenido .= '<th>Sisda</th>';
$contenido .= '<th>Cierre At.</th>';
$contenido .= '<th>ITO</th>';
$contenido .= '<th>Direccion</th>';
$contenido .= '<th>Env.Ppto</th>';
$contenido .= '<th>Memo</th>';
$contenido .= '<th>MV</th>';
$contenido .= '<th>Item</th>';
$contenido .= '<th>Uni</th>';
$contenido .= '<th>Cant</th>';
$contenido .= '<th>P.Unit</th>';
$contenido .= '<th>Valor Item</th>';
$contenido .= '<th>Total Ppto.</th>';
$contenido .= '</tr>';
$stmt = mssql_query("EXEC Orden..sp_F_AV01 $finicio, $ftermino, '$contrato'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido .= '<tr bgcolor='.($cont % 2 == 0 ? '#FFFFFF' : '#CBCBFF').'>';
	$contenido .= '<td align="center" style="vertical-align:Top">'.$cont.'</td>';
	$contenido .= '<td style="vertical-align:Top">'.trim($rst["Sisda"]).'</td>';
	$contenido .= '<td style="vertical-align:Top">'.trim($rst["CierreAt"]).'</td>';
	$contenido .= '<td style="vertical-align:Top">'.trim($rst["ITO"]).'</td>';
	$contenido .= '<td style="vertical-align:Top">'.trim($rst["Direccion"]).'</td>';
	$contenido .= '<td style="vertical-align:Top">'.trim($rst["EnvPpto"]).'</td>';
	$contenido .= '<td style="vertical-align:Top">'.trim($rst["Memo"]).'</td>';
	$contenido .= '<td style="vertical-align:Top">'.trim($rst["MV"]).'</td>';
	if(strlen($rst["Item"])>1)
		$contenido .= '<td style="vertical-align:Top"><table>'.trim($rst["Item"]).'</table></td>';
	else
		$contenido .= '<td>&nbsp;</td>';
	if(strlen($rst["Uni"])>1)
		$contenido .= '<td style="vertical-align:Top"><table>'.trim($rst["Unidad"]).'</table></td>';
	else
		$contenido .= '<td>&nbsp;</td>';
	if(strlen($rst["Cant"])>1)
		$contenido .= '<td style="vertical-align:Top"><table>'.trim($rst["Cant"]).'</table></td>';
	else
		$contenido .= '<td>&nbsp;</td>';
	if(strlen($rst["PUnit"])>1)
		$contenido .= '<td style="vertical-align:Top"><table>'.trim($rst["PUnit"]).'</table></td>';
	else
		$contenido .= '<td>&nbsp;</td>';
	if(strlen($rst["ValorItem"])>1)
		$contenido .= '<td style="vertical-align:Top"><table>'.trim($rst["ValorItem"]).'</table></td>';
	else
		$contenido .= '<td>&nbsp;</td>';
	$contenido .= '<td align="right" style="vertical-align:Top">'.number_format($rst["TotalPpto"], 0, ',', '').'</td>';
	$contenido .= '</tr>';
	$Total += $rst["TotalPpto"];
}
mssql_free_result($stmt);
$contenido .= '<tr><td colspan=13><b>TOTAL</b></td><td><b>'.number_format($Total, 0, ',', '').'</b></td>';
$contenido .= '</table></body></html>';
echo $contenido;

mssql_close($cnx);
?>


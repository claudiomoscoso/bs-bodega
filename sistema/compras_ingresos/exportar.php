<?php
include '../conexion.inc.php';

$contrato = $_POST["cmbContratos"];
$finicio = $_POST["txtFInicio"] != '' ? "'".formato_fecha($_POST["txtFInicio"], false, true)."'" : 'NULL';
$ftermino = $_POST["txtFTermino"] != '' ? "'".formato_fecha($_POST["txtFTermino"], false, true)."'" : 'NULL';

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=compras_ingresos".date('d-m-Y').".xls");

$TotalCompra = 0;
$TotalIngreso = 0;
$contenido .= '<html><body><table border=1>';
$contenido .= '<tr>';
$contenido .= '<th>N&deg;</th>';
$contenido .= '<th>Proveedor</th>';
$contenido .= '<th>O.Compra</th>';
$contenido .= '<th>Monto O.C.</th>';
$contenido .= '<th>Monto Ing.</th>';
$contenido .= '<th>NºIngreso</th>';
$contenido .= '<th>Fecha</th>';
$contenido .= '<th>Monto</th>';
$contenido .= '<th>Guia</th>';
$contenido .= '<th>Factura</th>';
$contenido .= '</tr>';
$stmt = mssql_query("EXEC Bodega..sp_getComprasVingresos '$contrato', $finicio, $ftermino", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$contenido .= '<tr bgcolor='.($cont % 2 == 0 ? '#FFFFFF' : '#CBCBFF').'>';
	$contenido .= '<td align="center" style="vertical-align:Top">'.$cont.'</td>';
	$contenido .= '<td style="vertical-align:Top">'.trim($rst["strProveedor"]).'</td>';
	$contenido .= '<td style="vertical-align:Top">'.trim($rst["dblNumero"]).'</td>';
	$contenido .= '<td align="right" style="vertical-align:Top">'.number_format($rst["dblCompra"], 0, ',', '').'</td>';
	$contenido .= '<td align="right" style="vertical-align:Top">'.number_format($rst["dblIngreso"], 0, ',', '').'</td>';
	if(strlen($rst["Ingresos"])>1)
		$contenido .= '<td><table>'.$rst["Ingresos"].'</table></td>';
	else
		$contenido .= '<td>&nbsp;</td>';
	if(strlen($rst["Fechas"])>1)
		$contenido .= '<td><table>'.$rst["Fechas"].'</table></td>';
	else
		$contenido .= '<td>&nbsp;</td>';
	if(strlen($rst["dblMonto"])>1)
		$contenido .= '<td><table>'.$rst["dblMonto"].'</table></td>';
	else
		$contenido .= '<td>&nbsp;</td>';
	if(strlen($rst["Guias"])>1)
		$contenido .= '<td><table>'.$rst["Guias"].'</table></td>';
	else
		$contenido .= '<td>&nbsp;</td>';
	if(strlen($rst["Facturas"])>1)
		$contenido .= '<td><table>'.$rst["Facturas"].'</table></td>';
	else
		$contenido .= '<td>&nbsp;</td>';
	$contenido .= '</tr>';
	$TotalCompra += $rst["dblCompra"];
	$TotalIngreso += $rst["dblIngreso"];
}
mssql_free_result($stmt);
$contenido .= '<tr><td colspan=3><b>TOTALES</b></td><td><b>'.number_format($TotalCompra, 0, ',', '').'</b></td>';
$contenido .= '<td><b>'.number_format($TotalIngreso, 0, ',', '').'</b></td></tr>';
$contenido .= '</table></body></html>';
echo $contenido;

mssql_close($cnx);
?>

<?php
include '../conexion.inc.php';

$epago = $_GET["epago"];
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=detalle_".date('d-m-Y').".xls");

echo '<html>';
echo '<body>';
echo '<table border="1" width="100%" cellpadding="0" cellspacing="0">';
echo '<tr>';
echo '<th width="3%">N&deg;</th>';
echo '<th width="10%">Fecha</th>';
echo '<th width="10%">N&deg;Recepci&oacute;n</th>';
echo '<th width="0%" align="left">&nbsp;Modulo</th>';
echo '<th width="10%">N&deg;O.Compra</th>';
echo '<th width="15%" align="left">&nbsp;T.Documento</th>';
echo '<th width="10%">N&deg;Documento</th>';
echo '<th width="10%" align="right">Impuesto&nbsp;</th>';
echo '<th width="10%" align="right">Total&nbsp;</th>';
echo '<th width="10%" align="left">&nbsp;Proveedor</th>';
echo '</tr>';
$stmt = mssql_query("EXEC Bodega..sp_getFacturas 7, NULL, NULL, $epago", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr>';
	echo '<td align="center">'.$cont.'</td>';
	echo '<td align="center">'.$rst["dtmFecha"].'</td>';
	echo '<td align="center">'.$rst["dblNumero"].'</td>';
	echo '<td align="left">&nbsp;'.$rst["strModulo"].'</td>';
	echo '<td align="center">'.$rst["dblUltima"].'</td>';
	echo '<td align="left">&nbsp;'.$rst["strTipoDoc"].'</td>';
	echo '<td align="center">'.$rst["dblNumDoc"].'</td>';
	echo '<td align="right">'.number_format($rst["dblIVA"], 2, '.', '').'</td>';
	echo '<td align="right">'.number_format($rst["dblMonto"], 0, '', '').'</td>';
	echo '<td align="left">&nbsp;'.$rst["strNombre"].'</td>';
	echo '</tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
echo '</table>';
echo '</body>';
echo '</html>';
?>
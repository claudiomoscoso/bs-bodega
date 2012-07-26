<?php
include '../conexion.inc.php';

$epago = $_GET["epago"];
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=resumen_".date('d-m-Y').".xls");

echo '<html>';
echo '<body>';
echo '<table border="1" width="100%" cellpadding="0" cellspacing="0">';
echo '<tr>';
echo '<th width="3%" align="center">N&deg;</th>';
echo '<th width="67%" >&nbsp;Descripci&oacute;n</th>';
echo '<th width="20%">&nbsp;Parcial</th>';
echo '<th width="10%" align="right">Total&nbsp;</th>';
echo '</tr>';
$stmt = mssql_query("EXEC Bodega..sp_getFacturas 6, NULL, NULL, $epago", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr>';
	echo '<td width="3%" align="center">'.$cont.'</td>';
	echo '<td width="67%" >&nbsp;'.$rst["strDetalle"].'</td>';
	echo '<td width="20%">&nbsp;</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblTotal"], 0, '', '').'</td>';
	echo '</tr>';
	$total += $rst["dblTotal"];
}
mssql_free_result($stmt);
mssql_close($cnx);
echo '<tr style="font-weight:bold">';
echo '<td colspan="3" align="right">TOTAL NETO </td>';
echo '<td align="right">'.number_format($total, 0, '', '').'</td>';
echo '</tr>';
echo '<tr style="font-weight:bold">';
echo '<td colspan="3" align="right">I.V.A. 19% </td>';
echo '<td align="right">'.number_format($total * 0.19, 0, '', '').'</td>';
echo '</tr>';
echo '<tr style="font-weight:bold">';
echo '<td colspan="3" align="right">TOTAL GENERAL </td>';
echo '<td align="right">'.number_format($total * 1.19, 0, '', '').'</td>';
echo '</tr>';
echo '</table>';
echo '</body>';
echo '</html>';
?>
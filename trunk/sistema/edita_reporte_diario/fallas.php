<?php
include '../conexion.inc.php';

$ccosto = $_GET["ccosto"];

echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$stmt = mssql_query("SELECT strDescripcion, CONVERT(VARCHAR, dtmFecha, 103) AS dtmFch FROM Operaciones..ReporteFalla WHERE strCCosto = '$ccosto' ORDER BY dtmFecha DESC", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'">';
		echo '<td width="90%">&nbsp;'.$rst["strDescripcion"].'</td>';
		echo '<td width="10%">'.$rst["dtmFch"].'</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
echo '</table>';
mssql_free_result($stmt);
mssql_close($cnx);
?>
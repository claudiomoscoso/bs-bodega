<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$cargo = $_GET["cargo"];
$material = $_GET["material"];
echo '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$stmt = mssql_query("EXEC Bodega..sp_getDevolucionesPendientes 4, NULL, '$bodega', '$cargo', '$material'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
		echo '<td width="65%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
		echo '<td width="10%" align="center">'.($rst["dtmFch"] != '' ? $rst["dtmFch"] : 'S/Registro').'</td>';
		echo '<td width="10%" align="right">'.$rst["dblSaldo"].'&nbsp;</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
echo '</table>';
echo '<input type="hidden" name="totfil" id="totfil" value="'.$cont.'">';
mssql_free_result($stmt);
mssql_close($cnx);
?>
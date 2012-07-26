<?php
include '../conexion.inc.php';

$cargo = $_GET["cargo"];
$estado = $_GET["estado"];
echo '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$stmt = mssql_query("EXEC Bodega..sp_getFacturaInterna 0, NULL, '$cargo', '$estado'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="10%" align="center">'.$rst["dtmFecha"].'</td>';
		echo '<td width="10%" align="center">';
		echo '<a href="#" title="Abre factura interna N&deg; '.$rst["dblNumero"].'"';
		echo 'onclick="javascript: ';
		echo 'Deshabilita(true);';
		echo "AbreDialogo('divVPrevia', 'frmVPrevia', 'factura_interna.php?numero=".$rst["dblNumero"]."')";
		echo '"';
		echo 'onmouseover="javascript: window.status=\'Abre factura interna N&deg; '.$rst["dblNumero"].'\'; return true;"';
		echo '>'.$rst["dblNumero"].'</a>';
		echo '</td>';
		echo '<td width="50%" align="left">&nbsp;'.$rst["strDescCargo"].'</td>';
		echo '<td width="15%" align="left">&nbsp;'.$rst["strDescEstado"].'</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblTotal"], 0, '', '.').'&nbsp;</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
mssql_free_result($stmt);
mssql_close($cnx);
echo '</table>';
?>
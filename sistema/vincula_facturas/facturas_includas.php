<?php
include '../conexion.inc.php';

$epago = $_GET["epago"];
$obra = $_GET["obra"];
echo '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$stmt = mssql_query("EXEC Bodega..sp_getFacturas 5, NULL, NULL, $epago, NULL, NULL, NULL, NULL, $obra", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td width="2%" align="center">';
		echo '<input type="hidden" name="hdnIProveedor'.$cont.'" id="hdnIProveedor'.$cont.'" value="'.$rst["strProveedor"].'">';
		echo '<input type="checkbox" name="chkIMarca'.$cont.'" id="chkIMarca'.$cont.'" value="'.$rst["dblNumDoc"].'" onclick="javascript: Selecciona(this)">';
		echo '</td>';
		echo '<td width="14%" align="center">'.$rst["dblNumDoc"].'</td>';
		echo '<td width="2%" align="center">';
		if(trim($rst["strArchivo"]) != ''){
			echo '<a href="#" title="Abre archivo adjunto..." ';
			echo 'onclick="javascript: MuestraArchivo(\''.$rst["strArchivo"].'\')"';
			echo 'onmouseover="window.status=\'Abre archivo adjunto...\'; return true;"';
			echo 'onmouseout="window.status=\'\'; return true;"';
			echo '><img border="0" align="absmiddle" src="../images/archivo.gif" /></a>';
		}else
			echo '&nbsp;';
		echo '</td>';
		echo '<td width="68%">&nbsp;'.ReemplazaInv($rst["strNombre"]).'</td>';
		echo '<td width="15%" align="right">'.number_format($rst["dblMonto"], 0, '', '.').'&nbsp;</td>';
		echo '<td width="5%">'.$rst["strModulo"].'</td>';
		echo '</tr>';
		$total += $rst["dblMonto"];
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="color:#FF0000" ><b>No se ha encontrado informacion.</b></td></tr>';
mssql_free_result($stmt);
mssql_close($cnx);
echo '</table>';
echo '<input type="hidden" name="totincl" id="totincl" value="'.$cont.'">';
echo '<input type="hidden" name="total" id="total" value="'.number_format($total, 0, '', '.').'">';
?>

<?php
include '../conexion.inc.php';

$obra = $_GET["obra"];
$factura = $_GET["factura"] != '' ? $_GET["factura"] : 'NULL';
$fdesde = ($_GET["fdesde"] != '' ? formato_fecha($_GET["fdesde"], false, true) : '');
$fhasta = ($_GET["fhasta"] != '' ? formato_fecha($_GET["fhasta"], false, true) : '');

echo '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$stmt = mssql_query("EXEC Bodega..sp_getFacturas 4, NULL, NULL, $factura, NULL, NULL, NULL, NULL, '$obra', '$fdesde', '$fhasta'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td width="2%" align="center">';
		echo '<input type="hidden" name="hdnDProveedor'.$cont.'" id="hdnDProveedor'.$cont.'" value="'.$rst["strProveedor"].'">';
		if(trim($rst["dblEPago"]) != '')
			echo '<input type="hidden" name="chkDMarca'.$cont.'" id="chkDMarca'.$cont.'" >'.$rst["dblEPago"];
		else
			echo '<input type="checkbox" name="chkDMarca'.$cont.'" id="chkDMarca'.$cont.'" value="'.$rst["dblNumDoc"].'" onclick="javascript: Selecciona(this)">';
		echo '</td>';
		echo '<td width="15%" align="center">'.$rst["dblNumDoc"].'</td>';
		echo '<td width="65%">&nbsp;'.ReemplazaInv($rst["strNombre"]).'</td>';
		echo '<td width="15%" align="right">'.number_format($rst["dblMonto"], 0, '', '.').'&nbsp;</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="color:#FF0000" ><b>No se ha encontrado informacion.</b></td></tr>';
mssql_free_result($stmt);
mssql_close($cnx);
echo '</table>';
echo '<input type="hidden" name="totdisp" id="totdisp" value="'.$cont.'">';
?>

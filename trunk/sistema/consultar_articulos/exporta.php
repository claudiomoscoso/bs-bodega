<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$material = $_GET["material"];

$nombarch = 'entregas'.date('d-m-Y').'.xls';
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=".$nombarch."");

$contenido='<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido.='<body >';
$contenido.='<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$contenido.='<tr>';
$contenido.='<td width="3%" align="center"><b>N&deg;</b></td>';
$contenido.='<td width="10%" align="center"><b>C&oacute;digo</b></td>';
$contenido.='<td width="65%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>';
$contenido.='<td width="10%" align="center"><b>Ult.Entrega</b></td>';
$contenido.='<td width="10%" align="right"><b>Cargos&nbsp;</b></td>';
$contenido.='</tr>';
$stmt = mssql_query("EXEC Bodega..sp_getDevolucionesPendientes 5, NULL, '$bodega', NULL, '$material'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		if($rut != trim($rst["strRut"])){ 
			$contenido.='<tr><td colspan="5" ><b>&nbsp;['.trim($rst["strRut"]).'] '.$rst["strNombre"].'</b></td></tr>';
			$rut = trim($rst["strRut"]);
		}
		$contenido.='<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'">';
		$contenido.='<td width="3%" align="center">'.$cont.'</td>';
		$contenido.='<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
		$contenido.='<td width="65%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
		$contenido.='<td width="10%" align="center">'.($rst["dtmFch"] != '' ? $rst["dtmFch"] : 'S/Registro').'</td>';
		$contenido.='<td width="10%" align="right">'.number_format($rst["dblSaldo"], 2, ',', '.').'&nbsp;</td>';
		$contenido.='</tr>';
	}while($rst=mssql_fetch_array($stmt));
}
mssql_free_result($stmt);
$contenido.='</table>';
$contenido.='</body>';
$contenido.='</html>';
print($contenido);

mssql_close($cnx);
?>
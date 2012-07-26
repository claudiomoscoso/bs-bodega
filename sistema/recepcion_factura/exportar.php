<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$mes = $_GET["mes"];
$ano = $_GET["ano"];
$numdoc = $_GET["numdoc"] != '' ? $_GET["numdoc"] : 'NULL';
$numoc = $_GET["numoc"] != '' ? $_GET["numoc"] : 'NULL';
$sing = $_GET["sing"];
$proveedor = $_GET["proveedor"];
$cargo = $_GET["cargo"];

$contenido='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$contenido.='<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido.='<body marginheight="0" marginwidth="0" >';
$contenido.='<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$contenido.='	<tr>';
$contenido.='		<th width="5%">N&deg;</th>';
$contenido.='		<th width="8%">Fecha</th>';
$contenido.='		<th width="10%">O.Compra N&deg;</th>';
$contenido.='		<th width="30%" align="left">&nbsp;Proveedor</th>';
$contenido.='		<th width="10%" align="left">&nbsp;T.Documento</th>';
$contenido.='		<th width="10%">N&deg;Documento</th>';
$contenido.='		<th width="10%" align="right">Monto&nbsp;</th>';
$contenido.='		<th width="10%" align="left">&nbsp;Usuario</th>';
$contenido.='	</tr>';
$stmt = mssql_query("EXEC Bodega..sp_getFacturas 0, $mes, $ano, $numdoc, '$proveedor', $numoc, 0, $sing, '$cargo'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
$contenido.='	<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
$contenido.='		<td align="center">'.$rst["dblNumero"].'</td>';
$contenido.='		<td align="center">'.$rst["dtmFecha"].'</td>';
$contenido.='		<td align="center">'.($rst["dblUltima"]!='' ? $rst["dblUltima"] : '--').'</td>';
$contenido.='		<td align="left">'.htmlentities(trim($rst["strNombProv"])).'</td>';
$contenido.='		<td align="center">'.$rst["TipoDoc"].'</td>';
$contenido.='		<td align="center">'.$rst["dblNumDoc"].'</td>';
$contenido.='		<td align="right">'.number_format($rst["dblMonto"], 0,'','').'</td>';
$contenido.='		<td align="left">'.$rst["nombre"].'</td>';
$contenido.='	</tr>';
}
mssql_free_result($stmt);
$contenido.='</table>';
$contenido.='</body>';
$contenido.='</html>';

$nombarch = 'recepcion_facturas'.date('d-m-Y').'.xls';
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=".$nombarch."");
print($contenido);

mssql_close($cnx);
?>
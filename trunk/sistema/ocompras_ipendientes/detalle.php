<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$proveedor = $_GET["proveedor"];
$fecdesde = formato_fecha($_GET["desde"], false, true);
$fechasta = formato_fecha($_GET["hasta"], false, true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra con Ingresos Pendientes</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	parent.Deshabilita(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'OCIP', NULL, '%', '$bodega', '', 0, 0, '$proveedor', 0, '', '', '$fecdesde', '$fechasta'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		if($proveedor != $rst["strProveedor"]){
			echo '<tr><td colspan="5" style="background-image:url(../images/borde_menu.gif)"><b>&nbsp;'.$rst["strNombre"].'</b></td></tr>';
			$proveedor = $rst["strProveedor"];
		}
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" '.($rst["dblCantOC"] > $rst["dblCantIngr"] ? 'style="color:#FF0000"' : '').'>';
		echo '<td width="10%" align="center">'.$rst["dblUltima"].'</td>';
		echo '<td width="10%" align="center">'.$rst["dtmFecha"].'</td>';
		echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
		echo '<td width="48%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblCantOC"], 2, ',', '.').'&nbsp;</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblCantIngr"], 2, ',', '.').'&nbsp;</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n</b></td></tr>';
mssql_free_result($stmt);
?>
	<tr style="color:#FF0000"></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>

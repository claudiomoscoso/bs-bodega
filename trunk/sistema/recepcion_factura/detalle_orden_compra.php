<?php
include '../conexion.inc.php';
$ordenes = $_GET["ordenes"];

$stmt = mssql_query("SELECT (dblFactor + 1) AS dblFactor FROM Impuesto WHERE id = 1", $cnx);
if($rst = mssql_fetch_array($stmt)) $factor = $rst["dblFactor"];
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	parent.document.getElementById('txtMonto').value = document.getElementById('hdnMonto').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table id="tbl" width="100%" border="0" cellpadding="0" cellspacing="1">
<?php
$fil=0;

$stmt = mssql_query("EXEC Bodega..sp_getDetalleOrdenCompra 1, NULL, '$ordenes'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$fil++;
	echo '<tr bgcolor="'.($fil % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	echo '<td width="3%" align="center">'.$fil.'</td>';
	echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
	echo '<td width="44%" align="left">&nbsp;'.htmlentities($rst["strDescripcion"]).'</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblValor"], 0, '', '.').'&nbsp;</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"] * $rst["dblValor"], 0, '', '.').'&nbsp;</td>';
	echo '</tr>';
	$monto += ($rst["dblCantidad"] * $rst["dblValor"]);
}
mssql_free_result($stmt);
$monto *= $factor;?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $fil;?>">
<input type="hidden" name="hdnMonto" id="hdnMonto" value="<?php echo number_format($monto, 0, '', '');?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
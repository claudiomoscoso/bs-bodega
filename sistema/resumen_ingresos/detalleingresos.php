<?php
include '../conexion.inc.php';
$bodega=$_GET["bodega"];
$mes=$_GET["mes"];
$ano=$_GET["ano"];
$codigo=$_GET["codigo"];
$col=$_GET["col"]!='' ? $_GET["col"] : 'NULL';
$orden=$_GET["orden"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consulta de Ingresos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	parent.document.getElementById('txtTotGI').value=document.getElementById('total').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getIngresosAcumulados 2, '$bodega', '$mes', $ano, '$codigo', $col, '$orden'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	echo '<td width="3%" align="center">'.$cont.'</td>';
	echo '<td width="10%" align="center">'.$rst["dblNum"].'</td>';
	echo '<td width="10%" align="center">'.$rst["dtmFecha"].'</td>';
	echo '<td width="20%" align="left">&nbsp;'.$rst["strTDocumento"].'</td>';
	echo '<td width="10%" align="center">'.$rst["strReferencia"].'</td>';
	echo '<td width="15%" align="center">'.($rst["dblUltima"] != '' ? $rst["dblUltima"] : 'Caja Chica').'</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 0, '','.').'&nbsp;</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblValor"], 0, '','.').'&nbsp;</td>';
	echo '<td align="right">'.number_format($rst["dblTotal"], 0, '','.').'&nbsp;</td>';
	echo '</tr>';
	$total+=$rst["dblTotal"];
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="total" id="total" value="<?php echo number_format($total, 0, '', '.');?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
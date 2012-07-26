<?php
include '../conexion.inc.php';
$bodega=$_GET["bodega"];
$usuario=$_GET["usuario"];
$perfil=$_GET["perfil"];
$nivel=$_GET["nivel"];

$responsable=$_GET["responsable"];
$estado=$_GET["estado"];
$mes=$_GET["mes"];
$ano=$_GET["ano"];
$periodo=$_GET["periodo"];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--

function Load(){
	self.focus();
	self.print();
}
-->
</script>
<head><title>Caja Chica</title></head>
<body style="background-color:#FFFFFF" onLoad="Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
	<tr>
		<td width="3%" align="center"><b>N&deg;</b></td>
		<td width="8%" align="center"><b>Fecha</b></td>
		<td width="8%" align="center"><b>N&deg;C.Chica</b></td>
		<td width="19%" align="left"><b>&nbsp;Bodega</b></td>
		<td width="15%" align="left"><b>&nbsp;Responsable</b></td>
		<td width="20%" align="left"><b>&nbsp;Nota</b></td>
		<td width="15%" align="center"><b>Estado</b></td>
		<td width="10%" align="right"><b>Total&nbsp;</b></td>
	</tr>
	<tr><td colspan="8"><hr></td></tr>
<?php
$stmt = mssql_query("EXEC Bodega..sp_getCajaChica 1, NULL, NULL, '$responsable', '$estado', '$mes', $ano, $periodo", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr>';
	echo '<td width="3%" align="center" style="border-bottom:1px solid">'.$cont.'</td>';
	echo '<td width="8%" align="center" style="border-bottom:1px solid">'.$rst["dtmFch"].'</td>';
	echo '<td width="8%" align="center" style="border-bottom:1px solid">'.$rst["dblNum"].'</td>';
	echo '<td width="19%" align="left" style="border-bottom:1px solid">&nbsp;'.$rst["strDescBodega"].'</td>';
	echo '<td width="15%" align="left" style="border-bottom:1px solid">&nbsp;'.htmlentities(trim($rst["strNombre"])).'</td>';
	echo '<td width="20%" align="left" style="border-bottom:1px solid">&nbsp;'.htmlentities(trim($rst["strNota"])).'</td>';
	echo '<td width="15%" align="center" style="border-bottom:1px solid">'.$rst["strDescEstado"].'</td>';
	echo '<td width="10%" align="right" style="border-bottom:1px solid">'.number_format($rst["dblTotal"], 0, '', '.').'&nbsp;</td>';
	echo '</tr>';
}
mssql_free_result($stmt);?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
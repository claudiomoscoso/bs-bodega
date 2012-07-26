<?php
include '../conexion.inc.php';

$numero = $_GET["numero"];
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
var Intervalo = 0;
-->
</script>
<body leftmargin="0" rightmargin="0" topmargin="0">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<th width="3%">N&deg;</th>
		<th width="27%" align="left">&nbsp;Descripci&oacute;n</th>
		<th width="10%">C.Costo</th>
		<th width="28%" align="left">&nbsp;Equipo</th>
		<th width="10%" >&nbsp;Patente</th>
		<th width="10%">F.Inicio</th>
		<th width="10%">F.T&eacute;rmino</th>
		<th width="2%">&nbsp;</th>
	</tr>
</table>
<div style="position:relative; width:100%; height:130px; overflow:scroll;" >
<table border="0" width="100%" cellpadding="0" cellspacing="1" >
<?php
$stmt = mssql_query("EXEC Bodega..sp_getDetalleOrdenCompra 0, $numero", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	echo '<td width="3%" align="center">'.$cont.'</td>';
	echo '<td width="27%" align="left">';
	echo '<input type="hidden" name="hdnDescripcion'.$cont.'" id="hdnDescripcion'.$cont.'" value="&nbsp;'.htmlentities(trim($rst["strDescripcion"])).'" />';
	echo '<input name="txtDescripcion'.$cont.'" id="txtDescripcion'.$cont.'" class="txt-sborde" style="width:99%;background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="&nbsp;'.$rst["strDescripcion"].'"';
	echo 'onmouseover="javascript: ';
	echo 'clearInterval(Intervalo); ';
	echo "Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion$cont\')', 250);";
	echo '"';
	echo 'onmouseout="javascript: ';
	echo "DetieneTexto(Intervalo, this.id, 'hdnDescripcion$cont');";
	echo '"';
	echo '>';
	echo '</td>';
	echo '<td width="10%" align="center">'.$rst["strCCosto"].'</td>';
	echo '<td width="28%" align="left">&nbsp;'.$rst["strEquipo"].'</td>';
	echo '<td width="10%" align="center">&nbsp;'.$rst["strPatente"].'</td>';
	echo '<td width="10%" align="center">'.$rst["dtmFchIni"].'</td>';
	echo '<td width="10%" align="center">'.$rst["dtmFchTer"].'</td>';
	echo '</tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
</div>
</body>
</html>
<?php
include '../conexion.inc.php';

$numero = $_GET["numero"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Autorizar Solicitud de Devoluci&oacute;n</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
-->
</script>
<body marginheight="0" marginwidth="0">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getDetalleSolicitudDevolucion 0, $numero", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF1FF').'">';
	echo '<td width="3%" align="center">'.$cont.'</td>';
	echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
	echo '<td width="65%" >&nbsp;'.$rst["strDescripcion"].'</td>';
	echo '<td width="10%" align="center">'.$rst["strUnidad"].'</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;</td>';
	echo '</tr>';
}
mssql_free_result($stmt);
?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
<?php
include '../conexion.inc.php';
$numero=$_GET["numero"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Renovaci&oacute;n Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<body marginheight="0" marginwidth="0">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getDetalleOrdenCompra 0, $numero", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 ? '#FFFFFF': '#EBF3FE').'">';
	echo '<td width="3%" align="center">'.$cont.'</td>';
	echo '<td width="85%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'</td>';
	echo '</tr>';
}
mssql_free_result($stmt);?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
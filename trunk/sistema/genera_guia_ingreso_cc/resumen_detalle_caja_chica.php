<?php
include '../conexion.inc.php';
$numero=$_GET["numero"];
$factor=$_GET["factor"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Genera Gu&iacute;a de Ingreso</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<body marginheight="0" marginwidth="0">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getDetalleCajaChica 1, $numero", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 ? '#FFFFFF': '#EBF3FE').'">';
	echo '<td width="3%" align="center">'.$cont.'</td>';
	echo '<td width="40%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
	echo '<td width="18%" align="left">&nbsp;'.($rst["dblTipoDoc"] == 0 ? 'Factura' : 'Boleta').'</td>';
	echo '<td width="13%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;</td>';
	echo '<td width="13%" align="right">'.number_format($rst["dblValor"], 0, '', '.').'&nbsp;</td>';
	echo '<td width="14%" align="right">';
	echo $rst["dblTipoDoc"] == 0 ? number_format($rst["dblCantidad"] * $rst["dblValor"] * $factor, 0, '', '.') : number_format($rst["dblCantidad"] * $rst["dblValor"], 0, '', '.').'&nbsp;';
	echo '</td>';
	echo '</tr>';
}
mssql_free_result($stmt);?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
<?php
include '../conexion.inc.php';
$numero = $_GET["numero"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gu&iacute;a de Ingreso por Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />

<body marginheight="0" marginwidth="0">
<table width="100%" border="0" cellpadding="0" cellspacing="1">
<?php	
$stmt = mssql_query("EXEC Bodega..sp_getDetalleCajaChica 1, $numero", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$ln++;
	echo '<tr bgcolor="'.($ln % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	echo '<td width="3%" align="center">'.$ln.'</td>';
	echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
	echo '<td width="65%">&nbsp;'.htmlentities($rst["strDescripcion"]).'</td>';
	echo '<td width="10%" align="center">'.$rst["strUnidad"].'</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;</td>';
	echo '</tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $ln;?>">
</body>
</html>
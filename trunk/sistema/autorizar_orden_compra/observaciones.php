<?php
include '../conexion.inc.php';
$numero=$_GET["numero"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Observaciones</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<body marginheight="0" marginwidth="0">
<table border="0" width="100%" cellpadding="0" cellspacing="0" >
<?php
$stmt = mssql_query("EXEC sp_getObservaciones 'IOC', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td width="1%">&nbsp;</td>';
		echo '<td width="49%" align="left"><b>'.$rst["nombre"].'</b></td>';
		echo '<td width="49%" align="right"><b>'.formato_fecha($rst["dtmFch"], false, false).'</b></td>';
		echo '<td width="1%">&nbsp;</td>';
		echo '</tr>';
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td>&nbsp;</td>';
		echo '<td colspan="2">'.$rst["strObservacion"].'</td>';
		echo '<td>&nbsp;</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center"><font color="#FF0000"><b>Sin observaci&oacute;n</b></font></td></tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
</body>
</html>
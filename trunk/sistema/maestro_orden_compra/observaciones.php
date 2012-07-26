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
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table border="0" width="100%" cellpadding="0" cellspacing="0" >
<?php
$stmt = mssql_query("EXEC sp_getObservaciones 'IOC', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="1%">&nbsp;</td>
		<td width="49%" align="left"><b><?php echo $rst["nombre"];?></b></td>
		<td width="49%" align="right"><b><?php echo formato_fecha($rst["dtmFch"], false, false);?></b></td>
		<td width="1%">&nbsp;</td>
	</tr>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td>&nbsp;</td>
		<td colspan="2"><?php echo $rst["strObservacion"];?></td>
		<td>&nbsp;</td>
	</tr>
<?php
	}while($rst=mssql_fetch_array($stmt));
}else{?>
	<tr><td align="center"><font color="#FF0000"><b>Sin observaci&oacute;n</b></font></td></tr>	
<?php
}
mssql_free_result($stmt);?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
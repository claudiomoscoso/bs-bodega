<?php
include '../conexion.inc.php';
$usuario=$_GET["usuario"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>&Uacute;ltima Actividad</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<body>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr><td colspan="3">&nbsp;<b>Usuario: </b><?php echo $usuario;?></td></tr>
	<tr>
		<th width="5%" align="left">&nbsp;Documento</th>
		<th width="30%">Fecha</th>
		<th width="15%">Total</th>
	</tr>
<?php
$stmt = mssql_query("EXEC Bodega..sp_getUltimaActividad 0, '$usuario'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;?>
		<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
			<td ><b>&nbsp;<?php echo $rst["strTDoc"];?></b></td>
			<td align="center"><?php echo formato_fecha($rst["dtmFecha"], false, false);?></td>
			<td align="right"><?php echo number_format($rst["dblTotDoc"], 0, '', '.');?>&nbsp;</td>
		</tr>
	<?php
	}while($rst=mssql_fetch_array($stmt));
}else{?>
	<tr><td align="center" colspan="3" style="color:#FF0000"><b>No registra actividad</b></td>
<?php
}
mssql_free_result($stmt);
mssql_close($cnx);?>
</table>
</body>
</html>
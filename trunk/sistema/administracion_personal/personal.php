<?php
include '../conexion.inc.php';
$contrato = $_GET["contrato"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Administraci&oacute;n de Moviles</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function CambiaVigencia(contrato, rut, vigente){
	vigente = vigente ? 1 : 0;
	parent.document.getElementById('transaccion').src='transaccion.php?accion=V&contrato='+contrato+'&rut='+rut+'&vigente='+vigente;
}
-->
</script>
<body marginheight="0" marginwidth="0">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC General..sp_getPersonalObra 6, '$contrato'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#EBF1FF' : '#FFFFFF' ?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="10%" align="center"><?php echo $rst["strRut"];?></td>
		<td width="40%" align="left"><?php echo $rst["strNombre"];?></td>
		<td width="25%" align="left">&nbsp;<?php echo $rst["strDescContrato"];?></td>
		<td width="6%" align="center">
			<input type="checkbox" name="chkVigente<?php echo $cont;?>" id="chkVigente<?php echo $cont;?>" 
<?php echo $rst['dblVigente']==1 ? 'checked=yes' : '';?>	onclick="javasscript: CambiaVigencia('<?php echo $rst["strContrato"];?>', '<?php echo $rst["strRut"];?>', this.checked)"/>
		</td>
	</tr>
<?php
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="color:#FF0000; font-weight:bold">No se ha encontrado informaci&oacute;n.</td></tr>';
mssql_free_result($stmt);?>	
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>

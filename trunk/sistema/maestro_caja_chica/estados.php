<?php
include '../conexion.inc.php';
$numero=$_GET["numero"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Estados</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getEstados 'HCC', NULL, $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="30%" align="center"><?php echo formato_fecha($rst["dtmFch"], false, false);?></td>
		<td width="40%" align="left">
			<input type="hidden" name="hdnNombre<?php echo $cont;?>" id="hdnNombre<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["nombre"]);?>" />
			<input name="txtNombre<?php echo $cont;?>" id="txtNombre<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["nombre"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNombre<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnNombre<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="30%" align="left">
			<input type="hidden" name="hdnEstado<?php echo $cont;?>" id="hdnEstado<?php echo $cont;?>" value="&nbsp;<?php echo $rst["strDescEstado"];?>" />
			<input name="txtEstado<?php echo $cont;?>" id="txtEstado<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo $rst["strDescEstado"];?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnEstado<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnEstado<?php echo $cont;?>');
				"
			/>
		</td>
	</tr>
<?php
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
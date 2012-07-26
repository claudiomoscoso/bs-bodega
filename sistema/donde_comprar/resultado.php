<?php
include '../conexion.inc.php';

$material = $_GET["material"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Kardex</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css"/>
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;
-->
</script>
<body marginheight="0" marginwidth="0">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$saldo = 0;
$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'DC', NULL, '%', '$material'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;?>
		<tr bgcolor="<?php echo ($cont%2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
			<td width="3%" align="center"><?php echo $cont;?></td>
			<td width="20%" align="left">
				<input type="hidden" name="hdnNombre<?php echo $cont;?>" id="hdnNombre<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["strNombre"]));?>" />
				<input name="txtNombre<?php echo $cont;?>" id="txtNombre<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["strNombre"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNombre<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnNombre<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="10%" align="center"><?php echo $rst["strTelefono"];?></td>
			<td width="20%" align="left">
				<input type="hidden" name="hdnContacto<?php echo $cont;?>" id="hdnContacto<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["strContacto"]));?>" />
				<input name="txtContacto<?php echo $cont;?>" id="txtContacto<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["strContacto"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnContacto<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnContacto<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="25%" align="left">
				<input type="hidden" name="hdnDescripcion<?php echo $cont;?>" id="hdnDescripcion<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["strDescripcion"]));?>" />
				<input name="txtDescripcion<?php echo $cont;?>" id="txtDescripcion<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["strDescripcion"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
			<td width="10%" align="right"><?php echo number_format($rst["dblValor"], 0, '', '.');?>&nbsp;</td>
		</tr>
<?php	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color: #FF0000"><b>No se han encontrado registros.</b></td></tr>';
}
mssql_free_result($stmt);?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
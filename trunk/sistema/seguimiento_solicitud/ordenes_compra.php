<?php
include '../conexion.inc.php';
$solicitud=$_GET["solicitud"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;
-->
</script>
<body marginheight="0" marginwidth="0">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC sp_getOrdenCompra 'SSM', $solicitud", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;?>
		<tr bgcolor="<?php echo ($cont%2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
			<td width="3%" align="center"><?php echo $cont;?></td>
			<td width="10%" align="center"><?php echo $rst["dtmFecha"];?></td>
			<td width="10%" align="center"><?php echo $rst["dblUltima"];?></td>
			<td width="20%" align="left">
				<input type="hidden" name="hdnProveedor<?php echo $cont;?>" id="hdnProveedor<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strNombre"]));?>" />
				<input name="txtProveedor<?php echo $cont;?>" id="txtProveedor<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strNombre"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnProveedor<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnProveedor<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="20%" align="left">
				<input type="hidden" name="hdnDescripcion<?php echo $cont;?>" id="hdnDescripcion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strDescripcion"]));?>" />
				<input name="txtDescripcion<?php echo $cont;?>" id="txtDescripcion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strDescripcion"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="10%" align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
			<td width="10%" align="right"><?php echo number_format($rst["dblValor"],0,'','.');?>&nbsp;</td>
			<td width="15%" align="left">
				<input type="hidden" name="hdnEstado<?php echo $cont;?>" id="hdnEstado<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strDetalle"]));?>" />
				<input name="txtEstado<?php echo $cont;?>" id="txtEstado<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strDetalle"]));?>" 
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
	echo '<tr><td align="center" style="color:#FF0000"><b>La solicitud no tiene orden de compra asociada.</b></td></tr>';
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
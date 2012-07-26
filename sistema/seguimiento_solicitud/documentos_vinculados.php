<?php
include '../conexion.inc.php';
$solicitud=$_GET["solicitud"];
$numusu=$_GET["numusu"];
$estado=$_GET["estado"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Seguimiento de Solicitud</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	if('<?php echo trim($estado);?>'=='2' || '<?php echo trim($estado);?>'=='3' || '<?php echo trim($estado);?>'=='6')
		parent.document.getElementById('btnFinalizar').disabled=false;
	else
		parent.document.getElementById('btnFinalizar').disabled=true;

}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getDetalleSeguimiento $solicitud", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#FFFFFF' : '#EBF3FE';?>" <?php echo $rst["dblCantidadAut"]-$rst["dblCantCompra"]!=0 ? 'style="color:#FF0000; font-weight: bold"' : '';?>>
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="35%">
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
		<td width="20%" align="right"><?php echo $rst["dblCantidad"];?>&nbsp;</td>
		<td width="20%" align="right"><?php echo $rst["dblCantidadAut"];?>&nbsp;</td>
		<td width="20%" align="right"><?php echo $rst["dblCantCompra"];?>&nbsp;</td>
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
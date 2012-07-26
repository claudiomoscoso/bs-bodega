<?php
include '../conexion.inc.php';
$partida=$_GET["partida"];
$bodega=$_GET["bodega"];
$codigo=$_GET["codigo"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Nivel3</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	parent.document.getElementById('TotalNivel3').value=document.getElementById('total').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
	$TotFin=0;
	$stmt = mssql_query("EXEC sp_getConsumoMaterialN3 '$bodega', '$partida', '$codigo'", $cnx);
	$TotFil=mssql_num_rows($stmt);
	while($rst=mssql_fetch_array($stmt)){
		$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
	   <td width="3%" align="center"><?php echo $cont;?></td>
	   <td width="15%" align="center"><?php echo $rst["dtmFecha"];?></td>
		<td width="15%" align="center"><?php echo $rst["dblNumero"];?></td>
		<td width="50%" align="left">
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
		<td width="17%" align="right"><?php echo number_format($rst["dblCantidad"],2,",",".");?>&nbsp;</td>
	</tr>
<?php
		$TotFin+=$rst["dblCantidad"];
	}
	mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="total" id="total" value="<?php echo number_format($TotFin,0,',','.');?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
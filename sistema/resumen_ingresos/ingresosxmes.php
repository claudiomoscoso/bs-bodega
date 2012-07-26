<?php
include '../conexion.inc.php';
$bodega=$_GET["bodega"];
$ano=$_GET["ano"];
$col=$_GET["col"]!='' ? $_GET["col"] : 'NULL';
$orden=$_GET["orden"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consulta de Ingresos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	parent.Deshabilita(false);
	parent.document.getElementById('txtTotGnral').value=document.getElementById('total').value;
}

-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getIngresosAcumulados 1, '$bodega', NULL, $ano, NULL, $col, '$orden'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
	$mes=mes(substr($rst["strFecha"], 0, strlen($rst["strFecha"])-5));?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center">
			<a href="#" title="Ver mas..."
				onclick="javascript: 
					parent.Deshabilita(true);
					parent.OcultaFlechas(1, true)
					parent.CierraDialogo('divGIngresos', 'frmGIngresos');
					parent.CierraDialogo('divIngresos', 'frmIngresos');
					parent.document.getElementById('txtSubTot').value='';
					parent.document.getElementById('txtMes').value='<?php echo "$mes de $ano";?>';
					parent.document.getElementById('hdnMes').value='<?php echo substr($rst["strFecha"], 0, strlen($rst["strFecha"])-5);?>';
					parent.document.getElementById('hdnAno').value='<?php echo $ano;?>';
					parent.OcultaFlechas(1, false)
					AbreDialogo('divIngresos', 'frmIngresos', 'ingresosxmaterial.php?bodega=<?php echo $bodega;?>&mes=<?php echo substr($rst["strFecha"], 0, strlen($rst["strFecha"])-5);?>&ano=<?php echo $ano;?>&ctrl=txtSubTot&col=1&orden=A', true);
				"
				onmouseover="javascript: window.status='Ingresos acumulados mes de <?php echo "$mes de $ano";?>.'; return true;"
			><img border="0" src="../images/mas.gif" /></a>
		</td>
		<td width="87%">&nbsp;<?php echo "$mes de $ano";?></td>
		<td width="10%" align="right"><?php echo number_format($rst["dblTotal"], 0, '','.');?>&nbsp;</td>
	</tr>
<?php
	$total+=$rst["dblTotal"];
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="total" id="total" value="<?php echo number_format($total,0,'','.');?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
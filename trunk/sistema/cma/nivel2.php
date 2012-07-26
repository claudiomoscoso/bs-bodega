<?php
include '../conexion.inc.php';

$usuario=$_GET["usuario"];
$partida=$_GET["partida"];
$bodega=$_GET["bodega"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Nivel2</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	parent.document.getElementById('TotalNivel2').value=document.getElementById('total').value;
}
-->
</script>

<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
	$TotFin=0;
	$stmt = mssql_query("EXEC sp_getConsumoMateriales 1, '$bodega', '$partida'", $cnx);
	$TotFil=mssql_num_rows($stmt);
	while($rst=mssql_fetch_array($stmt)){
		$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="2%" align="center">
			<a href="#" 
				onclick="
					AbreDialogo('divNivel3', 'frmNivel3', 'nivel3.php?bodega=<?php echo $bodega;?>&partida=<?php echo $partida;?>&codigo=<?php echo $rst["strCodigo"];?>', true);
					parent.document.getElementById('strMaterial').value='<?php echo htmlentities($rst["strDescripcion"]);?>';
				"
			><img border="0" align="middle" src="../images/mas.gif"/></a>
		</td>
		<td width="68%">&nbsp;<?php echo htmlentities('['.$rst["strCodigo"].'] '.$rst["strDescripcion"]);?></td>
		<td width="10%" align="center"><?php echo htmlentities($rst["strUnidad"]);?></td>
		<td width="10%" align="right"><?php echo number_format($rst["dblCantidad"],2,",",".");?>&nbsp;</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblTotal"],0,',','.');?>&nbsp;</td>
	</tr>
<?php
		$TotFin+=$rst["dblTotal"];
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
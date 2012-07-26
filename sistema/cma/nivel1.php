<?php
include '../conexion.inc.php';
$usuario=$_GET["usuario"];
$bodega=$_GET["bodega"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consumo de Manterial por Actividad</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css">
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	parent.document.getElementById('totgnral').value=document.getElementById('total').value;
	parent.document.getElementById('btnOk').disabled=false;
	parent.document.getElementById('bodega').disabled=false;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$TotFin=0;
$stmt = mssql_query("EXEC sp_getConsumoMateriales 0, '$bodega'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td align="center" valign="middle" width="3%">
			<a href="#" onClick="
				javascript: 
					parent.Deshabilita(true);
					AbreDialogo('divNivel2', 'frmNivel2', 'nivel2.php?usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&partida=<?php echo $rst["strCodigo"];?>', true);
					parent.document.getElementById('strPartida').value='<?php echo $rst["strDescripcion"];?>';
				"
			><img border="0" src="../images/mas.gif"></a>
		</td>
		<td width="75%">&nbsp;<?php echo htmlentities('['.$rst["strCodigo"].'] '.$rst["strDescripcion"]);?></td>
		<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
		<td width="12%" align="right"><?php echo number_format($rst["dblTotal"],0,',','.');?>&nbsp;</td>
	</tr>
<?php
		$TotFin+=$rst["dblTotal"];
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se han encontrado registros.</b></td></tr>';
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
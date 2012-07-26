<?php
include '../conexion.inc.php';

$accion = $_GET["accion"];
$usuario = $_GET["usuario"];
$bodega = $_GET["bodega"];
$codigo = $_POST["txtCodigo"]!='' ? $_POST["txtCodigo"] : $_GET["codigo"];
$ccosto = $_POST["txtCCosto"]!='' ? $_POST["txtCCosto"] : $_GET["ccosto"];
$cantidad = $_POST["txtCantidad"];
if($accion == 'G'){
	$stmt = mssql_query("EXEC Bodega..sp_setTMPDetalleValeConsumo 2, '$usuario', '$bodega', NULL, '$codigo', $cantidad, '$ccosto'", $cnx);
	if($rst = mssql_fetch_array($stmt)){ 
		$error = $rst["dblError"];
		$stock = $rst["dblStock"];
	}
	mssql_free_result($stmt);
}elseif($accion == 'E')
	mssql_query("EXEC Bodega..sp_setTMPDetalleValeConsumo 3, '$usuario', NULL, NULL, '$codigo', NULL, '$ccosto'", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Vales de Consumo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	if(document.getElementById('totfil').value != 0) document.getElementById('msj').style.visibility='hidden';
	if(parseInt('<?php echo $error;?>') == 1)
		alert('La cantidad ingresada sobrepasa el stock real (<?php echo $stock;?>).');
	else if(parseInt('<?php echo $error;?>') == 2)
		alert('El material ingresado ya existe para esta partida.');
}

function Elimina(codigo, ccosto){
	if(confirm('¿Está seguro que desea eliminar esta línea?')) 
		self.location.href = '<?php echo $_SERVER['PHP_SELF']."?usuario=$usuario";?>&accion=E&codigo=' + codigo + '&ccosto=' + ccosto;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<div id="msj" style="position:absolute; width:30%; height:10%; left:35%; top:20%; display:none">
<table border="0" width="100%" cellpadding="0" cellspacing="0">	
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$cont=0;
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleValeConsumo 1, '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#EBF1FF' : '#FFFFFF' ?>" bordercolor="#000000" style="width:20px;">
		<td width="3%" align="center" ><?php echo $cont;?></td>
		<td width="10%" align="center"><?php echo $rst["strCodigo"];?></td>
		<td width="51%">&nbsp;<?php echo $rst["strDescripcion"];?></td>
		<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
		<td width="10%" align="center"><?php echo $rst["strPartida"];?></td>
		<td width="10%" align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
		<td width="2%" align="center">
			<a href="#" title="Elimina l&iacute;nea N&deg;<?php echo "$cont: ".$rst["strDescripcion"];?>"
				onclick="javascript: Elimina('<?php echo $rst["strCodigo"];?>', '<?php echo $rst["strPartida"];?>');" 
				onmouseover="javascript: window.status='Elimina l&iacute;nea N&deg;<?php echo "$cont: ".trim($rst["strDescripcion"]);?>'; return true;"
			><img border="0" src="../images/borrar<?php echo ($cont%2)==0 ? 0 : 1;?>.gif" /></a>
		</td>
	</tr>
<?php
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

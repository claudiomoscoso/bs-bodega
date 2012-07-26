<?php
include '../conexion.inc.php';
$modulo=$_GET["modulo"];
$usuario=$_GET["usuario"];
$bodega = $_GET["bodega"];
switch($modulo){
	case 1:
		$rut=$_GET["rut"];
		$codigo=$_GET["codigo"];
		$documento=$_GET["documento"];
		$numdoc=$_GET["numdoc"];
		$numoc=$_GET["numoc"] != '' ? $_GET["numoc"] : 'NULL';
		$cantidad=$_GET["cantidad"];
		$precio=$_GET["precio"];
		$factor = 1;
		if($documento==0){
			$stmt = mssql_query("SELECT dblFactor FROM Impuesto WHERE id = 1", $cnx);
			if($rst = mssql_fetch_array($stmt)) $factor = $rst["dblFactor"] + 1;
			mssql_free_result($stmt);
		}
		$total = round($cantidad * $precio * $factor);

		$stmt = mssql_query("EXEC Bodega..sp_setTMPDetalleCajaChica 0, '$bodega', '$usuario', '$codigo', $documento, $numdoc, $numoc, $cantidad, $precio, $total, '$rut'", $cnx);
		if($rst=mssql_fetch_array($stmt)) $error = $rst["dblError"];
		mssql_free_result($stmt);
		break;
	case 2:
		$codigo=$_GET["codigo"];
		$documento = $_GET["documento"];
		$numdoc=$_GET["numdoc"];

		mssql_query("EXEC Bodega..sp_setTMPDetalleCajaChica 1, '$bodega', '$usuario', '$codigo', $documento, $numdoc", $cnx);
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $error;?>' == '0'){
		parent.setLimpiaDetalle();
		parent.document.getElementById('txtCodigo').focus();
		parent.document.getElementById('txtCodigo').select();
	}else if('<?php echo $error;?>' == '1')
		alert('Las facturas que excedan los $30000.- debe tener una orden de compra');
	else if('<?php echo $error;?>' == '2')
		alert('Las boletas por compubustible no pueden exceder los $7000.');
	else if('<?php echo $error;?>' == '3')
		alert('Las boletas no pueden exceder los $3000.');
	else if('<?php echo $error;?>' == '4')
		alert('La rendición está excediendo su fondo fijo.');
	else if('<?php echo $error;?>' == '5')
		alert('La orden de compra ingresada no existe.');
	else if('<?php echo $error;?>' == '6')
		alert('El material ingresado no corresponde a la orden de compra.');
	else if('<?php echo $error;?>' == '7')
		alert('El vale no pueden exceder los $10000.');
	parent.document.getElementById('txtTotGnral').value = document.getElementById('hdnTotGral').value;
}

function setEliminaItem(codigo, tipodoc, numdoc){
	if(confirm('¿Está seguro que desea eliminar este ítem?'))
		self.location.href='<?php echo $_SERVER['PHP_SELF'];?>?modulo=2&usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&codigo=' + codigo + '&documento=' + tipodoc + '&numdoc=' + numdoc;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php 
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleCajaChica 0, '$bodega', '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#EBF1FF' : '#FFFFFF' ?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="10%" align="center"><?php echo $rst["strCodigo"];?></td>
		<td width="23%" align="left">&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?></td>
		<td width="10%" align="left">&nbsp;<?php echo $rst["strTipoDoc"];?></td>
		<td width="10%" align="center"><?php echo $rst["dblNumDoc"];?></td>
		<td width="10%" align="right"><?php echo number_format($rst["dblCantidad"], 2, ',', '.');?>&nbsp;</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblPrecio"], 0, '', '.');?>&nbsp;</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblTotal"], 0, '', '.');?>&nbsp;</td>
		<td width="10%" align="center"><?php echo $rst["dblUltima"];?></td>
		<td width="2%" align="center">
		<?php 
			if($rst["strTipoDoc"] == 'Factura')
				$tipodoc= 0;
			elseif($rst["strTipoDoc"] == 'Boleta')
				$tipodoc = 1;
			elseif($rst["strTipoDoc"] == 'Vale por')
				$tipodoc = 2;
			elseif($rst["strTipoDoc"] == 'B.Honorario')
				$tipodoc = 3;
		?>
			<a href="#" title="Elimina l&iacute;nea <?php echo $cont;?>, &iacute;tem: <?php echo htmlentities($rst["strDescripcion"]);?>"
				onclick="javascript: setEliminaItem('<?php echo $rst["strCodigo"];?>', '<?php echo $tipodoc;?>', '<?php echo $rst["dblNumDoc"];?>');"
				onmouseover="javascript: window.status='Elimina línea <?php echo $cont;?>, ítem: <?php echo htmlentities($rst["strDescripcion"]);?>'; return true;"
			><img id="imgElim<?php echo $cont;?>" border="0" align="middle" src="../images/borrar0.gif" /></a>
		</td>
	</tr>
<?php
	$totgral+=$rst["dblTotal"];
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="hdnTotGral" id="hdnTotGral" value="<?php echo number_format($totgral, 0, '', '.');?>" />
<input type="hidden" name="hdnTotFil" id="hdnTotFil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
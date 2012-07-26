<?php
include '../conexion.inc.php';

$usuario = $_POST["usuario"];
$bodega = $_POST["cmbBodega"];
$accion = $_POST["accion"];
$devolucion = $_POST["txtDevolucion"] != '' ? $_POST["txtDevolucion"] : $_POST["hdnDevolucion"];
$fecha = $_POST["txtFecha"];
$norden = $_POST["txtOCompra"];
$ocompra = $_POST["hdnOCompra"];
$proveedor = $_POST["hdnProveedor"];
$observacion = $_POST["txtObservacion"];
if($accion == 'G')
	mssql_query("EXEC Bodega..sp_setDevolucionProveedor 0, '$bodega', '$usuario', $devolucion, '".formato_fecha($fecha, false, true)."', $ocompra, '$proveedor', '$observacion'", $cnx);

$fecha = substr($fecha, 0, 2).' de '.mes(substr($fecha, 3, 2)).' de '.substr($fecha, 6);
$stmt = mssql_query("EXEC Bodega..sp_getProveedor 2, NULL, NULL, '$proveedor'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$nombre = $rst["strNombre"];
	$rut = $rst["strRut"];
	$direccion = $rst["strDireccion"];
	$comuna = $rst["Comuna"];
	$telefono = $rst["strTelefono"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Devolucion a Proveedor</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	parent.Bloquea();
	self.focus();
	self.print();
}
-->
</script>
<body topmargin="600px" rightmargin="0px" leftmargin="0px" onLoad="javascript: Load();">
<?php
$ln = 35;
$stmt = mssql_query("EXEC Bodega..sp_getDetalleDespacho 1, $devolucion", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$ln++;
	if($ln > 35){
		$ln = 1;
		for($espacio = 1; $espacio <= 7; $espacio++){
			echo '<br />';
		}?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="600px">&nbsp;</td>
		<td align="left"><?php echo $devolucion;?></td>
	</tr>
</table>		
<?php	for($espacio=1; $espacio<=5; $espacio++){
			echo '<br />';
		}
		?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="20px">&nbsp;</td>
		<td align="left"><?php echo $fecha;?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="20px">&nbsp;</td>
		<td width="405px" align="left"><?php echo $nombre;?></td>
		<td width="40px" align="left">&nbsp;</td>
		<td align="left"><?php echo $rut;?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="25px">&nbsp;</td>
		<td width="400px" align="left"><?php echo $direccion;?></td>
		<td width="50px" align="left">&nbsp;</td>
		<td align="left"><?php echo $comuna;?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="90px">&nbsp;</td>
		<td width="335px" align="left"><?php echo $norden;?></td>
		<td width="65px" align="left">&nbsp;</td>
		<td align="left"><?php echo $telefono;?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="80px">&nbsp;</td>
		<td align="left"><b>NO CONSTITUYE VENTA, SOLO TRASLADO.</b></td>
	</tr>
</table>
<?php
		for($i=1; $i<=6; $i++){
			echo '<br />';
		}
	}?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50px" align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?></td>
		<td width="10px">&nbsp;</td>
		<td width="425px">&nbsp;<?php echo '['.$rst["strCodigo"].' - '.$rst["strUnidad"].']  '.$rst["strDescripcion"];?></td>
		<td width="10px">&nbsp;</td>
		<td width="100px" align="right">&nbsp;</td>
	</tr>
</table>
<?php
	if($ln==35){
		$stmt2=mssql_query("EXEC Bodega..sp_getComunasXBodega '$bodega'", $cnx);
		while($rst=mssql_fetch_array($stmt)) $comunas.=$rst["strDetalle"].', ';
		mssql_free_result($stmt);
		if($comunas!='') $comunas=substr($comunas, 0, strlen($comunas)-2).'.';
		?>
<br /><br /><br />
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center"><?php echo $comunas;?></td>
	</tr>
</table>
<?php
	}
}
mssql_free_result($stmt);
if($ln!=35){
	$stmt = mssql_query("EXEC Bodega..sp_getComunasXBodega '$bodega'", $cnx);
	while($rst=mssql_fetch_array($stmt)) $comunas.=$rst["strDetalle"].', ';
	mssql_free_result($stmt);
	if($comunas!='') $comunas=substr($comunas, 0, strlen($comunas)-2).'.';?>
<br /><br /><br />
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center"><?php echo $comunas;?></td>
	</tr>
</table>
<?php
}?>
</body>
</html>
<?php
mssql_close($cnx);
?>
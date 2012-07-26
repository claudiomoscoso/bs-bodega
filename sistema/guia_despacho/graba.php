<?php
include '../conexion.inc.php';

$accion = $_POST["accion"];
$usuario = $_POST["usuario"];
$bodega = $_POST["cmbBodega"] != '' ? $_POST["cmbBodega"] : $_POST["hdnBodega"];
$numGD = $_POST["txtNumero"] != '' ? $_POST["txtNumero"] : $_POST["hdnDespacho"];
$fecha = $_POST["txtFecha"];
$cargo = $_POST["hdnCargo"];
$observacion = $_POST["txtObservacion"];
if($accion == 'G'){
	$stmt = mssql_query("EXEC Bodega..sp_setGuiaDespacho 0, '$usuario', $numGD, '".formato_fecha($fecha, false, true)."', '$bodega', NULL, '$cargo', '$observacion'", $cnx);
	if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
	mssql_free_result($stmt);
}

$fecha = substr($fecha, 0, 2).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.mes(substr($fecha, 3, 2)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr($fecha, 6);
$stmt = mssql_query("EXEC General..sp_getMoviles 0, '$bodega', '$cargo', 'E'", $cnx);
if($rst = mssql_fetch_array($stmt)) $nombre = $rst["strNombre"];
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<title>Guia de Despacho</title>
</head>

<script language="javascript">
<!--
function Load(){
	if('<?php echo $accion;?>' == 'G' && parseInt('<?php echo $error;?>') == 1){
		alert('El número de guía de despacho ya existe.');
		parent.Deshabilita(false);
		parent.document.getElementById('txtNumero').value = '';
	}else{
		parent.Deshabilita(true);
		parent.document.getElementById('btnImprimir').disabled=false;
		parent.document.getElementById('btnGrabar').disabled=true;
		parent.document.getElementById('btnNueva').disabled=false;
		self.focus();
		self.print();
	}
}
-->
</script>
<body topmargin="600px" rightmargin="0px" leftmargin="0px" onLoad="javascript: Load();">
<?php
$ln=30;
$stmt=mssql_query("EXEC Bodega..sp_getDetalleDespacho 0, $numGD", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$ln++;
	if($ln>30){
		$ln=1;
		for($espacio=1; $espacio<=15; $espacio++){
			echo '<br />';
		}?>
<table border="0" cellpadding="0" cellspacing="0" class="impresion">
	<tr>
		<td width="30px">&nbsp;</td>
		<td align="left"><?php echo $fecha;?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="impresion">
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td width="50px">&nbsp;</td>
		<td width="405px" align="left"><?php echo $nombre;?></td>
		<td width="40px" align="left">&nbsp;</td>
		<td align="left"><?php echo $rut;?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="impresion">
	<tr>
		<td width="25px">&nbsp;</td>
		<td width="400px" align="left"><?php echo $direccion;?></td>
		<td width="50px" align="left">&nbsp;</td>
		<td align="left"><?php echo $comuna;?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="impresion">
	<tr>
		<td width="90px">&nbsp;</td>
		<td width="335px" align="left"><?php echo $numoc;?></td>
		<td width="65px" align="left">&nbsp;</td>
		<td align="left"><?php echo $telefono;?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="impresion">
	<tr>
		<td width="20px">&nbsp;</td>
	</tr>
	<tr>
		<td width="100px">&nbsp;</td>
		<td width="100px">&nbsp;</td>
		<td width="50px">&nbsp;</td>
		<td align="center"><b>NO CONSTITUYE VENTA, SOLO TRASLADO.</b></td>
	</tr>
</table>
<?php
		for($i=1; $i<=5; $i++){
			echo '<br />';
		}
	}?>
<table border="0" cellpadding="0" cellspacing="0" class="impresion">
	<tr>
		<td width="50px" align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?></td>
		<td width="10px">&nbsp;</td>
		<td width="10px">&nbsp;</td>
		<td width="10px">&nbsp;</td>
		<td width="425px">&nbsp;<?php echo '['.$rst["strCodigo"].' - '.$rst["strUnidad"].']  '.$rst["strDescripcion"];?></td>
		<td width="10px">&nbsp;</td>
		<td width="100px" align="right">&nbsp;</td>
	</tr>
</table>
<?php
	if($ln==30){
		$stmt2=mssql_query("EXEC Bodega..sp_getComunasXBodega '$bodega'", $cnx);
		while($rst=mssql_fetch_array($stmt)) $comunas.=$rst["strDetalle"].', ';
		mssql_free_result($stmt);
		if($comunas!='') $comunas=substr($comunas, 0, strlen($comunas)-2).'.';
		?>
<br /><br /><br />
<table border="0" cellpadding="0" cellspacing="0" class="impresion">
	<tr>
		<td width=90>&nbsp;</td><td align="center"><?php echo $comunas;?></td>
	</tr>
</table>
<?php
	}
}
mssql_free_result($stmt);
if($ln!=30){
	$stmt=mssql_query("EXEC Bodega..sp_getComunasXBodega '$bodega'", $cnx);
	while($rst=mssql_fetch_array($stmt)) $comunas.=$rst["strDetalle"].', ';
	mssql_free_result($stmt);
	if($comunas!='') $comunas=substr($comunas, 0, strlen($comunas)-2).'.';?>
<br /><br /><br />
<table border="0" cellpadding="0" cellspacing="0" class="impresion">
	<tr>
		<td width=90>&nbsp;</td><td align="center"><?php echo $comunas;?></td>
	</tr>
</table>
<?php
}
mssql_close($cnx);
?>
</body>
</html>

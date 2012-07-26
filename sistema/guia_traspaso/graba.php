<?php
include '../conexion.inc.php';

$accion  = $_POST["accion"];
$usuario = $_POST["usuario"];
$numero = $_POST["txtNumero"] != '' ? $_POST["txtNumero"] : $_POST["hdnNumero"];
$fecha = $_POST["txtFecha"];
$cargo = $_POST["hdnCargo"];
$observacion = $_POST["txtObservacion"];

if($accion=='G'){ 
	$stmt = mssql_query("EXEC sp_setGuiaTraspaso 0, '$usuario', $numero, '".formato_fecha($fecha, false, true)."', '$cargo', '$observacion'", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$error = $rst["dblError"];
		$despacho = $rst["dblNDespacho"];
		$ingreso = $rst["dblNIngreso"];
	}
	mssql_free_result($stmt);
}

$fecha = substr($fecha, 0, 2).' de '.mes(substr($fecha, 3, 2)).' de '.substr($fecha, 6);
$stmt = mssql_query("EXEC General..sp_getMoviles 0, '$bodega', '$codigo', 'E'", $cnx);
if($rst=mssql_fetch_array($stmt)) $nombre=$rst["strNombre"];
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gu&iacute;a de Despacho</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	if(parseInt('<?php echo $error;?>') == 0){
		if('<?php echo $accion;?>' == 'G')
			alert('Se han creado los siguientes documentos: \rG.Despacho Nº <?php echo $despacho;?>\rG.Ingreso Nº <?php echo $ingreso;?>');
			
		parent.Deshabilita(true);
		parent.document.getElementById('btnImprimir').disabled=false;
		parent.document.getElementById('btnGrabar').disabled=true;
		parent.document.getElementById('btnNueva').disabled=false;
		self.focus();
		self.print();
	}else if(parseInt('<?php echo $error;?>') == 1)
		alert('El número de guía ingresado ya existe.');
	else if(parseInt('<?php echo $error;?>') == 2)
		alert('No ha sido posible obtener la bodega del cargo.');
	else if(parseInt('<?php echo $error;?>') == 3)
		alert('No ha sido posible obtener el número interno de la guía.');
	else if(parseInt('<?php echo $error;?>') == 4)
		alert('No ha sido posible obtener el número correlativo de la guía.');
}
-->
</script>
<body topmargin="600px" rightmargin="0px" leftmargin="0px" onLoad="javascript: Load();">
<?php
$ln=35;

$stmt = mssql_query("EXEC sp_getDetalleDespacho 0, $numero", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$ln++;
	if($ln > 35){
		$ln=1;
		for($espacio=1; $espacio<=13; $espacio++){
			echo '<br />';
		}?>
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
		<td width="335px" align="left"><?php echo $numoc;?></td>
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
		<td width="425px"><input class="txt-sborde" style="width:100%" value="&nbsp;<?php echo '['.trim($rst["strCodigo"]).' - '.trim($rst["strUnidad"]).']  '.trim($rst["strDescripcion"]);?>" /></td>
		<td width="10px">&nbsp;</td>
		<td width="100px" align="right">&nbsp;</td>
	</tr>
</table>
<?php
	if($ln == 35){
		$stmt2 = mssql_query("EXEC sp_getComunasXBodega '$bodega'", $cnx);
		while($rst2 = mssql_fetch_array($stmt2)) $comunas .= $rst2["strDetalle"].', ';
		mssql_free_result($stmt2);
		if($comunas != '') $comunas = substr($comunas, 0, strlen($comunas)-2).'.';?>
<br /><br /><br />
<table border="0" cellpadding="0" cellspacing="0">
	<tr><td align="center"><?php echo $comunas;?></td></tr>
</table>
<?php
	}
}
mssql_free_result($stmt);
if($ln!=35){
	$stmt = mssql_query("EXEC sp_getComunasXBodega '$bodega'", $cnx);
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
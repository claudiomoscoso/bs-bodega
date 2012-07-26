<?php
include '../autentica.php';
include '../conexion.inc.php';
$numCC=$_GET["numCC"] != '' ? $_GET["numCC"] : $_POST["numCC"];

$stmt = mssql_query("EXEC Bodega..sp_getCajaChica 0, $numCC", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$bodCC=$rst["strBodega"];
	$numint=$rst["dblNum"];
	$codresp=$rst["strCargo"];
	$responsable=$rst["strNombre"];
	$fecha=$rst["dtmFch"];
	$observacion=$rst["strNota"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gu&iacute;a de Ingreso por Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 150);
	document.getElementById('frmDetalle').src="detalle_orden_compra.php?numOC=<?php echo $numOC;?>&usuario=<?php echo $usuario;?>"
}

function Siguiente(){
	var detalle='', totfil=frmDetalle.document.getElementById('totfil').value;
	for(i=1; i<=totfil; i++){
		if(frmDetalle.document.getElementById('chk'+i).checked)
			detalle+=(frmDetalle.document.getElementById('codigo'+i).value+'-'+frmDetalle.document.getElementById('cant'+i).value)+'&&&';
	}
	if(detalle!=''){
		detalle=detalle.substr(0, detalle.length-3);
		document.getElementById('cantingr').value=detalle;
		document.getElementById('frm').submit();
	}else
		alert('Debe seleccionar al menos un ítem.');
}
//-->
</script>
<body onload="javascript: Load();">
<center>
<form name="frm" id="frm" method="post" action="guia_ingreso.php">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="4%" align="left"><b>&nbsp;N&uacute;mero</b></td>
					<td width="1%"><b>:</b></td>
					<td width="20%" align="left">&nbsp;<?php echo $numint;?></td>
					<td width="4%" align="left"><b>&nbsp;Responsable</b></td>
					<td width="1%"><b>:</b></td>
					<td align="left">&nbsp;<?php echo $responsable;?></td>
				</tr>
				<tr>
					<td align="left" valign="top"><b>&nbsp;Fecha</b></td>
					<td valign="top"><b>:</b></td>
					<td align="left" valign="top">&nbsp;<?php echo $fecha;?></td>
					<td valign="top"><b>&nbsp;Nota</b></td>
					<td valign="top"><b>:</b></td>
					<td align="left">
						<textarea name="observacion" id="observacion" class="txt-plano" style="width:99%" readonly="readonly"><?php echo $observacion;?></textarea>
					</td>
				</tr>
	
			</table>
		</td>
	</tr>
	<tr><td height="5px" colspan="2"></td></tr>
	<tr>
		<td colspan="2">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr style="background-image:url(../images/borde_med.png);" height="25px">
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="53%" align="left">&nbsp;Detalle</th>
					<th width="10%">Unidad</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="2%">&nbsp;</th>
					<th width="10%">Ingresa...</th>
					<th width="2%">&nbsp;</th>
				</tr>
				<tr><td colspan="8"><iframe name="frmDetalle" id="frmDetalle" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" ></iframe></td></tr>
			</table>
		</td>
	</tr>
	<tr><td height="5px" colspan="2"><hr></td></tr>
	<tr>
		<td align="left" width="50%">
			<input type="button" name="btnAnt" id="btnAnt" class="boton" style="width:90px" value="<< Anterior"
				onClick="javascript: self.location.href='index.php<?php echo $parametros;?>';"
			>
		</td>
		<td align="right">
			<input type="button" name="btnSgte" id="btnSgte" class="boton" style="width:90px" value="Siguiente >>" 
				onClick="javascript: Siguiente();"
			>
		</td>
	</tr>
</table>
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>">
<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>">
<input type="hidden" name="login" id="login" value="<?php echo $login;?>">

<input type="hidden" name="numOC" id="numOC" value="<?php echo $numOC;?>">
<input type="hidden" name="ultima" id="ultima" value="<?php echo $ultima;?>">
<input type="hidden" name="bodOC" id="bodOC" value="<?php echo $bodOC;?>">
<input type="hidden" name="proveedor" id="proveedor" value="<?php echo $codprov;?>">
<input type="hidden" name="cantingr" id="cantingr" />
</form>
</center>
</body>
</html>
<?php
mssql_close($cnx);
?>
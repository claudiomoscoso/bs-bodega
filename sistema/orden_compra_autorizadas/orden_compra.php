<?php
include '../conexion.inc.php';
$numero=$_GET["numero"];

$stmt = mssql_query("EXEC sp_getOrdenCompra 'OC', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$bodega=$rst["strBodega"];
	$numusu=$rst["dblUltima"];
	$fpago=$rst["Forma_Pago"];
	$fecha=$rst["dtmFecha"];
	$proveedor=$rst["strNombre"];
	$rut=$rst["strRut"];
	$direccion=$rst["strDireccion"];
	$comuna=$rst["Comuna"];
	$telefono=$rst["strTelefono"];
	$fax=$rst["strFax"];
	$nota=$rst["strObservacion"];
	$cargo=$rst["Cargo"];
	$contacto=$rst["strContacto"];
	$factor=$rst["dblIva"];
	$tipodoc=$rst["strTipoDoc"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC General..sp_getCargo '$bodega'", $cnx);
if($rst=mssql_fetch_array($stmt)) $contrato=$rst["strDetalle"];
mssql_free_result($stmt);

$stmt = mssql_query("EXEC sp_getDatosUsuario 'OCA', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)) $nombsol=$rst["nombre"];
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	var totfil=document.getElementById('totfil').value;
	if(totfil>7)
		document.getElementById('tbl').width='97%';
	else
		document.getElementById('tbl').width='100%';
}
-->
</script>
<body leftmargin="0" rightmargin="0" topmargin="0" onload="javascript: Load();"  bgcolor="#FFFFFF">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr><td colspan="11" height="2px"></td></tr>
	<tr>
		<td width="13%" align="left" nowrap="nowrap">&nbsp;<b>Orden de Compra N&deg;</b></td>
		<td width="1%">:</td>
		<td width="20%" align="left">&nbsp;<?php echo $numusu;?></td>
		<td width="1%">&nbsp;</td>
		<td width="11%" align="left" nowrap="nowrap">&nbsp;<b>Forma de Pago</b></td>
		<td width="1%">:</td>
		<td width="20%" align="left">&nbsp;<?php echo $fpago;?></td>
		<td width="1%">&nbsp;</td>
		<td width="5%" align="left">&nbsp;<b>Fecha</b></td>
		<td width="1%">:</td>
		<td width="20%" align="left">&nbsp;<?php echo $fecha;?></td>
	</tr>
	<tr>
		<td align="left">&nbsp;<b>Proveedor</b></td>
		<td>:</td>
		<td align="left" colspan="5">&nbsp;<?php echo $proveedor;?></td>
		<td>&nbsp;</td>
		<td align="left">&nbsp;<b>R.U.T.</b></td>
		<td>:</td>
		<td align="left">&nbsp;<?php echo $rut;?></td>
	</tr>
	<tr>
		<td align="left">&nbsp;<b>Direcci&oacute;n</b></td>
		<td>:</td>
		<td align="left" colspan="9">&nbsp;<?php echo $direccion;?></td>
	</tr>
	<tr>
		<td align="left">&nbsp;<b>Comuna</b></td>
		<td>:</td>
		<td align="left">&nbsp;<?php echo $comuna;?></td>
		<td>&nbsp;</td>
		<td align="left">&nbsp;<b>Tel&eacute;fono</b></td>
		<td width="1%">:</td>
		<td align="left">&nbsp;<?php echo $telefono;?></td>
		<td>&nbsp;</td>
		<td align="left">&nbsp;<b>Fax</b></td>
		<td>:</td>
		<td align="left">&nbsp;<?php echo $fax;?></td>
	</tr>
	<tr>
		<td align="left" valign="top">&nbsp;<b>Nota</b></td>
		<td valign="top">:</td>
		<td align="left" colspan="9"><textarea name="obs" id="obs" class="txt-sborde" style="width: 100%; font-size:11px" readonly="true"><?php echo $nota;?></textarea></td>
	</tr>
	<tr>
		<td align="left">&nbsp;<b>Contrato</b></td>
		<td>:</td>
		<td align="left" colspan="9">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="46%" align="left">&nbsp;<?php echo $contrato;?></td>
					<td width="1%">&nbsp;</td>
					<td width="5%" align="left">&nbsp;<b>Cargo</b></td>
					<td width="1%">:</td>
					<td width="46%" align="left">&nbsp;<?php echo $cargo;?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="left">&nbsp;<b>Contacto</b></td>
		<td>:</td>
		<td align="left" colspan="9">&nbsp;<?php echo $contacto;?></td>
	</tr>
	<tr><td height="2px" colspan="11"></td></tr>
</table>
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<th width="5%">N&deg;</th>
		<th width="10%">C&oacute;digo</th>
		<th align="left">&nbsp;Descripci&oacute;n</th>
		<th width="10%">Unidad</th>
	<?php
	if($tipodoc=='O'){?>
		<th width="10%">F.Inicio</th>
		<th width="10%">F.T&eacute;rmino</th>
	<?php
	}
	?>
		<th width="10%">Cantidad</th>
		<th width="10%">Valor</th>
		<th width="10%">Total</th>
	</tr>
</table>
<div style="position:relative; width:100%; height:105px; overflow:auto; background-color:#FFFFFF">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<?php
$stmt = mssql_query("EXEC sp_getDetalleOrdenCompra 0, $numero", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="5%" align="center"><?php echo $cont;?></td>
		<td width="10%" align="center"><?php echo $rst["strCodigo"];?></td>
		<td align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
		<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
	<?php
	if($tipodoc=='O'){?>
		<td width="10%" align="center"><?php echo $rst["dtmFchIni"];?></td>
		<td width="10%" align="center"><?php echo $rst["dtmFchTer"];?></td>
	<?php
	}
	?>
		<td width="10%" align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblValor"],0,'','.');?>&nbsp;</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblCantidad"]*$rst["dblValor"],0,'','.');?>&nbsp;</td>
	</tr>
<?php
	$neto+=$rst["dblCantidad"]*$rst["dblValor"];
}
mssql_free_result($stmt);?>
</table>
</div>
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr><td><hr/></td></tr>
	<tr>
		<td align="right">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td rowspan="4" valign="top" align="left">&nbsp;<?php echo $nombsol;?></td>
				</tr>
				<tr>
					<td width="10%" align="right"><b>Neto</b>&nbsp;</td>
					<td width="1%">:</td>
					<td width="10%"><?php echo number_format($neto,0,'','.');?>&nbsp;</td>
				</tr>
				<tr>
					<td width="10%" align="right"><b>I.V.A.</b>&nbsp;</td>
					<td width="1%">:</td>
					<td width="15%"><?php echo number_format($neto*$factor,0,'','.');?>&nbsp;</td>
				</tr>
				<tr>
					<td width="10%" align="right"><b>Total</b>&nbsp;</td>
					<td width="1%">:</td>
					<td width="15%"><?php echo number_format($neto*($factor+1),0,'','.');?>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td></td></tr>
	<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
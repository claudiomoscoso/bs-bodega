<?php
include '../conexion.inc.php';
$numero = $_GET["numero"];
$perfil = $_GET["perfil"];
$stmt = mssql_query("EXEC sp_getOrdenCompra 'OC', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$bodega=$rst["strBDestino"];
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
	$totobs=$rst["dblTotObs"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC General..sp_getCargos 1, '$bodega'", $cnx);
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
	if('<?php echo $totobs;?>'=='0')
		parent.document.getElementById('btnObs').style.visibility='hidden';
	else
		parent.document.getElementById('btnObs').style.visibility='visible';
}
-->
</script>
<body leftmargin="0" rightmargin="0" topmargin="0" onload="javascript: Load();"  bgcolor="#FFFFFF">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">

	<tr>
		<td width="10%" align="left" nowrap="nowrap">&nbsp;<b>O.Compra N&deg;</b></td>
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
		<td align="left" colspan="9"><input name="obs" id="obs" class="txt-sborde" style="width: 100%; font-size:11px" readonly="true" value="&nbsp;<?php echo $nota;?>" /></td>
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
	
</table>
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<th width="3%">N&deg;</th>
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
		<th width="10%" align="right">Cantidad&nbsp;</th>
		<th width="10%" align="right">Valor&nbsp;</th>
		<th width="10%" align="right">Total&nbsp;</th>
		<th width="2%">&nbsp;</th>
	</tr>
</table>

<div style="position:relative; width:100%; height:90px; overflow:scroll;" >
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="1" >
<?php
$stmt = mssql_query("EXEC sp_getDetalleOrdenCompra 0, $numero", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	echo '<td width="3%" align="center">'.$cont.'</td>';
	echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
	echo '<td align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
	echo '<td width="10%" align="center">'.$rst["strUnidad"].'</td>';
	if($tipodoc=='O'){
		echo '<td width="10%" align="center">'.$rst["dtmFchIni"].'</td>';
		echo '<td width="10%" align="center">'.$rst["dtmFchTer"].'</td>';
	}
	echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblValor"], 0, '', '.').'&nbsp;</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"] * $rst["dblValor"], 0, '', '.').'&nbsp;</td>';
	echo '</tr>';
	$neto += $rst["dblCantidad"]*$rst["dblValor"];
}
mssql_free_result($stmt);?>
</table>
</div>
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td align="right">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td rowspan="4" valign="top" align="left">&nbsp;
					<?php 
						echo '<b>Hecha por:</b> '.$nombsol;
						$stmt = mssql_query("SELECT DISTINCT Nombre FROM Autorizaciones Left Join General..Usuarios ON strAutoriza=usuario WHERE dblNumero=$numero AND strAccion='1'", $cnx);
						if($rst=mssql_fetch_array($stmt)) echo '<br>&nbsp;<b>  V&deg;B&deg;:</b> '.$rst["Nombre"];
						mssql_free_result($stmt);
					?>
					</td>
				</tr>
				<tr>
					<td width="10%" align="right"><b>Neto</b>&nbsp;</td>
					<td width="1%">:</td>
					<td width="10%"><?php echo number_format($neto,0,'','.');?>&nbsp;</td>
				</tr>
<!--				<tr>
					<td width="10%" align="right"><b>I.V.A.</b>&nbsp;</td>
					<td width="1%">:</td>
					<td width="15%"><?php echo number_format($neto*$factor,0,'','.');?>&nbsp;</td>
				</tr>-->
				<tr>
					<td width="10%" align="right"><b>Total</b>&nbsp;</td>
					<td width="1%">:</td>
					<td width="15%"><?php echo number_format($neto*($factor+1),0,'','.');?>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>

	<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>

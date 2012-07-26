<?php
include '../autentica.php';
include '../conexion.inc.php';

$numOC = $_GET["numOC"] != '' ? $_GET["numOC"] : $_POST["numOC"];

$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'OC', $numOC", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$bdestino = $rst["strBDestino"];
	$cargo = $rst["strCargo"];;
	$ultima=$rst["dblUltima"];
	$codprov=$rst["strProveedor"];
	$proveedor=$rst["strNombre"];
	$fecha=$rst["dtmFecha"];
	$observacion=$rst["strObservacion"];
}
mssql_free_result($stmt);

$renueva = 0;
$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'ROC2', $numOC", $cnx);
if($rst = mssql_fetch_array($stmt)) $renueva = 1;
mssql_free_result($stmt);

switch($perfil){
	case 'informatica':
	case 'admin.contrato':
	case 'admin.contrato.m':
	case 'c.operaciones':
		$bloquea = 1;
		break;
	default:
		$bloquea = 0;
		break;
}

mssql_query("EXEC Bodega..sp_setTMPDetalleOrdenCompra 9, '$usuario', NULL, $numOC", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Renovaci&oacute;n Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 137);
	if(parseInt('<?php echo $renueva;?>') == 0 || parseInt('<?php echo $bloquea;?>') == 0) document.getElementById('btnRenueva').disabled = true;
}

function Renueva(){
	document.getElementById('frm').submit();
}
//-->
</script>
<body onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<form name="frm" id="frm" method="post" action="grabar.php" target="transaccion">
	<tr>
		<td colspan="2">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="4%" align="left"><b>&nbsp;N&uacute;mero</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="15%" align="left">&nbsp;<?php echo $ultima;?></td>
					<td width="4%" align="left"><b>&nbsp;Proveedor</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="30%" align="left"><input class="txt-sborde" style="width:99%; background-color:#ECECEC" readonly="true" value="&nbsp;<?php echo $proveedor;?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="3%"><b>&nbsp;Obra</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="0%">
						<select name="cmbCargo" id="cmbCargo" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getCargos 9, '$bdestino'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'" '.($cargo == $rst["strCodigo"] ? 'selected' : '').'>'.$rst["strCargo"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top"><b>&nbsp;Fecha</b></td>
					<td align="center" valign="top"><b>:</b></td>
					<td align="left" valign="top">&nbsp;<?php echo $fecha;?></td>
					<td valign="top"><b>&nbsp;Observaci&oacute;n</b></td>
					<td align="center" valign="top"><b>:</b></td>
					<td align="left" colspan="5"><textarea name="observacion" id="observacion" class="txt-plano" style="width:100%" ><?php echo $observacion;?></textarea></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td colspan="2">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr style="background-image:url(../images/borde_med.png);" height="25px">
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="41%" align="left">&nbsp;Detalle</th>
					<th width="10%">Unidad</th>
					<th width="12%">F.Inicio</th>
					<th width="12%">F.T&eacute;rmino</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
				<tr><td colspan="8"><iframe name="frmDetalle" id="frmDetalle" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" src="detalle_orden_compra.php?numOC=<?php echo $numOC;?>&usuario=<?php echo $usuario;?>"></iframe></td></tr>
			</table>
		</td>
	</tr>
	<tr><td height="5px" colspan="2"><hr></td></tr>
	<tr>
		<td align="left" width="50%">
			<input type="button" name="btnAtras" id="btnAtras" class="boton" style="width:90px" value="<< Anterior"
				onclick="javascript: self.location.href='index.php<?php echo $parametros;?>';"
			>
		</td>
		<td align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
			<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>">
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>">
			<input type="hidden" name="login" id="login" value="<?php echo $login;?>">
			
			<input type="hidden" name="numOC" id="numOC" value="<?php echo $numOC;?>">
			<input type="button" name="btnRenueva" id="btnRenueva" class="boton" style="width:90px" value="Renueva" 
				onclick="javascript: Renueva();"
			>
		</td>
	</tr>
</form>
</table>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
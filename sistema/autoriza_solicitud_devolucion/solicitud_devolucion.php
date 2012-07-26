<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$numero = $_GET["numero"];
$stmt = mssql_query("EXEC Bodega..sp_getSolicitudDevolucion 1, NULL, $numero", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$fecha = $rst["dtmFecha"];
	$cargo = $rst["strCargo"];
	$observacion = $rst["strObservacion"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Autorizar Solicitud de Devoluci&oacute;n</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 75);
	document.getElementById('detalle').src = 'detalle_solicitud_devolucion.php?numero=<?php echo $numero;?>';
	Deshabilita(false);
}

function Deshabilita(sw){
	document.getElementById('btnAprobar').disabled = sw;
	document.getElementById('btnRechazar').disabled = sw;
}
-->
</script>
<body onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%"><b>&nbsp;N&deg;Gu&iacute;a</b></td>
					<td width="1%"><b>:</b></td>
					<td width="10%">&nbsp;<?php echo $numero;?></td>
					<td width="1%">&nbsp;</td>
					<td width="5%"><b>&nbsp;Fecha</b></td>
					<td width="1%"><b>:</b></td>
					<td width="10%">&nbsp;<?php echo $fecha;?></td>
					<td width="1%">&nbsp;</td>
					<td width="5%"><b>&nbsp;Cargo</b></td>
					<td width="1%"><b>:</b></td>
					<td width="20%">&nbsp;<?php echo $cargo;?></td>
					<td width="1%">&nbsp;</td>
					<td width="5%"><b>&nbsp;Observacion</b></td>
					<td width="1%"><b>:</b></td>
					<td width="33%"><input class="txt-sborde" style="width:99%; background-color:#ececec" value="<?php echo $observacion;?>" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="65%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="detalle" id="detalle" frameborder="0" width="100%" scrolling="yes" src="../cargando.php"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnAprobar" id="btnAprobar" class="boton" style="width:90px" disabled="disabled" value="Aprobar..."
				onclick="
					Deshabilita(true);
					AbreDialogo('divContrasena', 'frmContrasena', 'contrasena.php?usuario=<?php echo $usuario;?>&numero=<?php echo $numero;?>&estado=1', true);
				"
			/>
			<input type="button" name="btnRechazar" id="btnRechazar" class="boton" style="width:90px" disabled="disabled" value="Rechazar..."
				onclick="
					Deshabilita(true);
					AbreDialogo('divContrasena', 'frmContrasena', 'contrasena.php?usuario=<?php echo $usuario;?>&numero=<?php echo $numero;?>&estado=0', true);
				"
			/>
	  </td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
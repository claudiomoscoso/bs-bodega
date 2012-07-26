<?php
include '../autentica.php';
include '../conexion.inc.php';
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
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 52);
}

function Buscar(){
	Deshabilita(true);
	var bodega = document.getElementById('cmbBodega').value;
	document.getElementById('detalle').src = 'detalle.php?usuario=<?php echo $usuario;?>&bodega=' + bodega;
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}
-->
</script>
<body onload="javascript: Load();">
<div id="divSolicitud" style="z-index:1; position:absolute; top:5px; left:10%; width:80%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr style="background-image:url(../images/borde_med.png)" height="20px">
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											CierraDialogo('divContrasena', 'frmContrasena');
											CierraDialogo('divSolicitud', 'frmSolicitud');
											Deshabilita(false);
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td id="titulo" align="center"><b>Solicitud de Devoluci&oacute;n</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmSolicitud" id="frmSolicitud" frameborder="0" scrolling="no" width="100%" height="310px" marginwidth="0" marginheight="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divContrasena" style="z-index:5; position:absolute; top:30px; left:37%; width:25%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr style="background-image:url(../images/borde_med.png)" height="20px">
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											CierraDialogo('divContrasena', 'frmContrasena');
											frmSolicitud.Deshabilita(false);
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td id="titulo" align="center"><b>Contrase&ntilde;a</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmContrasena" id="frmContrasena" frameborder="0" scrolling="no" width="100%" height="90px" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr >
					<td width="5%">&nbsp;Bodega</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strBodega"]).'" '.(trim($rst["strBodega"]) == $bodega ? 'select="select"' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%" >
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="15%" align="left">&nbsp;Tipo de Solicitud</th>
					<th width="10%">Fecha</th>
					<th width="10%">N&deg;Gu&iacute;a</th>
					<th width="20%" align="left">&nbsp;Solicitante</th>
					<th width="40%" align="left">&nbsp;Observaci&oacute;n</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="detalle" id="detalle" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Autorizar Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Deshabilita(sw){
	document.getElementById('observacion').disabled=sw;
	if(document.getElementById('btnAutorizar')) document.getElementById('btnAutorizar').disabled=sw;
	document.getElementById('btnRechazar').disabled=sw;
	document.getElementById('btnAnula').disabled=sw;
	document.getElementById('btnCerrar').disabled=sw;
}

function Envia(estado){
	Deshabilita(true)
	var numero = document.getElementById('numero').value;
	var numsol = document.getElementById('numsol').value;
	var observacion=document.getElementById('observacion').value;
	AbreDialogo('divContrasena', 'frmContrasena', 'contrasena.php?usuario=<?php echo $usuario;?>&estado='+estado+'&numero='+numero+'&numsol='+numsol+'&observacion='+observacion);
}

function Load(){
	document.getElementById('frmAdquisiciones').setAttribute('height', (window.innerHeight / 2) - 25);
	document.getElementById('frmOperaciones').setAttribute('height', (window.innerHeight / 2) - 25);
	
	document.getElementById('frmAdquisiciones').src = 'orden_compra_adquisiciones.php?perfil=<?php echo $perfil;?>'
	document.getElementById('frmOperaciones').src = 'orden_compra_operaciones.php?perfil=<?php echo $perfil;?>'
}
-->
</script>
<body onLoad="javascript: Load()">
<div id="divEstado" style="z-index:500; position:absolute; top:5px; left:30%; width:40%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr height="15px" style="background-image:url(../images/borde_med.png)">
					<td width="15px">
						<a href="#" title="Cierra cuadro de dialogo."
							onclick="javascript: CierraDialogo('divEstado', 'frmEstado');"
							onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true;"
						><img border="0" src="../images/close.png" /></a>
					</td>
					<td align="center"><b>&nbsp;Seguimiento</b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmEstado" id="frmEstado" width="100%" height="150px" frameborder="0" scrolling="no" src="../cargando.php"></iframe></td></tr>
</table>
</div>

<div id="divObservacion" style="z-index:2; position:absolute; top:5px; left:30%; width:40%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr height="15px" style="background-image:url(../images/borde_med.png)">
					<td width="15px">
						<a href="#" title="Cierra cuadro de dialogo."
							onclick="javascript: CierraDialogo('divObservacion', 'frmObservacion');"
							onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true;"
						><img border="0" src="../images/close.png" /></a>
					</td>
					<td align="center"><b>&nbsp;Observaciones</b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmObservacion" id="frmObservacion" width="100%" height="150px" frameborder="0" scrolling="yes" src="../cargando.php"></iframe></td></tr>
</table>
</div>

<div id="divOCompra" style="z-index:1; position:absolute; top:5px; left:5%; width:90%; visibility:hidden">
<table border="1" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td class="menu_principal">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="center" valign="bottom" width="15px">
									<a href="#" title="Cierra la ventana"
										onClick="javascript: 
											CierraDialogo('divOCompra', 'frmOCompra');
											CierraDialogo('divContrasena', 'frmContrasena');
											Deshabilita(false);
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center"><b>Orden de Compra</b></td>
								<td align="center" valign="bottom" width="15px">
									<a id="btnObs" href="#" title="Ver observaciones"
										onClick="javascript: AbreDialogo('divObservacion', 'frmObservacion', 'observaciones.php?numero='+document.getElementById('numero').value);"
										onMouseOver="javascript: window.status='Ver observaciones.'; return true;"
									><img border="0" src="../images/clip.gif"></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmOCompra" id="frmOCompra" frameborder="0" scrolling="no" width="100%" height="240px" marginwidth="0" marginheight="0" src="../cargando.php"></iframe></td></tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="5%">&nbsp;<b>Observaci&oacute;n</b></td>
								<td width="1%">:</td>
								<td>
									<input name="observacion" id="observacion" class="txt-plano" style="width:99%;border-width:0"
										onFocus="javascript: CambiaColor(this.id, true);"
										onBlur="javascript: CambiaColor(this.id, false);"
									>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr></tr>
				<tr>
					<td align="right">
					<?php
					if($perfil == 'gerente' || $perfil == 'informatica' || $perfil == 'sg.operaciones'){?>
						<input type="button" name="btnAutorizar" id="btnAutorizar" value="Autorizar" class="boton" style="width:90px" 
							onClick="javascript: Envia('A');"
						>
					<?php
					}
					?>
						<input type="button" name="btnRechazar" id="btnRechazar" value="Rechazar" class="boton" style="width:90px" 
							onClick="javascript: Envia('R');"
						>
						<input type="button" name="btnAnula" id="btnAnula" value="Anula" class="boton" style="width:90px" 
							onClick="javascript: Envia('N');"
						>
						<input type="button" name="btnCerrar" id="btnCerrar" value="Cerrar" class="boton" style="width:100px" 
							onclick="javascript: 
								CierraDialogo('divContrasena', 'frmContrasena');
								CierraDialogo('divOCompra', 'frmOCompra');
								Deshabilita(false);
							"
						>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divContrasena" style=" z-index: 1; position:absolute; top:20%; left:35%; width:30%; height:110px; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											CierraDialogo('divContrasena', 'frmContrasena');
											Deshabilita(false);
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="font-size:12px"><b>Contrase&ntilde;a</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmContrasena" id="frmContrasena" frameborder="0" scrolling="no" width="100%" height="90px" src="../blank.html"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="20%" align="left">&nbsp;Cargo</th>
					<th width="10%">N&uacute;mero</th>
					<th width="10%">Fecha</th>
					<th width="10%">Total</th>
					<th width="25%" align="left">&nbsp;Observaci&oacute;n</th>
					<th width="10%">Fecha Sol.</th>
					<th width="10%">Estado</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="color:#FFFFFF; font-weight:bold; background-image:url(../images/borde_menu.gif)"><td colspan="7">&nbsp;Ordenes de Compra de Adquisiciones</td></tr>
	<tr><td><iframe name="frmAdquisiciones" id="frmAdquisiciones" width="100%" frameborder="0" scrolling="yes"></iframe></td></tr>
	<tr style="color:#FFFFFF; font-weight:bold; background-image:url(../images/borde_menu.gif)"><td colspan="7">&nbsp;Ordenes de Compra de Operaciones</td></tr>
	<tr><td><iframe name="frmOperaciones" id="frmOperaciones" width="100%" frameborder="0" scrolling="yes"></iframe></td></tr>
</table>
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
<input type="hidden" name="numero" id="numero">
<input type="hidden" name="numsol" id="numsol">
</body>
</html>

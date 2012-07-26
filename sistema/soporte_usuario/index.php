<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Soporte a Usuarios</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" >
<!--
function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 88);
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 8 || tecla == 46){
		ctrl.value = '';
		return true;
	}else if(tecla == 9)
		return true;
	else
		return false;
}

function Buscar(){
	var usuario = document.getElementById('cmbUsuario').value;
	var problema = document.getElementById('txtProblema').value;
	var fecha = document.getElementById('txtFecha').value;
	var solucionado = (document.getElementById('chkListo').checked ? 1 : 0);
	Deshabilita(true);
	document.getElementById('resultado').src = 'resultado.php?usuario=' + usuario + '&problema=' + problema + '&fecha=' + fecha + '&solucionado=' + solucionado;
}

function Deshabilita(sw){
	document.getElementById('cmbUsuario').disabled = sw;
	document.getElementById('txtProblema').disabled = sw;
	document.getElementById('txtFecha').disabled = sw;
	document.getElementById('imgFecha').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('chkListo').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnNuevo').disabled = sw;
}
-->
</script>
<body onload="javascript: Load();">
<div id="divSoporte" style="z-index:500; position:absolute; top:5px; left:3%; width:95%; visibility:hidden">
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
											CierraDialogo('divSoporte', 'frmSoporte');
											Deshabilita(false);
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td id="titulo" align="center"><b>Soporte a Usuario</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmSoporte" id="frmSoporte" frameborder="0" scrolling="no" width="100%" height="272px" marginwidth="0" marginheight="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divCalendario" style="z-index:0; position:absolute; top:20px; left:57%; width:20%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr style="background-image:url(../images/borde_med.png)" height="20px">
								<td align="right" valign="middle" width="15px">
									<a href="#" title="Cierra calendario." 
										onClick="javascript: 
											CierraDialogo('divCalendario', 'frmCalendario');
											Deshabilita(false);
										"
										onMouseOver="javascript: window.status='Cierra calendario.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td id="titulo" align="center">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmCalendario" id="frmCalendario" frameborder="0" scrolling="no" width="100%" height="120px" marginwidth="0" marginheight="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="6%">&nbsp;Usuario</td>
					<td width="1%" align="center">:</td>
					<td width="20%">
						<select name="cmbUsuario" id="cmbUsuario" class="sel-plano" style="width:100%">
							<option value="all">Todos</option>
						<?php
						$stmt = mssql_query("EXEC General..sp_getUsuarios 1", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["usuario"].'">'.$rst["nombre"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="6%">&nbsp;Problema</td>
					<td width="1%" align="center">:</td>
					<td width="20%">
						<input name="txtProblema" id="txtProblema" class="txt-plano" style="width:99%" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Fecha</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtFecha" id="txtFecha" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a id="aFecha" href="#" title="Abre calendario"
							onblur="javascript: CambiaImagen('imgFecha', false);"
							onclick="javascript:
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFecha&fecha='+document.getElementById('txtFecha').value);
							"
							onfocus="javascript: CambiaImagen('imgFecha', true);"
							onmouseover="javascript: window.status='Abre calendario'; return true;"
						><img id="imgFecha" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="2%" align="center"><input type="checkbox" name="chkListo" id="chkListo" /></td>
					<td width="10%">&nbsp;Solucionados</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar();"
						/>
					</td>
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
					<th width="15%">Fecha</th>
					<th width="20%" align="left">&nbsp;Solicitante</th>
					<th width="20%" align="left">&nbsp;Motivo</th>
					<th width="25%" align="left">&nbsp;Soluci&oacute;n</th>
					<th width="15%">&nbsp;Solucionado</th>
					<th width="2%" >&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnNuevo" id="btnNuevo" class="boton" style="width:90px" value="Nuevo..." 
				onclick="javascript:
					Deshabilita(true);
					AbreDialogo('divSoporte', 'frmSoporte', 'soporte.php?modulo=0');
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
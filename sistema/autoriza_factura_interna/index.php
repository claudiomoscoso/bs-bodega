<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Autoriza Factura Interna</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl.id){
			case 'txtCargo':
				Deshabilita(true);
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_cargos.php?texto=' + ctrl.value);
				break;
		}
	}
}

function Load(){
	document.getElementById('divResultado').style.height = (window.innerHeight - 53) + 'px';
}

function Buscar(){
	var cargo = document.getElementById('hdnCargo').value;
	var estado = document.getElementById('cmbEstado').value;
	if(cargo == '')
		alert('Debe ingresar un cargo.');
	else{
		Deshabilita(true);
		var resultado = document.getElementById('divResultado');
		resultado.innerHTML = '<table border="0" width="100%" height="100%"><tr><td align="center"><img src="../images/cargando2.gif"></td></tr></table>';
		var ajax = new XMLHttpRequest();
		ajax.open('GET', 'resultado.php?cargo=' + cargo + '&estado=' + estado, true);
		ajax.onreadystatechange = function (){
			if(ajax.readyState == 4){
				resultado.innerHTML = ajax.responseText;
				Deshabilita(false);
			}
		}
		ajax.send(null);
	}
}

function Deshabilita(sw){
	document.getElementById('txtCargo').disabled = sw;
	document.getElementById('cmbEstado').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}
-->
</script>

<body onload="javascript: Load()">
<div id="divBuscador" style="z-index:10; position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra cuadro de busqueda."
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divBuscador', 'frmBuscador');
										"
										onmouseover="javascript: window.status='Cierra calendario.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px; font-weight:bold">Buscador</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmBuscador" id="frmBuscador" frameborder="1" style="border:thin" scrolling="no" width="100%" height="215px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divVPrevia" style="z-index:10; position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra cuadro de busqueda."
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divVPrevia', 'frmVPrevia');
											CierraDialogo('divContrasena', 'frmContrasena');
										"
										onmouseover="javascript: window.status='Cierra calendario.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px; font-weight:bold">Factura Interna</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmVPrevia" id="frmVPrevia" frameborder="1" style="border:thin" scrolling="no" width="100%" height="225px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divContrasena" style="z-index:10; position:absolute; top:30px; left:35%; width:30%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra cuadro de dialogo."
										onclick="javascript: 
											frmVPrevia.Deshabilita(false);
											CierraDialogo('divContrasena', 'frmContrasena');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px; font-weight:bold">Contrase&ntilde;a</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmContrasena" id="frmContrasena" frameborder="1" style="border:thin" scrolling="no" width="100%" height="125px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%">&nbsp;Cargo</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<input type="hidden" name="hdnCargo" id="hdnCargo" />
						<input name="txtCargo" id="txtCargo" class="txt-plano" style="width:99%" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') document.getElementById('hdnCargo').value = ''"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Estado</td>
					<td width="1%" align="center">:</td>
					<td width="20%">
						<select name="cmbEstado" id="cmbEstado" class="sel-plano" style="width:100%">
							
						<?php
						switch($perfil){
							case 'operaciones':
							case 's.operaciones':
							case 'sg.operaciones':
							case 'informatica':
								echo '<option value="all">Todos</option>';
								$stmt = mssql_query("SELECT strCodigo, strDetalle FROM General..Tablon WHERE strTabla = 'estfi' ORDER BY strCodigo", $cnx);
								break;
							case 'admin.contrato':
							case 'admin.contrato.e':
								$stmt = mssql_query("SELECT strCodigo, strDetalle FROM General..Tablon WHERE strTabla = 'estfi' AND strCodigo = 0 ORDER BY strCodigo", $cnx);
								break;
						}
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:80px" value="Buscar" 
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
					<th width="10%">Fecha</th>
					<th width="10%">N&deg;F.Interna</th>
					<th width="50%" align="left">&nbsp;Cargo</th>
					<th width="15%" align="left">&nbsp;Estado</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><div id="divResultado" style="height:100px;overflow:scroll;position:relative;width:100%"></div></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
<?php
include '../autentica.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Envio Orden de Trabajo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl.id){
			case 'txtNEnvio':
				var contrato = document.getElementById('cmbContrato').value;
				Deshabilita(true);
				AbreDialogo('divNumero', 'frmNumero', 'buscar_envios.php?contrato=' + contrato + '&texto=' + ctrl.value);
				break;
		}
	}else{
		return ValNumeros(evento, ctrl.id, false);
	}
}

function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 90);
	if('<?php echo $perfil;?>'=='j.cobranza'||'<?php echo $perfil;?>'=='valorizador'||'<?php echo $perfil;?>'=='informatica'){
	}else{
		document.getElementById('btnNuevo').disabled = true;
		document.getElementById('btnGuardar').disabled = true;
		document.getElementById('btnImprimir').disabled = true;
		document.getElementById('btnExportar').disabled = true;
		document.getElementById('btnReenvia').disabled = true;
	}
}

function Buscar(){
	var contrato = document.getElementById('cmbContrato').value;
	var nenvio = document.getElementById('txtNEnvio').value;
	var orden = document.getElementById('txtOrden').value;
	if(nenvio == '' && orden == '')
		alert('Debe ingresar el n�mero de envio o el de la orden.');
	else{
		Deshabilita(true);
		document.getElementById('resultado').src = 'resultado.php?modulo=0&usuario=<?php echo $usuario;?>&contrato=' + contrato + '&nenvio=' + nenvio + '&orden=' + orden;
	}
}

function Deshabilita(sw){
	document.getElementById('cmbContrato').disabled = sw;
	document.getElementById('txtNEnvio').disabled = sw;
	document.getElementById('txtOrden').disabled = sw;
	document.getElementById('chkAll').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnNuevo').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
	document.getElementById('btnImprimir').disabled = sw;
	document.getElementById('btnExportar').disabled = sw;
	document.getElementById('btnReenvia').disabled = sw;
	if('<?php echo $perfil;?>'=='j.cobranza'||'<?php echo $perfil;?>'=='valorizador'||'<?php echo $perfil;?>'=='informatica'){
	}else{
		document.getElementById('btnNuevo').disabled = true;
		document.getElementById('btnGuardar').disabled = true;
		document.getElementById('btnImprimir').disabled = false;
		document.getElementById('btnExportar').disabled = true;
		document.getElementById('btnReenvia').disabled = true;
		document.getElementById('chkAll').disabled = true;
	}	
	if(resultado.document.getElementById('totfil')){
		var totfil = resultado.document.getElementById('totfil').value;
		for(i = 1; i <= totfil; i++){
			if(resultado.document.getElementById('chkOrden' + i)) resultado.document.getElementById('chkOrden' + i).disabled = sw;
		}
	}
}

function EligeTodo(checked){
	var totfil = resultado.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(resultado.document.getElementById('chkOrden' + i)) resultado.document.getElementById('chkOrden' + i).checked = checked;
	}
	document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&envia=' + (checked ? 1 : 0);
}

function Exportar(){
	if(resultado.document.getElementById('totfil'))
		document.getElementById('transaccion').src = 'exportar.php?usuario=<?php echo $usuario;?>';
	else
		alert('No se ha encontrado informaci&oacute;n para exportar.')
}

function Guardar(){
	if(confirm('�Est� seguro que desea guardar los cambios realizados?')){
		var contrato = document.getElementById('cmbContrato').value;
		var envio = document.getElementById('txtNEnvio').value;
		if(envio == '')
			alert('Debe ingresar el n�mero del envio.');
		else{
			Deshabilita(true);
			document.getElementById('transaccion').src = 'transaccion.php?modulo=4&usuario=<?php echo $usuario;?>';
		}
	}
}

function Imprimir(){
	if(envio == '')
		alert('Debe ingresar el numero de envio.')
	else{
		var contrato = document.getElementById('cmbContrato').value;
		var envio = document.getElementById('txtNEnvio').value;
		if(envio != ''){
			if(confirm('¿Desea incluir las ordenes a la carta?'))
				document.getElementById('transaccion').src = 'grabar.php?modulo=2&contrato=' + contrato + '&envio=' + envio;
			else
				document.getElementById('transaccion').src = 'carta.php?contrato=' + contrato + '&envio=' + envio;			
		}else
			alert('Debe ingresar el numero de envio.');
	}
}
-->
</script>
<body onload="javascript: Load()">
<div id="divNumero" style="position:absolute; top:20px; left:24%; width:30%; visibility:hidden">
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
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divNumero', 'frmNumero');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&Uacute;ltimos Envios</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmNumero" id="frmNumero" frameborder="0" style="border:thin" scrolling="no" width="100%" height="145px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divEnvio" style="position:absolute; top:5px; left:2%; width:96%; visibility:hidden">
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
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divEnvio', 'frmEnvio');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>Envio Orden de Trabajo</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmEnvio" id="frmEnvio" frameborder="0" style="border:thin" scrolling="no" width="100%" height="300px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
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
					<td width="6%">&nbsp;Contrato</td>
					<td width="1%">:</td>
					<td width="30%">
						<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%">
							<option value="13000">Contrato ESVAL 2010</option>
							<option value="13001">Contrato AValle 2010</option>
							<option value="13054">Prueba de Sistema</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;N&deg;Envio</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtNEnvio" id="txtNEnvio" class="txt-plano" style="width:99%;text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;N&deg;Orden</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtOrden" id="txtOrden" class="txt-plano" style="width:99%;text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td>
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
			<table border="0" width="100%" cellpadding="0" cellspacing="0" >
				<tr>
					<th width="1%">
						<input type="checkbox" name="chkAll" id="chkAll" checked="checked" 
							onclick="javascript: EligeTodo(this.checked);"
						/>
					</th>
					<th width="2%">N&deg;</th>
					<th width="8%">Orden</th>
					<th width="15%">Fecha</th>
					<th width="20%" align="left">&nbsp;Direcci&oacute;n</th>
					<th width="18" align="left">&nbsp;Comuna</th>
					<th width="13%" align="left">&nbsp;Inspector</th>
					<th width="12%" align="right">Total&nbsp;</th>
					<th width="2%">E.P.</th>
					<th width="3%">N&deg;E</th>
					<th width="1%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: Guardar();"
			/>
			<input type="button" name="btnImprimir" id="btnImprimir" class="boton" style="width:90px" value="Imprimir..." 
				onclick="javascript: Imprimir();"
			/>
			<input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
				onclick="javascript: Exportar();"
			/>
			<input type="button" name="btnNuevo" id="btnNuevo" class="boton" style="width:90px" value="Nuevo..." 
				onclick="javascript: 
					Deshabilita(true);
					var contrato = document.getElementById('cmbContrato').value;
					AbreDialogo('divEnvio', 'frmEnvio', 'envio.php?modulo=0&contrato=' + contrato + '&usuario=<?php echo $usuario;?>')
				"
			/>
			<input type="button" name="btnReenvia" id="btnReenvia" class="boton" style="width:90px" value="Reenviar..." 
				onclick="javascript: 
					Deshabilita(true);
					var contrato = document.getElementById('cmbContrato').value;
					AbreDialogo('divEnvio', 'frmEnvio', 'envio.php?modulo=1&contrato=' + contrato + '&usuario=<?php echo $usuario;?>')
				"
			/>
		</td>
	</tr>
</table>
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>

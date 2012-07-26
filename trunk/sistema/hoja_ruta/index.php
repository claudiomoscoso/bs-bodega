<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Hoja de Ruta</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function KeyPress(evento, ctrl){
	switch(ctrl.id){
		case 'txtOrden':
			return ValNumeros(evento, ctrl.id, false);
			break;
		case 'txtFInicio':
		case 'txtFTermino':
			var tecla = getCodigoTecla(evento);
			switch(tecla){
				case 8:
				case 46:
					ctrl.value = '';
				case 9:
					return true;
					break;
				default:
					return false;
			}
			break;
	}
}

function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 90);
	setInterval("RecepcionArchivo()", 60000);
}

function getBuscar(){
	var contrato = document.getElementById('cmbContrato').value;
	var imprime = document.getElementById('cmbImprime').value;
	var orden = document.getElementById('txtOrden').value;
	var finicio = document.getElementById('txtFInicio').value
	var ftermino = document.getElementById('txtFTermino').value
	
	document.getElementById('chkAll').checked = false;
	Deshabilita(true);
	document.getElementById('resultado').src = 'resultado.php?usuario=<?php echo $usuario;?>&contrato=' + contrato + '&imprime=' + imprime + '&orden=' + orden + '&finicio=' + finicio + '&ftermino=' + ftermino;
}

function getImprimir(){
	var totfil = 0
	var sw = 0;
	if(resultado.document.getElementById('totfil')) totfil = resultado.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(resultado.document.getElementById('chk' + i).checked){
			sw=1;
			break;
		}
	}
	if(sw == 1){
		var cc = document.getElementById('chkCC').checked;
		var imprime = document.getElementById('cmbImprime').value;
		Deshabilita(true);
		if(imprime == 0)
			document.getElementById('original').src = 'hoja_ruta.php?usuario=<?php echo $usuario;?>&contrato=' + document.getElementById('cmbContrato').value + '&reimprime=0&original=1&cc=' + (cc ? 1 : 0);
		else
			document.getElementById('original').src = 'hoja_ruta.php?usuario=<?php echo $usuario;?>&contrato=' + document.getElementById('cmbContrato').value + '&reimprime=1&original=1&cc=' + (cc ? 1 : 0);
	}else
		alert('Debe seleccionar al menos una orden de trabajo.');
}

function setActiva(ctrl){
	if(ctrl.value == 0){
		document.getElementById('txtOrden').value = '';
		document.getElementById('txtOrden').disabled = true;
	}else
		document.getElementById('txtOrden').disabled = false;
}

function Deshabilita(sw){
	document.getElementById('cmbContrato').disabled = sw;
	document.getElementById('cmbImprime').disabled = sw;
	if(document.getElementById('cmbImprime').value == 1) document.getElementById('txtOrden').disabled = sw;
	document.getElementById('txtFInicio').disabled = sw;
	document.getElementById('imgFInicio').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('txtFTermino').disabled = sw;
	document.getElementById('imgFTermino').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('chkCC').disabled = sw;
	document.getElementById('btnImprimir').disabled = sw;
}

function setSelecciona(ctrl, id){
	var totfil = 0
	if(resultado.document.getElementById('totfil')) totfil = resultado.document.getElementById('totfil').value;
	if(ctrl.id=='chkAll'){
		for(i = 1; i <= totfil; i++) resultado.document.getElementById('chk' + i).checked = ctrl.checked;
		document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&correlativo=all&valor=' + (ctrl.checked ? 1 : 0);
	}else{
		var correlativo = resultado.document.getElementById('txtCorr' + id).value;
		if(ctrl.checked){
			var totsel = 0;
			for(i = 1; i <= totfil; i++) {
				if(resultado.document.getElementById('chk' + i).checked) totsel++;
			}
			if(totsel == totfil){ 
				document.getElementById('chkAll').checked = true;
				document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&correlativo=' + correlativo + '&valor=1';
			}else
				document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&correlativo=' + correlativo + '&valor=1';
		}else{
			document.getElementById('chkAll').checked = false;
			document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&correlativo=' + correlativo + '&valor=0';
		}
	}
}

function RecepcionArchivo(){
	var contrato = document.getElementById('cmbContrato').value;
	document.getElementById('transaccion').src = 'transaccion.php?modulo=1&contrato=' + contrato;
}
-->
</script>
<body onload="javascript: Load()">
<div id="divCalendario" style="position:absolute; top:62px; left:10%; width:20%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra calendario"
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCalendario', 'frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra calendario.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="0" style="border:thin" scrolling="no" width="100%" height="130px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
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
					<td width="5%">&nbsp;Contrato</td>
					<td width="1%" align="center">:</td>
					<td width="20%">
						<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%"
							onchange="javascript: RecepcionArchivo();"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getContratos 1, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strContrato"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="4%">&nbsp;Imprimir</td>
					<td width="1%" align="center">:</td>
					<td width="11%">
						<select name="cmbImprime" id="cmbImprime" class="sel-plano" style="width:100%"
							onchange="javascript: setActiva(this);"
						>
							<option value="0">Nuevas</option>
							<option value="1">Ya impresas</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;N&deg;Orden</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtOrden" id="txtOrden" class="txt-plano" style="width: 99%; text-align:center" disabled="disabled"
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="4%">&nbsp;F.Inicio</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtFInicio" id="txtFInicio" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre cuadro calendario."
							onblur="javascript: CambiaImagen('imgFInicio', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFInicio&foco=imgFTermino&fecha=' + document.getElementById('txtFInicio').value, '', '52%', '20px');
							"
							onfocus="javascript: CambiaImagen('imgFInicio', true);"
							onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
						><img id="imgFInicio" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="6%">&nbsp;F.T&eacute;rmino</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtFTermino" id="txtFTermino" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre cuadro calendario."
							onblur="javascript: CambiaImagen('imgFTermino', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFTermino&foco=btnBuscar&fecha=' + document.getElementById('txtFTermino').value, '', '70%', '20px');
							"
							onfocus="javascript: CambiaImagen('imgFTermino', true);"
							onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
						><img id="imgFTermino" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: getBuscar();"
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
					<th width="2%">&nbsp;</th>
					<th width="10%">Correlativo</th>
					<th width="10%">Fecha</th>
					<th width="10%">ODT</th>
					<th width="20%" align="left">&nbsp;Comuna</th>
					<th width="38%" align="left">&nbsp;Trabajo</th>
					<th width="2%">
						<input type="checkbox" name="chkAll" id="chkAll"
							onclick="javascript: setSelecciona(this);"
						 />
					</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" marginheight="0" scrolling="yes" marginwidth="0" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="30%"><input name="txtEstadoRecepcion" id="txtEstadoRecepcion" class="txt-sborde" style="font-weight:bold; background-color:#ececec; width:100%" /></td>
					<td width="0%" align="right">
						<input type="button" name="btnImprimir" id="btnImprimir" class="boton" style="width:90px" value="Imprimir..." 
							onclick="javascript: getImprimir();"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="2%" align="center"><input type="checkbox" name="chkCC" id="chkCC"/></td>
					<td width="6%">&nbsp;Con copia</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<iframe name="transaccion" id="transaccion" style="display:none" ></iframe>
<iframe name="original" id="original" frameborder="0" width="0px" height="0px" ></iframe>
<iframe name="copia" id="copia" frameborder="0" width="0px" height="0px" ></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
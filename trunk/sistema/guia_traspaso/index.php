<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Guía de Traspaso</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 118);
	document.getElementById('frmDetalle').src = "agrega.php?usuario=<?php echo $usuario;?>";
	document.getElementById('btnImprimir').disabled=true;
	document.getElementById('btnGrabar').disabled=false;
	document.getElementById('txtNumero').focus();
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		CambiaColor(ctrl.id, false);
		switch(ctrl.id){
			case 'txtCargo':
				Deshabilita(true);
				AbreDialogo('divMoviles', 'frmMoviles', 'buscar_movil.php?usuario=<?php echo $usuario;?>&texto='+ ctrl.value);
				break;
			case 'txtCodigo':
				Deshabilita(true);
				AbreDialogo('divMateriales', 'frmMateriales', 'buscar_material.php?bodega=<?php echo $bodega;?>&texto='+ ctrl.value);
				break;
			case 'txtCantidad':
				if(document.getElementById('txtCodigo').value == ''){
					alert('Debe ingresar el código del material.');
					document.getElementById('txtCodigo').focus();
				}else if(document.getElementById('txtCantidad').value == 0){
					alert('Debe ingresar una cantidad mayor a cero.');
					document.getElementById('txtCantidad').focus();
				}else if(ComparaNumeros(ctrl.value, '>', document.getElementById('hdnStock').value)){
					alert('La cantidad ingresada debe ser menor a ' + document.getElementById('hdnStock').value);
					ctrl.value = document.getElementById('hdnStock').value;
					ctrl.focus();
					ctrl.select();
				}else{
					document.getElementById('frmDetalle').src = 'agrega.php?usuario=<?php echo $usuario;?>&accion=G&codigo=' + document.getElementById('txtCodigo').value + '&cantidad=' + ctrl.value;
					LimpiaDetalle();
					document.getElementById('txtCodigo').focus();
				}
		}
	}else{
		if(ctrl == 'txtCantidad') return ValNumeros(evento, ctrl, true);
	}
}

function Blur(ctrl){
	switch(ctrl.id){
		case 'txtNumero':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=0&valor=' + ctrl.value;
			break;
		case 'txtCargo':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=1&valor=' + ctrl.value;
			break;
		case 'txtCodigo':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=2&valor=' + ctrl.value;
			break;
	}
}

function Graba(){
	var FchAct = RestarFecha('<?php echo date('d/m/Y');?>', 15);
	var fil = frmDetalle.document.getElementById('totfil').value;
	if(document.getElementById('txtNumero').value == '')
		alert('Debe ingresar el número de la guía de despacho.');
	else if(!ComparaFechas(document.getElementById('txtFecha').value,'Entre', FchAct, '<?php echo date('d/m/Y');?>'))
		alert('La fecha ingresada debe estar entre ' + FchAct + ' y <?php echo date('d/m/Y');?>');
	else if(document.getElementById('hdnCargo').value == '')
		alert('Debe ingresar una cargo.');
	else if(fil > 0){
		document.getElementById('btnGrabar').disabled = true;
		document.getElementById('accion').value = 'G';
		document.getElementById('hdnNumero').value = document.getElementById('txtNumero').value;
		document.getElementById('frm').target = 'transaccion';
		document.getElementById('frm').action = 'graba.php';
		document.getElementById('frm').submit();
	}else
		alert('Debe ingresar el detalle para la guía de despacho.')
}

function Deshabilita(sw){
	document.getElementById('txtNumero').disabled=sw;
	document.getElementById('aFecha').style.display=sw ? 'none' : '';
	document.getElementById('txtObservacion').disabled=sw;
	document.getElementById('txtCargo').disabled=sw;
	document.getElementById('txtCodigo').disabled=sw;
	document.getElementById('txtCantidad').disabled=sw;
	
	document.getElementById('btnNueva').disabled=sw;
	document.getElementById('btnGrabar').disabled=sw;
}

function LimpiaDetalle(){
	document.getElementById('txtCodigo').value='';
	document.getElementById('txtDescripcion').value='';
	document.getElementById('txtUnidad').value='';
	document.getElementById('hdnStock').value=0;
	document.getElementById('txtCantidad').value=0;
}

function Nueva(){
	self.location.href = '<?php echo $_SERVER['PHP_SELF'].$parametros;?>';
	document.getElementById('btnImprimir').disabled=true;
	document.getElementById('btnGrabar').disabled=false;
}

function Imprimir(){
	document.getElementById('accion').value = 'I';
	document.getElementById('frm').action = 'graba.php';
	document.getElementById('frm').target = 'transaccion';
	document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load();">
<div id="divCalendario" style="position:absolute; top:20px; left:202px; width:20%; visibility:hidden">
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

<div id="divMoviles" style="z-index: 1; position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" onClick="javascript: 
									    Deshabilita(false);
										CierraDialogo('divMoviles', 'frmMoviles');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Moviles</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmMoviles" id="frmMoviles" frameborder="0" scrolling="no" width="100%" height="245px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divMateriales" style="z-index:1; position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" onClick="javascript:
										Deshabilita(false); 
										CierraDialogo('divMateriales', 'frmMateriales');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Materiales</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmMateriales" id="frmMateriales" frameborder="0" scrolling="no" width="100%" height="145px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" action="graba.php">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="10%" align="left">&nbsp;N&deg; Gu&iacute;a</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtNumero" id="txtNumero" class="txt-plano" style="width:100%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%" align="left" >&nbsp;Fecha</td>
					<td width="1%">:</td>
					<td width="10%"><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>"/></td>
					<td width="2%" align="right">
						<a href="#" id="aFecha" title="Abre calendario"
							onblur="javascript: CambiaImagen('imgFecha', false);"
							onclick="javascript:
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFecha&foco=txtCargo&fecha='+document.getElementById('txtFecha').value+'&avanza=0');
							"
							onfocus="javascript: CambiaImagen('imgFecha', true);"
							onmouseover="javascript: window.status='Abre calendario'; return true;"
						><img id="imgFecha" border="0" align="middle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Cargo</td>
					<td width="1%">:</td>
					<td width="53%" align="right">
						<input name="txtCargo" id="txtCargo" class="txt-plano" style="width:99%;" value="&nbsp;-- Ingrese el c&oacute;digo o la descripci&oacute;n y presione ENTER --"
							onblur="javascript: Blur(this);"
							onfocus="javascript: 
								if(this.value==' -- Ingrese el c&oacute;digo o la descripci&oacute;n y presione ENTER --') this.value='';
								CambiaColor(this.id, true);
							" 
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
				</tr>
				<tr>
					<td align="left" >&nbsp;Observaci&oacute;n</td>
					<td>:</td>
					<td colspan="12" align="left">
						<input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:100%" maxlength="1000" 
							onblur="javascript: 
								CambiaColor(this.id, false);
								document.getElementById('txtCodigo').focus();
							"
							onfocus="javascript: CambiaColor(this.id, true);" 
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="10%">C&oacute;digo</th>
					<th width="66">Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%">Cantidad</th>
					<th width="4%">&nbsp;</th>
				</tr>
				<tr>
					<td >
						<input name="txtCodigo" id="txtCodigo" class="txt-plano" style="width: 99%; text-align:center" 
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onblur="javascript: Blur(this);"
						/>
					</td>
					<td ><input name="txtDescripcion" id="txtDescripcion" class="txt-plano" style="width: 99%;" readonly="true"/></td>
					<td ><input name="txtUnidad" id="txtUnidad" class="txt-plano" style="width: 99%; text-align:center" readonly="true"/></td>
					<td >
						<input name="txtCantidad" id="txtCantidad" class="txt-plano" style="width: 99%; text-align:right" value="0"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onblur="javascript: CambiaColor(this.id, false);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') this.value=0;"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmDetalle" id="frmDetalle" width="100%" frameborder="0" scrolling="yes" marginheight="0" marginwidth="0" ></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>"/>
			<input type="hidden" name="accion" id="accion" />
			<input type="hidden" name="hdnCargo" id="hdnCargo" />
			<input type="hidden" name="hdnStock" id="hdnStock" />
			<input type="hidden" name="hdnNumero" id="hdnNumero" />
			
			<input type="button" name="btnNueva" id="btnNueva" class="boton" style="width:80px" value="Nueva" 
				onclick="javascript: Nueva();" 
			/>
			<input type="button" name="btnImprimir" id="btnImprimir" class="boton" style="width:80px;" disabled="disabled" value="Imprimir..." 
				onclick="javascript: Imprimir();" 
			/>
			<input type="button" name="btnGrabar" id="btnGrabar" class="boton" style="width:80px" value="Grabar" 
				onclick="javascript: Graba();" 
			/>
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>

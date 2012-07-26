<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Devoluci&oacute;n a Proveedor</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	var bodega = document.getElementById('cmbBodega').value;
	CambiaColor(ctrl.id, false);
	switch(ctrl.id){
		case 'txtDevolucion':
			document.getElementById('transaccion').src = 'valida.php?modulo=0&bodega=' + bodega + '&gdevolucion=' + ctrl.value;
			break;
		case 'txtOCompra':
			document.getElementById('frmDetalle').src = 'detalle.php<?php echo $parametros;?>&bodega=' + bodega + '&ocompra=' + ctrl.value;
			break;
	}
}

function Change(){
	document.getElementById('txtOCompra').value = '';
	document.getElementById('hdnOCompra').value = '';
	document.getElementById('txtProveedor').value = '';
	document.getElementById('hdnProveedor').value = '';
	document.getElementById('frmDetalle').src = '../blank.html';
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl.id){
			case 'txtDevolucion':
				document.getElementById('aFecha').focus();
				break;
			case 'cmbBodega':
				document.getElementById('txtOCompra').focus();
				document.getElementById('txtOCompra').select();
				break;
			case 'txtOCompra':
				document.getElementById('txtObservacion').focus();
				document.getElementById('txtObservacion').select();
				break;
			case 'txtObservacion':
				if(frmDetalle.document.getElementById('sel_1')) frmDetalle.document.getElementById('sel_1').focus();
				break;
		}
		return true;
	}else{
		switch(ctrl.id){
			case 'txtDevolucion':
			case 'txtOCompra':
				return ValNumeros(evento, ctrl.id, false);
				break;
		}
	}
}

function Load(){
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 105);
	document.getElementById('btnImprimir').disabled = true;
	document.getElementById('btnGrabar').disabled = false;
	document.getElementById('txtDevolucion').focus();
}

function Guardar(){
	var FchAct = RestarFecha('<?php echo date('d/m/Y');?>', 15);
	var fil = frmDetalle.document.getElementById('totfil').value;
	if(document.getElementById('txtDevolucion').value == '')
		alert('Debe ingresar el número de la guía de despacho.');
	else if(!ComparaFechas(document.getElementById('txtFecha').value, 'Entre', FchAct, '<?php echo date('d/m/Y');?>'))
		alert('La fecha ingresada debe estar entre ' + FchAct + ' y <?php echo date('d/m/Y');?>');
	else if(document.getElementById('txtOCompra').value == '')
		alert('Debe ingresar una orden de compra.');
	else if(fil == 0){
		alert('Debe seleccionar al menos un ítem.');
	}else{
		document.getElementById('accion').value = 'G';
		document.getElementById('frm').setAttribute('action', 'graba.php');
		document.getElementById('frm').setAttribute('target', 'transaccion');
		document.getElementById('frm').submit();
	}
}

function Deshabilita(sw){
	document.getElementById('txtDevolucion').disabled = sw;
	document.getElementById('aFecha').style.display = sw ? 'none' : '';
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('txtOCompra').disabled = sw;
	document.getElementById('txtObservacion').disabled = sw;
	if(frmDetalle.document.getElementById('totfil')){
		var totfil=frmDetalle.document.getElementById('totfil').value;
		for(i = 1; i <= totfil; i++){
			frmDetalle.document.getElementById('chkDevuelve' + i).disabled = sw;
			if(frmDetalle.document.getElementById('chkDevuelve' + i).checked)
				frmDetalle.document.getElementById('txtCDevuelta' + i).disabled = sw;
		}
	}
	document.getElementById('btnNueva').disabled = sw;
	document.getElementById('btnGrabar').disabled = sw;
}

function Nueva(){
	self.location.href='<?php echo $_SERVER['PHP_SELF'].$parametros;?>';
	document.getElementById('btnImprimir').disabled = true;
	document.getElementById('btnGrabar').disabled = false;
}

function Imprimir(){
	document.getElementById('accion').value = 'I';
	document.getElementById('txtOCompra').disabled = false;
	document.getElementById('txtDevolucion').disabled = false;
	document.getElementById('frm').setAttribute('action', 'graba.php');
	document.getElementById('frm').setAttribute('target', 'transaccion');
	document.getElementById('frm').submit();
}

function Bloquea(){
	document.getElementById('txtDevolucion').disabled = true;
	document.getElementById('aFecha').style.display = 'none';
	document.getElementById('cmbBodega').disabled = true;
	document.getElementById('txtOCompra').disabled = true;
	document.getElementById('txtObservacion').disabled = true;
	var totfil= frmDetalle.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(frmDetalle.document.getElementById('chkDevuelve' + i)) frmDetalle.document.getElementById('chkDevuelve' + i).disabled = true;
		if(frmDetalle.document.getElementById('txtCDevuelta' + i)) frmDetalle.document.getElementById('txtCDevuelta' + i).disabled = true;
	}
	document.getElementById('btnNueva').disabled = false;
	document.getElementById('btnImprimir').disabled = false;
	document.getElementById('btnGrabar').disabled = true;
}

function setDevolverTodo(sw){
	var totfil = frmDetalle.document.getElementById('totfil').value;
	if(sw){
		for(i = 1; i <= totfil; i++){ 
			frmDetalle.document.getElementById('chkDevuelve' + i).checked = sw;
			frmDetalle.document.getElementById('txtCDevuelta' + i).disabled = false;
			frmDetalle.document.getElementById('txtCDevuelta' + i).value = frmDetalle.document.getElementById('hdnCantidad' + i).value
		}
		document.getElementById('transaccion').src = 'valida.php?modulo=2&bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>';
	}else{
		for(i = 1; i <= totfil; i++){
			frmDetalle.document.getElementById('chkDevuelve' + i).checked = sw;
			frmDetalle.document.getElementById('txtCDevuelta' + i).disabled = true;
			frmDetalle.document.getElementById('txtCDevuelta' + i).value = '0.00';
		}
		document.getElementById('transaccion').src = 'valida.php?modulo=3&bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>';
	}	
}
-->
</script>
<body onload="javascript: Load();">
<div id="divCalendario" style="position:absolute; top:20px; left:27%; width:20%; visibility:hidden">
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
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="0" style="border:thin" scrolling="no" width="100%" height="135px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="11%" align="left">&nbsp;N&deg;G.Despacho</td>
					<td width="1%">:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="12%">
									<input name="txtDevolucion" id="txtDevolucion" class="txt-plano" style="width:100%; text-align:center" 
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onkeypress="javascript: return KeyPress(event, this);"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="3%" align="left" >&nbsp;Fecha</td>
								<td width="1%">:</td>
								<td width="12%"><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:96%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>"/></td>
								<td width="2%" align="center">
									<a href="#" id="aFecha" title="Abre cuadro calendario"
										onblur="javascript: CambiaImagen('imgFecha', false);"
										onclick="javascript:
											Deshabilita(true);
											AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFecha&foco=cmbBodega&fecha='+document.getElementById('txtFecha').value+'&retrocede=-1&avanza=0');
										"
										onfocus="javascript: CambiaImagen('imgFecha', true);"
										onmouseover="javascript: window.status='Abre cuadro calendario'; return true;"
									><img id="imgFecha" border="0" align="middle" src="../images/aba.gif" /></a>								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Bodega</td>
								<td width="1%">:</td>
								<td width="42%">
									<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%"
										onchange="javascript: Change(this);"
										onkeypress="javascript: return KeyPress(event, this);"
									>
									<?php
									$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
									while($rst = mssql_fetch_array($stmt)){
										echo '<option value="'.trim($rst["strBodega"]).'" '.(trim($rst["strBodega"]) == $bodega ? 'selected="selected"' : '').'>'.trim($rst["strDetalle"]).'</option>';
									}
									mssql_free_result($stmt);?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;N&deg;O.Compra</td>
								<td width="1%">:</td>
								<td width="12%">
									<input name="txtOCompra" id="txtOCompra" class="txt-plano" style="width:98%; text-align:center;"
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onkeypress="javascript: return KeyPress(event, this);"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td >&nbsp;Proveedor</td>
					<td >:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="44%"><input name="txtProveedor" id="txtProveedor" class="txt-plano" style="width:100%;" readonly="true"/></td>
								<td width="1%">&nbsp;</td>
								<td width="10%">&nbsp;Observaci&oacute;n</td>
								<td width="1%">:</td>
								<td width="44%">
									<input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:100%" maxlength="1000" 
										onfocus="javascript: CambiaColor(this.id, true);" 
										onblur="javascript: CambiaColor(this.id, false);"
										onkeypress="javascript: return KeyPress(event, this);"
									/>								</td>
							</tr>
						</table>					</td>
				</tr>
			</table>		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="10%">C&oacute;digo</th>
					<th width="46%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="10%" align="right">Stock&nbsp;</th>
					<th width="2%">
						<input type="checkbox" name="chkDTodo" id="chkDTodo" 
							onclick="javascript: setDevolverTodo(this.checked);"
						/>					</th>
					<th width="10%">Devolver...</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>		</td>
	</tr>
	<tr>
	  <td><iframe name="frmDetalle" id="frmDetalle" width="100%" frameborder="0" scrolling="yes" marginheight="0" marginwidth="0" src="../blank.html"></iframe></td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td align="right">
						<input type="hidden" name="accion" id="accion" />
						<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
						<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>"/>
						<input type="hidden" name="hdnProveedor" id="hdnProveedor" />
						<input type="hidden" name="hdnOCompra" id="hdnOCompra" />
						
						<input type="button" name="btnNueva" id="btnNueva" class="boton" style="width:80px" value="Nueva" 
							onclick="javascript: Nueva();" 
						/>
						<input type="button" name="btnImprimir" id="btnImprimir" class="boton" style="width:80px;" disabled="disabled" value="Imprimir..." 
							onclick="javascript: Imprimir();" 
						/>
						<input type="button" name="btnGrabar" id="btnGrabar" class="boton" style="width:80px" value="Guardar" 
							onclick="javascript: Guardar();" 
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
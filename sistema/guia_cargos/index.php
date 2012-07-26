<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Guía de Cargos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Change(bodega){
	var bodega = document.getElementById('cmbBodega').value;
	document.getElementById('txtCargo').value = ' -- Ingrese el código o la descripción y presione ENTER --';
	document.getElementById('hdnCargo').value = '';
	document.getElementById('detalle').src = 'agrega.php?bodega=' + bodega + '&usuario=<?php echo $usuario;?>';
}

function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	if(ctrl.value == '') return 0;
	var bodega = document.getElementById('cmbBodega').value;
	switch(ctrl.id){
		case 'txtNumero':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=0&bodega=' + bodega + '&usuario=<?php echo $usuario;?>&valor=' + ctrl.value;
			break;
		case 'txtCargo':
			document.getElementById('transaccion').src='transaccion.php?modulo=1&bodega=' + bodega + '&valor=' + ctrl.value;
			break;
		case 'txtCodigo':
			document.getElementById('transaccion').src='transaccion.php?modulo=2&bodega=' + bodega + '&usuario=<?php echo $usuario;?>&valor=' + ctrl.value;
			break;
	}							
}

function Load(){
	var bodega = document.getElementById('cmbBodega').value;
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 135);
	document.getElementById('detalle').src = 'agrega.php?bodega=' + bodega + '&usuario=<?php echo $usuario;?>';
	document.getElementById('btnImprimir').disabled = true;
	document.getElementById('btnFicha').disabled = true;
	document.getElementById('btnGuardar').disabled = false;
	document.getElementById('txtNumero').focus();
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	var bodega = document.getElementById('cmbBodega').value;
	if(tecla==13){
		CambiaColor(ctrl.id, false);
		switch(ctrl.id){
			case 'txtCargo':
				Deshabilita(true);
				AbreDialogo('divMoviles', 'frmMoviles', 'buscar_cargos.php?bodega=' + bodega + '&texto='+ ctrl.value);
				break;
			case 'txtCodigo':
				Deshabilita(true);
				AbreDialogo('divMateriales', 'frmMateriales', 'buscar_material.php?bodega=' + bodega + '&texto='+ ctrl.value);
				break;
			case 'txtCantidad':
				if(document.getElementById('txtCodigo').value==''){
					alert('Debe ingresar el código del material.');
					document.getElementById('txtCodigo').focus();
				}else if(document.getElementById('txtCantidad').value == 0){
					alert('Debe ingresar una cantidad mayor a cero.');
					document.getElementById('txtCantidad').focus();
				}else if(document.getElementById('txtDescripcion').value == ''){
					alert('El código del material ingresado no es valido.');
					document.getElementById('txtCodigo').focus();
				}else{
					var stock = document.getElementById('hdnStock').value;
					if(parseFloat(ctrl.value) <= parseFloat(stock)){
						var codigo = document.getElementById('txtCodigo').value;
						document.getElementById('detalle').src = 'agrega.php?accion=G&usuario=<?php echo $usuario;?>&bodega=' + bodega + '&codigo=' + codigo + '&cantidad=' + ctrl.value;
						LimpiaDetalle();
						document.getElementById('txtCodigo').focus();
					}else{
						alert('El stock actual es menor (' + stock + ') a la cantidad ingresada.');
					}
				}
		}
	}else{
		switch(ctrl.id){
			case 'txtCantidad':
				switch(document.getElementById('txtUnidad').value){
					case 'Nº':
					case 'JGO':
					case 'LATA':
					case 'N':
					case 'PAR':
					case 'GLOBAL':
					case 'PZA':
						return ValNumeros(evento, ctrl.id, false);
						break;
					default:
						return ValNumeros(evento, ctrl.id, true);
						break;
				}
				break;
		}
	}
}

function Guardar(){
	var FchAct = RestarFecha('<?php echo date('d/m/Y');?>', 100);
	var fil = detalle.document.getElementById('totfil').value;
	if(document.getElementById('txtNumero').value == '')
		alert('Debe ingresar el número de la guía de cargo.');
	else if(!ComparaFechas(document.getElementById('txtFecha').value, 'Entre', FchAct, '<?php echo date('d/m/Y');?>'))
		alert('La fecha ingresada debe estar entre ' + FchAct + ' y <?php echo date('d/m/Y');?>');
	else if(document.getElementById('hdnCargo').value == '')
		alert('Debe ingresar el cargo.');
	else if(fil > 0){
		document.getElementById('hdnNumero').value = document.getElementById('txtNumero').value;
		document.getElementById('hdnBodega').value = document.getElementById('cmbBodega').value;
		document.getElementById('accion').value = 'G';
		document.getElementById('frm').submit();
	}else
		alert('Debe ingresar al menos un item al detalle de la guía de cargo.')
}

function Deshabilita(sw){
	document.getElementById('txtNumero').disabled = sw;
	document.getElementById('imgFecha').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('txtObservacion').disabled = sw;
	document.getElementById('txtCargo').disabled = sw;
	document.getElementById('txtCodigo').disabled=sw;
	document.getElementById('txtCantidad').disabled = sw;
	if(detalle.document.getElementById('totfil')){
		var totfil = detalle.document.getElementById('totfil').value;
		for(i = 1; i <= totfil; i++) 
			detalle.document.getElementById('imgBorrar' + i).style.visibility = sw ? 'hidden' : 'visible';
	}
	document.getElementById('btnNueva').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
	
}

function LimpiaDetalle(){
	document.getElementById('txtCodigo').value='';
	document.getElementById('txtDescripcion').value='';
	document.getElementById('txtUnidad').value='';
	document.getElementById('hdnStock').value=0;
	document.getElementById('txtCantidad').value=0;
}

function Nueva(){
	self.location.href='<?php echo $_SERVER['PHP_SELF'].$parametros;?>';
	document.getElementById('btnImprimir').disabled = true;
	document.getElementById('btnFicha').disabled = true;
	document.getElementById('btnGuardar').disabled = false;
}

function Imprimir(){
	document.getElementById('accion').value='I';
	document.getElementById('frm').submit();
}

function ImprimirFicha(){
	var bodega = document.getElementById('cmbBodega').value;
	var cargo = document.getElementById('hdnCargo').value;
	document.getElementById('transaccion').src = 'imprimir.php?bodega=' + bodega + '&cargo=' + cargo;
}

function Bloquea(){
	document.getElementById('txtNumero').disabled=true;
	document.getElementById('aFecha').style.display='none';
	document.getElementById('numOC').disabled=true;
	document.getElementById('txtObservacion').disabled=true;
	
	
	document.getElementById('btnNueva').disabled = false;
	document.getElementById('btnImprimir').disabled = false;
	document.getElementById('btnFicha').disabled = false;
	document.getElementById('btnGuardar').disabled = true;
}
-->
</script>
<body onload="javascript: Load();">
<div id="divCalendario" style="position:absolute; top:20px; left:15%; width:20%; visibility:hidden">
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
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="0" style="border:thin" scrolling="no" width="100%" height="125px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
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
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Personal</b></td>
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

<form name="frm" id="frm" method="post" action="graba.php" target="transaccion">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="8%">&nbsp;N&deg;Gu&iacute;a Desp.</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtNumero" id="txtNumero" class="txt-plano" style="width:98%;text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="3%" align="left" >&nbsp;Fecha</td>
					<td width="1%" align="center">:</td>
					<td width="10%"><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:96%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>"/></td>
					<td width="1%" align="right">
						<a href="#" title="Abre cuadro calendario"
							onblur="javascript: CambiaImagen('imgFecha', false);"
							onclick="javascript:
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFecha&fecha='+document.getElementById('txtFecha').value+'&retrocede=&avanza=0');
							"
							onfocus="javascript: CambiaImagen('imgFecha', true);"
							onmouseover="javascript: window.status='Abre cuadro calendario'; return true;"
						><img id="imgFecha" border="0" align="middle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Bodega</td>
					<td width="1%" align="center">:</td>
					<td width="58%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%"
							onchange="javascript: Change(this.value);"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strBodega"].'" '.($rst["strBodega"] == $bodega ? 'selected="selected"' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="3%">&nbsp;Cargo</td>
					<td width="1%" align="center">:</td>
					<td width="30%">
						<input name="txtCargo" id="txtCargo" class="txt-plano" style="width:99%;" value="&nbsp;-- Ingrese el c&oacute;digo o la descripci&oacute;n y presione ENTER --"
							onblur="javascript: Blur(this);"
							onfocus="javascript: 
								if(this.value==' -- Ingrese el c&oacute;digo o la descripci&oacute;n y presione ENTER --') this.value='';
								CambiaColor(this.id, true);
							" 
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%" align="left" >&nbsp;Observaci&oacute;n</td>
					<td width="1%" align="center">:</td>
					<td width="57%" align="left">
						<input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:100%" maxlength="1000" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);" 
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
					<th width="10%">C&oacute;digo</th>
					<th width="66%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%">Cantidad</th>
					<th width="4%">&nbsp;</th>
				</tr>
				<tr>
					<td >
						<input name="txtCodigo" id="txtCodigo" class="txt-plano" style="width: 97%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"  
						/>
					</td>
					<td ><input name="txtDescripcion" id="txtDescripcion" class="txt-plano" style="width: 99%;" readonly="true"/></td>
					<td ><input name="txtUnidad" id="txtUnidad" class="txt-plano" style="width: 96%; text-align:center" readonly="true"/></td>
					<td >
						<input name="txtCantidad" id="txtCantidad" class="txt-plano" style="width: 97%; text-align:right" value="0"
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr><td><iframe name="detalle" id="detalle" width="100%" frameborder="0" scrolling="yes" marginheight="0" marginwidth="0" ></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>"/>
			<input type="hidden" name="hdnNumero" id="hdnNumero"/>
			<input type="hidden" name="hdnBodega" id="hdnBodega"/>
			<input type="hidden" name="hdnStock" id="hdnStock" />
			<input type="hidden" name="hdnCargo" id="hdnCargo" />
			<input type="hidden" name="accion" id="accion" />
			
			<input type="button" name="btnNueva" id="btnNueva" class="boton" style="width:90px" value="Nueva" 
				onclick="javascript: Nueva();" 
			/>
			<input type="button" name="btnImprimir" id="btnImprimir" class="boton" style="width:90px;" disabled="disabled" value="Imprimir..." 
				onclick="javascript: Imprimir();" 
			/>
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: Guardar();" 
			/>
			<input type="button" name="btnFicha" id="btnFicha" class="boton" style="width:90px;" disabled="disabled" value="Imprime ficha" 
				onclick="javascript: ImprimirFicha();" 
			/>
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>

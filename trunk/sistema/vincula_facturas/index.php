<?php
include '../autentica.php';
include '../conexion.inc.php';
include '../globalvar.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Vincula Facturas en Estados de Pago</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	
	switch(ctrl.id){
		case 'txtFactura':
		case 'txtEPago':
			if(parseInt(tecla) != 13) return ValNumeros(evento, ctrl.id, false);
			break;
		case 'txtFDesde':
		case 'txtFHasta':
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
	document.getElementById('divFDisponibles').style.height = (window.innerHeight - 143) + 'px';
	document.getElementById('divFIncluidas').style.height = (window.innerHeight - 179) + 'px';
}

function Agregar(){
	if(document.getElementById('totdisp')){
		var epago = document.getElementById('txtEPago').value;
		var clasificacion = document.getElementById('cmbModulo').value;
		var sw = false;
		var facturas = '', proveedores = '';
		var totfil = document.getElementById('totdisp').value;
		for(i = 1; i <= totfil; i++){
			if(document.getElementById('chkDMarca' + i).checked){
				facturas += document.getElementById('chkDMarca' + i).value + ',';
				proveedores += document.getElementById('hdnDProveedor' + i).value + ',';
			}
		}
		if(facturas == '')
			alert('Debe seleccionar al menos una factura.');
		else if(epago == '')
			alert('Debe ingresar el número de estado de pago');
		else{
			Deshabilita(true);
			facturas = facturas.substring(0, facturas.length - 1);
			proveedores = proveedores.substring(0, proveedores.length - 1);
			var transaccion = document.getElementById('divTransaccion');
			var ajax = new XMLHttpRequest();
			ajax.open('GET', 'transaccion.php?modulo=0&facturas=' + facturas + '&proveedores=' + proveedores + '&epago=' + epago + '&clasificacion=' + clasificacion, true);
			ajax.onreadystatechange = function (){
				if(ajax.readyState == 4){
					transaccion.innerHTML = ajax.responseText;
					BuscarFacturas();
					BuscarEPagos();
					Deshabilita(false);
				}
			}
			ajax.send(null);
		}	
	}
}

function BuscarEPagos(){
	var epago = document.getElementById('txtEPago').value;
	var obra = document.getElementById('cmbObras').value;
	if(epago == '')
		alert('Debe ingresar el número de estado de pago.');
	else{
		var incluidas = document.getElementById('divFIncluidas');
		var ajax = new XMLHttpRequest();
		Deshabilita(true);
		incluidas.innerHTML = '<table border="0" width="100%" height="100%"><tr><td align="center"><img src="../images/cargando2.gif"></td></tr></table>';
		ajax.open('GET', 'facturas_includas.php?epago=' + epago + '&obra=' + obra, true);
		ajax.onreadystatechange = function (){
			if(ajax.readyState == 4){
				incluidas.innerHTML = ajax.responseText;
				document.getElementById('txtTotal').value = document.getElementById('total').value;
				Deshabilita(false);
			}
		}
		ajax.send(null);
	}
}

function BuscarFacturas(){
	var obra = document.getElementById('cmbObras').value;
	var factura = document.getElementById('txtFactura').value;
	var fdesde = document.getElementById('txtFDesde').value;
	var fhasta = document.getElementById('txtFHasta').value;
	
	if(factura == '' && fdesde == '' && fhasta == '')
		alert('Debe ingresar un número de factura o un periodo de fecha.');
	else if(fdesde != '' && fhasta == '')
		alert('Debe ingresar correctamente las fechas del periodo.');
	else if(fdesde == '' && fhasta != '')
		alert('Debe ingresar correctamente las fechas del periodo.');
	else{
		var disponibles = document.getElementById('divFDisponibles');
		var ajax = new XMLHttpRequest();
		disponibles.innerHTML = '<table border="0" width="100%" height="100%"><tr><td align="center"><img src="../images/cargando2.gif"></td></tr></table>';
		Deshabilita(true);
		ajax.open('GET', 'facturas_disponibles.php?obra=' + obra + '&factura=' + factura + '&fdesde=' + fdesde + '&fhasta=' + fhasta, true);
		ajax.onreadystatechange = function(){
			if(ajax.readyState == 4){
				disponibles.innerHTML = ajax.responseText;
				Deshabilita(false);
			}
		}
		ajax.send(null);
	}
}

function Deshabilita(sw){
	document.getElementById('cmbObras').disabled = sw;
	document.getElementById('txtFactura').disabled = sw;
	document.getElementById('txtFDesde').disabled = sw;
	document.getElementById('imgFDesde').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('txtFHasta').disabled = sw;
	document.getElementById('imgFHasta').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('btnBuscaFacturas').disabled = sw;
	document.getElementById('txtEPago').disabled = sw;
	document.getElementById('cmbModulo').disabled = sw;
	document.getElementById('btnBuscaEPagos').disabled = sw;
	document.getElementById('chkDisponibles').disabled = sw;
	document.getElementById('chkIncluidas').disabled = sw;
	document.getElementById('btnAgrega').disabled = sw;
	document.getElementById('btnQuita').disabled = sw;
	document.getElementById('btnResumen').disabled = sw;
	
	if(document.getElementById('totdisp')){
		var totfil = document.getElementById('totdisp').value;
		for(i = 1; i <= totfil; i++){ 
			if(document.getElementById('chkDMarca' + i)) document.getElementById('chkDMarca' + i).disabled = sw;
		}
	}
	
	if(document.getElementById('totincl')){
		var totfil = document.getElementById('totincl').value;
		for(i = 1; i <= totfil; i++){ 
			if(document.getElementById('chkIMarca' + i)) document.getElementById('chkIMarca' + i).disabled = sw;
		}
	}
}

function MuestraArchivo(url){
	Deshabilita(true);
	AbreDialogo('divPreview', 'frmPreview', '<?php echo $dtn_documento;?>/' + url);
}

function Quitar(){
	if(document.getElementById('totincl')){
		var sw = false;
		var facturas = '', proveedores = '';
		var totfil = document.getElementById('totincl').value;
		for(i = 1; i <= totfil; i++){
			if(document.getElementById('chkIMarca' + i).checked){
				facturas += document.getElementById('chkIMarca' + i).value + ',';
				proveedores += document.getElementById('hdnIProveedor' + i).value + ',';
			}
		}
		if(facturas == '')
			alert('Debe seleccionar al menos una factura.');
		else{
			Deshabilita(true);
			facturas = facturas.substring(0, facturas.length - 1);
			proveedores = proveedores.substring(0, proveedores.length - 1);
			var transaccion = document.getElementById('divTransaccion');
			var ajax = new XMLHttpRequest();
			ajax.open('GET', 'transaccion.php?modulo=0&facturas=' + facturas + '&proveedores=' + proveedores, true);
			ajax.onreadystatechange = function (){
				if(ajax.readyState == 4){
					transaccion.innerHTML = ajax.responseText;
					BuscarFacturas();
					BuscarEPagos();
					Deshabilita(false);
				}
			}
			ajax.send(null);
		}	
	}
}

function Resumen(){
	var epago = document.getElementById('txtEPago').value;
	document.getElementById('frmExporta').src = 'resumen.php?epago=' + epago;
	document.getElementById('frmExporta2').src = 'detalle.php?epago=' + epago;
}

function Selecciona(ctrl){
	if(ctrl.id == 'chkDisponibles'){
		var totfil = document.getElementById('totdisp').value;
		for(i = 1; i <= totfil; i++) document.getElementById('chkDMarca' + i).checked = ctrl.checked;
	}else if(ctrl.id == 'chkIncluidas'){
		var totfil = document.getElementById('totincl').value;
		for(i = 1; i <= totfil; i++) document.getElementById('chkIMarca' + i).checked = ctrl.checked;
	}
}
</script>
<body onload="javascript: Load();">
<div id="divCalendario" style="z-index:10; position:absolute; width:20%; visibility:hidden; left: 10%; top: 80px;">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra cuadro calendario."
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCalendario', 'frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra cuadro calendario.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="1" style="border:thin" scrolling="no" width="100%" height="130px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divPreview" style="z-index: 1; position:absolute; top:0px; left:13%; width:75%; visibility:hidden">
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
										CierraDialogo('divPreview', 'frmPreview');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Vista del documento</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmPreview" id="frmPreview" frameborder="0" scrolling="no" width="100%" height="250px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
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
					<td width="3%">&nbsp;Obra</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<select name="cmbObras" id="cmbObras" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getCargos 5, NULL, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strCodigo"]).'">'.$rst["strCargo"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="0%">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr valign="top">
					<td width="50%">
						<fieldset>
							<legend><b>Facturas Disponibles</b></legend>
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table border="0" width="100%" cellpadding="0" cellspacing="1">
											<tr>
												<td width="11%">&nbsp;N&deg;Factura</td>
												<td width="1%" align="center">:</td>
												<td width="0%">
													<input name="txtFactura" id="txtFactura" class="txt-plano" style="width:99%; text-align:center" 
														onblur="javascript: CambiaColor(this.id, false);"
														onfocus="javascript: CambiaColor(this.id, true);"
														onkeypress="javascript: return KeyPress(event, this);"
													/>
												</td>
												<td width="10%" align="right">Periodo&nbsp;</td>
												<td width="1%" align="center">:</td>
												<td width="0%">
													<input name="txtFDesde" id="txtFDesde" class="txt-plano" style="width:99%; text-align:center" 
														onblur="javascript: CambiaColor(this.id, false);"
														onfocus="javascript: CambiaColor(this.id, true);"
														onkeypress="javascript: return KeyPress(event, this);"
													/>
												</td>
												<td width="3%" align="center">
													<a href="#" title="Abre calendario."
														onblur="javascript: CambiaImagen('imgFDesde', false);"
														onclick="javascript: 
															Deshabilita(true);
															AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFDesde&foco=imgFHasta', '', '10%', '70px');
														"
														onfocus="javascript: CambiaImagen('imgFDesde', true);"
														onmouseover="window.status='Abre calendario.'; return true;"
													><img id="imgFDesde" border="0" align="absmiddle" src="../images/aba.gif" /></a>
												</td>
												<td width="1%">&nbsp;&mdash;&nbsp;</td>
												<td width="0%">
													<input name="txtFHasta" id="txtFHasta" class="txt-plano" style="width:99%; text-align:center" 
														onblur="javascript: CambiaColor(this.id, false);"
														onfocus="javascript: CambiaColor(this.id, true);"
														onkeypress="javascript: return KeyPress(event, this);"
													/>
												</td>
												<td width="3%" align="center">
													<a href="#" title="Abre calendario."
														onblur="javascript: CambiaImagen('imgFHasta', false);"
														onclick="javascript: 
															Deshabilita(true);
															AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFHasta&foco=btnBuscaFacturas', '', '21%', '70px');
														"
														onfocus="javascript: CambiaImagen('imgFHasta', true);"
														onmouseover="window.status='Abre calendario.'; return true;"
													><img id="imgFHasta" border="0" align="absmiddle" src="../images/aba.gif" /></a>
												</td>
												<td width="15%">
													<input type="button" name="btnBuscaFacturas" id="btnBuscaFacturas" class="boton" style="width: 80px" value="Buscar" 
														onclick="javascript: BuscarFacturas();"
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
												<th width="2%"><input type="checkbox" name="chkDisponibles" id="chkDisponibles" onclick="javascript: Selecciona(this)"/></th>
												<th width="15%">N&deg;Factura</th>
												<th width="65%" align="left">&nbsp;Proveedor</th>
												<th width="15%" align="right">Total&nbsp;</th>
												<th width="3%">&nbsp;</th>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><div id="divFDisponibles" style="border:solid 1px; height:100px; overflow:scroll; position:relative; width:100%"></div></td></tr>
								<tr><td style="height:5px"></td></tr>
								<tr>
									<td align="right">
										<input type="button" name="btnAgrega" id="btnAgrega" class="boton" style="width:80px" value="Agregar &gt;&gt;" onclick="javascript: Agregar();"/>
									</td>
								</tr>
							</table>
						</fieldset>
					</td>
					<td width="50%">
						<fieldset>
							<legend><b>Facturas Incluidas</b></legend>
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table border="0" width="100%" cellpadding="0" cellspacing="2">
											<tr>
												<td width="5%">&nbsp;N&deg;E.Pago</td>
												<td width="1%" align="center">:</td>
												<td width="15%">
													<input name="txtEPago" id="txtEPago" class="txt-plano" style="width:99%; text-align:center" 
														onblur="javascript: CambiaColor(this.id, false);"
														onfocus="javascript: CambiaColor(this.id, true);"
														onkeypress="javascript: return KeyPress(event, this);"
													/>
												</td>
												<td width="15%">
													<input type="button" name="btnBuscaEPagos" id="btnBuscaEPagos" class="boton" style="width:80px" value="Buscar" 
														onclick="javascript: BuscarEPagos();"
													/>
												</td>
												<td width="5%">&nbsp;Modulo</td>
												<td width="1%" align="center">:</td>
												<td width="0%">
													<select name="cmbModulo" id="cmbModulo" class="sel-plano" style="width:100%">
													<?php
													$stmt = mssql_query("EXEC General..sp_getModulo 0", $cnx);
													while($rst = mssql_fetch_array($stmt)){
														echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
													}
													mssql_free_result($stmt);
													?>
													</select>
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
												<th width="2%"><input type="checkbox" name="chkIncluidas" id="chkIncluidas" onclick="javascript: Selecciona(this)"/></th>
												<th width="15%">N&deg;Factura</th>
												<th width="2%">&nbsp;</th>
												<th width="62%" align="left">&nbsp;Proveedor</th>
												<th width="10%" align="right">Total&nbsp;</th>
												<th width="5%">M&nbsp;</th>
												<th width="3%">&nbsp;</th>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><div id="divFIncluidas" style="border:solid 1px; height:100px; overflow:scroll; position:relative; width:100%"></div></td></tr>
								<tr><td style="height:5px"></td></tr>
								<tr>
									<td>
										<table border="0" width="100%" cellpadding="0" cellspacing="0">
											<tr>
												<td width="0%" align="right">TOTAL&nbsp;</td>
												<td width="1%" align="center">:</td>
												<td width="15%"><input name="txtTotal" id="txtTotal" class="txt-plano" style="width:99%; text-align:right" readonly="true" value="0" /></td>												
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<tr>
									<td>
										<table border="0" width="100%" cellpadding="0" cellspacing="0">
											<tr>
												<td width="50%"><input type="button" name="btnQuita" id="btnQuita" class="boton" style="width:80px" value="&lt;&lt; Quitar" onclick="javascript: Quitar();" /></td>
												<td width="50%" align="right"><input type="button" name="btnResumen" id="btnResumen" class="boton" style="width:80px" value="Resumen" onclick="javascript: Resumen();" /></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="divTransaccion" style="display:none"></div>
<iframe name="frmExporta" id="frmExporta" style="display:none"></iframe>
<iframe name="frmExporta2" id="frmExporta2" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
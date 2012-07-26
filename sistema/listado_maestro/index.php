<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado Maestro</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var SelAnt = -1;

function Change(ctrl){
	switch(ctrl.id){
		case 'cmbFormatos':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=1&codigo=' + ctrl.value;
			break;
		case 'cmbContrato':
			document.getElementById('cmbMovil').disabled = true;
			document.getElementById('cmbAnexos').disabled = true;
			document.getElementById('cmbTAnexo').disabled = true;
			document.getElementById('transaccion').src = 'transaccion.php?modulo=0&contrato=' + ctrl.value;
			break;
		case 'cmbOCriterios':
			Bloquea(ctrl.value);
			break;
	}
}

function KeyPress(evento, ctrl){
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
}

function Bloquea(valor){
	Desbloquea();
	switch(parseInt(valor)){
		case 1:
		case 2:
		case 4:
		case 7:
		case 8:
		case 10:
			document.getElementById('txtFODesde').value = '';
			document.getElementById('txtFODesde').disabled = true;
			document.getElementById('imgFODesde').style.visibility = 'hidden';
			document.getElementById('txtFOHasta').value = '';
			document.getElementById('txtFOHasta').disabled = true;
			document.getElementById('imgFOHasta').style.visibility = 'hidden';
		case 5:
			document.getElementById('txtFHDesde').value = '';
			document.getElementById('txtFHDesde').disabled = true;
			document.getElementById('imgFHDesde').style.visibility = 'hidden';
			document.getElementById('txtFHHasta').value = '';
			document.getElementById('txtFHHasta').disabled = true;
			document.getElementById('imgFHHasta').style.visibility = 'hidden';
		case 3:
			document.getElementById('txtFTDesde').value = '';
			document.getElementById('txtFTDesde').disabled = true;
			document.getElementById('imgFTDesde').style.visibility = 'hidden';
			document.getElementById('txtFTHasta').value = '';
			document.getElementById('txtFTHasta').disabled = true;
			document.getElementById('imgFTHasta').style.visibility = 'hidden';
			break;
		case 9:
			document.getElementById('txtFHDesde').value = '';
			document.getElementById('txtFHDesde').disabled = true;
			document.getElementById('imgFHDesde').style.visibility = 'hidden';
			document.getElementById('txtFHHasta').value = '';
			document.getElementById('txtFHHasta').disabled = true;
			document.getElementById('imgFHHasta').style.visibility = 'hidden';
			break;
	}
}

function CargaLista(){
	var ordenes = document.getElementById('txtOrdenes').value;
	var lista = document.getElementById('cmbOrdenes');
	for(i = lista.length; i >= 0; i--) lista.remove(i);
	if(ordenes != ''){
		var orden = ordenes.split(',');
		for(i = 0; i < orden.length; i++) lista.options[lista.length] = new Option(orden[i], orden[i]);
	}
}

function Desbloquea(){
	document.getElementById('txtFTDesde').disabled = false;
	document.getElementById('imgFTDesde').style.visibility = 'visible';
	document.getElementById('txtFTHasta').disabled = false;
	document.getElementById('imgFTHasta').style.visibility = 'visible';
	document.getElementById('txtFODesde').disabled = false;
	document.getElementById('imgFODesde').style.visibility = 'visible';
	document.getElementById('txtFOHasta').disabled = false;
	document.getElementById('imgFOHasta').style.visibility = 'visible';
	document.getElementById('txtFHDesde').disabled = false;
	document.getElementById('imgFHDesde').style.visibility = 'visible';
	document.getElementById('txtFHHasta').disabled = false;
	document.getElementById('imgFHHasta').style.visibility = 'visible';
}

function Deshabilita(sw){
	document.getElementById('cmbFormatos').disabled = sw;
	
	document.getElementById('cmbDCOrden').disabled = sw;
	document.getElementById('btnAgregarOrden').disabled = sw;
	
	document.getElementById('cmbDAnexos').disabled = sw;
	document.getElementById('btnAgregarAnexos').disabled = sw;
	
	document.getElementById('cmbCampos').disabled = sw;
	document.getElementById('btnQuitar').disabled = sw;
	document.getElementById('btnAgregarOrdena').disabled = sw;
	document.getElementById('txtOrdenes').disabled = sw;
	document.getElementById('btnAOrdenes').disabled = sw;
	document.getElementById('btnSSubir').disabled = sw;
	document.getElementById('btnSBajar').disabled = sw;
	
	document.getElementById('cmbOrdenado').disabled = sw;
	document.getElementById('btnQuitarOrden').disabled = sw;
	document.getElementById('btnOSubir').disabled = sw;
	document.getElementById('btnOBajar').disabled = sw;
	
	document.getElementById('cmbContrato').disabled = sw;
	document.getElementById('cmbMovil').disabled = sw;
	document.getElementById('cmbAnexos').disabled = sw;
	document.getElementById('cmbTAnexo').disabled = sw;
	document.getElementById('cmbOCriterios').disabled = sw;

	switch(parseInt(document.getElementById('cmbOCriterios').value)){
		case 3:
			document.getElementById('txtFHDesde').disabled = sw;
			document.getElementById('imgFHDesde').style.visibility = (sw ? 'hidden' : 'visible');
			document.getElementById('txtFHHasta').disabled = sw;
			document.getElementById('imgFHHasta').style.visibility = (sw ? 'hidden' : 'visible');
		case 5:
			document.getElementById('txtFODesde').disabled = sw;
			document.getElementById('imgFODesde').style.visibility = (sw ? 'hidden' : 'visible');
			document.getElementById('txtFOHasta').disabled = sw;
			document.getElementById('imgFOHasta').style.visibility = (sw ? 'hidden' : 'visible');
			break;
		default:
			document.getElementById('txtFHDesde').disabled = sw;
			document.getElementById('imgFHDesde').style.visibility = (sw ? 'hidden' : 'visible');
			document.getElementById('txtFHHasta').disabled = sw;
			document.getElementById('imgFHHasta').style.visibility = (sw ? 'hidden' : 'visible');
			
			document.getElementById('txtFTDesde').disabled = sw;
			document.getElementById('imgFTDesde').style.visibility = (sw ? 'hidden' : 'visible');
			document.getElementById('txtFTHasta').disabled = sw;
			document.getElementById('imgFTHasta').style.visibility = (sw ? 'hidden' : 'visible');
			
			document.getElementById('txtFODesde').disabled = sw;
			document.getElementById('imgFODesde').style.visibility = (sw ? 'hidden' : 'visible');
			document.getElementById('txtFOHasta').disabled = sw;
			document.getElementById('imgFOHasta').style.visibility = (sw ? 'hidden' : 'visible');
	}
	
	document.getElementById('btnGuardar').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}

function Buscar(){
	var seleccion = document.getElementById('cmbCampos');
	var ordenar = document.getElementById('cmbOrdenado');
	if(seleccion.length == 0)
		alert('Debe agregar al menos una columna.');
	else{
		var paso = '', campos = '', orden = '';
		for(i = 0; i < seleccion.length; i++){ 
			paso = seleccion.options[i].value;
			campos += ',' + paso.substring(1);
		}
		document.getElementById('hdnCampos').value = campos.substring(1);
		
		for(i = 0; i < ordenar.length; i++){ 
			paso = ordenar.options[i].value;
			orden += ',' + paso.substring(1);
		}
		document.getElementById('hdnOrden').value = orden.substring(1);
		
		document.getElementById('frm').submit();
	}
}

function ListaOrdenes(){
	var ordenes = document.getElementById('cmbOrdenes');
	var lista = '';
	if(ordenes.length > 0){
		for(i = 0; i < ordenes.length; i++) lista += ordenes.options[i].value + ',';
		lista = lista.substr(0, lista.length - 1);
	}
	document.getElementById('txtOrdenes').value = lista;
	Deshabilita(false);
	CierraDialogo('divOrdenes', 'frmOrdenes');
}

function Ordenar(ctrl){
	var seleccion = document.getElementById('cmbCampos');
	var ordena = document.getElementById('cmbOrdenado');
	var valor = '', texto = '', ind = 0;
	switch(ctrl.id){
		case 'btnSSubir':
			if(seleccion.length > 0){
				ind = seleccion.selectedIndex;
				if(ind > -1){
					if((ind - 1) >= 0){
						valor = seleccion.options[ind - 1].value;
						texto = seleccion.options[ind - 1].text;
						seleccion.options[ind - 1].value = seleccion.options[ind].value;
						seleccion.options[ind - 1].text = seleccion.options[ind].text;
						seleccion.options[ind].value = valor;
						seleccion.options[ind].text = texto;
						seleccion.selectedIndex = ind - 1;
					}
				}
			}
			break;
		case 'btnSBajar':
			if(seleccion.length > 0){
				ind = seleccion.selectedIndex;
				if(ind > -1){
					if((ind + 1) < seleccion.length){
						valor = seleccion.options[ind + 1].value;
						texto = seleccion.options[ind + 1].text;
						seleccion.options[ind + 1].value = seleccion.options[ind].value;
						seleccion.options[ind + 1].text = seleccion.options[ind].text;
						seleccion.options[ind].value = valor;
						seleccion.options[ind].text = texto;
						seleccion.selectedIndex = ind + 1;
					}
				}
			}
			break;
		case 'btnOSubir':
			if(ordena.length > 0){
				ind = ordena.selectedIndex;
				if(ind > -1){
					if((ind - 1) >= 0){
						valor = ordena.options[ind - 1].value;
						texto = ordena.options[ind - 1].text;
						ordena.options[ind - 1].value = ordena.options[ind].value;
						ordena.options[ind - 1].text = ordena.options[ind].text;
						ordena.options[ind].value = valor;
						ordena.options[ind].text = texto;
						ordena.selectedIndex = ind - 1;
					}
				}
			}
			break;
		case 'btnOBajar':
			if(ordena.length > 0){
				ind = ordena.selectedIndex;
				if(ind > -1){
					if((ind + 1) < ordena.length){
						valor = ordena.options[ind + 1].value;
						texto = ordena.options[ind + 1].text;
						ordena.options[ind + 1].value = ordena.options[ind].value;
						ordena.options[ind + 1].text = ordena.options[ind].text;
						ordena.options[ind].value = valor;
						ordena.options[ind].text = texto;
						ordena.selectedIndex = ind + 1;
					}
				}
			}
			break;
	}
}

function QuitAgrega(ctrl){
	var seleccion = document.getElementById('cmbCampos');
	var ordenado = document.getElementById('cmbOrdenado');
	var Orden = document.getElementById('cmbDCOrden');
	var Anexos = document.getElementById('cmbDAnexos');
	
	switch(ctrl.id){
		case 'btnAgregarOrden':
			for(i = 0; i < Orden.length; i++){
				if(Orden.options[i].selected){
					seleccion.options[seleccion.length] = new Option(Orden.options[i].text, Orden.options[i].value);
					Orden.remove(i);
				}
			}
			break;
		case 'btnAgregarAnexos':
			for(i = 0; i < Anexos.length; i++){
				if(Anexos.options[i].selected){
					seleccion.options[seleccion.length] = new Option(Anexos.options[i].text, Anexos.options[i].value);
					Anexos.remove(i);
				}
			}
			break;
		case 'btnQuitar':
			for(i = 0; i < seleccion.length; i++){
				if(seleccion.options[i].selected){
					var valor = seleccion.options[i].value;
					if(valor.substring(0, 1) == 'O')
						Orden.options[Orden.length] = new Option(seleccion.options[i].text, seleccion.options[i].value);
					else
						Anexos.options[Anexos.length] = new Option(seleccion.options[i].text, seleccion.options[i].value);
					seleccion.remove(i);
				}
				for(j = 0; j < ordenado.length; j++){
					if(ordenado.options[j].value == valor){ 
						ordenado.remove(j);
						break;
					}
				}			
			}
			break;
		case 'btnAgregarOrdena':
			for(i = 0; i < seleccion.length; i++){
				if(seleccion.options[i].selected){
					ordenado.options[ordenado.length] = new Option(seleccion.options[i].text, seleccion.options[i].value);
					break;
				}
			}
			break;
		case 'btnQuitarOrden':
			for(i = 0; i < ordenado.length; i++){
				if(ordenado.options[i].selected){
					ordenado.remove(i);
					break;
				}
			}
			break;
		case 'btnAgregaOT':
			var ordenes = document.getElementById('cmbOrdenes');
			var orden = document.getElementById('txtOrden');
			if(parseInt(orden.value) > 0){ 
				var sw = 0;
				for(i = 0; i < ordenes.length; i++){ 
					if(parseInt(ordenes.options[i].value) == parseInt(orden.value)){
						sw = 1;
						break;
					}
				}			
				if(sw == 0){
					ordenes.options[ordenes.length] = new Option(orden.value, orden.value);
					orden.value = '';
				}else
					alert('El número de orden de trabajo ingresado ya existe en la lista.');
			}else
				alert('Debe ingresar un número de orden de trabajo.');
			break;
		case 'btnQuitaOT':
			var ordenes = document.getElementById('cmbOrdenes');
			for(i = 0; i <= ordenes.length; i++){ 
				if(ordenes.options[i].selected){ 
					ordenes.remove(i);
					break;
				}
			}
			break;
		case 'btnQuitaTodas':
			var ordenes = document.getElementById('cmbOrdenes');
			for(i = ordenes.length; i >= 0; i--) ordenes.remove(i);
	}
}

function Seleccionar(ind){
	if(document.getElementById('chkItem' + ind).disabled) return 0;
	if(SelAnt > -1){
		document.getElementById('trItem' + SelAnt).style.backgroundColor = '#FFFFFF';
		document.getElementById('trItem' + SelAnt).style.color = '#000000';
	}
	SelAnt = ind;
	document.getElementById('trItem' + ind).style.backgroundColor = '#316AC5';
	document.getElementById('trItem' + ind).style.color = '#FFFFFF';
	document.getElementById('chkItem' + ind).focus();
}
-->
</script>
<body>
<div id="divCalendario" style="position:absolute; width:20%; visibility:hidden; left: 10%; top: 5px;">
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

<div id="divGuardar" style="position:absolute; width:50%; visibility:hidden; left: 25%; top: 5px;">
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
											CierraDialogo('divGuardar', 'frmGuardar');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px;"><b>Guardar formato como...</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmGuardar" id="frmGuardar" frameborder="1" style="border:thin" scrolling="no" width="100%" height="132px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divOrdenes" style="position:absolute; width:30%; visibility:hidden; left: 40%; top: 125px;">
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
											CierraDialogo('divOrdenes', 'frmOrdenes');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>Agrega ordenes de trabajo</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="77%">
									<table border="0" width="100%" cellpadding="1" cellspacing="1">
										<tr>
											<td width="3%">&nbsp;N&deg;Orden</td>
											<td width="1%" align="center">:</td>
											<td width="96%">
												<input name="txtOrden" id="txtOrden" class="txt-plano" style="width:99%; text-align:center" 
													onblur="javascript: CambiaColor(this.id, false);"
													onfocus="javascript: CambiaColor(this.id, true);"
													onkeypress="javascript: return ValNumeros(event, this.id, false);"
												/>
											</td>
										</tr>
										<tr>
											<td colspan="3">
												<select name="cmbOrdenes" id="cmbOrdenes" class="sel-plano" style="width:100%" size="5">
												</select>
											</td>
										</tr>
									</table>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="22%" valign="top">
									<table border="0" width="100%" cellpadding="1" cellspacing="1">
										<tr>
											<td align="center">
												<input type="button" id="btnAgregaOT" name="btnAgregaOT" class="boton" style="width:80px" value="&lt;&lt; Agregar" 
													onclick="javascript: QuitAgrega(this)"
												/>
											</td>
										</tr>
										<tr>
											<td align="center">
												<input type="button" id="btnQuitaOT" name="btnQuitaOT" class="boton" style="width:80px" value="Quitar &gt;&gt;" 
													onclick="javascript: QuitAgrega(this)"
												/>
											</td>
										</tr>
										<tr>
											<td align="center">
												<input type="button" id="btnQuitaTodas" mane="btnQuitaTodas" class="boton" style="width:80px" value="Quitar todas" 
													onclick="javascript: QuitAgrega(this)"
												/>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td colspan="3"><hr></td></tr>
							<tr>
								<td colspan="3" align="right">
									<input type="button" name="btnAceptar" id="btnAceptar" class="boton" style="width:80px" value="Aceptar" 
										onclick="javascript: ListaOrdenes();"
									/>
								</td>
							</tr>
						</table>
						<iframe name="frmOrdenes" id="frmOrdenes" frameborder="1" style="border:thin;display:none" scrolling="no" width="100%" height="132px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" action="resultado.php<?php echo $parametros;?>">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="13%">&nbsp;Formatos Personales</td>
					<td width="1%" align="center">:</td>
					<td width="18%">
						<select name="cmbFormatos" id="cmbFormatos" class="sel-plano" style="width:100%"
							onchange="javascript: Change(this);"
						>
						<?php
						$stmt = mssql_query("EXEC Orden..sp_getFormatosInforme 0, '$usuario'", $cnx);
						if($rst = mssql_fetch_array($stmt)){
							echo '<option value="0">-- Seleccione --</option>';
							do{
								echo '<option value="'.$rst["dblCodigo"].'">'.$rst["strNombre"].'</option>';
							}while($rst = mssql_fetch_array($stmt));
						}else
							echo '<option value="0">-- Sin formatos --</option>';
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="60%">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<!--<tr><td ><hr></td></tr>-->
	<tr>
		<td valign="top">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="39%">
									<fieldset >
										<legend>Disponibles</legend>
										<table border="0" width="100%" cellpadding="1" cellspacing="0">
											<tr >
												<td width="49%" align="center">Caratula Orden</td>
												<td width="2%">&nbsp;</td>
												<td width="49%" align="center">Anexos</td>
											</tr>
											<tr>
												<td width="49%" align="right">
													<select name="cmbDCOrden" id="cmbDCOrden" class="sel-plano" style="width:137px; " size="7">
													<?php
													$stmt = mssql_query("EXEC Orden..sp_getCampos 0", $cnx);
													while($rst = mssql_fetch_array($stmt)){
														echo '<option value="'.$rst["strTabla"].$rst["dblCodigo"].'">'.trim($rst["strAlias"]).'</option>';
													}
													mssql_free_result($stmt);
													?>
													</select>
												</td>
												<td width="2%">&nbsp;</td>
												<td width="49%" align="right">
													<select name="cmbDAnexos" id="cmbDAnexos" class="sel-plano" style="width:137px" size="7">
													<?php
													$stmt = mssql_query("EXEC Orden..sp_getCampos 1", $cnx);
													while($rst = mssql_fetch_array($stmt))
														echo '<option style="width:100%" value="'.$rst["strTabla"].$rst["dblCodigo"].'">'.trim($rst["strAlias"]).'</option>';
													mssql_free_result($stmt);
													?>
													</select>
												</td>
											</tr>
											<tr>
												<td width="49%" align="right">
													<input type="button" name="btnAgregarOrden" id="btnAgregarOrden" class="boton" style="width:75px" value="Agregar &gt;&gt;" 
														onclick="javascript: QuitAgrega(this);"
													/>
												</td>
												<td width="2%">&nbsp;</td>
												<td width="49%" align="right">
													<input type="button" name="btnAgregarAnexos" id="btnAgregarAnexos" class="boton" style="width:75px" value="Agregar &gt;&gt;" 
														onclick="javascript: QuitAgrega(this);"
													/>
												</td>
											</tr>
										</table>
									</fieldset>
								</td>
								<td width="60%">
									<fieldset>
										<legend>Seleccionados</legend>
										<table border="0" width="100%" cellpadding="1" cellspacing="0">
											<tr >
												<td width="39%" align="center">Columnas</td>
												<td width="1%">&nbsp;</td>
												<td width="9%">&nbsp;</td>
												<td width="2%">&nbsp;</td>
												<td width="39%" align="center">Ordeno por</td>
												<td width="1%">&nbsp;</td>
												<td width="9%">&nbsp;</td>
											</tr>
											<tr>
												<td width="39%">
													<select name="cmbCampos" id="cmbCampos" class="sel-plano" style="width:100%" size="7">
													</select>
												</td>
												<td width="1%">&nbsp;</td>
												<td width="9%">
													<table border="0" width="100%" cellpadding="1" cellspacing="0">
														<tr>
															<td align="center">
																<input type="button" name="btnSSubir" id="btnSSubir" class="boton" style="width:75px" value="Subir" 
																	onclick="javascript: Ordenar(this);"
																/>
															</td>
														</tr>
														<tr>
															<td align="center">
																<input type="button" name="btnSBajar" id="btnSBajar" class="boton" style="width:75px" value="Bajar" 
																	onclick="javascript: Ordenar(this);"
																/>
															</td>
														</tr>
													</table>
												</td>
												<td width="2%">&nbsp;</td>
												<td width="39%" >
													<select name="cmbOrdenado" id="cmbOrdenado" class="sel-plano" style="width:100%" size="7">
													</select>
												</td>
												<td width="1%">&nbsp;</td>
												<td width="9%">
													<table border="0" width="100%" cellpadding="1" cellspacing="0">
														<tr>
															<td align="center">
																<input type="button" name="btnOSubir" id="btnOSubir" class="boton" style="width:75px" value="Subir" 
																	onclick="javascript: Ordenar(this);"
																/>
															</td>
														</tr>
														<tr>
															<td align="center">
																<input type="button" name="btnOBajar" id="btnOBajar" class="boton" style="width:75px" value="Bajar" 
																	onclick="javascript: Ordenar(this);"
																/>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr >
												<td>
													<table border="0" width="100%" cellpadding="0" cellspacing="0">
														<tr>
															<td >
																<input type="button" name="btnQuitar" id="btnQuitar" class="boton" style="width:75px" value="&lt;&lt; Quitar" 
																	onclick="javascript: QuitAgrega(this);"
																/>
															</td>
															<td align="right">
																<input type="button" name="btnAgregarOrdena" id="btnAgregarOrdena" class="boton" style="width:75px" value="Agregar &gt;&gt;" 
																	onclick="javascript: QuitAgrega(this);"
																/>
															</td>
														</tr>
													</table>
												</td>
												<td colspan="3">&nbsp;</td>
												<td>
													<input type="button" name="btnQuitarOrden" id="btnQuitarOrden" class="boton" style="width:75px" value="&lt;&lt; Quitar" 
														onclick="javascript: QuitAgrega(this);"
													/>
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
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="70%">
						<fieldset>
							<legend>Criterios</legend>
							<table border="0" width="100%" cellpadding="1" cellspacing="0">
								<tr>
									<td>
										<table border="0" width="100%" cellpadding="0" cellspacing="0">
											<tr>
												<td width="4%">Contrato</td>
												<td width="1%" align="center">:</td>
												<td width="22%">
													<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%"
														onchange="javascript: Change(this);"
													>
													<?php
													$stmt = mssql_query("EXEC General..sp_getContratos 1, '$usuario'", $cnx);
													while($rst=mssql_fetch_array($stmt)){
														if($contrato == '') $contrato = $rst["strContrato"];
														echo '<option value="'.$rst["strContrato"].'">'.$rst["strDetalle"].'</option>';
													}
													mssql_free_result($stmt);?>
													</select>
												</td>
												<td width="1%">&nbsp;</td>
												<td width="6%">&nbsp;Movil H.</td>
												<td width="1%" align="center">:</td>
												<td width="8%">
													<select name="cmbMovil" id="cmbMovil" class="sel-plano" style="width:100%">
													<?php
													$stmt = mssql_query("EXEC General..sp_getMoviles 13, '$contrato'", $cnx);
													if($rst = mssql_fetch_array($stmt)){
														echo '<option value="all">Todos</option>';
														do{
															echo '<option value="'.$rst["strMovil"].'">'.$rst["strMovil"].'</option>';
														}while($rst = mssql_fetch_array($stmt));
													}
													mssql_free_result($stmt);
													?>
													</select>
												</td>
												<td width="1%">&nbsp;</td>
												<td width="3%">&nbsp;Anexos</td>
												<td width="1%" align="center">:</td>
												<td width="8%">
													<select name="cmbAnexos" id="cmbAnexos" class="sel-plano" style="width:100%">
													<?php
													$stmt = mssql_query("EXEC General..sp_getMoviles 13, '$contrato'", $cnx);
													if($rst = mssql_fetch_array($stmt)){
														echo '<option value="all">Todos</option>';
														do{
															echo '<option value="'.$rst["strMovil"].'">'.$rst["strMovil"].'</option>';
														}while($rst = mssql_fetch_array($stmt));
													}
													mssql_free_result($stmt);?>
													</select>
												</td>
												<td width="1%">&nbsp;</td>
												<td width="4%">T.Anexo</td>
												<td width="1%" align="center">:</td>
												<td width="15%">
													<select name="cmbTAnexo" id="cmbTAnexo" class="sel-plano" style="width:100%">
													<?php
													$stmt = mssql_query("EXEC General..sp_getEstados 0, '$contrato'", $cnx);
													if($rst = mssql_fetch_array($stmt)){
														echo '<option value="all">Todos</option>';
														do{
															echo '<option value="'.$rst["strDetalle"].'">'.$rst["strDetalle"].'</option>';
														}while($rst = mssql_fetch_array($stmt));
													}
													mssql_free_result($stmt);
													?>
													</select>
												</td>
												<td width="1%">&nbsp;</td>
												<td width="6%">&nbsp;Criterios</td>
												<td width="1%" align="center">:</td>
												<td width="15%">
													<select name="cmbOCriterios" id="cmbOCriterios" class="sel-plano" style="width:100%"
														onchange="javascript: Change(this);"
													>
														<option value="0">--</option>
														<option value="1">Anexos pendientes</option>
														<option value="2">Sin cobro</option>
														<option value="3">Sin fin hidráulico</option>
														<option value="4">Sin informar</option>
														<option value="5">Nulas</option>
														<option value="6">Informada sin t&eacute;rmino</option>
														<option value="7">Regular</option>
														<option value="8">Diario Hidraulico</option>
														<option value="9">Sin inicio hidráulico</option>
														<option value="10">Control Diario</option>
														<option value="11">Anexos Terminados</option>
														<option value="12">Con Informe Hidraulico</option>
														<option value="13">Ordenes Pagadas</option>
													</select>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<table border="0" width="100%" cellpadding="0" cellspacing="0">
											<tr>
												<td width="32%">
													<fieldset>
														<legend>Fecha T&eacute;rmino Hidraulico</legend>
														<table border="0" width="100%" cellpadding="0" cellspacing="0">
															<tr>
																<td width="42%">
																	<input name="txtFTDesde" id="txtFTDesde" class="txt-plano" style="width:99%; text-align:center"
																		onblur="javascript: CambiaColor(this.id, false);"
																		onfocus="javascript: CambiaColor(this.id, true);"
																		onkeypress="javascript: return KeyPress(event, this);"
																	/>
																</td>
																<td width="2%" align="center">
																	<a href="#" title="Abre cuadro calendario."
																		onblur="javascript: CambiaImagen('imgFTDesde', false);"
																		onclick="javascript: 
																			Deshabilita(true);
																			AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFTDesde&foco=imgFTHasta&fecha=' + document.getElementById('txtFTDesde').value, '', '2%', '98px');
																		"
																		onfocus="javascript: CambiaImagen('imgFTDesde', true);"
																		onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
																	><img id="imgFTDesde" border="0" align="absmiddle" src="../images/aba.gif" /></a>
																</td>
																<td width="6%" align="center">-</td>
																<td width="42%">
																	<input name="txtFTHasta" id="txtFTHasta" class="txt-plano" style="width:99%; text-align:center" 
																		onblur="javascript: CambiaColor(this.id, false);"
																		onfocus="javascript: CambiaColor(this.id, true);"
																		onkeypress="javascript: return KeyPress(event, this);"
																	/>
																</td>
																<td width="2%" align="center">
																	<a href="#" title="Abre cuadro calendario."
																		onblur="javascript: CambiaImagen('imgFTHasta', false);"
																		onclick="javascript: 
																			Deshabilita(true);
																			AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFTHasta&foco=imgFODesde&fecha=' + document.getElementById('txtFTHasta').value, '', '13%', '98px');
																		"
																		onfocus="javascript: CambiaImagen('imgFTHasta', true);"
																		onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
																	><img id="imgFTHasta" border="0" align="absmiddle" src="../images/aba.gif" /></a>
																</td>
															</tr>
														</table>
													</fieldset>
												</td>
												<td width="33%">
													<fieldset>
														<legend>Fecha Orden</legend>
														<table border="0" width="100%" cellpadding="0" cellspacing="0">
															<tr>
																<td width="45%">
																	<input name="txtFODesde" id="txtFODesde" class="txt-plano" style="width:99%; text-align:center" 
																		onblur="javascript: CambiaColor(this.id, false);"
																		onfocus="javascript: CambiaColor(this.id, true);"
																		onkeypress="javascript: return KeyPress(event, this);"
																	/>
																</td>
																<td width="2%" align="center">
																	<a href="#" title="Abre cuadro calendario."
																		onblur="javascript: CambiaImagen('imgFODesde', false);"
																		onclick="javascript: 
																			Deshabilita(true);
																			AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFODesde&foco=imgFOHasta&fecha=' + document.getElementById('txtFODesde').value, '', '29%', '98px');
																		"
																		onfocus="javascript: CambiaImagen('imgFODesde', true);"
																		onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
																	><img id="imgFODesde" border="0" align="absmiddle" src="../images/aba.gif" /></a>
																</td>
																<td width="6%" align="center">-</td>
																<td width="45%">
																	<input name="txtFOHasta" id="txtFOHasta" class="txt-plano" style="width:99%; text-align:center" 
																		onblur="javascript: CambiaColor(this.id, false);"
																		onfocus="javascript: CambiaColor(this.id, true);"
																		onkeypress="javascript: return KeyPress(event, this);"
																	/>
																</td>
																<td width="2%" align="center">
																	<a href="#" title="Abre cuadro calendario."
																		onblur="javascript: CambiaImagen('imgFOHasta', false);"
																		onclick="javascript: 
																			Deshabilita(true);
																			AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFOHasta&foco=imgFHDesde&fecha=' + document.getElementById('txtFOHasta').value, '', '46%', '98px');
																		"
																		onfocus="javascript: CambiaImagen('imgFOHasta', true);"
																		onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
																	><img id="imgFOHasta" border="0" align="absmiddle" src="../images/aba.gif" /></a>
																</td>
															</tr>
														</table>
													</fieldset>
												</td>
												<td width="32%">
													<fieldset>
														<legend>F.Baja</legend>
														<table border="0" width="100%" cellpadding="0" cellspacing="0">
															<tr>
																<td width="45%">
																	<input name="txtFHDesde" id="txtFHDesde" class="txt-plano" style="width:99%; text-align:center" 
																		onblur="javascript: CambiaColor(this.id, false);"
																		onfocus="javascript: CambiaColor(this.id, true);"
																		onkeypress="javascript: return KeyPress(event, this);"
																	/>
																</td>
																<td width="2%" align="center">
																	<a href="#" title="Abre cuadro calendario."
																		onblur="javascript: CambiaImagen('imgFHDesde', false);"
																		onclick="javascript: 
																			Deshabilita(true);
																			AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFHDesde&foco=imgFHHasta&fecha=' + document.getElementById('txtFHDesde').value, '', '62%', '98px');
																		"
																		onfocus="javascript: CambiaImagen('imgFHDesde', true);"
																		onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
																	><img id="imgFHDesde" border="0" align="absmiddle" src="../images/aba.gif" /></a>
																</td>
																<td width="6%" align="center">-</td>
																<td width="45%">
																	<input name="txtFHHasta" id="txtFHHasta" class="txt-plano" style="width:99%; text-align:center" 
																		onblur="javascript: CambiaColor(this.id, false);"
																		onfocus="javascript: CambiaColor(this.id, true);"
																		onkeypress="javascript: return KeyPress(event, this);"
																	/>
																</td>
																<td width="2%" align="center">
																	<a href="#" title="Abre cuadro calendario."
																		onblur="javascript: CambiaImagen('imgFHHasta', false);"
																		onclick="javascript: 
																			Deshabilita(true);
																			AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFHHasta&foco=btnOSubir&fecha=' + document.getElementById('txtFHHasta').value, '', '78%', '98px');
																		"
																		onfocus="javascript: CambiaImagen('imgFHHasta', true);"
																		onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
																	><img id="imgFHHasta" border="0" align="absmiddle" src="../images/aba.gif" /></a>
																</td>
															</tr>
														</table>
													</fieldset>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<table border="0" width="100%" cellpadding="0" cellspacing="0">
											<tr>
												<td width="3%">&nbsp;N&deg;Orden</td>
												<td width="1%" align="center">:</td>
												<td width="46%">
													<input name="txtOrdenes" id="txtOrdenes" class="txt-plano" style="width:99%"
														onblur="javascript: CambiaColor(this.id, false);"
														onfocus="javascript: CambiaColor(this.id, true);"
														onkeypress="javascript: return KeyPress(event, this)"
													/>
												</td>
												<td width="1%">&nbsp;</td>
												<td width="16%">
													<input type="button" name="btnAOrdenes" id="btnAOrdenes" class="boton" style="width:120px" value="Agregar Ordenes..." 
														onclick="javascript:
															Deshabilita(true);
															CargaLista();
															AbreDialogo('divOrdenes', 'frmOrdenes', '../blank.html')
														"
													/>
												</td>
												<td width="1%">&nbsp;</td>
												<td width="5%">&nbsp;Depto.</td>
												<td width="1%" align="center">:</td>
												<td width="8%">
													<select name="cmbDepto" id="cmbDepto" class="sel-plano" style="width:100%">
														<option value="all">Todos</option>
													<?php
													$stmt = mssql_query("SELECT strCodigo, strDetalle FROM Orden..Tablon WHERE strTabla = 'depto' AND strContrato = '$contrato' ORDER BY strCodigo", $cnx);
													while($rst = mssql_fetch_array($stmt)){
														echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
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
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="right" valign="middle" style="height:30px">
			<input type="hidden" name="hdnCampos" id="hdnCampos" />
			<input type="hidden" name="hdnOrden" id="hdnOrden" />
			
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar..." 
				onclick="javascript: 					
					if(document.getElementById('cmbCampos').length > 0){
						Deshabilita(true);
						AbreDialogo('divGuardar', 'frmGuardar', 'guardar.php?usuario=<?php echo $usuario;?>');
					}else
						alert('Debe agregar al menos una columna.');
				"
			/>
			<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
				onclick="javascript: Buscar()"
			/>
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>

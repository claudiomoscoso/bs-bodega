<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Busqueda de Documentos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var swBuscar = 0;
function Load(){
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 130);
	document.getElementById('frmGeneral').setAttribute('height', window.innerHeight - 130);
	ClickFicha(1);
}

function Change(ctrl){
	var documento = document.getElementById('cmbTDoc').value;
	document.getElementById('txtObservacion').value = '';
	switch(ctrl.id){
		case 'cmbTBusq':
			if(parseInt(documento) == 1){
				if(parseInt(ctrl.value) == 0)
					document.getElementById('txtObservacion').disabled = false;
				else
					document.getElementById('txtObservacion').disabled = true;
			}
			break;
		case 'cmbTDoc':
			document.getElementById('txtNumero').value='';
			document.getElementById('hdnNumero').value='';
			document.getElementById('txtProveedor').value='';
			document.getElementById('hdnProveedor').value='';
			OpcionesBusqueda(ctrl);
			document.getElementById('transaccion').src = 'transacciones.php?usuario=<?php echo $usuario;?>&tipo=2&doc=' + ctrl.value;
			break;
	}
}

function Deshabilita(sw){
	var doc = document.getElementById('cmbTDoc').value;
	var bus = document.getElementById('cmbTBusq').value;
	if(parseInt(doc) != 3 && parseInt(doc) != 4 && parseInt(doc) != 5 && parseInt(doc) != 7 && parseInt(doc) != 8 && parseInt(doc) != 9 && parseInt(doc) != 11){
		document.getElementById('cmbTBusq').disabled=sw;
		if(!(parseInt(doc) == 1 && parseInt(bus) == 1)) document.getElementById('txtObservacion').disabled=sw;
	}
	if(parseInt(doc) != 7 && parseInt(doc) != 8 && parseInt(doc) != 9 && parseInt(doc) != 10 && parseInt(doc) != 11){
		document.getElementById('txtMaterial').disabled=sw;
		document.getElementById('txtProveedor').disabled=sw;
	}
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('cmbTDoc').disabled=sw;
	document.getElementById('cmbMes').disabled=sw;
	document.getElementById('cmbAno').disabled=sw;

	document.getElementById('txtNumero').disabled=sw;
	document.getElementById('btnBuscar').disabled=sw;
	document.getElementById('btnImprimir1').disabled=sw;
}
function DeshabilitaImprimirOC(sw){
	document.getElementById('btnImprimir2').disabled=sw;
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		var url = '';
		CambiaColor(ctrl.id, false);
		if(ctrl.id == 'txtMaterial'){
			Deshabilita(true);
			url = 'buscar_material.php?texto=' + ctrl.value + '&bodega=' + document.getElementById('cmbBodega').value+'&ctrl='+ctrl.id+'&foco=txtProveedor';
			//alert(url);
		}else if(ctrl.id=='txtProveedor'){
			Deshabilita(true);
			switch (parseInt(document.getElementById('cmbTDoc').value)){
				case 0:
					url = 'buscar_cargo.php?bodega='+document.getElementById('cmbBodega').value+'&texto='+ctrl.value+'&ctrl='+ctrl.id+'&foco=txtObservacion';
					break;
				case 1:
				case 2:
				case 6:
					url = 'buscar_proveedor.php?texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=txtObservacion';
					break;
				case 3:
				case 4:
					url = 'buscar_movil.php?texto='+ctrl.value+'&bodega='+document.getElementById('cmbBodega').value+'&ctrl='+ctrl.id+'&foco=txtObservacion';
					break;
				case 5:
					url = 'buscar_responsable.php?texto='+ctrl.value+'&bodega='+document.getElementById('cmbBodega').value+'&ctrl='+ctrl.id+'&foco=txtObservacion';
					break;
			}
		}
		AbreDialogo('divBuscador', 'frmBuscador', url);
	}
}

function Busqueda(ctrl){
	if(ctrl.value!=''){
		if(ctrl.id=='txtMaterial')
			document.getElementById('transaccion').src='transacciones.php?tipo=0&bodega='+document.getElementById('cmbBodega').value+'&valor='+ctrl.value+'&ctrl='+ctrl.id+'&hdn=hdnMaterial';
		else if(ctrl.id=='txtProveedor')
			document.getElementById('transaccion').src='transacciones.php?tipo=1&doc='+document.getElementById('cmbTDoc').value+'&bodega='+document.getElementById('cmbBodega').value+'&valor='+ctrl.value+'&ctrl='+ctrl.id+'&hdn=hdnProveedor';
	}else{
		if(ctrl.id=='txtMaterial')
			document.getElementById('hdnMaterial').value='';
		else if(ctrl.id=='txtProveedor')
			document.getElementById('hdnProveedor').value='';
		else if(ctrl.id=='txtNumero')
			document.getElementById('hdnNumero').value='';
	}
}

function Buscar(ficha){
	var tipodoc = document.getElementById('cmbTDoc').value;
	var bodega = document.getElementById('cmbBodega').value;
	var mes = document.getElementById('cmbMes').value;
	var ano = document.getElementById('cmbAno').value;
	var material = document.getElementById('hdnMaterial').value;
	var proveedor = document.getElementById('hdnProveedor').value;
	var tbusq = document.getElementById('cmbTBusq').value;
	var observacion = document.getElementById('txtObservacion').value;
	//alert(bodega);
	
	Deshabilita(true);
	ClickFicha(1);
	if(document.getElementById('txtNumero').value == ''){
		url1='resultado_general.php?bodega=' + bodega + '&tipodoc=' + tipodoc + '&mes=' + mes +'&ano=' + ano + 
		'&material=' + material + '&proveedor=' + proveedor + '&tbusqueda=' + tbusq + '&observacion=' + observacion;
		//alert(url1);
		document.getElementById('frmGeneral').src = url1;
		document.getElementById('frmDetalle').src = 'cab_detalle.php?bodega=' + bodega + '&tipodoc=' + tipodoc + '&mes=' + mes + '&ano=' + ano + 
		'&material=' + material + '&proveedor=' + proveedor + '&tbusqueda=' + tbusq + '&observacion=' + observacion;
	}else{
		var numero = document.getElementById('txtNumero').value, url = '';
		switch(parseInt(tipodoc)){
			case 0:
				url = 'imprime_sm.php?directo=true&bodega=' + bodega + '&numero=' + numero;
				break;
			case 1:
				url = 'imprime_oc.php?directo=true&bodega=' + bodega + '&numero=' + numero;
				break;
			case 2:
				url = 'imprime_ing.php?directo=true&bodega=' + bodega + '&numero=' + numero;
				break;
			case 3:
				url = 'imprime_desp.php?directo=true&bodega=' + bodega + '&numero=' + numero;
				break;
			case 4:
				url = 'imprime_dev.php?directo=true&bodega=' + bodega + '&numero=' + numero;
				break;
			case 5:
				url = 'resultado_general.php?bodega=' + bodega + '&tipodoc=' + tipodoc + '&mes=' + mes + '&ano=' + ano + '&proveedor=' + proveedor + '&tbusqueda=' + tbusq + '&observacion=' + observacion + '&numero=' + numero;
				
				document.getElementById('frmDetalle').src = 'cab_detalle.php?bodega=' + bodega + '&tipodoc=' + tipodoc + '&mes=' + mes + '&ano=' + ano + 
				'&material=' + material + '&proveedor=' + proveedor + '&tbusqueda=' + tbusq + '&observacion=' + observacion + '&numero=' + numero;
				break;
			case 6:
				url = 'imprime_fact.php?directo=true&cargo=' + bodega + '&numero=' + numero + '&mes=' + mes + '&ano=' + ano + '&bodega=' + bodega;
				break;
			case 7:
				url = 'resultado_general.php?bodega=' + bodega + '&tipodoc=' + tipodoc + '&mes=' + mes + '&ano=' + ano + '&proveedor=' + proveedor + '&tbusqueda=' + tbusq + '&observacion=' + observacion + '&numero=' + numero;
				
				document.getElementById('frmDetalle').src = 'cab_detalle.php?bodega=' + bodega + '&tipodoc=' + tipodoc + '&mes=' + mes + '&ano=' + ano + 
				'&material=' + material + '&proveedor=' + proveedor + '&tbusqueda=' + tbusq + '&observacion=' + observacion + '&numero=' + numero;
				break;
			case 8:
				url = 'imprime_gcargo.php?directo=true&bodega=' + bodega + '&numero=' + numero;
				break;
			case 9:
				url = 'imprime_gdevcargo.php?directo=true&bodega=' + bodega + '&numero=' + numero;
				break;
			case 10:
				url = 'imprime_tb.php?directo=true&numero=' + numero;
				break;
			case 11:
				url = 'imprime_factint.php?directo=true&numero=' + numero + '&cargo=' + bodega;
				break;
		}
		document.getElementById('frmGeneral').src = url;
	}
}

function ClickFicha(idFicha){
	idTab = idFicha;
	if(idFicha == 1){
		document.getElementById('frmDetalle').style.display = 'none';
		document.getElementById('tdFicha1').setAttribute('background', '../images/ficha_sel.gif');
		document.getElementById('aFicha1').style.color='#FFFFFF';
		document.getElementById('aFicha1').style.fontWeight='bold';
		document.getElementById('tdFicha2').setAttribute('background', '../images/ficha.gif');
		document.getElementById('aFicha2').style.color='#3A4CFB';
		document.getElementById('aFicha2').style.fontWeight='normal';
		document.getElementById('frmGeneral').style.display = '';
		document.getElementById('btnDescarga').style.display = 'none';
	}else{
		document.getElementById('frmGeneral').style.display = 'none';
		document.getElementById('tdFicha1').setAttribute('background', '../images/ficha.gif');
		document.getElementById('aFicha1').style.color='#3A4CFB';
		document.getElementById('aFicha1').style.fontWeight='normal';
		document.getElementById('tdFicha2').setAttribute('background', '../images/ficha_sel.gif');
		document.getElementById('aFicha2').style.color='#FFFFFF';
		document.getElementById('aFicha2').style.fontWeight='bold';
		document.getElementById('frmDetalle').style.display = '';
		if(frmDetalle.document.getElementById('frmListado')){
			frmDetalle.document.getElementById('frmListado').setAttribute('height', document.getElementById('frmDetalle').height - 20);
			document.getElementById('btnDescarga').style.display = '';
		}
	}
}

function Descargar(){
	if(idTab==2 && frmDetalle.document.getElementById('frmListado')){ 
		document.getElementById('transaccion').src='descarga_detalle.php?bodega='+document.getElementById('cmbBodega').value+'&tipodoc='+document.getElementById('cmbTDoc').value+'&mes='+document.getElementById('cmbMes').value+'&ano='+document.getElementById('cmbAno').value+'&material='+document.getElementById('hdnMaterial').value+'&proveedor='+document.getElementById('hdnProveedor').value+'&tbusqueda='+document.getElementById('cmbTBusq').value+'&observacion='+document.getElementById('txtObservacion').value;
	}
}

function Imprimir(idFrm){
	if(!frmGeneral.document.getElementById('resultado')){
		if(frmGeneral.document.getElementById('documento')) frmGeneral.print();
	}else{
		if(frmDocumento.document.getElementById('documento')) frmDocumento.print();
	}
}

function OpcionesBusqueda(ctrl){
	var cmb = document.getElementById('cmbTBusq'), opciones = null;
	for(i = cmb.length - 1; i >= 0; i--){ cmb.remove(i);}
	cmb.disabled = false;
	document.getElementById('cmbBodega').disabled = false;
	document.getElementById('cmbMes').disabled = false;
	document.getElementById('cmbAno').disabled = false;
	document.getElementById('txtMaterial').disabled = false;
	document.getElementById('txtProveedor').disabled = false;
	document.getElementById('cmbTBusq').disabled = false;
	document.getElementById('txtObservacion').disabled = false;
	document.getElementById('tblTotal').style.visibility = 'hidden';
	document.getElementById('txtTotal').value = 0;
	document.getElementById('txtObservacion').value = '';
	switch(parseInt(ctrl.value)){
		case 0:
			opciones = new Option('O.Compra Nº', 0);
			cmb.options[cmb.length] = opciones;
			opciones = new Option('Observación', 1);
			cmb.options[cmb.length] = opciones;
			break;
		case 1:
			opciones = new Option('Observación', 0);
			cmb.options[cmb.length] = opciones;
			opciones = new Option('Sin solicitud', 1);
			cmb.options[cmb.length] = opciones;
			document.getElementById('tblTotal').style.visibility = 'visible';
			break;
		case 2:
			opciones = new Option('O.Compra Nº', 0);
			cmb.options[cmb.length] = opciones;
			opciones = new Option('Referencia', 1);
			cmb.options[cmb.length] = opciones;
			break;
		case 3:
		case 4:
		case 5:
			opciones = new Option('Referencia Vale', 1);
			cmb.options[cmb.length] = opciones;
			document.getElementById('txtObservacion').disabled = false;
			break;
		case 6:
			document.getElementById('cmbBodega').disabled = false;
			opciones = new Option('O.Compra Nº', 0);
			cmb.options[cmb.length] = opciones;
			opciones = new Option('G.Ingreso', 1);
			cmb.options[cmb.length] = opciones;
			document.getElementById('tblTotal').style.visibility = 'visible';
			break;
		case 7:
		case 8:
		case 9:
		case 10:
		case 11:
			document.getElementById('txtMaterial').disabled = true;
			document.getElementById('txtProveedor').disabled = true;
			if(parseInt(ctrl.value) == 7) document.getElementById('tblTotal').style.visibility = 'visible';
			document.getElementById('cmbTBusq').disabled = true;
			document.getElementById('txtObservacion').disabled = true;
			break;
	}
}
-->
</script>
<body onload="javascript: Load();">
<div id="divBuscador" style="position:absolute; top:10px; left:12%; width:75%; visibility:hidden">
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
											CierraDialogo('divBuscador', 'frmBuscador');
										"
										onmouseover="javascript: window.status='Cierra cuadro de busqueda.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>Cuadro de Busqueda</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmBuscador" id="frmBuscador" frameborder="0" style="border:thin" scrolling="no" width="100%" height="200px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divCalendario" style="position:absolute; top:62px; left:79%; width:20%; visibility:hidden">
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

<div id="divDocumento" style="position:absolute; top:5px; left:2%; width:95%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra cuadro de visualizaci&oacute;n"
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divDocOrigen', 'frmDocOrigen');
											CierraDialogo('divDocumento', 'frmDocumento');
										"
										onmouseover="javascript: window.status='Cierra cuadro de visualización.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>Visualizador de Documentos</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmDocumento" id="frmDocumento" frameborder="0" style="border:thin" scrolling="auto" width="100%" height="275px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
				<tr><td><hr /></td></tr>
				<tr>
					<td align="right">
						<input type="button" name="btnImprimir2" id="btnImprimir2" class="boton" style="width:90px" value="Imprimir..." 
							onclick="javascript: Imprimir('frmDocumento');"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divDocOrigen" style="position:absolute; top:5px; left:2%; width:95%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra cuadro de visualizaci&oacute;n"
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divDocOrigen', 'frmDocOrigen');
										"
										onmouseover="javascript: window.status='Cierra cuadro de visualización.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>Visualizador de Documentos</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmDocOrigen" id="frmDocOrigen" frameborder="0" style="border:thin" scrolling="auto" width="100%" height="275px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
				<tr><td><hr /></td></tr>
				<tr>
					<td align="right">
						<input type="button" name="btnImprimir3" id="btnImprimir3" class="boton" style="width:90px" value="Imprimir.." 
							onclick="javascript: Imprimir('frmDocOrigen');"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="6%">&nbsp;Tipo Doc.</td>
					<td width="1%">:</td>
					<td width="0%">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="16%">
									<select name="cmbTDoc" id="cmbTDoc" class="sel-plano" style="width:100%"
										onchange="javascript: Change(this);"
									>
										<option value="0">Solicitud de Materiales</option>
										<option value="1">Orden de Compra</option>
										<option value="2">Gu&iacute;a de Ingreso</option>
										<option value="3">Gu&iacute;a de Despacho</option>
										<option value="4">Gu&iacute;a de Devoluci&oacute;n</option>
										<option value="5">Vale de Consumo</option>
										<option value="6">Factura/Boletas</option>
										<option value="7">Caja Chica</option>
										<option value="8">Gu&iacute;a de Cargos</option>
										<option value="9">Gu&iacute;a de Devoluci&oacute;n de Cargos</option>
										<option value="10">T&eacute;rmino de Bodega</option>
										<option value="11">Factura Interna</option>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="8%">&nbsp;Bodega/Cargo</td>
								<td width="1%">:</td>
								<td width="20%">
									<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%">
									<?php
									$stmt = mssql_query("EXEC General..sp_getBodega 4, '$usuario'", $cnx);
									while($rst=mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strBodega"].'" '.($bodega == $rst["strBodega"] ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left">&nbsp;N&uacute;mero</td>
								<td width="1%">:</td>
								<td width="10%">
									<input name="txtNumero" id="txtNumero" class="txt-plano" style="width:100%; text-align:center" maxlength="10" 
										onblur="javascript: 
											CambiaColor(this.id, false);
											Busqueda(this);
										"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return ValNumeros(event, this.id, false);"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Fecha</td>
								<td width="1%">:</td>
								<td width="10%">
									<select name="cmbMes" id="cmbMes" class="sel-plano" style="width:100%">
										<option value="NULL" >--</option>
										<option value="01" >Enero</option>
										<option value="02" >Febrero</option>
										<option value="03" >Marzo</option>
										<option value="04" >Abril</option>
										<option value="05" >Mayo</option>
										<option value="06" >Junio</option>
										<option value="07" >Julio</option>
										<option value="08" >Agosto</option>
										<option value="09" >Septiembre</option>
										<option value="10" >Octubre</option>
										<option value="11" >Noviembre</option>
										<option value="12" >Diciembre</option>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="7%">
									<select name="cmbAno" id="cmbAno" class="sel-plano" style="width:100%">
									<?php
									for($i = 2005; $i <= date('Y'); $i++){?>
										<option value="<?php echo $i;?>"  <?php echo date('Y')==$i ? 'selected' : '';?>><?php echo $i;?></option>
									<?php
									}
									?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;Material</td>
					<td>:</td>
					<td colspan="14">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="23%">
									<input name="txtMaterial" id="txtMaterial" class="txt-plano" style="width:100%" 
										onblur="javascript: 
											CambiaColor(this.id, false);
											Busqueda(this);
										"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: KeyPress(event, this);"
									/>
								</td>
								<td width="1%" >&nbsp;</td>
								<td width="6%" >&nbsp;Dirigido a</td>
								<td width="1%">:</td>
								<td width="23%">
									<input name="txtProveedor" id="txtProveedor" class="txt-plano" style="width:100%" 
										onblur="javascript: 
											CambiaColor(this.id, false);
											Busqueda(this);
										"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript:
											KeyPress(event, this);
										"
									/>
								</td>
								<td width="1%" >&nbsp;</td>
								<td width="12%" >
									<select name="cmbTBusq" id="cmbTBusq" class="sel-plano" style="width:100%"
										onchange="javascript: Change(this);"
									>
										<option value="0">O.Compra N&deg;</option>
										<option value="1">Observaci&oacute;n</option>
									</select>
								</td>
								<td width="1%" align="center">:</td>
								<td width="20%">
									<input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:100%" maxlength="100" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="16">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="right">
									<input type="hidden" name="hdnNumero" id="hdnNumero" />
									<input type="hidden" name="hdnMaterial" id="hdnMaterial" />
									<input type="hidden" name="hdnProveedor" id="hdnProveedor" />
									<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
										onclick="javascript: Buscar(1);"
									/>
									<input type="button" name="btnImprimir1" id="btnImprimir1" class="boton" style="width:90px" value="Imprimir..."
										onclick="javascript: Imprimir();"
									/>
									<input type="button" name="btnDescarga" id="btnDescarga" class="boton" style="width:90px; display:none" value="Exportar..." 
										onclick="javascript: Descargar();"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="16"><hr /></td></tr>
				<tr>
					<td colspan="16">
						<table border="0" width="160px" cellpadding="0" cellspacing="0">
							<tr>
								<td id="tdFicha1" width="80px" align="center" style="background-repeat:no-repeat;">
									<a id="aFicha1" href="#" style="color:#FFFFFF" title="General"
										onclick="javascript: ClickFicha(1);"
										onmouseover="javascript: window.status='General.'; return true;"
									>General</a>
								</td>
								<td id="tdFicha2" width="80px" align="center" style="background-repeat:no-repeat">
									<a id="aFicha2" href="#" title="Detalle"
										onclick="javascript: ClickFicha(2);"
										onmouseover="javascript: window.status='Detalle.'; return true;"
									>Detalle</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<iframe name="frmGeneral" id="frmGeneral" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe>
			<iframe name="frmDetalle" id="frmDetalle" frameborder="0" width="100%" scrolling="no" style="display:none" src="../blank.html"></iframe>
		</td>
	</tr>
	<tr>
		<td>
			<table id="tblTotal" border="0" width="100%" cellpadding="0" cellspacing="0" style="visibility:hidden">
				<tr><td colspan="3"><hr /></td></tr>
				<tr>
					<td align="right"><b>TOTAL</b></td>
					<td width="1%"><b>:</b></td>
					<td width="10%" align="right"><input name="txtTotal" id="txtTotal" class="txt-plano" style="width:100%; text-align:right" readonly="true"/></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>

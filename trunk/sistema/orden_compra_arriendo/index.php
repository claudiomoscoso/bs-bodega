<?php
include '../autentica.php';
include '../conexion.inc.php';

$stmt = mssql_query("EXEC Bodega..sp_getProveedor 4, 'E', NULL, '96574650-1'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$p_codigo = $rst["strCodigo"];
	$p_nombre = $rst["strNombre"];
	$p_direccion = $rst["strDireccion"];
	$p_comuna = $rst["strDetalle"];
	$p_telefono = $rst["strTelefono"];
	$p_fax = $rst["strFax"];
	$p_atencion = $rst["strContacto"];
	$p_fpago = $rst["intFormaPago"];
}
mssql_free_result($stmt);

$cc_codigo = '11012';
$stmt = mssql_query("SELECT strDetalle FROM General..Tablon WHERE strTabla = 'cenco' AND strCodigo = '$cc_codigo'", $cnx);
if($rst = mssql_fetch_array($stmt)) $cc_descripcion = $rst["strDetalle"];
mssql_free_result($stmt);

$soloInterna = 0;
switch($perfil){
	case 'admin.contrato':
	case 'admin.contrato.m':
	case 'c.operaciones':
	//case 'informatica':
		$soloInterna = 1;
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra de Arriendo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	var ocinterna = (document.getElementById('chkInterna').checked ? 1 : 0);
	CambiaColor(ctrl.id, false);
	switch(ctrl.id){
		case 'txtProveedor':
			if(ocinterna == 1) return false;
			document.getElementById('valido').src = 'valida.php?modulo=0&valor=' + ctrl.value + '&foco=cmbCargo';
			break;
		case 'txtCCosto':
			if(ocinterna == 0) return false;
			document.getElementById('valido').src = 'valida.php?modulo=4&bodega=12000&valor=' + ctrl.value;
			break;
		case 'txtCodigo':
			document.getElementById('valido').src = 'valida.php?modulo=1&valor=' + ctrl.value + '&foco=imgFchIni';
			break;
		case 'txtSolicitud':
			var cargo = document.getElementById('cmbCargo').value;
			document.getElementById('valido').src='valida.php?modulo=2&cargo=' + cargo + '&valor=' + ctrl.value + '&foco=txtCantidad';
			break;
	}
}

function Change(ctrl){
	switch(ctrl.id){
		case 'cmbBodega':
			var dpago = document.getElementById('cmbDPago').value;
			document.getElementById('cmbCargo').disabled = true;
			document.getElementById('valido').src = 'valida.php?modulo=3&valor=' + ctrl.value;
			document.getElementById('detalle').src = 'agrega.php?usuario=<?php echo $usuario;?>&bodega=' + ctrl.value + '&dpago=' + dpago;
			break;
		case 'cmbDPago':
			var bodega = document.getElementById('cmbBodega').value;
			if(ctrl.value == 1){ 
				document.getElementById('CellNeto').value = 'NETO'; 
				document.getElementById('CellImp').value = 'I.V.A.'; 
			}else{ 
				document.getElementById('CellNeto').value = 'A Pago'; 
				document.getElementById('CellImp').value = '(-)Impuesto 10%';
			}
			document.getElementById('detalle').src = 'agrega.php?usuario=<?php echo $usuario;?>&bodega=' + bodega + '&dpago=' + ctrl.value;
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	var ocinterna = (document.getElementById('chkInterna').checked ? 1 : 0);
	if(tecla == 13){
		CambiaColor(ctrl.id, false);
		switch(ctrl.id){
			case 'txtCCosto':
				if(ocinterna == 0) return false;
				Deshabilita(true);
				AbreDialogo('divBuscador','frmBuscador','buscar_ccosto.php?texto=' + ctrl.value);
				break;
			case 'txtProveedor':
				if(ocinterna == 1) return false;
				Deshabilita(true);
				AbreDialogo('divBuscador','frmBuscador','buscar_proveedor.php?texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=cmbCargo');
				break;
			case 'txtCodigo':
				Deshabilita(true);
				AbreDialogo('divBuscador','frmBuscador','buscar_material.php?bodega=' + document.getElementById('cmbBodega').value + '&ocinterna=' + ocinterna + '&texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=aFchIni');
				break;
			case 'txtSolicitud':
				Deshabilita(true);
				AbreDialogo('divBuscador','frmBuscador','buscar_solicitud.php?cargo=' + document.getElementById('cmbCargo').value + '&texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=txtCantidad');
				break;
			case 'txtCantidad':
				document.getElementById('txtValor').focus();
				document.getElementById('txtValor').select();
				break;
			case 'txtValor':
				if(document.getElementById('txtCodigo').value==''){
					alert('Debe ingresar el código del material.');
					document.getElementById('txtCodigo').focus();
				}else if(document.getElementById('txtDescripcion').value == ''){
					alert('El código del material ingresado no es valido.');
					document.getElementById('txtCodigo').focus();
				}else if(document.getElementById('txtFInicio').value==''){
					alert('Debe ingresar la fecha de inicio.');
					document.getElementById('txtFInicio').focus();
				}else if(document.getElementById('txtFTermino').value==''){
					alert('Debe ingresar la fecha de término.');
					document.getElementById('txtFTermino').focus();
				}else if(ComparaFechas(document.getElementById('txtFInicio').value,'>', document.getElementById('txtFTermino').value)){
					alert('La fecha de inicio debe ser menor a la de término.');
					document.getElementById('txtFInicio').focus();
				}else if(document.getElementById('txtCantidad').value==0){
					alert('Debe ingresar la cantidad.');
					document.getElementById('txtCantidad').focus();
				}else if(ctrl.value==0){
					alert('Debe ingresar el valor.');
					ctrl.focus();
				}else{
					var bodega = document.getElementById('cmbBodega').value;
					var dpago = document.getElementById('cmbDPago').value;
					var ccosto = document.getElementById('hdnCCosto').value;
					if(ocinterna == 1){
						if(ccosto == ''){
							alert('Debe ingresar un centro de costo.');
							document.getElementById('txtCCosto').focus();
						}
					}else
						ccosto = '';
					
					document.getElementById('frm').setAttribute('action', 'agrega.php?accion=A&usuario=<?php echo $usuario;?>&bodega=' + bodega + '&dpago=' + dpago + '&ccosto=' + ccosto);
					document.getElementById('frm').setAttribute('target', 'detalle');
					document.getElementById('frm').submit();
					
					LimpiaDetalle();
					document.getElementById('txtCodigo').focus();
					break;
				}
		}
	}else{
		switch(ctrl.id){
			case 'txtSolicitud':
				return ValNumeros(evento, ctrl.id, false);
				break;
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
			case 'txtValor':
				return ValNumeros(evento, ctrl.id, false);
				break;
		}
	}
}

function Load(){
	var bodega = document.getElementById('cmbBodega');
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 235);
	document.getElementById('detalle').src = 'agrega.php?usuario=<?php echo $usuario;?>&bodega=' + bodega.value + '&dpago=1';
	
	if('<?php echo $bodega;?>' == '12000'){
		for(i=0; i < bodega.length; i++){
			if(bodega.options[i].value == '12000'){
				bodega.selectedIndex = i;
				break;
			}
		}
		document.getElementById('valido').src = 'valida.php?modulo=3&valor=12000';
	}
	if(parseInt('<?php echo $soloInterna;?>') == 1){
		var interna = document.getElementById('chkInterna');
		interna.checked = true;
		interna.disabled = true;
		setInterna(interna);
	}
}

function Deshabilita(sw){
	var ocinterna = (document.getElementById('chkInterna').checked ? 1 : 0);
	
	if(parseInt(ocinterna) == 0){
		document.getElementById('cmbFPago').disabled = sw;
		document.getElementById('cmbDPago').disabled = sw;
	}
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('imgFch').style.visibility = (sw ? 'hidden' : 'visible');
	if(parseInt('<?php echo $soloInterna;?>') == 0) document.getElementById('chkInterna').disabled = sw;
	document.getElementById('txtProveedor').disabled = sw;
	document.getElementById('cmbCargo').disabled = sw;
	document.getElementById('txtCCosto').disabled = sw;
	document.getElementById('txtAtencion').readOnly = sw;
	document.getElementById('txtNota').disabled = sw;
	document.getElementById('txtCodigo').disabled = sw;
	document.getElementById('imgFchIni').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('imgFchTer').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('txtSolicitud').disabled = sw;
	document.getElementById('txtCantidad').disabled = sw;
	document.getElementById('txtValor').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
}

function Calcular(){
	var cantidad = document.getElementById('txtCantidad').value;
	var valor = document.getElementById('txtValor').value;
	document.getElementById('total').value = cantidad * valor;
}

function Graba(){
	document.getElementById('frm').setAttribute('target', 'valido');
	document.getElementById('frm').setAttribute('action', 'graba.php');
	document.getElementById('frm').submit();
}

function LimpiaDatosProveedor(){
	document.getElementById('cmbFPago').selectedIndex = 0;
	document.getElementById('hdnProveedor').value = '';
	document.getElementById('txtProveedor').value = '';
	document.getElementById('txtDireccion').value = '';
	document.getElementById('txtComuna').value = '';
	document.getElementById('txtTelefono').value = '';
	document.getElementById('txtFax').value = '';
	document.getElementById('txtAtencion').value = '';
}

function LimpiaDetalle(){
	var ocinterna = (document.getElementById('chkInterna').checked ? 1 : 0);
	document.getElementById('txtCodigo').value = '';
	document.getElementById('txtDescripcion').value = '';
	document.getElementById('txtUnidad').value = '';
	document.getElementById('txtFInicio').value = '';
	document.getElementById('txtFTermino').value = '';
	document.getElementById('txtSolicitud').value = '';
	document.getElementById('txtCantidad').value = 0;
	document.getElementById('hdnStock').value = 0;
	if(ocinterna == 0) document.getElementById('txtValor').value = 0;
}

function setInterna(ctrl){
	if(ctrl.checked){
		document.getElementById('cmbFPago').disabled = true;
		document.getElementById('cmbDPago').disabled = true;
		
		document.getElementById('txtProveedor').readOnly = true;
		document.getElementById('hdnProveedor').value = '<?php echo $p_codigo;?>';
		document.getElementById('txtProveedor').value = ' <?php echo $p_nombre;?>';
		document.getElementById('txtDireccion').value = ' <?php echo $p_direccion;?>';
		document.getElementById('txtComuna').value = ' <?php echo $p_comuna;?>';
		document.getElementById('txtTelefono').value = ' <?php echo $p_telefono;?>';
		document.getElementById('txtFax').value = ' <?php echo $p_fax;?>';
		document.getElementById('txtAtencion').value = ' <?php echo $p_atencion;?>';
		var fpago = document.getElementById('cmbFPago');
		for(i = 0; i < fpago.length; i++){
			if(parseInt(fpago.options[i].value) == parseInt('<?php echo $p_fpago;?>')){
				fpago.selectedIndex = i;
				break;
			}
		}
		
		document.getElementById('txtCCosto').readOnly = false;
		document.getElementById('hdnCCosto').value = '';
		document.getElementById('txtCCosto').value = '';
	}else{
		document.getElementById('cmbFPago').disabled = false;
		document.getElementById('cmbDPago').disabled = false;
		
		document.getElementById('txtProveedor').readOnly = false;
		document.getElementById('hdnProveedor').value = '';
		document.getElementById('txtProveedor').value = '';
		document.getElementById('txtDireccion').value = '';
		document.getElementById('txtComuna').value = '';
		document.getElementById('txtTelefono').value = '';
		document.getElementById('txtFax').value = '';
		document.getElementById('txtAtencion').value = '';
		document.getElementById('cmbFPago').selectedIndex = 0;
		
		document.getElementById('txtCCosto').readOnly = true;
		document.getElementById('hdnCCosto').value = '<?php echo $cc_codigo;?>';
		document.getElementById('txtCCosto').value = ' <?php echo $cc_descripcion;?>';
	}
}

function ValStock(valor){
	var sw=true;
	if(valor=='') valor=0;
	if(parseFloat(valor)>parseFloat(document.getElementById('hdnStock').value)){
		alert('El stock actual es menor ('+document.getElementById('hdnStock').value+')');
		sw = false;
	}
	return sw;
}
-->
</script>
<body onload="javascript: Load();">
<div id="divCalendario" style="position:absolute; width:20%; visibility:hidden; left: 765px; top: 77px;">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" 
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCalendario', 'frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra calendario.'; return true"
									title="Cierra calendario.">
										<img border="0" src="../images/close.png">
									</a>
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

<div id="divBuscador" style="position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" 
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divBuscador','frmBuscador');
										"
										onmouseover="javascript: window.status='Cierra la lista de proveeedores.'; return true"
									title="Cierra la lista de proveeedores.">
										<img border="0" src="../images/close.png">
									</a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Buscador</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmBuscador" id="frmBuscador" frameborder="0" scrolling="no" width="100%" height="235px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" target="detalle">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td >
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td width="5%" align="left" nowrap="nowrap">&nbsp;F.Pago</td>
					<td width="1%">:</td>
					<td width="94%">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="37%">
									<select name="cmbFPago" id="cmbFPago" class="sel-plano" style="width:100%">
									<?php	
									$stmt = mssql_query("select strCodigo, strDetalle from General..Tablon where strTabla='tipop' and strVigente='1' order by strDetalle");
									while($rst = mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);?>
									</select>
								</td>
								<td width="1%"></td>
								<td width="5%" align="left">&nbsp;Bodega</td>
								<td width="1%">:</td>
								<td width="37%">
									<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%"
										onchange="javascript: Change(this);"
									>
<!--								<?php
									$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
									while($rst=mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strBodega"].'" '.($rst["strBodega"] == $bodega ? 'selected' : '').'>['.$rst["strBodega"].'] '.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);
									?>
-->
										<option value="12000">Operaciones</option>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%" >&nbsp;Fecha</td>
								<td width="1%">:</td>
								<td width="10%"><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:99%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>" /></td>
								<td width="2%" align="center">
									<a href="#" title="Seleccione una fecha"
										onblur="javascript: CambiaImagen('imgFch', false);"
										onclick="javascript: 
											Deshabilita(true);
											AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFecha&foco=txtProveedor&fecha='+document.getElementById('txtFecha').value, '', '79%', '20px');
										"
										onfocus="javascript: CambiaImagen('imgFch', true);"
										onmouseover="javascript: window.status='Seleccione una fecha'; return true;"
									><img border="0" name="imgFch" id="imgFch" align="middle" src="../images/aba.gif" /></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;OC.Interna</td>
					<td align="center">:</td>
					<td >
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="1%">
									<input type="hidden" name="hdnInterna" id="hdnInterna" value="<?php echo $soloInterna;?>" />
									<input type="checkbox" name="chkInterna" id="chkInterna" 
										onclick="javascript: setInterna(this);"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="6%">&nbsp;Proveedor</td>
								<td width="1%">:</td>
								<td width="41%">
									<input name="txtProveedor" id="txtProveedor" class="txt-plano" style="width:99%" 
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onkeypress="javascript: return KeyPress(event, this);"
										onkeyup="javascript: if(this.value == '') LimpiaDatosProveedor();"
									/>
									<input type="hidden" name="hdnProveedor" id="hdnProveedor" />
								</td>
								<td width="1%"></td>
								<td width="6%" >&nbsp;Direcci&oacute;n</td>
								<td width="1%">:</td>
								<td width="42%"><input name="txtDireccion" id="txtDireccion" class="txt-plano" style="width:99%" readonly="true"/></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left">&nbsp;Comuna</td>
					<td >:</td>
					<td >
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="25%" ><input name="txtComuna" id="txtComuna" class="txt-plano" style="width:99%" readonly="true"/></td>
								<td width="1%">&nbsp;</td>
								<td width="5%" >&nbsp;Tel&eacute;fono</td>
								<td width="1%">:</td>
								<td width="15%"><input name="txtTelefono" id="txtTelefono" class="txt-plano" style="width:99%" readonly="true"/></td>
								<td width="1%"></td>
								<td width="4%" >&nbsp;Fax</td>
								<td width="1%">:</td>
								<td width="15%" ><input name="txtFax" id="txtFax" class="txt-plano" style="width:98%" readonly="true"/></td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Atenci&oacute;n</td>
								<td width="1%">:</td>
								<td width="25%"><input name="txtAtencion" id="txtAtencion" class="txt-plano" style="width:99%" readonly="true" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td >&nbsp;Cargo</td>
					<td align="center">:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="25%" >
									<select name="cmbCargo" id="cmbCargo" class="sel-plano" style="width:100%">
									<?php
									$stmt = mssql_query("EXEC General..sp_getCargos 1, '$bodega'", $cnx);
									while($rst=mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strCodigo"].'">'.$rst["strCargo"].'</option>';
									}
									mssql_free_result($stmt);
									?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;C.Costo</td>
								<td width="1%">:</td>
								<td width="10%">
									<input type="hidden" name="hdnCCosto" id="hdnCCosto" value="<?php echo $cc_codigo;?>" />
									<input name="txtCCosto" id="txtCCosto" class="txt-plano" style="width:99%" value="&nbsp;<?php echo $cc_descripcion;?>"
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onkeypress="javascript: return KeyPress(event, this);"
										onkeyup="javascript: if(this.value == '') document.getElementById('hdnCCosto').value = '';"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="9%">&nbsp;T.Documento</td>
								<td width="1%">:</td>
								<td width="10%">
									<select name="cmbDPago" id="cmbDPago" class="sel-plano" style="width:100%"
										onchange="javascript: Change(this);">
										<option value="1">Factura</option>
										<option value="2">Boleta</option>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="3%">&nbsp;Nota</td>
								<td width="1%" align="center">:</td>
								<td width="42%">
									<input name="txtNota" id="txtNota" class="txt-plano" style="width:99%"
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td >
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<th width="10%">C&oacute;digo</th>
					<th width="21%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="5%">Unidad</th>
					<th width="10%">F.Inicio</th>
					<th width="10%">F.T&eacute;rmino</th>
					<th width="10%">N&deg;Solicitud</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="10%" align="right">Valor&nbsp;</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="4%">&nbsp;</th>
				</tr>
				<tr>
					<td>
						<input name="txtCodigo" id="txtCodigo" class="txt-plano" style="width: 99%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);" 
							onkeyup="javascript: if(this.value == '') LimpiaDetalle();"
						/>
					</td>
					<td><input name="txtDescripcion" id="txtDescripcion" class="txt-plano" style="width: 99%;" readonly="true"/></td>
					<td align="left"><input name="txtUnidad" id="txtUnidad" class="txt-plano" style="width: 99%; text-align:center" readonly="true"/></td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td><input name="txtFInicio" id="txtFInicio" class="txt-plano" style="width:99%; text-align:center" readonly="true" /></td>
								<td width="2%" align="center" valign="middle">
									<a name="aFchIni" id="aFchIni" href="#" title="Seleccione una fecha" 
										onblur="javascript: CambiaImagen('imgFchIni', false);"
										onclick="javascript: 
											Deshabilita(true);
											AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFInicio&foco=aFchTer&fecha='+document.getElementById('txtFInicio').value, '', '29%', '130px');
										"
										onfocus="javascript: CambiaImagen('imgFchIni', true);"
										onmouseover="javascript: window.status='Seleccione una fecha'; return true;"
									><img border="0" name="imgFchIni" id="imgFchIni" align="middle" src="../images/aba.gif" /></a>
								</td>
							</tr>
						</table>
					</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td><input name="txtFTermino" id="txtFTermino" class="txt-plano" style="width:99%; text-align:center" readonly="true" /></td>
								<td width="2%" align="center" valign="middle">
									<a name="aFchTer" id="aFchTer" href="#" title="Seleccione una fecha"
										onblur="javascript: CambiaImagen('imgFchTer', false);"
										onclick="javascript: 
											Deshabilita(true);
											AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFTermino&foco=txtSolicitud&fecha='+document.getElementById('txtFTermino').value, '', '41%', '130px');
										"
										onfocus="javascript: CambiaImagen('imgFchTer', true);"
										onmouseover="javascript: window.status='Seleccione una fecha'; return true;"
									><img border="0" name="imgFchTer" id="imgFchTer" align="middle" src="../images/aba.gif" /></a>
								</td>
							</tr>
						</table>
					</td>
					<td>
						<input name="txtSolicitud" id="txtSolicitud" class="txt-plano" style="width:99%; text-align:center"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);" 
						/>
					</td>
					<td>
						<input type="hidden" name="hdnStock" id="hdnStock" />
						<input name="txtCantidad" id="txtCantidad" class="txt-plano" style="width: 99%; text-align:right" value="0"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value==0) this.value=0;"
						/>
					</td>
					<td>
						<input name="txtValor" id="txtValor" class="txt-plano" style="width:99%; text-align:right" value="0"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value==0) this.value=0;"
						/>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td ><iframe name="detalle" id="detalle" width="100%" frameborder="0" scrolling="yes" marginheight="0" marginwidth="0" ></iframe></td></tr>
	<tr>
		<td align="right">
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td width="0%" align="right" ><input name="CellNeto" id="CellNeto" class="txt-sborde" style="width: 98%; text-align:right; font-weight:bold; font-size:12px; background-color:#ececec" readonly="true" value="NETO"></td>
					<td width="1%">:</td>
					<td width="15%" align="left"><input name="neto" id="neto" class="txt-plano" style="width:98%; text-align:right" readonly="true" value="0" /></td>
				</tr>
				<tr>
					<td align="right" ><input name="CellImp" id="CellImp" class="txt-sborde" style="width: 98%; text-align:right; font-weight:bold; font-size:12px; background-color:#ececec" readonly="true" value="I.V.A."></td>
					<td align="center">:</td>
					<td><input name="impto" id="impto" class="txt-plano" style="width: 98%; text-align:right" readonly="true" value="0" ></td>
				</tr>
				<tr>
					<td align="right"><b>TOTAL</b></td>
					<td align="center">:</td>
					<td><input name="totalOC" id="totalOC" class="txt-plano" style="width:98%; text-align:right" readonly="true" value="0" ></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="1px"><HR /></td></tr>
	<tr>
		<td align="right">
			<table width="100%" border="0" cellpadding="1" cellspacing="2">
				<tr>
					<td width="90%">
					<?php 
						$stmt = mssql_query("SELECT nombre FROM General..Usuarios WHERE usuario='$usuario'", $cnx);
						if($rst=mssql_fetch_array($stmt)) $nombre=$rst["nombre"];
						mssql_free_result($stmt);?>
						<input name="solicitante" id="solicitante" class="txt-sborde" readonly="true" style="width:99%; background-color:#ececec" value="<?php echo $nombre;?>" />
					</td>
					<td align="right">
						<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
						<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
						<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>" />
						<input type="hidden" name="login" id="login" value="<?php echo $login;?>" />
						
						<input type="hidden" name="tdoc" id="tdoc" value="OCA" />
						<input type="hidden" name="accion" id="accion"/>
						<input type="button" name="btnGuardar" id="btnGuardar" class="boton" value="Guarda" 
							onclick="javascript:
								if(document.getElementById('neto').value==0){
									alert('Debe ingresar a lo menos una línea en el detalle.');
								}else if(document.getElementById('hdnProveedor').value==''){
									alert('Debe seleccionar el proveedor.');
									document.getElementById('txtProveedor').focus();
								}else{
									Graba();
								}
							"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<iframe name="valido" id="valido" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
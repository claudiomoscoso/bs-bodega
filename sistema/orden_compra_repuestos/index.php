<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra Manual</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	if(ctrl.value != ''){
		switch(ctrl.id){
			case 'txtProveedor':
				document.getElementById('valido').src = 'valida.php?modulo=0&valor=' + ctrl.value;
				break;
			case 'txtCodigo':
				document.getElementById('valido').src = 'valida.php?modulo=1&bodega=12000&valor=' + ctrl.value;
				break;
			case 'txtCCosto':
				document.getElementById('valido').src = 'valida.php?modulo=2&bodega=12000&valor=' + ctrl.value;
				break;
		}
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla==13){
		switch(ctrl.id){
			case 'txtProveedor':
				Deshabilita(true);
				AbreDialogo('divBuscador','frmBuscador','buscar_proveedor.php?texto=' + ctrl.value);
				break;
			case 'txtCodigo':
				Deshabilita(true);
				AbreDialogo('divBuscador','frmBuscador','buscar_material.php?bodega=<?php echo $bodega;?>&texto=' + ctrl.value);
				break;
			case 'txtCCosto':
				Deshabilita(true);
				AbreDialogo('divBuscador','frmBuscador','buscar_ccosto.php?texto=' + ctrl.value);
				break;
			case 'txtCantidad':
				document.getElementById('txtValor').focus();
				document.getElementById('txtValor').select();
				break;
			case 'txtValor':
				if(document.getElementById('txtCodigo').value == ''){
					alert('Debe ingresar el código del material.');
					document.getElementById('txtCodigo').focus();
				}else if(parseFloat(document.getElementById('txtCantidad').value) == 0){
					alert('Debe ingresar cantidades mayores a cero.');
					document.getElementById('txtCantidad').focus();
				}else if(parseInt(ctrl.value) == 0){
					alert('Debe ingresar valores mayores a cero.');
					ctrl.focus();
				}else{
					var tdocumento = document.getElementById('hdnDPago').value;
					document.getElementById('frm').setAttribute('action','agrega.php?accion=A&usuario=<?php echo $usuario;?>&bodega=12000&tdocumento=' + tdocumento);
					document.getElementById('frm').setAttribute('target', 'detalle');
					document.getElementById('frm').submit();
					
					LimpiaDetalle();
					document.getElementById('txtCodigo').focus();
					break;
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
			case 'txtValor':
				return ValNumeros(evento, ctrl.id, false);
				break;
		}
	}
}

function Load(){
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 245);
	document.getElementById('detalle').src = 'agrega.php?usuario=<?php echo $usuario;?>&bodega=12000&tdocumento=1';
	document.getElementById('cmbFPago').focus()
}

function Calcular(){
	var cantidad = document.getElementById('txtCantidad').value;
	var valor = document.getElementById('txtValor').value;
	document.getElementById('total').value = txtCantidad * valor;
}

function Deshabilita(sw){
	document.getElementById('cmbFPago').disabled = sw;
	document.getElementById('btnFch').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('txtProveedor').disabled = sw;
	document.getElementById('txtCCosto').disabled = sw;
	document.getElementById('txtAtencion').disabled = sw;
	document.getElementById('txtNota').disabled = sw;
	document.getElementById('txtCodigo').disabled = sw;
	document.getElementById('txtCantidad').disabled = sw;
	document.getElementById('txtValor').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
}

function Graba(){
	document.getElementById('frm').setAttribute('target', 'valido');
	document.getElementById('frm').setAttribute('action' ,'graba.php');
	document.getElementById('frm').submit();
}

function LimpiaDatosProveedor(){
	document.getElementById('cmbFPago').options[0].selected=true;
	document.getElementById('hdnProveedor').value = '';
	document.getElementById('txtProveedor').value = '';
	document.getElementById('txtDireccion').value = '';
	document.getElementById('txtComuna').value = '';
	document.getElementById('txtTelefono').value = '';
	document.getElementById('txtFax').value = '';
	document.getElementById('txtAtencion').value = '';
}

function LimpiaDetalle(){
	document.getElementById('txtCodigo').value = '';
	document.getElementById('txtDescripcion').value = '';
	document.getElementById('txtUnidad').value = '';
	document.getElementById('hdnStock').value = 0;
	document.getElementById('txtCantidad').value = 0;
	document.getElementById('txtValor').value = 0;
}

function ValStock(valor){
	var sw = true;
	if(valor == '') valor = 0;
	if(parseFloat(valor) > parseFloat(document.getElementById('hdnStock').value)){
		alert('El stock actual es menor (' + document.getElementById('hdnStock').value + ')');
		sw = false;
	}
	return sw;
}
-->
</script>
<body onload="javascript: Load()">
<div id="divCalendario" style="position:absolute; top:20px; left:78%; width:20%; visibility:hidden">
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
										onClick="javascript: 
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
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="1" style="border:thin" scrolling="no" width="100%" height="125px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divBuscador" style="position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra el cuadro de busqueda." 
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divBuscador','frmBuscador');
										"
										onmouseover="javascript: window.status='Cierra el cuadro de busqueda.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Cuadro de b&uacute;squeda.</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmBuscador" id="frmBuscador" frameborder="0" scrolling="no" width="100%" height="215px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" target="_self">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td width="7%" align="left">&nbsp;F.Pago</td>
					<td width="1%" align="center">:</td>
					<td >
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="35%" align="left">
									<select name="cmbFPago" id="cmbFPago" class="sel-plano" style="width:100%">
									<?php	
									$stmt = mssql_query("select strCodigo, strDetalle from General..Tablon where strTabla='tipop' and strVigente='1' order by strDetalle");
									while($rst = mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);
									?>
									</select>
								</td>
								<td width="1%"></td>
								<td width="5%" >&nbsp;Bodega</td>
								<td width="1%" align="center">:</td>
								<td width="39%">
								<?php
									$stmt = mssql_query("SELECT strDetalle FROM General..Tablon WHERE strCodigo = '12000'", $cnx);
									if($rst = mssql_fetch_array($stmt)) echo $rst["strDetalle"];
									mssql_free_result($stmt);
								?>
								</td>
								<td width="1%"></td>
								<td width="5%" align="left">&nbsp;Fecha</td>
								<td width="1%" align="center">:</td>
								<td width="10%" ><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:99%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>" /></td>
								<td width="2%" align="center">
									<a href="#" title="Seleccione una fecha"
										onblur="javascript: CambiaImagen('btnFch', false);"
										onclick="javascript: 
											Deshabilita(true);
											AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFecha&foco=txtProveedor&fecha='+document.getElementById('txtFecha').value);
										"
										onfocus="javascript: CambiaImagen('btnFch', true);"
									><img border="0" name="btnFch" id="btnFch" src="../images/aba.gif" /></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left">&nbsp;Proveedor</td>
					<td align="center">:</td>
					<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="45%">
									<input name="txtProveedor" id="txtProveedor" class="txt-plano" style="width:100%" 
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onkeypress="javascript: return KeyPress(event, this);"
										onkeyup="javascript: if(this.value=='') LimpiaDatosProveedor();"
									/>
									<input type="hidden" name="hdnProveedor" id="hdnProveedor" />
								</td>
								<td width="1%"></td>
								<td width="8%" align="left">&nbsp;Direcci&oacute;n</td>
								<td width="1%" align="center">:</td>
								<td width="45%"><input name="txtDireccion" id="txtDireccion" class="txt-plano" style="width:100%" readonly="true"/></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5%" align="left">&nbsp;Comuna</td>
					<td width="1%" align="center">:</td>
					<td >
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="25%"><input name="txtComuna" id="txtComuna" class="txt-plano" style="width:100%" readonly="true"/></td>
								<td width="1%">&nbsp;</td>
								<td width="5%" >&nbsp;Tel&eacute;fono</td>
								<td width="1%" align="center">:</td>
								<td width="15%"><input name="txtTelefono" id="txtTelefono" class="txt-plano" style="width:100%" readonly="true"/></td>
								<td width="1%">&nbsp;</td>
								<td width="4%" >&nbsp;Fax</td>
								<td width="1%" align="center">:</td>
								<td width="15%"><input name="txtFax" id="txtFax" class="txt-plano" style="width:100%" readonly="true"/></td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Atenci&oacute;n</td>
								<td width="1%" align="center">:</td>
								<td width="25%" align="left"><input name="txtAtencion" id="txtAtencion" class="txt-plano" style="width:100%" readonly="true" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left">&nbsp;Cargo</td>
					<td align="center">:</td>
					<td >
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%" >
								<?php
									$stmt = mssql_query("SELECT strDetalle FROM General..Contrato WHERE strCodigo = 'M2101'", $cnx);
									if($rst = mssql_fetch_array($stmt)) echo $rst["strDetalle"];
									mssql_free_result($stmt);
								?>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;C.Costo</td>
								<td width="1%" align="center">:</td>
								<td width="10%">
									<input type="hidden" name="hdnCCosto" id="hdnCCosto" />
									<input name="txtCCosto" id="txtCCosto" class="txt-plano" style="width:99%"  
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onkeypress="javascript: return KeyPress(event, this);"
										onkeyup="javascript: if(this.value == '') document.getElementById('hdnCCosto').value = '';"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="10%">&nbsp;T.Documento</td>
								<td width="1%" align="center">:</td>
								<td width="5%">
									<input type="hidden" name="hdnDPago" id="hdnDPago" value="1">
									Factura
								</td>
								<td width="1%">&nbsp;</td>
								<td width="3%" align="left">&nbsp;Nota</td>
								<td width="1%" align="center">:</td>
								<td width="0%" align="left" >
									<input name="txtNota" id="txtNota" class="txt-plano" style="width:100%"
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);" 
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<th width="10%">C&oacute;digo</th>
					<th width="46%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="10%" align="right">Valor&nbsp;</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="4%">&nbsp;</th>
				</tr>
				<tr>
					<td>
						<input name="txtCodigo" id="txtCodigo" class="txt-plano" style="width: 97%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);" 
							onkeyup="javascript: if(this.value=='') LimpiaDetalle();"
						/>
					</td>
					<td><input name="txtDescripcion" id="txtDescripcion" class="txt-plano" style="width: 99%;" readonly="true"/></td>
					<td><input name="txtUnidad" id="txtUnidad" class="txt-plano" style="width: 96%; text-align:center" readonly="true"/></td>
					<td>
						<input type="hidden" name="hdnStock" id="hdnStock" />
						<input name="txtCantidad" id="txtCantidad" class="txt-plano" style="width: 97%; text-align:right" value="0"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') this.value = 0;"
						/>
					</td>
					<td>
						<input name="txtValor" id="txtValor" class="txt-plano" style="width:97%; text-align:right" value="0"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') this.value = 0;"
						/>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr><td><iframe name="detalle" id="detalle" width="100%" frameborder="0" scrolling="yes" marginheight="0" marginwidth="0" ></iframe></td></tr>
	<tr>
		<td align="right">
			<table border="0" width="30%" cellpadding="1" cellspacing="1">
				<tr>
					<td width="14%" align="right"><input name="CellNeto" id="CellNeto" class="txt-sborde" style="width: 98%; text-align:right; font-weight:bold; font-size:12px; background-color:#ececec" readonly="true" value="NETO"></td>
					<td width="1%" align="center">:</td>
					<td width="15%" align="left"><input name="neto" id="neto" class="txt-plano" style="width:98%; text-align:right" readonly="true" value="0" /></td>
				</tr>
				<tr>
					<td align="right"><input name="CellImp" id="CellImp" class="txt-sborde" style="width: 98%; text-align:right; font-weight:bold; font-size:12px; background-color:#ececec" readonly="true" value="I.V.A."></td>
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
	<tr><td style="height:1px"><HR /></td></tr>
	<tr>
		<td align="right">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="left">
						<?php 
						$stmt = mssql_query("SELECT nombre FROM General..Usuarios WHERE usuario='$usuario'", $cnx);
						if($rst=mssql_fetch_array($stmt)) $nombre=$rst["nombre"];
						mssql_free_result($stmt);
						?>
						<input name="solicitante" id="solicitante" class="txt-sborde" readonly="true" style="width:99%; background-color:#ECECEC" value="&nbsp;<?php echo $nombre;?>" />
					</td>
					<td align="right">
						<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
						<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
						<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>" />
						<input type="hidden" name="login" id="login" value="<?php echo $login;?>" />						
						
						<input type="button" name="btnGuardar" id="btnGuardar" class="boton" value="Grabar" 
							onclick="javascript:
								if(document.getElementById('hdnProveedor').value==''){
									alert('Debe seleccionar el proveedor.');
									document.getElementById('txtProveedor').focus();
								}else if(document.getElementById('neto').value==0){
									alert('Debe ingresar a lo menos una línea de detalle.');
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

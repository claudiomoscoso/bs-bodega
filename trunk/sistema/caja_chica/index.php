<?php
include '../autentica.php';
include '../conexion.inc.php';

$stmt = mssql_query("SELECT dblFactor + 1 AS dblFactor FROM Impuesto WHERE id = 1", $cnx);
if($rst = mssql_fetch_array($stmt)) $factor = $rst["dblFactor"];
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Change(bodega){
	document.getElementById('cmbResponsable').disabled = true;
	var bodega = document.getElementById('cmbBodega').value;
	document.getElementById('transaccion').src = 'transaccion.php?modulo=0&bodega=' + bodega;
	document.getElementById('frmDetalle').src='detalle.php?modulo=0&usuario=<?php echo $usuario;?>&bodega=' + bodega
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('cmbResponsable').disabled = sw;
	document.getElementById('txtNota').disabled = sw;
	document.getElementById('txtCodigo').disabled = sw;
	document.getElementById('cmbDocumento').disabled = sw;
	document.getElementById('txtDocumento').disabled = sw;
	document.getElementById('txtCantidad').disabled = sw;
	document.getElementById('txtPrecio').disabled = sw;
	document.getElementById('txtTotal').disabled = sw;
	document.getElementById('txtOCompra').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
	var totfil = frmDetalle.document.getElementById('hdnTotFil').value;
	for(i=1; i<=totfil; i++){
		frmDetalle.document.getElementById('imgElim'+ i).style.visibility = sw ? 'hidden' : 'visible';
	}
}

function getBusqueda(ctrl){
	var bodega = document.getElementById('cmbBodega').value;
	switch(ctrl.id){
		case 'txtCodigo':
			if(ctrl.value!='')
				document.getElementById('transaccion').src='transaccion.php?modulo=2&bodega=' + bodega + '&texto='+ctrl.value;
			else{
				document.getElementById('txtCodigo').value = '';
				document.getElementById('txtDescripcion').value = '';
			}
			break;
		case 'txtDocumento':
			if(ctrl.value != '')
				document.getElementById('transaccion').src='transaccion.php?modulo=1&usuario=<?php echo $usuario;?>&numero='+ctrl.value;
			break;
	}
}

function getTotalLinea(){
	var cantidad = document.getElementById('txtCantidad').value;
	var precio = document.getElementById('txtPrecio').value;
	if(document.getElementById('cmbDocumento').value == 0){
		document.getElementById('txtTotal').value = Math.round((parseFloat(cantidad) * parseInt(precio)) * parseFloat('<?php echo $factor;?>'));
	}else{
		document.getElementById('txtTotal').value = parseFloat(cantidad) * parseInt(precio);
	}
}

function getPrecioUnitario(){
	var cantidad = document.getElementById('txtCantidad').value;
	var total = document.getElementById('txtTotal').value;
	if(parseFloat(cantidad) != 0){
		if(document.getElementById('cmbDocumento').value == 0){
			var total = parseInt(total) / parseFloat(cantidad);
			document.getElementById('txtPrecio').value = Math.round(parseFloat(total) / parseFloat('<?php echo $factor;?>'));
		}else{
			document.getElementById('txtPrecio').value = Math.round(parseInt(total) / parseFloat(cantidad));
		}
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		var bodega = document.getElementById('cmbBodega').value;
		switch(ctrl.id){
			case 'txtCodigo':
				Deshabilita(true);
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_material.php?texto=' + ctrl.value + '&bodega=' + bodega + '&ctrl=' + ctrl.id + '&foco=cmbDocumento');
				break;
			case 'cmbDocumento':
				document.getElementById('txtDocumento').focus();
				document.getElementById('txtDocumento').select();
				break;
			case 'txtDocumento':
				document.getElementById('txtCantidad').focus();
				document.getElementById('txtCantidad').select();
				break;
			case 'txtCantidad':
				document.getElementById('txtPrecio').focus();
				document.getElementById('txtPrecio').select();
				break;
			case 'txtPrecio':
				document.getElementById('txtTotal').focus();
				document.getElementById('txtTotal').select();
				break;
			case 'txtTotal':
				if(document.getElementById('cmbDocumento').value==0){
					document.getElementById('txtOCompra').focus();
					document.getElementById('txtOCompra').select();
					break;
				}
			case 'txtOCompra':
				var rut=document.getElementById('cmbResponsable').value;
				var codigo=document.getElementById('txtCodigo').value;
				var descmat=document.getElementById('txtDescripcion.value');
				var tipodoc=document.getElementById('cmbDocumento').value;
				var numdoc=document.getElementById('txtDocumento').value;
				var numoc=document.getElementById('txtOCompra').value;
				var cantidad=document.getElementById('txtCantidad').value;
				var precio=document.getElementById('txtPrecio').value; 
				var total = document.getElementById('txtTotal').value;
				if(rut == '')
					alert('No hay responsable seleccionado.');
				else if(descmat == '')
					alert('Debe ingresar un material.');
				else if(numdoc == '')
					alert('Debe ingresar el número de documento.');
				else if(parseFloat(cantidad) <= 0)
					alert('Debe ingresar la cantidad de material');
				else if(parseInt(precio) <= 0)
					alert('Debe ingresar el precio neto del material');
				else if(parseFloat(total) <= 0)
					alert('Debe ingresar el total del material');
				else
					document.getElementById('frmDetalle').src='detalle.php?modulo=1&usuario=<?php echo $usuario;?>&bodega=' + bodega + '&codigo=' + codigo + '&documento=' + tipodoc + '&numdoc=' + numdoc + '&numoc=' + numoc +
					'&cantidad=' + cantidad + '&precio=' + precio + '&rut=' + rut;
				break;
		}
	}else{
		switch(ctrl.id){
			case 'txtCantidad':
				switch(document.getElementById('hdnUnidad').value){
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
			case 'txtPrecio':
				return ValNumeros(evento, ctrl.id, true)
				break;
			case 'txtDocumento':
			case 'txtOCompra':
			case 'txtTotal':
				return ValNumeros(evento, ctrl.id, false)
		}
	}
}

function Load(){
	var bodega = document.getElementById('cmbBodega').value;
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 158);
	document.getElementById('frmDetalle').src = 'detalle.php?modulo=0&usuario=<?php echo $usuario;?>&bodega=' + bodega
}

function setBloqueaCampos(valor){
	document.getElementById('txtDocumento').value='';
	getTotalLinea()
	if(valor == 0){
		document.getElementById('txtOCompra').readOnly=false;
	}else{
		document.getElementById('txtOCompra').value='';
		document.getElementById('txtOCompra').readOnly=true;
	}
}

function setGuardar(){
	if(frmDetalle.document.getElementById('hdnTotFil').value == '')
		alert('Debe ingresar al menos un ítem en el detalle.');
	else{
		document.getElementById('btnGuardar').disabled = true;
		document.getElementById('frm').setAttribute('target', 'transaccion');
		document.getElementById('frm').setAttribute('action', 'graba.php');
		document.getElementById('frm').submit();
	}
}

function setLimpiaDetalle(){
	document.getElementById('txtCodigo').value = '';
	document.getElementById('txtDescripcion').value = '';
	document.getElementById('cmbDocumento').selectedIndex = 0;
	document.getElementById('txtDocumento').value = '';
	document.getElementById('txtOCompra').value = '';
	document.getElementById('txtOCompra').readOnly = false;
	document.getElementById('txtCantidad').value = 0;
	document.getElementById('txtPrecio').value = 0;
	document.getElementById('txtTotal').value = 0;
}
-->
</script>
<body onload="javascript: Load()">
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

<form name="frm" id="frm" method="post">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="4%">&nbsp;Fecha</td>
					<td width="1%">:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%"><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="6%">&nbsp;Bodega</td>
								<td width="1%">:</td>
								<td width="35%">
									<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%"
										onchange="javascript: Change(this.value);"
									>
									<?php
									$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
									while($rst = mssql_fetch_array($stmt)){
										if($bodsel == '') $bodsel = trim($rst["strBodega"]);
										echo '<option value="'.trim($rst["strBodega"]).'">'.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);
									?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="10%">&nbsp;Responsable</td>
								<td width="1%">:</td>
								<td width="35%">
									<select name="cmbResponsable" id="cmbResponsable" class="sel-plano" style="width:100%">
									<?php
									$stmt = mssql_query("EXEC General..sp_getEncargadoFondoFijo 0, '$bodsel'", $cnx);
									while($rst = mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strUsuario"].'">'.$rst["strNombre"].'</option>';
									}
									mssql_free_result($stmt);?>
									</select>
								</td>							
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td >&nbsp;Nota</td>
					<td >:</td>
					<td >
						<input name="txtNota" id="txtNota" class="txt-plano" style="width:100%" maxlength="1000" 
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
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="23%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%" align="left">&nbsp;Documento</th>
					<th width="10%" >N&deg; Doc.</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="10%" align="right">Precio Neto&nbsp;</th>
					<th width="10%" align="right">Total <font color="#FF0000">(*)</font>&nbsp;</th>
					<th width="10%" >N&deg; O.Compra</th>
					<th width="7%">&nbsp;</th>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input name="txtCodigo" id="txtCodigo" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: 
								CambiaColor(this.id, false);
								getBusqueda(this);
							"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td><input name="txtDescripcion" id="txtDescripcion" class="txt-plano" style="width:99%" readonly="true" /></td>
					<td>
						<select name="cmbDocumento" id="cmbDocumento" class="sel-plano" style="width:99%"
							onchange="javascript: setBloqueaCampos(this.value);"
							onkeypress="javascript: return KeyPress(event, this);"
						>
							<option value="0">Factura</option>
							<option value="1">Boleta</option>
							<option value="3">B.Honorario</option>
							<option value="2">Vale por</option>
						</select>
					</td>
					<td>
						<input name="txtDocumento" id="txtDocumento" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: 
								CambiaColor(this.id, false);
								getBusqueda(this);
							"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td>
						<input name="txtCantidad" id="txtCantidad" class="txt-plano" style="width:99%; text-align:right" value="0"
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: 
								if(this.value == '') this.value = 0;
								getTotalLinea();
							"
						/>
					</td>
					<td>
						<input name="txtPrecio" id="txtPrecio" class="txt-plano" style="width:99%; text-align:right" value="0" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: 
								if(this.value == '') this.value = 0;
								getTotalLinea();
							"
						/>
					</td>
					<td>
						<input name="txtTotal" id="txtTotal" class="txt-plano" style="width:99%; text-align:right" value="0" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript:
								if(this.value == '') this.value = 0;
								getPrecioUnitario();
							"
						/>
					</td>
					<td>
						<input name="txtOCompra" id="txtOCompra" class="txt-plano" style="width:99%; text-align:center" 
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
	<tr><td><iframe name="frmDetalle" id="frmDetalle" frameborder="0" width="100%" scrolling="yes" src="detalle.php?modulo=0&usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>"></iframe></td></tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="left" style="color:#FF0000"><b>&nbsp;(*) El Total incluye I.V.A. si corresponde a una factura.</b></td>
					<td align="right">Total</td>
					<td width="1%">:</td>
					<td width="10%"><input name="txtTotGnral" id="txtTotGnral" class="txt-plano" style="width:100%; text-align:right" readonly="true" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
			<input type="hidden" name="hdnUnidad" id="hdnUnidad" />
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: setGuardar();"
			/>
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" frameborder="0" width="80Opx" height="100px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
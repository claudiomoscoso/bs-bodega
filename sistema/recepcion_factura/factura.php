<?php
include '../conexion.inc.php';
include '../globalvar.inc.php';

$usuario = $_GET["usuario"] != '' ? $_GET["usuario"] : $_POST["usuario"];
$perfil = $_GET["perfil"] != '' ? $_GET["perfil"] : $_POST["perfil"];
$accion = $_POST["accion"] != '' ? $_POST["accion"] : $_GET["accion"];
$numero = $_GET["numero"] != '' ? $_GET["numero"] : $_POST["numero"];
$fecha = $_POST["txtFecha"];
$ordenes = $_POST["hdnOrdenes"];
if($ordenes != '') $tordenes = count(explode(',', $ordenes));
$proveedor = $_POST["hdnProveedor"];
$tipodoc = $_POST["cmbTipoDoc"];
$numdoc = $_POST["txtNumDoc"];
$monto = $_POST["txtMonto"];
$estado = $_POST["cmbEstado"];
$archivo = $_POST["hdnArchivo"];
$sololectura = 0;
if($accion == 'G'){
	if($numero != '')
		$stmt = mssql_query("EXEC Bodega..sp_setFacturas 2, '".formato_fecha($fecha, false, true)."', NULL, NULL, NULL, NULL, NULL, NULL, NULL, $numero, $estado, '$archivo'", $cnx);
	else
		$stmt = mssql_query("EXEC Bodega..sp_setFacturas 0, '".formato_fecha($fecha, false, true)."', '$ordenes', $tordenes, $tipodoc, $numdoc, $monto, '$usuario', '$proveedor', NULL, $estado, '$archivo'", $cnx);
	if($rst = mssql_fetch_array($stmt)){ 
		$error = $rst["dblError"];
		$recepciones = $rst["strRecepciones"];
		$diferencia = number_format($rst["dblDiferencia"], 2, ',', '.');
	}
	mssql_free_result($stmt);
}else{
	if($numero != ''){
		$sololectura = 1;
		$ano = substr($_GET["fecha"], 6);
		$stmt = mssql_query("EXEC Bodega..sp_getFacturas 1, $numero, $ano", $cnx);
		if($rst = mssql_fetch_array($stmt)){
			$fecha = $rst["dtmFecha"];
			$cargo = $rst["strCargo"];
			$numoc = $rst["dblUltima"];
			$intoc = $rst["dblNumOC"];
			$proveedor = $rst["strProveedor"];
			$nombprov = $rst["strNombre"];
			$tipodoc = $rst["dblTipoDoc"];
			$numdoc = $rst["dblNumDoc"];
			$monto = $rst["dblMonto"];
			$estado = $rst["dblEstado"];
			$archivo = $rst["strArchivo"];
		}
		mssql_free_result($stmt);
	}
 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Recepci&oacute;n de Facturas</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css"  />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	switch(ctrl.id){
		case 'txtNumOC':
			if(ctrl.value != '')
				document.getElementById('busqueda').src = 'busqueda_rapida.php?modulo=OC&cargo=' + document.getElementById('cmbCargo').value + '&numoc='+ctrl.value;
			break;
		case 'txtNumDoc':
			if(ctrl.value != '')
				document.getElementById('busqueda').src = 'busqueda_rapida.php?modulo=DOC&proveedor=' + document.getElementById('hdnProveedor').value + '&tipodoc=' + document.getElementById('cmbTipoDoc').value + '&numdoc=' + ctrl.value;
			break;
		case 'txtMonto':
			var ordenes = document.getElementById('lstOCompras');
			var sugerido = frmDetalleOC.document.getElementById('hdnMonto');
			var monto = document.getElementById('txtMonto');
			if(ordenes.length > 1){
				if(parseInt(monto.value) < parseInt(sugerido.value))
					alert('El monto ingresado es menor al sugerido ($' + sugerido.value + ')');
			}else{
				if(parseInt(monto.value) > parseInt(sugerido.value)){
					alert('El monto ingresado no puede ser mayor a $' + sugerido.value);
					monto.value = sugerido.value;
				}
			}
			break;
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	var foco = '';
	if(tecla == 13){
		switch(ctrl.id){
			case 'cmbCargo':
				document.getElementById('txtNumOC').focus();
				break;
			case 'txtNumOC':
				document.getElementById('lstOCompras').focus();
				ctrl.value = '';
				ctrl.focus();
				ctrl.select();
				break;
			case 'cmbTipoDoc':
				document.getElementById('txtNumDoc').focus();
				break;
			case 'txtNumDoc':
				document.getElementById('txtMonto').focus();
				break;
			case 'txtMonto':
				document.getElementById('cmbEstado').focus();
				break;
			case 'cmbEstado':
				document.getElementById('btnGuardar').focus();
				break;
		}
	}else if(tecla == 46){
		if(ctrl.id == 'lstOCompras'){
			if(confirm('¿Está seguro que desea quitar esta orden de compra?')){
				ctrl.remove(ctrl.selectedIndex);
				var ordenes = '';
				for(i = 0; i < ctrl.length; i++) ordenes += ctrl.options[i].value+',';
				ordenes = ordenes.substr(0, ordenes.length - 1);
				document.getElementById('frmDetalleOC').src = 'detalle_orden_compra.php?ordenes=' + ordenes;
			}
		}
	}else{
		switch(ctrl.id){
			case 'txtNumOC':
			case 'txtNumDoc':
			case 'txtMonto':
				return ValNumeros(evento, ctrl.id, false)
				break;
		}
	}
}

function Load(){
	Deshabilita(false)
	if('<?php echo $accion;?>' == 'G'){
		if(parseInt('<?php echo $error;?>') == 0){
			if('<?php echo trim($recepciones);?>' != '')
				alert("Se han guardado las siguientes recepciones: <?php echo $recepciones;?>.");
			parent.document.getElementById('frmListado').src = parent.document.getElementById('frmListado').src;
			parent.Deshabilita(false);
			parent.CierraDialogo('divFactura', 'frmFactura');
		}else if(parseInt('<?php echo $error;?>') == 1)
			alert('El monto ingresado no puede ser menor al sugerido');
		else if(parseInt('<?php echo $error;?>') == 2)
			alert('El monto ingresado no puede ser mayor al sugerido');
		else if(parseInt('<?php echo $error;?>') == 3)
			alert('No ha sido posible obtener el número interno de la recepción');
	}else{
		document.getElementById('cmbCargo').focus();
		if('<?php echo $intoc;?>' != ''){
			SoloLectura();
			var ocompra = document.getElementById('lstOCompras');
			ocompra.options[ocompra.length] = new Option('<?php echo $numoc.' ['.$intoc.']';?>', '<?php echo $intoc;?>');
			document.getElementById('frmDetalleOC').src = 'detalle_orden_compra.php?ordenes=<?php echo $intoc;?>';
		}
	}
}

function SoloLectura(){
	document.getElementById('cmbCargo').disabled = true;
	document.getElementById('txtNumOC').disabled = true;
	document.getElementById('lstOCompras').disabled = true;
	document.getElementById('cmbTipoDoc').disabled = true;
	document.getElementById('txtNumDoc').disabled = true;
	document.getElementById('txtMonto').disabled = true;
	
	if('<?php echo $perfil;?>' != 'recepcion' && '<?php echo $perfil;?>' != 'informatica') document.getElementById('cmbEstado').disabled = true;
}

function Escanear(){
	var proveedor = document.getElementById('hdnProveedor').value;
	var documento = document.getElementById('txtNumDoc').value;
	if(proveedor == '')
		alert('Debe ingresar una orden de compra.');
	else if(documento == '')
		alert('Debe ingresar el número del documento antes de scannear.');
	else{
		Deshabilita(true);
		AbreDialogo('divScanner', 'frmScanner', 'captura_archivo.php?estado=0&proveedor=' + proveedor + '&documento=' + documento);
	}
}

function Guardar(){
	if(document.getElementById('lstOCompras').length == 0)
		alert('Debe ingresar el número de orden de compra.');
	else if(document.getElementById('hdnProveedor').value == '')
		alert('El proveedor de la Orden de Compra no fue encontrado.');
	else if(document.getElementById('txtNumDoc').value == '')
		alert('Debe ingresar el número de documento.');
	else if(document.getElementById('txtMonto').value == '')
		alert('Debe ingresar el monto de documento.');
	else{
		document.getElementById('btnGuardar').disabled = true;
		var ordenes = '';
		for(i = 0; i < document.getElementById('lstOCompras').length; i++) ordenes += document.getElementById('lstOCompras').options[i].value + ',';
		if(document.getElementById('lstOCompras').length > 1)
			document.getElementById('hdnOrdenes').value = ordenes.substr(0, ordenes.length - 1);
		else
			document.getElementById('hdnOrdenes').value = ordenes;
		document.getElementById('frm').submit();
	}
}

function Deshabilita(sw){
	if(parseInt('<?php echo $sololectura;?>') == 0){
		document.getElementById('cmbCargo').disabled = sw;
		document.getElementById('txtNumOC').disabled = sw;
		document.getElementById('lstOCompras').disabled = sw;
		document.getElementById('cmbTipoDoc').disabled = sw;
		document.getElementById('txtNumDoc').disabled = sw;
		document.getElementById('txtMonto').disabled = sw;
		document.getElementById('btnScanner').disabled = sw;
		document.getElementById('btnGuardar').disabled = sw;
		document.getElementById('btnCerrar').disabled = sw;
	}
	document.getElementById('cmbEstado').disabled = sw;
}
-->
</script>
<body onload="javascript: Load();">
<div id="divScanner" style="position:absolute; width:55%;visibility:hidden;left:23%;top:5px;">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr><td align="center" style="color:#000000; font-size:12px; font-weight:bold">&nbsp;Vinculado archivo...</td></tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmScanner" id="frmScanner" frameborder="1" style="border:thin" scrolling="no" width="100%" height="150px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td width="8%">&nbsp;Fecha</td>
		<td width="1%">:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="15%"><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:98%; text-align:center" readonly="true" value="<?php echo $fecha!='' ? $fecha : date('d/m/Y');?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Cargo</td>
					<td width="1%">:</td>
					<td width="56%">
						<select name="cmbCargo" id="cmbCargo" class="sel-plano" style="width:100%"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getCargos 3", $cnx);			
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strCodigo"]).'" '.($cargo == $rst["strCodigo"] ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%" nowrap="nowrap">&nbsp;N&deg; O.Compra</td>
					<td width="1%">:</td>
					<td width="15%" align="left">
						<input name="txtNumOC" id="txtNumOC" class="txt-plano" style="width:98%; text-align:center" value="<?php echo $numoc;?>" 
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
		<td>&nbsp;Proveedor</td>
		<td>:</td>
		<td><input name="txtProveedor" id="txtProveedor" class="txt-plano" style="width:99%" readonly="true" value="<?php echo $nombprov;?>" /></td>
	</tr>
	<tr><td colspan="5" style="height:10px"></td></tr>
	<tr>
		<td colspan="5">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="10%">O.Compras</th>
					<th width="1%">&nbsp;</th>
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="44%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Cantidad</th>
					<th width="10%">Valor</th>
					<th width="10%">Total</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="5">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="11%">
						<select name="lstOCompras" id="lstOCompras" class="sel-plano" style="width:100%" size="5"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						</select>
					</td>
					<td width="89%"><iframe name="frmDetalleOC" id="frmDetalleOC" frameborder="0" width="100%" height="75px" scrolling="yes" src="../blank.html"></iframe></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="5" style="height:3px"></td></tr>
	<tr>
		<td >&nbsp;Documento</td>
		<td>:</td>
	  <td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="15%">
						<select name="cmbTipoDoc" id="cmbTipoDoc" class="sel-plano" style="width:100%"
							onkeypress="javascript: return KeyPress(event, this);"
						>
							<option value="0" <?php echo $tipodoc==0 ? 'selected' : '';?>>Factura</option>
							<option value="1" <?php echo $tipodoc==1 ? 'selected' : '';?>>Boleta</option>
							<option value="2" <?php echo $tipodoc==2 ? 'selected' : '';?>>Letra</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="2%">&nbsp;N&deg;</td>
					<td width="1%">:</td>
					<td width="15%" >
						<input name="txtNumDoc" id="txtNumDoc" class="txt-plano" style="width:100%; text-align:center" value="<?php echo $numdoc;?>" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this)"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Monto</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtMonto" id="txtMonto" class="txt-plano" style="width:98%; text-align:right" value="<?php echo $monto;?>" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this)"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Estado</td>
					<td width="1%">:</td>
					<td width="37%">
						<select name="cmbEstado" id="cmbEstado" class="sel-plano" style="width:100%;"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						<?php 
						$stmt = mssql_query("EXEC General..sp_getEstados 1", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strCodigo"]).'" '.(trim($rst["strCodigo"]) == $estado ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="3" valign="bottom" style="height:5px"><hr /></td></tr>
	<tr>
		<td colspan="5" align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>"/>
			<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>"/>
			<input type="hidden" name="numero" id="numero" value="<?php echo $numero;?>"/>
			<input type="hidden" name="hdnOrdenes" id="hdnOrdenes" value="<?php echo $ordenes;?>" />
			<input type="hidden" name="hdnProveedor" id="hdnProveedor" value="<?php echo $proveedor;?>"/>
			<input type="hidden" name="hdnArchivo" id="hdnArchivo" value="<?php echo $archivo;?>" />
			<input type="hidden" name="accion" id="accion" value="G"/>
			
			<input type="button" name="btnScanner" id="btnScanner" class="boton" style="width:90px" value="Scanner..." 
				onclick="javascript: Escanear();"
			/>
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: Guardar();"
			/>
			<input type="button" name="btnCerrar" id="btnCerrar" class="boton" style="width:90px" value="Cerrar" 
				onclick="javascript:
					parent.CierraDialogo('divFactura', 'frmFactura')
					parent.Deshabilita(false);
				"
			/>
		</td>
	</tr>
</table>
</form>
<iframe name="busqueda" id="busqueda" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>

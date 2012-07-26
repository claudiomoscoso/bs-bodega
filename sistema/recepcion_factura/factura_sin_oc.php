<?php
include '../conexion.inc.php';
include '../globalvar.inc.php';

$usuario = $_GET["usuario"] != '' ? $_GET["usuario"] : $_POST["usuario"];
$perfil = $_GET["perfil"] != '' ? $_GET["perfil"] : $_POST["perfil"];
$accion = $_POST["accion"] != '' ? $_POST["accion"] : $_GET["accion"];
$numero = $_GET["numero"] != '' ? $_GET["numero"] : $_POST["numero"];
$fecha = $_POST["txtFecha"];
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
		$stmt = mssql_query("EXEC Bodega..sp_setFacturas 1, '".formato_fecha($fecha, false, true)."', NULL, 0, $tipodoc, $numdoc, $monto, '$usuario', '$proveedor', NULL, $estado, '$archivo'", $cnx);
	if($rst = mssql_fetch_array($stmt)){ 
		$error = $rst["dblError"];
		$recepciones = $rst["strRecepciones"];
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
			$proveedor = $rst["strProveedor"];
			$nombprov = $rst["NombProv"];
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
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	if(ctrl.value == '') return 0;
	switch(ctrl.id){
		case 'txtProveedor':
			document.getElementById('busqueda').src = 'busqueda_rapida.php?modulo=PRV&prov=' + ctrl.value;
			break;
		case 'txtNumDoc':
			document.getElementById('busqueda').src = 'busqueda_rapida.php?modulo=DOC&proveedor=' + document.getElementById('hdnProveedor').value + '&tipodoc=' + document.getElementById('cmbTipoDoc').value + '&numdoc=' + ctrl.value;
			break;
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		if(ctrl == 'txtProveedor'){
			Deshabilita(true);
			AbreDialogo('divBuscador', 'frmBuscador', 'buscar_proveedor.php?texto=' + document.getElementById('txtProveedor').value);
		}
	}
}

function Load(){
	Deshabilita(false)
	if('<?php echo $accion;?>' == 'G'){
		if(parseInt('<?php echo $error;?>') == 0){
			if('<?php echo trim($recepciones);?>' != '')
				alert("La recepción se ha guardado con el número: <?php echo $recepciones;?>.");
			parent.document.getElementById('frmListado').src = parent.document.getElementById('frmListado').src;
			parent.Deshabilita(false);
			parent.CierraDialogo('divFactura', 'frmFactura');
		}else if(parseInt('<?php echo $error;?>') == 3)
			alert('No ha sido posible obtener el número interno de la recepción');
	}else{
		document.getElementById('txtProveedor').focus();
		if('<?php echo $numero;?>' != '') SoloLectura();
	}
}

function Deshabilita(sw){
	if(parseInt('<?php echo $sololectura;?>') == 0){
		document.getElementById('txtProveedor').disabled = sw;
		document.getElementById('cmbTipoDoc').disabled = sw;
		document.getElementById('txtNumDoc').disabled = sw;
		document.getElementById('txtMonto').disabled = sw;
		document.getElementById('btnScanner').disabled = sw;
		document.getElementById('btnGuardar').disabled = sw;
		document.getElementById('btnCerrar').disabled = sw;
	}
	document.getElementById('cmbEstado').disabled = sw;
}

function Escanear(){
	var proveedor = document.getElementById('hdnProveedor').value;
	var documento = document.getElementById('txtNumDoc').value;
	if(proveedor == '')
		alert('Debe ingresar un proveedor.');
	else if(documento == '')
		alert('Debe ingresar el número del documento antes de scannear.');
	else{
		Deshabilita(true);
		AbreDialogo('divScanner', 'frmScanner', 'captura_archivo.php?estado=0&proveedor=' + proveedor + '&documento=' + documento);
	}
}

function Guardar(){
	if(document.getElementById('hdnProveedor').value == '')
		alert('Debe ingresar el proveedor.');
	else if(document.getElementById('txtNumDoc').value == '')
		alert('Debe ingresar el número de documento.');
	else if(document.getElementById('txtMonto').value == '')
		alert('Debe ingresar el monto de documento.');
	else{
		document.getElementById('btnGuardar').disabled = true;
		document.getElementById('frm').submit();
	}
}

function SoloLectura(){
	document.getElementById('txtProveedor').disabled = true;
	document.getElementById('cmbTipoDoc').disabled = true;
	document.getElementById('txtNumDoc').disabled = true;
	document.getElementById('txtMonto').disabled = true;
	if('<?php echo $perfil;?>' != 'recepcion' && '<?php echo $perfil;?>' != 'informatica') document.getElementById('cmbEstado').disabled = true;
}
-->
</script>
<body onLoad="javascript: Load();">
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

<div id="divBuscador" style="z-index: 1; position:absolute; top:0px; left:13%; width:75%; visibility:hidden">
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
										CierraDialogo('divBuscador', 'frmBuscador');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Proveedores</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmBuscador" id="frmBuscador" frameborder="0" scrolling="no" width="100%" height="155px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
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
					<td width="8%">&nbsp;Proveedor</td>
					<td width="1%">:</td>
					<td width="75%">
						<input name="txtProveedor" id="txtProveedor" class="txt-plano" style="width:99%" value="<?php echo $nombprov;?>" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this.id);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td >&nbsp;Documento</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="15%">
						<select name="cmbTipoDoc" id="cmbTipoDoc" class="sel-plano" style="width:100%">
							<option value="0" <?php echo $tipodoc==0 ? 'selected' : '';?>>Factura</option>
							<option value="1" <?php echo $tipodoc==1 ? 'selected' : '';?>>Boleta</option>
							<option value="2" <?php echo $tipodoc==2 ? 'selected' : '';?>>Letra</option>
							<option value="3" <?php echo $tipodoc==3 ? 'selected' : '';?>>Nota de Cr&eacute;dito</option>
							<option value="4" <?php echo $tipodoc==4 ? 'selected' : '';?>>Nota de D&eacute;bito</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="2%">&nbsp;N&deg;</td>
					<td width="1%">:</td>
					<td width="15%" >
						<input name="txtNumDoc" id="txtNumDoc" class="txt-plano" style="width:100%; text-align:center" value="<?php echo $numdoc;?>" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false)"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Monto</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtMonto" id="txtMonto" class="txt-plano" style="width:99%; text-align:center" value="<?php echo $monto;?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false)"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Estado</td>
					<td width="1%">:</td>
					<td width="37%">
						<select name="cmbEstado" id="cmbEstado" class="sel-plano" style="width:100%;">
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
	<tr><td colspan="5" valign="bottom" style="height:150px"><hr /></td></tr>
	<tr>
		<td colspan="5" align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>"/>
			<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>"/>
			<input type="hidden" name="numero" id="numero" value="<?php echo $numero;?>"/>
			<input type="hidden" name="hdnProveedor" id="hdnProveedor" value="<?php echo $proveedor;?>" />
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
<iframe name="busqueda" id="busqueda" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
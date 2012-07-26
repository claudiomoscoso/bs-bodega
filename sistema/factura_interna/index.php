<?php
include '../autentica.php';
include '../conexion.inc.php';

$accion = $_GET["accion"];
if($accion == 'G'){
	$fecha = $_POST["txtFecha"];
	$proveedor = $_POST["hdnProveedor"];
	$cargo = $_POST["hdnCargo"];
	
	$stmt = mssql_query("EXEC Bodega..sp_setFacturaInterna 0, '$usuario', '".formato_fecha($fecha, false, true)."', '$proveedor', '$cargo'", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$error = $rst["dblError"];
		$numero = $rst["dblNumero"];
	}
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Factura Interna</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl, ind){
	CambiaColor(ctrl.id, false);
	var numero = document.getElementById('hdnNumero' + ind).value;
	var codigo = document.getElementById('txtCodigo' + ind).value;
	if(ctrl.id.substr(0, 11) == 'txtCantidad'){
		var cantidad = document.getElementById('hdnCantidad' + ind).value;
		if(parseFloat(ctrl.value) != parseFloat(cantidad)){
			var precio = document.getElementById('txtPrecio' + ind).value;
			var detalle = document.getElementById('divDetalle');
			var ajax = new XMLHttpRequest();
			ajax.open('GET', 'transaccion.php?modulo=3&usuario=<?php echo $usuario;?>&numero=' + numero + '&codigo=' + codigo + '&precio=' + precio + '&cantidad=' + ctrl.value, true);
			ajax.onreadystatechange = function (){
				if(ajax.readyState == 4) document.getElementById('hdnCantidad' + ind).value = ctrl.value;
			}
			ajax.send(null);
		}
	}else if(ctrl.id.substr(0, 9) == 'txtPrecio'){	
		var precio = document.getElementById('hdnPrecio' + ind).value;
		if(parseFloat(ctrl.value) != parseFloat(precio)){
			var cantidad = document.getElementById('txtCantidad' + ind).value;
			var detalle = document.getElementById('divDetalle');
			var ajax = new XMLHttpRequest();
			ajax.open('GET', 'transaccion.php?modulo=3&usuario=<?php echo $usuario;?>&numero=' + numero + '&codigo=' + codigo + '&precio=' + ctrl.value + '&cantidad=' + cantidad, true);
			ajax.onreadystatechange = function (){
				if(ajax.readyState == 4) document.getElementById('hdnPrecio' + ind).value = ctrl.value;
			}
			ajax.send(null);
		}
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl.id){
			case 'txtCargo':
				Deshabilita(true);
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_cargos.php?texto=' + ctrl.value);
				break;
		}
	}else{
		if(ctrl.id.substr(0, 11) == 'txtCantidad')
			return ValNumeros(evento, ctrl.id, true);
		else if(ctrl.id.substr(0, 9) == 'txtPrecio')
			return ValNumeros(evento, ctrl.id, false);
	}
}

function KeyUp(ctrl, ind){
	if(ctrl.value == '') ctrl.value = 0;
	if(ctrl.id.substr(0, 11) == 'txtCantidad')
		document.getElementById('txtTotal' + ind).value = parseInt(parseFloat(ctrl.value) * parseInt(document.getElementById('txtPrecio' + ind).value));
	else if(ctrl.id.substr(0, 9) == 'txtPrecio')
		document.getElementById('txtTotal' + ind).value = parseInt(parseInt(ctrl.value) * parseFloat(document.getElementById('txtCantidad' + ind).value));

	var totfil = document.getElementById('totfil').value;
	var total = 0;
	for(i = 1; i <= totfil; i++) total += parseInt(document.getElementById('txtTotal' + i).value);
	document.getElementById('txtTFactura').value = total;
}

function Load(){
	document.getElementById('divDetalle').style.height = (window.innerHeight - 112) + 'px';
	if('<?php echo $accion;?>' == 'G'){
		if(parseInt('<?php echo $error;?>') == 1)
			alert('No ha sido posible guardar la factura interna.');
	}
}

function Deshabilita(sw){
	document.getElementById('imgFecha').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('txtCargo').disabled = sw;
	document.getElementById('btnCargar').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
}

function Guardar(){
	if(document.getElementById('hdnCargo').value == '')
		alert('Debe ingresar un cargo');
	else if(!document.getElementById('totfil'))
		alert('Debe haber a lo menos una línea en el detalle.');
	else
		document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load()">
<div id="divCalendario" style="z-index:10; position:absolute; top:20px; left:5%; width:20%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra calendario."
										onclick="javascript: 
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
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="1" style="border:thin" scrolling="no" width="100%" height="125px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divBuscador" style="z-index:10; position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra cuadro de busqueda."
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divBuscador', 'frmBuscador');
										"
										onmouseover="javascript: window.status='Cierra calendario.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px; font-weight:bold">Buscador</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmBuscador" id="frmBuscador" frameborder="1" style="border:thin" scrolling="no" width="100%" height="215px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'].$parametros."&accion=G";?>">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="3%">&nbsp;Fecha</td>
					<td width="1%" align="center">:</td>
					<td width="10%"><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:99%;text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>" /></td>
					<td width="2%" align="center">
						<a href="#" title="Abre calendario."
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFecha&foco=txtcargo&fecha='+document.getElementById('txtFecha').value);
							"
							onblur="javascript: CambiaImagen('imgFecha', false);"
							onfocus="javascript: CambiaImagen('imgFecha', true);"
							onmouseover="javascript: window.status='Abre calendario'; return true;"
						><img id="imgFecha" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%">&nbsp;Proveedor</td>
					<td width="1%" align="center">:</td>
					<td width="30%">
						<input type="hidden" id="hdnProveedor" name="hdnProveedor" value="00127" />
						<?php
						$stmt = mssql_query("SELECT strNombre FROM Bodega..Proveedor WHERE strCodigo = '00127'", $cnx);
						if($rst = mssql_fetch_array($stmt)) echo $rst["strNombre"];
						mssql_free_result($stmt);
						?>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="4%">&nbsp;Cargo</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<input type="hidden" name="hdnCargo" id="hdnCargo" />
						<input name="txtCargo" id="txtCargo" class="txt-plano" style="width:99%" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') document.getElementById('hdnCargo').value = ''"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="13%">
						<input type="button" name="btnCargar" id="btnCargar" class="boton" style="width:90px" value="Carga detalle..." 
							onclick="javascript:
								var cargo = document.getElementById('hdnCargo').value;
								if(cargo != ''){
									Deshabilita(true);
									AbreDialogo('divBuscador', 'frmBuscador', 'buscar_detalle_orden.php?usuario=<?php echo $usuario;?>&cargo=' + cargo);
								}else
									alert('Debe ingresar un cargo.')
							"
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
					<th width="25%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">F.Inicio</th>
					<th width="10%">F.T&eacute;rmino</th>
					<th width="10%">C.Costo</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="10%" align="right">Valor&nbsp;</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="2%" >&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><div id="divDetalle" style="height:100px; overflow:scroll; position:relative; width:100%"></div></td></tr>
	<tr><td height="5px"></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="0%" align="right"><b>TOTAL&nbsp;</b></td>
					<td width="1%" align="center">:</td>
					<td width="10%"><input name="txtTFactura" id="txtTFactura" class="txt-plano" style="width:99%;text-align:right;" readonly="true" value="0" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:80px" value="Guardar" 
				onclick="javascript: Guardar();"
			/>
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?php
mssql_close($cnx);
?>
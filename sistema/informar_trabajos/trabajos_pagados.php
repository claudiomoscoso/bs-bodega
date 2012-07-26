<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario= $_GET["usuario"];
$correlativo = $_GET["correlativo"];
$item = $_GET["item"];
$movil = $_GET["movil"];
$informado = $_GET["informado"];

if($modulo == 0){
	$variante = $_GET["variante"];
	if(variante == 1) mssql_query("DELETE FROM ItemPagados WHERE dblCorrelativo = $correlativo");
	mssql_query("EXEC Orden..sp_getItemPagados 0, $correlativo", $cnx);

	mssql_query("EXEC Orden..sp_getTMPItemPagados 0, '$usuario', $correlativo, '$item', '$movil'", $cnx);
}elseif($modulo == 1)
	mssql_query("EXEC Orden..sp_setItemPagados 0, '$usuario', $correlativo, '$item', '$movil'", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" >
<!--
function Blur(ctrl){
	CambiaColor(ctrl.id, false);
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl.id){
			case 'txtEPago':
				document.getElementById('txtCertificado').focus();
				break;
			case 'txtCertificado':
				document.getElementById('txtPagado').focus();
				break;
			case 'txtPagado':
				var epago = document.getElementById('txtEPago');
				var certificado = document.getElementById('txtCertificado');
				var items = document.getElementById('txtItem');
				var informado = document.getElementById('txtInformado');
				var pagado = document.getElementById('txtPagado');
				if(epago.value == '')
					alert('Debe ingresar el número de estado de pago.');
				else if(certificado.value == '')
					alert('Debe ingresar el número de  certificado.');
				else if(parseFloat(pagado.value) <= 0)
					alert('Debe ingresar la cantidad.');
				else{
					document.getElementById('detalle').src = 'detalle_trabajos_pagados.php?modulo=1&usuario=<?php echo $usuario;?>&epago=' + epago.value + '&certificado=' + certificado.value + '&item=' + items.value + '&informado=' + informado.value + '&pagado=' + pagado.value;
					epago.value = '';
					certificado.value = '';
					pagado.value = '';
					epago.focus();
				}
				break;
		}
	}else{
		switch(ctrl.id){
			case 'txtEPago':
			case 'txtCertificado':
				return ValNumeros(evento, ctrl.id, false);
				break;
			case 'txtPagado':
				return ValNumeros(evento, ctrl.id, true);
				break;
		}
	}
}

function Load(){
	if(parseInt('<?php echo $modulo;?>') == 0){
		document.getElementById('detalle').style.height = (window.innerHeight - 90) + 'px';
		document.getElementById('detalle').src = 'detalle_trabajos_pagados.php?usuario=<?php echo $usuario;?>&item=<?php echo $item;?>';
		document.getElementById('txtEPago').focus();
	}else if(parseInt('<?php echo $modulo;?>') == 1){
		parent.document.getElementById('detalle').src = parent.document.getElementById('detalle').src;
		parent.Deshabilita(false);
		parent.CierraDialogo('divPagados', 'frmPagados');
	}
}

function Actualizar(){
	if(confirm('Esta accion actualizará los datos perdiendo los existentes.¿Está seguro de que desea continuar?'))
		self.location.href = '<?php echo $_SERVER['PHP_SELF']."?modulo=0&variante=1&usuario=$usuario&correlativo=$correlativo&movil=$movil&item=$item";?>';
}

function Guardar(){
	var totfil = detalle.document.getElementById('totfil').value;
	if(totfil > 1){
		document.getElementById('btnGuardar').disabled = true;
		self.location.href = '<?php echo $_SERVER['PHP_SELF'];?>?modulo=1&usuario=<?php echo $usuario;?>&correlativo=<?php echo $correlativo;?>&movil=<?php echo $movil;?>&item=<?php echo $item;?>';
	}else
		alert('Debe ingresar al menos una fila al detalle.');
}
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="4%">N&deg;</th>
					<th width="15%">E.Pago</th>
					<th width="15%">Certificado</th>
					<th width="15%">Item</th>
					<th width="15%">Informado</th>
					<th width="15%">Pagado</th>
					<th width="15%">Saldo</th>
					<th width="2%">&nbsp;</th>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="center">
						<input name="txtEPago" id="txtEPago" class="txt-plano" style="width:97%; text-align:center"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td align="center">
						<input name="txtCertificado" id="txtCertificado" class="txt-plano" style="width:97%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td align="center"><input name="txtItem" id="txtItem" class="txt-sborde" style="width:97%; text-align:center; background-color:#ececec" readonly="true" value="<?php echo $item;?>"  /></td>
					<td align="center"><input name="txtInformado" id="txtInformado" class="txt-sborde" style="width:97%; text-align:right; background-color:#ececec" readonly="true" value="<?php echo $informado;?>" /></td>
					<td align="center">
						<input name="txtPagado" id="txtPagado" class="txt-plano" style="width:97%; text-align:right" value="0" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') this.value = 0"
						/>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr><td><iframe name="detalle" id="detalle" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td >
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="11%"><b>&nbsp;Total Pagado</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="15%"><input name="txtTotal" id="txtTotal" class="txt-plano" style="width:99%; text-align:right" readonly="true" value="0" /></td>
					<td width="1%">&nbsp;</td>
					<td width="0%" align="right">
						<input type="button" name="btnActualizar" id="btnActualizar" class="boton" style="width:90px" value="Reiniciar..." 
							onclick="javascript: Actualizar();"
						/>
						<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
							onclick="javascript: Guardar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
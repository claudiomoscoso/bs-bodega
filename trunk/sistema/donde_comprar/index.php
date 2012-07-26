<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Donde Comprar</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css"/>
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Buscar(){
	var material = document.getElementById('hdnMaterial').value;
	if(material=='')
		alert('Debe seleccionar un material');
	else
		document.getElementById('frmResultado').src='resultado.php?material='+material
}

function BusquedaRapida(ctrl){
	if(ctrl.value!='')
		document.getElementById('transaccion').src='transaccion.php?texto='+ctrl.value;
	else{
		document.getElementById('hdnMaterial').value='';
		document.getElementById('txtMaterial').value='';
	}
}

function Deshabilita(sw){
	document.getElementById('txtMaterial').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13 && ctrl.id == 'txtMaterial'){
		Deshabilita(true);
		AbreDialogo('divBuscador', 'frmBuscador', 'buscar_material.php?texto='+ctrl.value+'&ctrl='+ctrl.id+'&foco=btnBuscar');
	}
	return true;
}

function Load(){
	document.getElementById('frmResultado').setAttribute('height', window.innerHeight - 54);
}
-->
</script>
<body onload="javascript: Load()">
<div id="divBuscador" style="position:absolute; top:10%; left:12%; width:75%; visibility:hidden">
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

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%">&nbsp;Material</td>
					<td width="1%">:</td>
					<td width="30%">
						<input name="txtMaterial" id="txtMaterial" class="txt-plano" style="width:100%" 
							onblur="javascript: 
								CambiaColor(this.id, false);
								BusquedaRapida(this);
							"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td >
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar()"
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
					<th width="3%">N&deg;</th>
					<th width="20%" align="left">&nbsp;Proveedor</th>
					<th width="10%">Telefono.</th>
					<th width="20%" align="left">&nbsp;Vendedor</th>
					<th width="25%" align="left">&nbsp;Detalle</th>
					<th width="10%">Fecha O.C.</th>
					<th width="10%" align="right">Precio&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmResultado" id="frmResultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
</table>
<input type="hidden" id="hdnMaterial" />
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>

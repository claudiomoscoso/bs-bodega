<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Hist&oacute;rico de Cargos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" >
function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	if(ctrl.value != ''){
		var bodega = document.getElementById('cmbBodega').value;
		switch(ctrl.id){
			case 'txtCargo':
				document.getElementById('transaccion').src = 'transaccion.php?modulo=0&bodega=' + bodega + '&texto=' + ctrl.value;
				break;
		}
	}
}

function Change(){
	document.getElementById('hdnCargo').value = '';
	document.getElementById('txtCargo').value = '';
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		Deshabilita(true);
		CambiaColor(ctrl.id, false);
		var bodega = document.getElementById('cmbBodega').value;
		switch(ctrl.id){
			case 'txtCargo':
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_cargos.php?bodega=' + bodega + '&texto=' + ctrl.value + '&ctrl=' + ctrl.id);
				break;
		}
	}
}

function Load(){
	document.getElementById('divResultado').style.height = (window.innerHeight - 53) + 'px';
}

function Buscar(){
	var bodega = document.getElementById('cmbBodega').value;
	var cargo = document.getElementById('hdnCargo').value;
	if(cargo == '')
		alert('Debe selecccionar un cargo.');
	else{
		Deshabilita(true);
		var resultado = document.getElementById('divResultado');
		resultado.innerHTML = '<table border="0" width="100%" height="100%"><tr><td align="center"><img src="../images/cargando2.gif"></td></tr></table>';
		var ajax = new XMLHttpRequest();
		ajax.open('GET', 'resultado.php?bodega=' + bodega + '&cargo=' + cargo, true);
		ajax.onreadystatechange = function(){
			if(ajax.readyState == 4){
				resultado.innerHTML = ajax.responseText;
				Deshabilita(false);
			}
		}
		ajax.send(null);
	}
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('txtCargo').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}


</script>
<body onload="javascript: Load()">
<div id="divBuscador" style="position:absolute; z-index:5; top:5px; left:15%; width:70%; visibility:hidden">
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
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divBuscador', 'frmBuscador');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>Cuadro de B&uacute;squeda</b></td>
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
					<td width="5%">&nbsp;Obra</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%"
							onchange="javascript: Change();"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strBodega"].'" '.($rst["strBodega"] == $bodega ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%" align="left">&nbsp;Cargo</td>
					<td width="1%">:</td>
					<td width="20%">
						<input type="hidden" name="hdnCargo" id="hdnCargo" />
						<input name="txtCargo" id="txtCargo" class="txt-plano" style="width:100%"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') document.getElementById('hdnCargo').value = '';"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar();"
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
					<th width="10%">&nbsp;N&deg;Gu&iacute;a</th>
					<th width="10%">&nbsp;C&oacute;digo</th>
					<th width="25%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="20%" align="left">&nbsp;Nombre</th>
					<th width="10%" align="right">Retiro&nbsp;</th>
					<th width="10%" align="right">Devuelto&nbsp;</th>
					<th width="10%">Fecha</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><div id="divResultado" style="position:relative; width:100%; height:100px; overflow:scroll"></div></td></tr>
</table>
<iframe id="transaccion" frameborder="0" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>

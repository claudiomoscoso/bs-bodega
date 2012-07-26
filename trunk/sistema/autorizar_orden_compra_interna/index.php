<?php
include '../autentica.php';
include '../conexion.inc.php';

$estado = $_POST["estado"];
if($estado != '')
	mssql_query("EXEC Bodega..sp_setCambiaEstado 0, '$usuario', '$estado'", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Autorizar Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Deshabilita(sw){
	if(document.getElementById('btnVB')) document.getElementById('btnVB').disabled = sw;
	if(document.getElementById('btnAutoriza')) document.getElementById('btnAutoriza').disabled = sw;
	document.getElementById('btnRechaza').disabled = sw;
}

function Visado(estado){
	document.getElementById('estado').value = estado;
	document.getElementById('frm').submit();
}

function Load(){
	var ajax = new XMLHttpRequest();
	var resultado = document.getElementById('divResultado');
	resultado.style.height = (window.innerHeight - 55) + 'px';
	resultado.innerHTML = '<table border="0" width="100%" height="100%"><tr><td align="center"><img src="../images/cargando2.gif"></td></tr></table>';
	ajax.open('GET', 'resultado.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>', true);
	ajax.onreadystatechange = function (){
		if(ajax.readyState == 4) resultado.innerHTML = ajax.responseText;
	}
	ajax.send(null);
}

function Seleccionar(ctrl){
	var resultado = document.getElementById('divResultado');
	var ajax = new XMLHttpRequest();
	if(ctrl.id == 'chkTodo'){
		var totfil = document.getElementById('totfil').value;
		for(i = 1; i <= totfil; i++) document.getElementById('chkMarca' + i).checked = ctrl.checked;
		ajax.open('GET', 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&estado=' + (ctrl.checked ? 1 : 0), true);
	}else{
		ajax.open('GET', 'transaccion.php?modulo=1&usuario=<?php echo $usuario;?>&numero=' + ctrl.value + '&estado=' + (ctrl.checked ? 1 : 0), true);
	}
	ajax.send(null);
}
-->
</script>
<body onLoad="javascript: Load()">
<div id="divOCompra" style="z-index:1; position:absolute; top:5px; left:35%; width:60%; visibility:hidden">
<table border="1" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td class="menu_principal">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="center" valign="bottom" width="15px">
									<a href="#" title="Cierra la ventana"
										onClick="javascript: 
											CierraDialogo('divOCompra', 'frmOCompra');
											Deshabilita(false);
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center"><b>Orden de Compra Interna</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmOCompra" id="frmOCompra" frameborder="0" scrolling="no" width="100%" height="150px" marginwidth="0" marginheight="0" src="../cargando.php"></iframe></td></tr>
				<tr><td><hr></td></tr>
				<tr>
					<td align="right">
						<input type="button" name="btnCerrar" id="btnCerrar" value="Cerrar" class="boton" style="width:100px" 
							onclick="javascript: 
								CierraDialogo('divOCompra', 'frmOCompra');
								Deshabilita(false);
							"
						>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'].$parametros;?>">
<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%" t>N&deg;</th>
					<th width="20%" align="left">&nbsp;Cargo</th>
					<th width="10%">N&uacute;mero</th>
					<th width="10%">Fecha</th>
					<th width="38%" align="left">&nbsp;Observaci&oacute;n</th>
					<th width="15%" align="left">&nbsp;Estado</th>
					<th width="2%">
						<input type="checkbox" name="chkTodo" id="chkTodo" 
							onclick="javascript: Seleccionar(this);"
						/>
					</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><div id="divResultado" style="height:100px;overflow:scroll;position:relative;width:100%;"></div></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="estado" id="estado" />
		<?php
		if($perfil == 'sg.operaciones'){?>
			<input type="button" name="btnVB" id="btnVB" class="boton" style="width:80px" value="V&deg;B&deg;" 
				onclick="javascript: Visado(1);"
			/>
		<?php
		}else{?>
			<input type="button" name="btnAutoriza" id="btnAutoriza" class="boton" style="width:80px" value="Autoriza" 
				onclick="javascript: Visado(2);"
			/>
		<?php
		}?>
			<input type="button" name="btnRechaza" id="btnRechaza" class="boton" style="width:80px" value="Rechaza" 
				onclick="javascript: Visado(4);"
			/>
		</td>
	</tr>
</table>
</form>
</body>
</html>

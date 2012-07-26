<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Entrega de Cargos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	switch(ctrl.id){
		case 'txtFInicio':
		case 'txtFTermino':
			switch(tecla){
				case 8:
				case 46:
					ctrl.value = '';
				case 9:
					return true;
					break;
				default:
					return false;
			}
			break;
	}
}

function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 88);
}

function Buscar(){
	document.getElementById('frm').submit();
	Deshabilita(true);
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('txtFInicio').disabled = sw;
	document.getElementById('imgFInicio').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('txtFTermino').disabled = sw;
	document.getElementById('imgFTermino').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnExportar').disabled = sw;
}

function Exportar(){
	if(document.getElementById('transaccion')){
		var bodega = document.getElementById('cmbBodega').value;
		var finicio = document.getElementById('txtFInicio').value;
		var ftermino = document.getElementById('txtFTermino').value;
		document.getElementById('transaccion').src = 'exportar.php?bodega=' + bodega + '&finicio=' + finicio + '&ftermino=' + ftermino;
	}else
		alert('No se han encontrado información para exportar.');
}
-->
</script>
<body onload="javascript: Load()">
<div id="divCalendario" style="position:absolute; top:20px; left:17%; width:20%; visibility:hidden">
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
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="0" style="border:thin" scrolling="no" width="100%" height="130px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<form name="frm" id="frm" method="post" action="resultado.php" target="resultado">
				<tr>
					<td width="5%">&nbsp;Bodega</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strBodega"]).'" '.(trim($rst["strBodega"]) == $bodega ? 'selected="selected"' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;F.Inicio</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtFInicio" id="txtFInicio" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre cuadro calendario."
							onblur="javascript: CambiaImagen('imgFInicio', false);"
							onclick="javascript:
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFInicio&fecha=' + document.getElementById('txtFInicio').value, false, '30%', '20px');
							"
							onfocus="javascript: CambiaImagen('imgFInicio', true);"
							onmouseover="javascript: window.status = 'Abre cuadro calendario.'; return true;"
						><img id="imgFInicio" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%">&nbsp;F.T&eacute;rmino</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtFTermino" id="txtFTermino" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre cuadro calendario."
							onblur="javascript: CambiaImagen('imgFTermino', false);"
							onclick="javascript:
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFTermino&fecha=' + document.getElementById('txtFTermino').value, false, '52%', '20px');
							"
							onfocus="javascript: CambiaImagen('imgFTermino', true);"
							onmouseover="javascript: window.status = 'Abre cuadro calendario.'; return true;"
						><img id="imgFTermino" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar();"
						/>
					</td>
				</tr>
			</form>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="25%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="5%">Unidad</th>
					<th width="8%" align="right">Cantidad I.&nbsp;</th>
					<th width="8%" align="right">Cantidad S.&nbsp;</th>
					<th width="10%" align="right">Precio&nbsp;</th>
					<th width="8%">Fecha</th>
					<th width="25%" align="left">Nombre</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
				onclick="javascript: Exportar();"
			/>
		</td>
	</tr>
</table>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
